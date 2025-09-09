<?php
// src/Controller/Traits/SearchableTrait.php
namespace App\Controller\Traits;

use Cake\ORM\Table;
use Cake\Datasource\Exception\RecordNotFoundException;


trait SearchableTrait
{

    /**
     * Busca genérica com retorno em JSON
     *
     * @param Table $table   Tabela que será consultada
     * @param array $fields  Campos que serão buscados
     * @param array $extraConditions Condições adicionais opcionais
     * @return void
     */
    public function doSearch(Table $table, array $fields, array $extraConditions = [])
    {
        $this->request->allowMethod(['get']);
        $q = $this->request->getQuery('q');

        $query = $table->find();

        if (!empty($q)) {
            $or = [];

            foreach ($fields as $field) {
                //$term = mb_strtolower($q, 'UTF-8');
                $or["$field LIKE"] = "%$q%";
            }

            $conditions = array_merge($extraConditions, ['OR' => $or]);

            $query->where($conditions);
        } elseif (!empty($extraConditions)) {
            $query->where($extraConditions);
        }

        $results = $query->limit(50)->all()->toArray();

        $this->autoRender = false;
        $this->response = $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['data' => $results]));
    }


    /**
     * Busca avançada genérica com filtros seguros, ordenação e paginação
     *
     * @param Table $table
     * @param array $allowedFilters Campos permitidos para filtros diretos, ex:
     *      [
     *          'nome' => ['field' => 'Usuarios.nome_completo', 'operator' => 'LIKE'],
     *          'email' => ['field' => 'Usuarios.email'], // '=' por padrão
     *      ]
     * @param array $defaultOrder Ex: ['Usuarios.id' => 'ASC']
     */
    public function doAdvancedSearch(Table $table, array $allowedFilters = [], array $defaultOrder = ['id' => 'ASC'], array $contain = [])
    {

        $queryParams = $this->request->getQueryParams();
        $q = $queryParams['q'] ?? null;
        unset($queryParams['q']);

        $extra = [];

        $query = $table->find();

        // aplica contain se tiver
        if (!empty($contain)) {
            $query->contain($contain);
        }
        // Processa filtros permitidos
        foreach ($queryParams as $param => $value) {
            if ($value === '' || $value === null)
                continue;
            if (!isset($allowedFilters[$param]))
                continue;

            $filter = $allowedFilters[$param];
            $field = $filter['field'];
            $operator = $filter['operator'] ?? '=';
            $value = $filter['value'] ?? $value;

            if (str_contains($field, '.')) {
                // Se for associação: Ex. Tipos.id
                [$assoc, $col] = explode('.', $field, 2);
                $query->matching($assoc, function ($q) use ($col, $operator, $value, $assoc) {
                    return $q->where(["$assoc.$col $operator" => $value]);
                });
            } else {
                // Campos locais da tabela principal
                if ($operator === 'LIKE') {
                    $extra["$field LIKE"] = "%$value%";
                } elseif ($operator === 'BETWEEN' && is_string($value) && strpos($value, ',') !== false) {
                    [$start, $end] = explode(',', $value, 2);
                    $extra[] = function ($exp) use ($field, $start, $end) {
                        return $exp->between($field, $start, $end);
                    };
                } else {
                    $extra["$field $operator"] = $value;
                }
            }
        }

        foreach ($allowedFilters as $param => $filter) {
            if (!isset($filter['value']))
                continue; // só os fixos
            $field = $filter['field'];
            $operator = $filter['operator'] ?? '=';
            $value = $filter['value'];

            $extra["$field $operator"] = $value;
        }

        // Busca textual livre via 'q'
        if (!empty($q)) {
            // Extrai do $allowedFilters apenas os campos com operador LIKE
            $likeFields = array_map(fn($f) => $f['field'], array_filter($allowedFilters, fn($f) => ($f['operator'] ?? '=') === 'LIKE'));

            if (!empty($likeFields)) {
                $query->where(function ($exp) use ($likeFields, $q) {
                    $or = [];
                    //$termo = mb_strtolower($q, 'UTF-8');
                    foreach ($likeFields as $field) {
                        //$or["$field LIKE"] = "%$q%";
                        $or["LOWER($field) LIKE"] = '%' . mb_strtolower($q, 'UTF-8') . '%';
                    }
                    return $exp->or($or);
                });
            }
        }


        // Aplica filtros extras
        if (!empty($extra)) {
            $query->where($extra);
        }

        // Ordenação
        $sort = $queryParams['sort'] ?? null;
        $direction = strtoupper($queryParams['direction'] ?? 'ASC');
        if ($sort && array_key_exists($sort, $allowedFilters)) {
            $query->orderBy([$allowedFilters[$sort]['field'] => in_array($direction, ['ASC', 'DESC']) ? $direction : 'ASC']);
        } else {
            $query->orderBy($defaultOrder);
        }

        // Paginação
        $page = max(1, (int) ($queryParams['page'] ?? 1));
        $limit = max(1, min(100, (int) ($queryParams['limit'] ?? 50)));
        $offset = ($page - 1) * $limit;

        $results = $query->limit($limit)->offset($offset)->all()->toArray();

        $this->autoRender = false;
        $this->response = $this->response
            ->withType('application/json')
            ->withStringBody(json_encode([
                'data' => $results,
                'paginator' => [
                    'page' => $page,
                    'limit' => $limit,
                    'count' => count($results)
                ]
            ]));
    }

    /**
     * Delete genérico para controllers
     *
     * @param Table $table A tabela do model, ex: $this->Usuarios
     * @param int|null $id ID do registro
     * @param array $conditions Condições adicionais para buscar o registro
     */
    public function doDelete(Table $table, $id = null, array $conditions = [])
    {
        $this->request->allowMethod(['post', 'delete']);

        try {
            $entity = $table->get($id, ['conditions' => $conditions]);
        } catch (RecordNotFoundException $e) {
            $entity = null;
        }

        if (empty($entity)) {
            $message = __('Registro não encontrado.');
            if ($this->request->is('ajax') || $this->request->accepts('application/json')) {
                $this->autoRender = false;
                $this->response = $this->response
                    ->withType('application/json')
                    ->withStringBody(json_encode(['success' => false, 'message' => $message]));
                return;
            }
            $this->Flash->error($message);
            return $this->redirect(['action' => 'index']);
        }

        $success = $table->delete($entity);
        $message = $success
            ? __('O registro foi excluído com sucesso.')
            : __('Ocorreu um erro na tentativa de excluir. Por favor, tente novamente.');

        if ($this->request->is('ajax') || $this->request->accepts('application/json')) {
            $this->autoRender = false;
            $this->response = $this->response
                ->withType('application/json')
                ->withStringBody(json_encode(['success' => $success, 'message' => $message]));
            return;
        }

        if ($success) {
            $this->Flash->success($message);
        } else {
            $this->Flash->error($message);
        }
        return $this->redirect(['action' => 'index']);
    }
}
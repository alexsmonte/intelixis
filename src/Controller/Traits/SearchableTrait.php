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
        }
        elseif (!empty($extraConditions)) {
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
     *
     *
     ==================================================================
     MANUAL DE USO —  doAdvancedSearch (CakePHP)
     ==================================================================
     Este component permite:
     - Busca livre (q) com MULTI_LIKE
     - Filtros simples (=, LIKE)
     - Filtros IN
     - Filtros BETWEEN
     - Filtros em associações (matching)
     - Ordenação
     - Paginação completa
     Todos os filtros são enviados via QUERY STRING.
     ------------------------------------------------------------------
     ==================================================================
     1) BUSCA LIVRE (q → MULTI_LIKE)
     ==================================================================
     $allowedFilters:
     'q' => [
     'operator' => 'MULTI_LIKE',
     'fields' => [
     'Consultas.nome',
     'Clientes.nome',
     'Clientes.email'
     ]
     ]
     URL:
     ? q=joao
     Resultado SQL:
     LOWER(Consultas.nome) LIKE '%joao%'
     OR LOWER(Clientes.nome) LIKE '%joao%'
     OR LOWER(Clientes.email) LIKE '%joao%'
     ==================================================================
     2) FILTRO SIMPLES (=)
     ==================================================================
     $allowedFilters:
     'status' => [
     'field' => 'Consultas.status',
     'operator' => '='
     ]
     URL:
     ? status=ativo
     Resultado SQL:
     Consultas.status = 'ativo'
     ==================================================================
     3) FILTRO LIKE
     ==================================================================
     $allowedFilters:
     'nome' => [
     'field' => 'Consultas.nome',
     'operator' => 'LIKE'
     ]
     URL:
     ? nome=mar
     Resultado SQL:
     Consultas.nome LIKE '%mar%'
     ==================================================================
     4) FILTRO IN
     ==================================================================
     $allowedFilters:
     'profissional_id' => [
     'field' => 'Consultas.profissional_id',
     'operator' => 'IN'
     ]
     URL (string):
     ? profissional_id=10,20,30
     URL (array):
     ? profissional_id[]=10&profissional_id[]=20
     Resultado SQL:
     Consultas.profissional_id IN (10,20,30)
     ==================================================================
     5) FILTRO BETWEEN
     ==================================================================
     $allowedFilters:
     'created' => [
     'field' => 'Consultas.created',
     'operator' => 'BETWEEN'
     ]
     URL:
     ? created=2025-01-01,2025-01-31
     Resultado SQL:
     Consultas.created BETWEEN '2025-01-01' AND '2025-01-31'
     ==================================================================
     6) FILTRO EM ASSOCIAÇÃO (matching)
     ==================================================================
     $allowedFilters:
     'cliente_id' => [
     'field' => 'Clientes.id',
     'operator' => '=',
     'association' => 'Clientes'
     ]
     URL:
     ? cliente_id=90
     Resultado SQL:
     matching('Clientes') WHERE Clientes.id = 90
     ==================================================================
     7) ASSOCIAÇÃO + IN
     ==================================================================
     $allowedFilters:
     'procedimento_id' => [
     'field' => 'Procedimentos.id',
     'operator' => 'IN',
     'association' => 'Procedimentos'
     ]
     URL:
     ? procedimento_id=1,2,3
     Resultado SQL:
     Procedimentos.id IN (1,2,3)
     ==================================================================
     8) ASSOCIAÇÃO + BETWEEN
     ==================================================================
     $allowedFilters:
     'valor' => [
     'field' => 'Procedimentos.valor',
     'operator' => 'BETWEEN',
     'association' => 'Procedimentos'
     ]
     URL:
     ? valor=100,500
     Resultado SQL:
     Procedimentos.valor BETWEEN 100 AND 500
     ==================================================================
     9) FILTRO FIXO (não vem da URL)
     ==================================================================
     $allowedFilters:
     'empresa_id' => [
     'field' => 'Consultas.empresa_id',
     'operator' => '=',
     'value' => 5
     ]
     OBS:
     Este filtro SEMPRE será aplicado, mesmo que não exista na URL.
     Resultado SQL:
     Consultas.empresa_id = 5
     ==================================================================
     10) ORDENAÇÃO
     ==================================================================
     $allowedFilters:
     'created' => [
     'field' => 'Consultas.created'
     ]
     URL:
     ? sort=created&direction=DESC
     Resultado SQL:
     ORDER BY Consultas.created DESC
     ==================================================================
     11) PAGINAÇÃO
     ==================================================================
     URL:
     ? page=2&limit=20
     Retorno JSON:
     "paginator": {
     "page": 2,
     "limit": 20,
     "count": 20,
     "total": 143,
     "pageCount": 8
     }
     Onde:
     page       = página atual
     limit      = registros por página
     count      = registros retornados nesta página
     total      = total geral de registros
     pageCount  = total de páginas
     ==================================================================
     12) EXEMPLO COMPLETO REAL
     ==================================================================
     $allowedFilters:
     'q' => [
     'operator' => 'MULTI_LIKE',
     'fields' => [
     'Consultas.descricao',
     'Clientes.nome'
     ]
     ],
     'status' => [
     'field' => 'Consultas.status'
     ],
     'profissional_id' => [
     'field' => 'Consultas.profissional_id',
     'operator' => 'IN'
     ],
     'data' => [
     'field' => 'Consultas.data',
     'operator' => 'BETWEEN'
     ]
     URL FINAL:
     /consultas/search
     ?q=retorno
     &status=ativo
     &profissional_id=2,3
     &data=2025-01-01,2025-01-31
     &page=1
     &limit=10
     ***/
    public function doAdvancedSearch(
        Table $table,
        array $allowedFilters = [],
        array $defaultOrder = ['id' => 'ASC'],
        array $contain = []
    )
    {
        $queryParams = $this->request->getQueryParams();

        $q = $queryParams['q'] ?? null;
        unset($queryParams['q']);

        $extra = [];

        $alias = $table->getAlias();

        $query = $table->find()
            ->distinct([$alias . '.id']);

        if (!empty($contain)) {
            $query->contain($contain);
        }

        /* ========================= */
        $normalizeAssociation = function ($path) {
            return is_array($path) ? implode('.', $path) : $path;
        };

        /* ========================= */
        $joinsNeeded = [];

        // FROM q
        if (!empty($q) && isset($allowedFilters['q'])) {

            $filter = $allowedFilters['q'];

            if (($filter['operator'] ?? null) === 'MULTI_LIKE') {

                foreach ($filter['fields'] as $field) {

                    if (str_contains($field, '.')) {

                        $parts = explode('.', $field);

                        if (count($parts) > 1) {
                            array_pop($parts);
                            $assocPath = implode('.', $parts);

                            if ($assocPath !== $alias) {
                                $joinsNeeded[$assocPath] = $assocPath;
                            }
                        }
                    }
                }
            }
        }

        // FROM filtros
        foreach ($allowedFilters as $filter) {

            if (!empty($filter['association'])) {
                $assoc = $normalizeAssociation($filter['association']);
                $joinsNeeded[$assoc] = $assoc;
            }

            if (!empty($filter['field']) && str_contains($filter['field'], '.')) {

                $parts = explode('.', $filter['field']);

                if (count($parts) > 1) {
                    array_pop($parts);
                    $assocPath = implode('.', $parts);

                    if ($assocPath !== $alias) {
                        $joinsNeeded[$assocPath] = $assocPath;
                    }
                }
            }
        }

        /* ========================= */
        foreach ($joinsNeeded as $assocPath) {

            try {
                if ($table->hasAssociation(explode('.', $assocPath)[0])) {
                    $query->leftJoinWith($assocPath);
                }
            } catch (\Exception $e) {
                // opcional: log
            }
        }

        /* ========================= */
        if (!empty($q) && isset($allowedFilters['q'])) {

            $filter = $allowedFilters['q'];

            if (($filter['operator'] ?? null) === 'MULTI_LIKE') {

                $query->where(function ($exp) use ($filter, $q) {

                    $or = [];

                    foreach ($filter['fields'] as $field) {
                        $or["LOWER($field) LIKE"] =
                            '%' . mb_strtolower($q, 'UTF-8') . '%';
                    }

                    return $exp->or($or);
                });
            }
        }

        /* ========================= */
        foreach ($queryParams as $param => $value) {

            if ($value === '' || $value === null) {
                continue;
            }

            if (!isset($allowedFilters[$param])) {
                continue;
            }

            $filter = $allowedFilters[$param];
            $field = $filter['field'];
            $operator = strtoupper($filter['operator'] ?? '=');

            /* ASSOCIATION */
            if (!empty($filter['association'])) {

                $assoc = $normalizeAssociation($filter['association']);

                $query->matching($assoc, function ($q) use ($field, $operator, $value) {

                    if ($operator === 'IN') {
                        $vals = is_array($value) ? $value : explode(',', $value);
                        return $q->where([$field . ' IN' => $vals]);
                    }

                    if ($operator === 'BETWEEN' && str_contains($value, ',')) {
                        [$start, $end] = explode(',', $value, 2);
                        return $q->where(function ($exp) use ($field, $start, $end) {
                            return $exp->between($field, $start, $end);
                        });
                    }

                    return $q->where(["$field $operator" => $value]);
                });

                continue;
            }

            /* CAMPOS LOCAIS */
            if ($operator === 'LIKE') {
                $extra["$field LIKE"] = "%$value%";
            }
            elseif ($operator === 'IN') {
                $vals = is_array($value) ? $value : explode(',', $value);
                $extra["$field IN"] = $vals;
            }
            elseif ($operator === 'BETWEEN' && str_contains($value, ',')) {
                [$start, $end] = explode(',', $value, 2);

                $extra[] = function ($exp) use ($field, $start, $end) {
                    return $exp->between($field, $start, $end);
                };
            }
            elseif ($operator === 'DATE') {

                $start = $value . ' 00:00:00';
                $end = $value . ' 23:59:59';

                $extra[] = function ($exp) use ($field, $start, $end) {
                    return $exp->between($field, $start, $end);
                };
            }
            else {
                $extra["$field $operator"] = $value;
            }
        }

        /* ========================= */
        foreach ($allowedFilters as $filter) {

            if (!isset($filter['value'])) {
                continue;
            }

            $extra[$filter['field'] . ' ' . ($filter['operator'] ?? '=')] = $filter['value'];
        }

        if (!empty($extra)) {
            $query->where($extra);
        }

        /* ========================= */
        $sort = $queryParams['sort'] ?? null;
        $direction = strtoupper($queryParams['direction'] ?? 'ASC');

        if ($sort && isset($allowedFilters[$sort]['field'])) {
            $query->orderBy([
                $allowedFilters[$sort]['field'] =>
                in_array($direction, ['ASC', 'DESC']) ? $direction : 'ASC'
            ]);
        } else {
            $query->orderBy($defaultOrder);
        }

        /* ========================= */
        $page = max(1, (int)($queryParams['page'] ?? 1));
        $limit = max(1, min(100, (int)($queryParams['limit'] ?? 50)));
        $offset = (($page - 1) * $limit);

        $countQuery = $table->find()
            ->select([$alias . '.id'])
            ->distinct([$alias . '.id']);

        foreach ($joinsNeeded as $assocPath) {
            try {
                if ($table->hasAssociation(explode('.', $assocPath)[0])) {
                    $countQuery->leftJoinWith($assocPath);
                }
            } catch (\Exception $e) {
                // opcional: log
            }
        }

        $countQuery->where($query->clause('where'));

        $total = $countQuery->count();

        $results = $query
            ->limit($limit)
            ->offset($offset)
            ->all()
            ->toArray();

        $pageCount = (int)ceil($total / $limit);

        /* ========================= */
        $this->autoRender = false;

        $this->response = $this->response
            ->withType('application/json')
            ->withStringBody(json_encode([
                'data' => $results,
                'paginator' => [
                    'page' => $page,
                    'limit' => $limit,
                    'count' => count($results),
                    'total' => $total,
                    'pageCount' => $pageCount
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
        }
        catch (RecordNotFoundException $e) {
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
        }
        else {
            $this->Flash->error($message);
        }
        return $this->redirect(['action' => 'index']);
    }
}

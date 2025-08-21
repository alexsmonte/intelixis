<?php
// src/Controller/Traits/SearchableTrait.php
namespace App\Controller\Traits;

use Cake\ORM\Table;

trait SearchableTrait
{
    /**
     * Método genérico de busca
     * @param Table $table Tabela do controller
     * @param array $fields Campos que serão buscados
     */
    public function search(Table $table, array $fields)
    {
        $this->request->allowMethod(['get']);
        $q = $this->request->getQuery('q');

        $query = $table->find();

        if ($q) {
            $query->where(function ($exp) use ($fields, $q) {
                $orConditions = [];
                foreach ($fields as $field) {
                    $orConditions[$field . ' LIKE'] = "%$q%";
                }
                return $exp->or_($orConditions);
            });
        }

        $results = $query->limit(50)->all();
        $this->set([
            'data' => $results,
            '_serialize' => ['data']
        ]);
    }
}

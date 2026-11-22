<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddFotoToClientes extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('clientes');
        $table
            ->addColumn('foto', 'boolean', [
                'default' => false,
                'null' => false,
            ])
            ->update();
    }
}

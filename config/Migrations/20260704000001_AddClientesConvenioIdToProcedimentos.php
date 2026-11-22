<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class AddClientesConvenioIdToProcedimentos extends BaseMigration
{
    public function up(): void
    {
        $this->table('procedimentos')
            ->addColumn('clientes_convenio_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'signed' => false,
            ])
            ->update();
    }

    public function down(): void
    {
        $this->table('procedimentos')
            ->removeColumn('clientes_convenio_id')
            ->update();
    }
}

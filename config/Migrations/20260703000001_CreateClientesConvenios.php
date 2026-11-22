<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateClientesConvenios extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('clientes_convenios');
        $table
            ->addColumn('cliente_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('convenio_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('numero_carteirinha', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('nome_titular', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('cpf_titular', 'string', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('is_titular', 'boolean', [
                'default' => true,
                'null' => false,
            ])
            ->addColumn('grau_dependencia', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => true,
            ])
            ->addColumn('plano', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true,
            ])
            ->addColumn('data_inicio', 'date', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('data_validade', 'date', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('acomodacao', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('abrangencia', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('status', 'string', [
                'default' => 'ATIVO',
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('observacoes', 'text', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'null' => false,
            ])
            ->addIndex('cliente_id', ['name' => 'idx_cc_cliente'])
            ->addIndex('convenio_id', ['name' => 'idx_cc_convenio'])
            ->addIndex(
                ['cliente_id', 'convenio_id', 'numero_carteirinha'],
                ['name' => 'uk_cc_cliente_conv_carteirinha', 'unique' => true]
            )
            ->create();

        $this->table('clientes_convenios')
            ->addForeignKey('cliente_id', 'clientes', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION',
                'constraint' => 'fk_cc_cliente',
            ])
            ->addForeignKey('convenio_id', 'convenios', 'id', [
                'delete' => 'RESTRICT',
                'update' => 'NO_ACTION',
                'constraint' => 'fk_cc_convenio',
            ])
            ->save();
    }
}

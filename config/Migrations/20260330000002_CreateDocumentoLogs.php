<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateDocumentoLogs extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('documento_logs');
        $table->addColumn('documento_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ])
            ->addColumn('usuario_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
                'comment' => 'ID do usuário logado ou null se for o paciente'
            ])
            ->addColumn('acao', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
                'comment' => 'ex: gerado, visualizado, assinado'
            ])
            ->addColumn('hash_antes', 'string', [
                'default' => null,
                'limit' => 64,
                'null' => true,
            ])
            ->addColumn('hash_depois', 'string', [
                'default' => null,
                'limit' => 64,
                'null' => true,
            ])
            ->addColumn('ip', 'string', [
                'default' => null,
                'limit' => 45,
                'null' => false,
            ])
            ->addColumn('detalhes', 'json', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'null' => false,
            ])
            ->addIndex(['documento_id'])
            ->create();
    }
}
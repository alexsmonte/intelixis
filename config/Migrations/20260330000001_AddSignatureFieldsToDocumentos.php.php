<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AddSignatureFieldsToDocumentos extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('documentos');

        // Note que usaremos seu 'codigo_validacao' atual como token de URL, 
        // então adicionamos apenas os campos de prova digital
        $table->addColumn('assinatura_hash', 'string', [
            'default' => null,
            'limit' => 64,
            'null' => true,
            'comment' => 'Hash SHA-256 do PDF final assinado'
        ])
            ->addColumn('assinatura_ip', 'string', [
                'default' => null,
                'limit' => 45,
                'null' => true,
            ])
            ->addColumn('assinatura_selfie_path', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('assinatura_details', 'json', [
                'default' => null,
                'null' => true,
                'comment' => 'Metadados como User-Agent e Geolocalização'
            ])
            ->addColumn('assinatura_data', 'datetime', [
                'default' => null,
                'null' => true,
            ])
            ->update();
    }
}
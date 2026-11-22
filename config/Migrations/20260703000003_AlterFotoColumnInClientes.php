<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class AlterFotoColumnInClientes extends AbstractMigration
{
    public function up(): void
    {
        // Remove default e NOT NULL antes de converter o tipo
        $this->execute("ALTER TABLE clientes ALTER COLUMN foto DROP DEFAULT");
        $this->execute("ALTER TABLE clientes ALTER COLUMN foto DROP NOT NULL");
        // Converte de string para boolean
        $this->execute("ALTER TABLE clientes ALTER COLUMN foto TYPE boolean USING CASE WHEN foto IS NULL OR foto = '' THEN false ELSE true END");
        // Restaura default e NOT NULL
        $this->execute("ALTER TABLE clientes ALTER COLUMN foto SET DEFAULT false");
        $this->execute("ALTER TABLE clientes ALTER COLUMN foto SET NOT NULL");
    }

    public function down(): void
    {
        $this->execute("ALTER TABLE clientes ALTER COLUMN foto TYPE varchar(500) USING foto::varchar");
        $this->execute("ALTER TABLE clientes ALTER COLUMN foto DROP DEFAULT");
        $this->execute("ALTER TABLE clientes ALTER COLUMN foto DROP NOT NULL");
    }
}

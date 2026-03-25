<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class Inicial extends BaseMigration
{
    public bool $autoId = false;

    /**
     * Up Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
     * @return void
     */
    public function up(): void
    {
        $this->table('agendamentos')
            ->addColumn('id', 'biginteger', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('estabelecimento_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('cliente_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('servico_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('statu_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('profissional_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'signed' => false,
            ])
            ->addColumn('local_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'signed' => false,
            ])
            ->addColumn('convenio_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'signed' => false,
            ])
            ->addColumn('data_hora_inicio', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('data_hora_fim', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('descricao', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                $this->index('statu_id')
                    ->setName('idx_status')
            )
            ->addIndex(
                $this->index('estabelecimento_id')
                    ->setName('idx_agendamentos_estabelecimentos')
            )
            ->addIndex(
                $this->index('cliente_id')
                    ->setName('idx_agendamentos_cliente')
            )
            ->addIndex(
                $this->index('profissional_id')
                    ->setName('idx_agendamentos_profissional')
            )
            ->addIndex(
                $this->index('servico_id')
                    ->setName('idx_agendamentos_servico')
            )
            ->addIndex(
                $this->index('local_id')
                    ->setName('idx_agendamentos_local')
            )
            ->addIndex(
                $this->index('convenio_id')
                    ->setName('fk_agendamentos_convenio')
            )
            ->create();

        $this->table('assinaturas')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('conta_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addColumn('plano_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addColumn('data_vencimento', 'date', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('desconto', 'decimal', [
                'default' => '0.00',
                'null' => true,
                'precision' => 16,
                'scale' => 2,
                'signed' => true,
            ])
            ->addColumn('status', 'string', [
                'default' => 'ativa',
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                $this->index('conta_id')
                    ->setName('fk_assinaturas_contas_idx')
            )
            ->addIndex(
                $this->index('plano_id')
                    ->setName('fk_assinaturas_planos1_idx')
            )
            ->create();

        $this->table('bloqueios')
            ->addColumn('id', 'biginteger', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('estabelecimento_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('profissional_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'signed' => false,
            ])
            ->addColumn('servico_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'signed' => false,
            ])
            ->addColumn('local_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'signed' => false,
            ])
            ->addColumn('data_inicio', 'date', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('data_fim', 'date', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('dia_inteiro', 'boolean', [
                'default' => true,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('hora_inicio', 'smallinteger', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'signed' => true,
            ])
            ->addColumn('hora_fim', 'smallinteger', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'signed' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                $this->index('estabelecimento_id')
                    ->setName('idx_bloqueios_estabelecimentos')
            )
            ->addIndex(
                $this->index('profissional_id')
                    ->setName('idx_bloqueios_profissional')
            )
            ->addIndex(
                $this->index('servico_id')
                    ->setName('idx_bloqueios_servico')
            )
            ->addIndex(
                $this->index('local_id')
                    ->setName('idx_bloqueios_local')
            )
            ->create();

        $this->table('clientes')
            ->addColumn('id', 'biginteger', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('estabelecimento_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('nome', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('documento', 'string', [
                'default' => null,
                'limit' => 14,
                'null' => false,
            ])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('telefone', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('celular', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('cep', 'string', [
                'default' => null,
                'limit' => 8,
                'null' => true,
            ])
            ->addColumn('endereco', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('cidade', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true,
            ])
            ->addColumn('bairro', 'string', [
                'default' => null,
                'limit' => 45,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                $this->index('estabelecimento_id')
                    ->setName('idx_clientes_estabelecimentos')
            )
            ->create();

        $this->table('contas')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('cpf_cnpj', 'string', [
                'default' => null,
                'limit' => 21,
                'null' => false,
            ])
            ->addColumn('nome', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('razao_social', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('logradouro', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('cep', 'string', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('bairro', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true,
            ])
            ->addColumn('cidade', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true,
            ])
            ->addColumn('uf', 'string', [
                'default' => null,
                'limit' => 2,
                'null' => true,
            ])
            ->addColumn('complemento', 'string', [
                'default' => null,
                'limit' => 80,
                'null' => true,
            ])
            ->addColumn('ativar', 'boolean', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                $this->index('email')
                    ->setName('email_UNIQUE')
                    ->setType('unique')
            )
            ->create();

        $this->table('convenios')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('estabelecimento_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('nome', 'string', [
                'default' => null,
                'limit' => 150,
                'null' => false,
            ])
            ->addColumn('registro_ans', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => true,
            ])
            ->addColumn('cnpj', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('telefone', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 150,
                'null' => true,
            ])
            ->addColumn('site', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('ativar', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                $this->index('estabelecimento_id')
                    ->setName('fk_convenio_estabelecimento')
            )
            ->create();

        $this->table('convenios_profissionais')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('convenio_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('profissional_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                $this->index([
                        'convenio_id',
                        'profissional_id',
                    ])
                    ->setName('uk_convenio_profissional')
                    ->setType('unique')
            )
            ->addIndex(
                $this->index('profissional_id')
                    ->setName('fk_conv_prof_profissional')
            )
            ->create();

        $this->table('convenios_servicos')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('convenio_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('servico_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => 'CURRENT_TIMESTAMP',
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                $this->index([
                        'convenio_id',
                        'servico_id',
                    ])
                    ->setName('uk_convenio_servico')
                    ->setType('unique')
            )
            ->addIndex(
                $this->index('servico_id')
                    ->setName('fk_conv_serv_servico')
            )
            ->create();

        $this->table('estabelecimentos')
            ->addColumn('id', 'biginteger', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('conta_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addColumn('assinatura_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addColumn('razao_social', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('nome_fantasia', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('cpf_cnpj', 'string', [
                'default' => null,
                'limit' => 21,
                'null' => false,
            ])
            ->addColumn('logradouro', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('numero', 'string', [
                'default' => null,
                'limit' => 30,
                'null' => true,
            ])
            ->addColumn('complemento', 'string', [
                'default' => null,
                'limit' => 80,
                'null' => true,
            ])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('telefone', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('celular', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('ativar', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                $this->index('conta_id')
                    ->setName('idx_estabelecimento_conta')
            )
            ->addIndex(
                $this->index('assinatura_id')
                    ->setName('fk_estabelecimentos_assinaturas1_idx')
            )
            ->create();

        $this->table('estabelecimentos_extensoes')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('estabelecimento_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('extensao_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                $this->index('extensao_id')
                    ->setName('fk_estabelecimentos_has_extensoes_extensoes1_idx')
            )
            ->addIndex(
                $this->index('estabelecimento_id')
                    ->setName('fk_estabelecimentos_has_extensoes_estabelecimentos1_idx')
            )
            ->create();

        $this->table('extensoes')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('nome', 'string', [
                'default' => null,
                'limit' => 80,
                'null' => false,
            ])
            ->addColumn('descricao', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('valor', 'decimal', [
                'default' => '0.00',
                'null' => true,
                'precision' => 16,
                'scale' => 2,
                'signed' => true,
            ])
            ->addColumn('ativar', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('locais')
            ->addColumn('id', 'biginteger', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('estabelecimento_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('nome', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('endereco', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('ativar', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                $this->index('estabelecimento_id')
                    ->setName('idx_extensoes_estabelecimentos')
            )
            ->create();

        $this->table('periodos')
            ->addColumn('id', 'biginteger', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('estabelecimento_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('profissional_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'signed' => false,
            ])
            ->addColumn('servico_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'signed' => false,
            ])
            ->addColumn('local_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'signed' => false,
            ])
            ->addColumn('convenio_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'signed' => false,
            ])
            ->addColumn('data_hora_inicio', 'datetime', [
                'comment' => 'minutos desde 00:00',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('data_hora_fim', 'datetime', [
                'comment' => 'minutos desde 00:00',
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('dia_semana', 'tinyinteger', [
                'comment' => '1=domingo, 7=sábado',
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addColumn('data_personalizada', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('cadeia_id', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('status', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                $this->index('estabelecimento_id')
                    ->setName('idx_periodos_estabelecimentos')
            )
            ->addIndex(
                $this->index('profissional_id')
                    ->setName('idx_periodos_profissional')
            )
            ->addIndex(
                $this->index('servico_id')
                    ->setName('idx_periodos_servico')
            )
            ->addIndex(
                $this->index('local_id')
                    ->setName('idx_periodos_local')
            )
            ->addIndex(
                $this->index('dia_semana')
                    ->setName('idx_dia_semana')
            )
            ->addIndex(
                $this->index('data_personalizada')
                    ->setName('idx_data_personalizada')
            )
            ->addIndex(
                $this->index('convenio_id')
                    ->setName('fk_periodos_convenio')
            )
            ->create();

        $this->table('planos')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('nome', 'string', [
                'default' => null,
                'limit' => 80,
                'null' => false,
            ])
            ->addColumn('descricao', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('resumo', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('valor', 'decimal', [
                'default' => '0.00',
                'null' => true,
                'precision' => 16,
                'scale' => 2,
                'signed' => true,
            ])
            ->addColumn('numero_whatsapp', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'signed' => true,
            ])
            ->addColumn('ativar', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('profissionais')
            ->addColumn('id', 'biginteger', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('estabelecimento_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('nome', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('telefone', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('celular', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('cep', 'string', [
                'default' => null,
                'limit' => 8,
                'null' => true,
            ])
            ->addColumn('logradouro', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('numero', 'string', [
                'default' => null,
                'limit' => 30,
                'null' => true,
            ])
            ->addColumn('complemento', 'string', [
                'default' => null,
                'limit' => 80,
                'null' => true,
            ])
            ->addColumn('bairro', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true,
            ])
            ->addColumn('cidade', 'string', [
                'default' => null,
                'limit' => 120,
                'null' => true,
            ])
            ->addColumn('ativar', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                $this->index('estabelecimento_id')
                    ->setName('idx_profissionais_estabelecimentos')
            )
            ->create();

        $this->table('profissionais_servicos')
            ->addColumn('id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('profissional_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('servico_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('tem_horario_personalizado', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('tem_preco_personalizado', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('tem_duracao_personalizada', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                $this->index('servico_id')
                    ->setName('fk_profissionais_has_servicos_servicos1_idx')
            )
            ->addIndex(
                $this->index('profissional_id')
                    ->setName('fk_profissionais_has_servicos_profissionais1_idx')
            )
            ->create();

        $this->table('servicos')
            ->addColumn('id', 'biginteger', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('estabelecimento_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('nome', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('descricao', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('duracao', 'smallinteger', [
                'comment' => 'em minutos',
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addColumn('ativar', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                $this->index('estabelecimento_id')
                    ->setName('idx_servicos_estabelecimentos')
            )
            ->create();

        $this->table('status')
            ->addColumn('id', 'biginteger', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('estabelecimento_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('nome', 'string', [
                'default' => null,
                'limit' => 30,
                'null' => false,
            ])
            ->addColumn('ativar', 'boolean', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('cor', 'string', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                $this->index('id')
                    ->setName('id_UNIQUE')
                    ->setType('unique')
            )
            ->addIndex(
                $this->index('estabelecimento_id')
                    ->setName('fk_status_estabelecimentos1_idx')
            )
            ->create();

        $this->table('usuarios')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('conta_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addColumn('nome', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('password', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('ativar', 'boolean', [
                'default' => true,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('tipo', 'string', [
                'default' => 'usuario',
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                $this->index('email')
                    ->setName('email_user_UNIQUE')
                    ->setType('unique')
            )
            ->addIndex(
                $this->index('conta_id')
                    ->setName('fk_usuarios_contas1_idx')
            )
            ->create();

        $this->table('usuarios_estabelecimentos')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('usuario_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addColumn('estabelecimento_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                $this->index('estabelecimento_id')
                    ->setName('fk_usuarios_has_estabelecimentos_estabelecimentos1_idx')
            )
            ->addIndex(
                $this->index('usuario_id')
                    ->setName('fk_usuarios_has_estabelecimentos_usuarios1_idx')
            )
            ->create();

        $this->table('whatsapps')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('conta_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addColumn('estabelecimento_id', 'biginteger', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('phone_number', 'string', [
                'default' => null,
                'limit' => 60,
                'null' => false,
            ])
            ->addColumn('business_account', 'string', [
                'default' => null,
                'limit' => 60,
                'null' => false,
            ])
            ->addColumn('display_phone_number', 'string', [
                'default' => null,
                'limit' => 40,
                'null' => false,
            ])
            ->addColumn('access_token', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('nome', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('pin', 'string', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('senha_chatwoot', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('token_empresa', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                $this->index('phone_number')
                    ->setName('phone_number_UNIQUE')
                    ->setType('unique')
            )
            ->addIndex(
                $this->index('conta_id')
                    ->setName('fk_whatsapps_contas1_idx')
            )
            ->addIndex(
                $this->index('estabelecimento_id')
                    ->setName('fk_whatsapps_estabelecimentos1_idx')
            )
            ->create();

        $this->table('agendamentos')
            ->addForeignKey(
                $this->foreignKey('convenio_id')
                    ->setReferencedTable('convenios')
                    ->setReferencedColumns('id')
                    ->setOnDelete('SET_NULL')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_agendamentos_convenio')
            )
            ->addForeignKey(
                $this->foreignKey('cliente_id')
                    ->setReferencedTable('clientes')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_age_cliente')
            )
            ->addForeignKey(
                $this->foreignKey('estabelecimento_id')
                    ->setReferencedTable('estabelecimentos')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_age_estabelecimento')
            )
            ->addForeignKey(
                $this->foreignKey('local_id')
                    ->setReferencedTable('locais')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_age_local')
            )
            ->addForeignKey(
                $this->foreignKey('profissional_id')
                    ->setReferencedTable('profissionais')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_age_profissional')
            )
            ->addForeignKey(
                $this->foreignKey('servico_id')
                    ->setReferencedTable('servicos')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_age_servico')
            )
            ->addForeignKey(
                $this->foreignKey('statu_id')
                    ->setReferencedTable('status')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_age_status')
            )
            ->update();

        $this->table('assinaturas')
            ->addForeignKey(
                $this->foreignKey('conta_id')
                    ->setReferencedTable('contas')
                    ->setReferencedColumns('id')
                    ->setOnDelete('NO_ACTION')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_assinaturas_contas')
            )
            ->addForeignKey(
                $this->foreignKey('plano_id')
                    ->setReferencedTable('planos')
                    ->setReferencedColumns('id')
                    ->setOnDelete('NO_ACTION')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_assinaturas_planos1')
            )
            ->update();

        $this->table('bloqueios')
            ->addForeignKey(
                $this->foreignKey('estabelecimento_id')
                    ->setReferencedTable('estabelecimentos')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_b_estabelecimento')
            )
            ->addForeignKey(
                $this->foreignKey('local_id')
                    ->setReferencedTable('locais')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_b_local')
            )
            ->addForeignKey(
                $this->foreignKey('profissional_id')
                    ->setReferencedTable('profissionais')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_b_profissional')
            )
            ->addForeignKey(
                $this->foreignKey('servico_id')
                    ->setReferencedTable('servicos')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_b_servico')
            )
            ->update();

        $this->table('clientes')
            ->addForeignKey(
                $this->foreignKey('estabelecimento_id')
                    ->setReferencedTable('estabelecimentos')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_cli_estabelecimento')
            )
            ->update();

        $this->table('convenios')
            ->addForeignKey(
                $this->foreignKey('estabelecimento_id')
                    ->setReferencedTable('estabelecimentos')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_convenio_estabelecimento')
            )
            ->update();

        $this->table('convenios_profissionais')
            ->addForeignKey(
                $this->foreignKey('convenio_id')
                    ->setReferencedTable('convenios')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_conv_prof_convenio')
            )
            ->addForeignKey(
                $this->foreignKey('profissional_id')
                    ->setReferencedTable('profissionais')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_conv_prof_profissional')
            )
            ->update();

        $this->table('convenios_servicos')
            ->addForeignKey(
                $this->foreignKey('convenio_id')
                    ->setReferencedTable('convenios')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_conv_serv_convenio')
            )
            ->addForeignKey(
                $this->foreignKey('servico_id')
                    ->setReferencedTable('servicos')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_conv_serv_servico')
            )
            ->update();

        $this->table('estabelecimentos')
            ->addForeignKey(
                $this->foreignKey('assinatura_id')
                    ->setReferencedTable('assinaturas')
                    ->setReferencedColumns('id')
                    ->setOnDelete('NO_ACTION')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_estabelecimentos_assinaturas1')
            )
            ->addForeignKey(
                $this->foreignKey('conta_id')
                    ->setReferencedTable('contas')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_est_conta')
            )
            ->update();

        $this->table('estabelecimentos_extensoes')
            ->addForeignKey(
                $this->foreignKey('estabelecimento_id')
                    ->setReferencedTable('estabelecimentos')
                    ->setReferencedColumns('id')
                    ->setOnDelete('NO_ACTION')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_estabelecimentos_has_extensoes_estabelecimentos1')
            )
            ->addForeignKey(
                $this->foreignKey('extensao_id')
                    ->setReferencedTable('extensoes')
                    ->setReferencedColumns('id')
                    ->setOnDelete('NO_ACTION')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_estabelecimentos_has_extensoes_extensoes1')
            )
            ->update();

        $this->table('locais')
            ->addForeignKey(
                $this->foreignKey('estabelecimento_id')
                    ->setReferencedTable('estabelecimentos')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_le_estabelecimento')
            )
            ->update();

        $this->table('periodos')
            ->addForeignKey(
                $this->foreignKey('convenio_id')
                    ->setReferencedTable('convenios')
                    ->setReferencedColumns('id')
                    ->setOnDelete('SET_NULL')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_periodos_convenio')
            )
            ->addForeignKey(
                $this->foreignKey('estabelecimento_id')
                    ->setReferencedTable('estabelecimentos')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_pt_estabelecimento')
            )
            ->addForeignKey(
                $this->foreignKey('local_id')
                    ->setReferencedTable('locais')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_pt_local')
            )
            ->addForeignKey(
                $this->foreignKey('profissional_id')
                    ->setReferencedTable('profissionais')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_pt_profissional')
            )
            ->addForeignKey(
                $this->foreignKey('servico_id')
                    ->setReferencedTable('servicos')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_pt_servico')
            )
            ->update();

        $this->table('profissionais')
            ->addForeignKey(
                $this->foreignKey('estabelecimento_id')
                    ->setReferencedTable('estabelecimentos')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_pro_estabelecimento')
            )
            ->update();

        $this->table('profissionais_servicos')
            ->addForeignKey(
                $this->foreignKey('profissional_id')
                    ->setReferencedTable('profissionais')
                    ->setReferencedColumns('id')
                    ->setOnDelete('NO_ACTION')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_profissionais_has_servicos_profissionais1')
            )
            ->addForeignKey(
                $this->foreignKey('servico_id')
                    ->setReferencedTable('servicos')
                    ->setReferencedColumns('id')
                    ->setOnDelete('NO_ACTION')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_profissionais_has_servicos_servicos1')
            )
            ->update();

        $this->table('servicos')
            ->addForeignKey(
                $this->foreignKey('estabelecimento_id')
                    ->setReferencedTable('estabelecimentos')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_se_estabelecimento')
            )
            ->update();

        $this->table('status')
            ->addForeignKey(
                $this->foreignKey('estabelecimento_id')
                    ->setReferencedTable('estabelecimentos')
                    ->setReferencedColumns('id')
                    ->setOnDelete('NO_ACTION')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_status_estabelecimentos1')
            )
            ->update();

        $this->table('usuarios')
            ->addForeignKey(
                $this->foreignKey('conta_id')
                    ->setReferencedTable('contas')
                    ->setReferencedColumns('id')
                    ->setOnDelete('NO_ACTION')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_usuarios_contas1')
            )
            ->update();

        $this->table('usuarios_estabelecimentos')
            ->addForeignKey(
                $this->foreignKey('estabelecimento_id')
                    ->setReferencedTable('estabelecimentos')
                    ->setReferencedColumns('id')
                    ->setOnDelete('NO_ACTION')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_usuarios_has_estabelecimentos_estabelecimentos1')
            )
            ->addForeignKey(
                $this->foreignKey('usuario_id')
                    ->setReferencedTable('usuarios')
                    ->setReferencedColumns('id')
                    ->setOnDelete('NO_ACTION')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_usuarios_has_estabelecimentos_usuarios1')
            )
            ->update();

        $this->table('whatsapps')
            ->addForeignKey(
                $this->foreignKey('conta_id')
                    ->setReferencedTable('contas')
                    ->setReferencedColumns('id')
                    ->setOnDelete('NO_ACTION')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_whatsapps_contas1')
            )
            ->addForeignKey(
                $this->foreignKey('estabelecimento_id')
                    ->setReferencedTable('estabelecimentos')
                    ->setReferencedColumns('id')
                    ->setOnDelete('NO_ACTION')
                    ->setOnUpdate('NO_ACTION')
                    ->setName('fk_whatsapps_estabelecimentos1')
            )
            ->update();
    }

    /**
     * Down Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-down-method
     * @return void
     */
    public function down(): void
    {
        $this->table('agendamentos')
            ->dropForeignKey(
                'convenio_id'
            )
            ->dropForeignKey(
                'cliente_id'
            )
            ->dropForeignKey(
                'estabelecimento_id'
            )
            ->dropForeignKey(
                'local_id'
            )
            ->dropForeignKey(
                'profissional_id'
            )
            ->dropForeignKey(
                'servico_id'
            )
            ->dropForeignKey(
                'statu_id'
            )->save();

        $this->table('assinaturas')
            ->dropForeignKey(
                'conta_id'
            )
            ->dropForeignKey(
                'plano_id'
            )->save();

        $this->table('bloqueios')
            ->dropForeignKey(
                'estabelecimento_id'
            )
            ->dropForeignKey(
                'local_id'
            )
            ->dropForeignKey(
                'profissional_id'
            )
            ->dropForeignKey(
                'servico_id'
            )->save();

        $this->table('clientes')
            ->dropForeignKey(
                'estabelecimento_id'
            )->save();

        $this->table('convenios')
            ->dropForeignKey(
                'estabelecimento_id'
            )->save();

        $this->table('convenios_profissionais')
            ->dropForeignKey(
                'convenio_id'
            )
            ->dropForeignKey(
                'profissional_id'
            )->save();

        $this->table('convenios_servicos')
            ->dropForeignKey(
                'convenio_id'
            )
            ->dropForeignKey(
                'servico_id'
            )->save();

        $this->table('estabelecimentos')
            ->dropForeignKey(
                'assinatura_id'
            )
            ->dropForeignKey(
                'conta_id'
            )->save();

        $this->table('estabelecimentos_extensoes')
            ->dropForeignKey(
                'estabelecimento_id'
            )
            ->dropForeignKey(
                'extensao_id'
            )->save();

        $this->table('locais')
            ->dropForeignKey(
                'estabelecimento_id'
            )->save();

        $this->table('periodos')
            ->dropForeignKey(
                'convenio_id'
            )
            ->dropForeignKey(
                'estabelecimento_id'
            )
            ->dropForeignKey(
                'local_id'
            )
            ->dropForeignKey(
                'profissional_id'
            )
            ->dropForeignKey(
                'servico_id'
            )->save();

        $this->table('profissionais')
            ->dropForeignKey(
                'estabelecimento_id'
            )->save();

        $this->table('profissionais_servicos')
            ->dropForeignKey(
                'profissional_id'
            )
            ->dropForeignKey(
                'servico_id'
            )->save();

        $this->table('servicos')
            ->dropForeignKey(
                'estabelecimento_id'
            )->save();

        $this->table('status')
            ->dropForeignKey(
                'estabelecimento_id'
            )->save();

        $this->table('usuarios')
            ->dropForeignKey(
                'conta_id'
            )->save();

        $this->table('usuarios_estabelecimentos')
            ->dropForeignKey(
                'estabelecimento_id'
            )
            ->dropForeignKey(
                'usuario_id'
            )->save();

        $this->table('whatsapps')
            ->dropForeignKey(
                'conta_id'
            )
            ->dropForeignKey(
                'estabelecimento_id'
            )->save();

        $this->table('agendamentos')->drop()->save();
        $this->table('assinaturas')->drop()->save();
        $this->table('bloqueios')->drop()->save();
        $this->table('clientes')->drop()->save();
        $this->table('contas')->drop()->save();
        $this->table('convenios')->drop()->save();
        $this->table('convenios_profissionais')->drop()->save();
        $this->table('convenios_servicos')->drop()->save();
        $this->table('estabelecimentos')->drop()->save();
        $this->table('estabelecimentos_extensoes')->drop()->save();
        $this->table('extensoes')->drop()->save();
        $this->table('locais')->drop()->save();
        $this->table('periodos')->drop()->save();
        $this->table('planos')->drop()->save();
        $this->table('profissionais')->drop()->save();
        $this->table('profissionais_servicos')->drop()->save();
        $this->table('servicos')->drop()->save();
        $this->table('status')->drop()->save();
        $this->table('usuarios')->drop()->save();
        $this->table('usuarios_estabelecimentos')->drop()->save();
        $this->table('whatsapps')->drop()->save();
    }
}

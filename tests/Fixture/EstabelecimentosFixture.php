<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EstabelecimentosFixture
 */
class EstabelecimentosFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'estabelecimentos';
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'conta_id' => 1,
                'assinatura_id' => 1,
                'logo' => 'Lorem ipsum dolor sit amet',
                'razao_social' => 'Lorem ipsum dolor sit amet',
                'nome_fantasia' => 'Lorem ipsum dolor sit amet',
                'cpf_cnpj' => 'Lorem ipsum dolor s',
                'cep' => 'Lorem i',
                'logradouro' => 'Lorem ipsum dolor sit amet',
                'bairro' => 'Lorem ipsum dolor sit amet',
                'numero' => 'Lorem ipsum dolor sit amet',
                'complemento' => 'Lorem ipsum dolor sit amet',
                'email' => 'Lorem ipsum dolor sit amet',
                'telefone' => 'Lorem ipsum dolor ',
                'telefone_opcional' => 'Lorem ipsum dolor sit amet',
                'celular' => 'Lorem ipsum dolor ',
                'ativar' => 1,
                'created' => '2026-03-11 16:08:00',
                'modified' => '2026-03-11 16:08:00',
                'habilitar_programacao' => 1,
                'intervalo_atendimento' => 1,
                'retorno_atendimento' => 1,
                'cidade' => 'Lorem ipsum dolor sit amet',
                'uf' => 'Lo',
            ],
        ];
        parent::init();
    }
}

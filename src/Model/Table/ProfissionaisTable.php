<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Cake\Event\EventInterface;
use Cake\Datasource\EntityInterface;

/**
 * Profissionais Model
 *
 * @property \App\Model\Table\EstabelecimentosTable&\Cake\ORM\Association\BelongsTo $Estabelecimentos
 * @property \App\Model\Table\AgendamentosTable&\Cake\ORM\Association\HasMany $Agendamentos
 * @property \App\Model\Table\BloqueiosTable&\Cake\ORM\Association\HasMany $Bloqueios
 * @property \App\Model\Table\PeriodosTable&\Cake\ORM\Association\HasMany $Periodos
 * @property \App\Model\Table\ConveniosTable&\Cake\ORM\Association\BelongsToMany $Convenios
 * @property \App\Model\Table\ServicosTable&\Cake\ORM\Association\BelongsToMany $Servicos
 *
 * @method \App\Model\Entity\Profissional newEmptyEntity()
 * @method \App\Model\Entity\Profissional newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Profissional> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Profissional get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Profissional findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Profissional patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Profissional> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Profissional|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Profissional saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Profissional>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Profissional>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Profissional>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Profissional> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Profissional>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Profissional>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Profissional>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Profissional> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProfissionaisTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('profissionais');
        $this->setDisplayField('nome');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Estabelecimentos', [
            'foreignKey' => 'estabelecimento_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('Agendamentos', [
            'foreignKey' => 'profissional_id',
        ]);
        $this->hasMany('Bloqueios', [
            'foreignKey' => 'profissional_id',
        ]);
        /*$this->hasMany('Periodos', [
         'foreignKey' => 'profissional_id',
         ]);*/
        $this->hasMany('Periodos', [
            'foreignKey' => 'profissional_id',
            'saveStrategy' => 'replace',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
        $this->belongsToMany('Convenios', [
            'foreignKey' => 'profissional_id',
            'targetForeignKey' => 'convenio_id',
            'joinTable' => 'convenios_profissionais',
        ]);
        $this->belongsToMany('Servicos', [
            'foreignKey' => 'profissional_id',
            'targetForeignKey' => 'servico_id',
            'joinTable' => 'profissionais_servicos',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->notEmptyString('estabelecimento_id');

        $validator
            ->scalar('nome')
            ->minLength('nome', 5, __('O nome deve ter pelo menos 5 caracteres.'))
            ->maxLength('nome', 255)
            ->add('nome', 'fullName', [
                'rule' => 'isFullName',
                'provider' => 'table',
                'message' => __('Informe nome e sobrenome.'),
            ])
            ->requirePresence('nome', 'create')
            ->notEmptyString('nome', __('O nome é obrigatório.'));
        $validator
            ->requirePresence('documento', 'create', __('O documento é obrigatório.'))
            ->notEmptyString('documento', __('O documento é obrigatório.'))
            ->add('documento', 'validLength', [
                'rule' => ['custom', '/^(\d{11}|\d{14})$/'],
                'message' => __('O documento deve conter exatamente 11 ou 14 caracteres.')
            ]);


        $validator
            ->date('data_nascimento', ['dmy'], 'Informe uma data válida')
            ->requirePresence('data_nascimento', 'create')
            ->allowEmptyDate('data_nascimento');

        $validator
            ->scalar('conselho')
            ->allowEmptyString('conselho');

        $validator
            ->email('email')
            ->allowEmptyString('email');

        $validator
            ->scalar('telefone')
            ->minLength('telefone', 10, __('O Telefone deve conter 10 caracteres.'))
            ->maxLength('telefone', 14)
            ->allowEmptyString('telefone');

        $validator
            ->scalar('celular')
            ->minLength('celular', 11, __('O Celular deve conter 11 caracteres.'))
            ->maxLength('celular', 15)
            ->allowEmptyString('celular');

        $validator
            ->scalar('cep')
            ->minLength('cep', 8, __('O CEP deve conter 8 caracteres.'))
            ->maxLength('cep', 9)
            ->allowEmptyString('cep');

        $validator
            ->scalar('logradouro')
            ->maxLength('logradouro', 255)
            ->allowEmptyString('logradouro');

        $validator
            ->scalar('numero')
            ->maxLength('numero', 30)
            ->allowEmptyString('numero');

        $validator
            ->scalar('complemento')
            ->maxLength('complemento', 80)
            ->allowEmptyString('complemento');

        $validator
            ->scalar('bairro')
            ->maxLength('bairro', 100)
            ->allowEmptyString('bairro');

        $validator
            ->scalar('cidade')
            ->maxLength('cidade', 120)
            ->allowEmptyString('cidade');

        $validator
            ->scalar('uf')
            ->maxLength('uf', 2)
            ->allowEmptyString('uf');

        $validator
            ->boolean('ativar')
            ->allowEmptyString('ativar');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['estabelecimento_id'], 'Estabelecimentos'), ['errorField' => 'estabelecimento_id']);

        return $rules;
    }

    public function findAtivar(SelectQuery $query)
    {
        return $query->where(['Profissionais.ativar' => 1]);
    }


    public function isFullName($value, array $context): bool
    {
        if (!is_string($value)) {
            return false;
        }

        // Normaliza espaços
        $value = trim(preg_replace('/\s+/u', ' ', $value));

        // Separa por espaço
        $parts = explode(' ', $value);

        // Mantém apenas partes com pelo menos 2 letras (Unicode)
        $validParts = array_filter($parts, function ($w) {
            $lettersOnly = preg_replace('/[^\\p{L}]/u', '', $w);
            return mb_strlen($lettersOnly) >= 2;
        });

        // Precisa de pelo menos duas partes válidas
        return count($validParts) >= 2;
    }

    public function beforeMarshal(Event $event, \ArrayObject $data, \ArrayObject $options)
    {

        $caracterInvalido = [" ", ".", "-", "/", ")", "(", ","];

        if (isset($data['documento']))
            $data['documento'] = str_replace($caracterInvalido, "", $data['documento']);

        if (isset($data['cep']))
            $data['cep'] = str_replace($caracterInvalido, "", $data['cep']);

        if (isset($data['telefone']))
            $data['telefone'] = preg_replace('/[\p{L}]/', '', str_replace($caracterInvalido, "", $data['telefone']));

        if (isset($data['celular']))
            $data['celular'] = preg_replace('/[\p{L}]/', '', str_replace($caracterInvalido, "", $data['celular']));

    }


    public function beforeSave(EventInterface $event, EntityInterface $entity, \ArrayObject $options)
    {
        if ($entity->isNew() && empty($entity->cor)) {
            $entity->cor = $this->gerarCorUnica($entity->estabelecimento_id);
        }
    }


    private function gerarCorUnica(int $estabelecimentoId): string
    {
        $tabela = $this;

        $coresProibidas = [
            '#ADB5BD',
            '#FAED96',
            '#A5D4ED',
            '#A4E082',
            '#FF0000',
            '#FF7F7F',
            '#0000FF',
            '#A020F0'
        ];

        //  Busca cores já usadas
        $coresUsadas = $tabela->find()
            ->select(['cor'])
            ->where([
                'estabelecimento_id' => $estabelecimentoId,
                'cor IS NOT' => null
            ])->enableHydration(false)
            ->toArray();

        $usadas = array_map(
            fn($c) => strtoupper($c),
            array_column($coresUsadas, 'cor')
        );

        // Índice baseado na quantidade já usada
        $index = count($usadas);

        // Tenta gerar uma cor válida
        $tentativas = 0;

        do {
            $cor = $this->gerarCorHSLGoldenAngle($index + $tentativas);

            //  Igual já usada
            $existe = in_array($cor, $usadas);

            //  Parecida com proibidas
            $proximaDeProibida = false;
            foreach ($coresProibidas as $bloqueada) {
                if ($this->coresSaoParecidas($cor, $bloqueada, 80)) {
                    $proximaDeProibida = true;
                    break;
                }
            }

            //  Parecida com usadas
            $proximaDeUsada = false;
            foreach ($usadas as $usada) {
                if ($this->coresSaoParecidas($cor, $usada, 80)) {
                    $proximaDeUsada = true;
                    break;
                }
            }

            $tentativas++;

        } while (($existe || $proximaDeProibida || $proximaDeUsada) && $tentativas < 20);

        return $cor;
    }

    private function gerarCorVisivel(): string
    {
        // Gera uma cor aleatória saturada e contrastante
        do {
            $r = mt_rand(0, 200);
            $g = mt_rand(0, 200);
            $b = mt_rand(0, 200);
            $lum = $this->calcularLuminosidade($r, $g, $b);
        } while ($lum > 180 || $lum < 50); // evita muito clara ou muito escura

        return sprintf("#%02X%02X%02X", $r, $g, $b);
    }

    private function calcularLuminosidade(int $r, int $g, int $b): float
    {
        return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
    }

    private function coresSaoParecidas(string $cor1, string $cor2, int $limite = 60): bool
    {
        if (!preg_match('/^#[0-9A-F]{6}$/i', $cor1) || !preg_match('/^#[0-9A-F]{6}$/i', $cor2)) {
            return false; // evita erro silencioso
        }

        [$r1, $g1, $b1] = sscanf($cor1, "#%02x%02x%02x");
        [$r2, $g2, $b2] = sscanf($cor2, "#%02x%02x%02x");

        $distancia = sqrt(
            pow($r1 - $r2, 2) +
            pow($g1 - $g2, 2) +
            pow($b1 - $b2, 2)
        );

        return $distancia < $limite;
    }

    private function hslParaHex($h, $s, $l): string
    {
        $s /= 100;
        $l /= 100;

        $c = (1 - abs(2 * $l - 1)) * $s;
        $x = $c * (1 - abs(fmod($h / 60, 2) - 1));
        $m = $l - $c / 2;

        if ($h < 60) {
            $r = $c;
            $g = $x;
            $b = 0;
        } elseif ($h < 120) {
            $r = $x;
            $g = $c;
            $b = 0;
        } elseif ($h < 180) {
            $r = 0;
            $g = $c;
            $b = $x;
        } elseif ($h < 240) {
            $r = 0;
            $g = $x;
            $b = $c;
        } elseif ($h < 300) {
            $r = $x;
            $g = 0;
            $b = $c;
        } else {
            $r = $c;
            $g = 0;
            $b = $x;
        }

        $r = (int) (($r + $m) * 255);
        $g = (int) (($g + $m) * 255);
        $b = (int) (($b + $m) * 255);

        return sprintf("#%02X%02X%02X", $r, $g, $b);
    }

    private function gerarCorHSLGoldenAngle(int $index): string
    {
        $goldenAngle = 137.508;

        // Distribuição perfeita
        $h = fmod($index * $goldenAngle, 360);

        // Ajustes visuais (pode brincar aqui)
        $s = 75; // saturação
        $l = 55; // luminosidade

        return $this->hslParaHex($h, $s, $l);
    }
}
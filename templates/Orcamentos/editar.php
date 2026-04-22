<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Orcamento $orcamento
 * @var \Cake\Collection\CollectionInterface|string[] $estabelecimentos
 * @var \Cake\Collection\CollectionInterface|string[] $usuarios
 * @var \Cake\Collection\CollectionInterface|string[] $clientes
 * @var \Cake\Collection\CollectionInterface|string[] $consultas
 * @var \Cake\Collection\CollectionInterface|string[] $servicos
 */

$this->BreadcrumbsPlus->add([['title' => 'Orcamentos', 'url'=>['controller' => 'Orcamentos', 'action' => 'index']],['title'=> 'Editar']]);

$statusOrcamento    =   ['aberto' => 'Aberto', 'NAprovado' => 'Não aprovado','aprovado' => 'Aprovado'];

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden" id="orcamento-itens-app">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Orcamentos') ?></h4>
            </div>

            <?= $this->Form->create($orcamento) ?>
            <div class="row">
                    <div class="col-md-4 mb-3">
                        <?= $this->Form->control('cliente_id', ['type' => 'hidden']); ?>
                        <label for="titulo">Nome do cliente</label>
                        <div class="input-group text">
                            <input type="text" name="titulo" class="form-control" id="titulo"
                                autocomplete="off" aria-label="" aria-describedby="basic-addon1" value="<?= $orcamento->cliente->nome; ?>">
                            <button class="btn btn-primary" type="button" id="btnAbrirCadastroCliente"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Adicionar cliente">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                    <path d="M16 19h6" />
                                    <path d="M19 16v6" />
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                </svg>
                            </button>
                        </div>
                    </div>

            <div class="col-md-2 mb-3">
                    <?= $this->Form->control('validade', ['label' => 'Validade','class' => 'form-control']); ?>
            </div>
            <div class="col-md-2 mb-3">
                    <?= $this->Form->control('status',['label' => 'Status','class' => 'form-select', 'options' => $statusOrcamento]); ?>
            </div>
            <div class="col-md-2 mb-3">
                    <?= $this->Form->control('desconto',['label' => 'Desconto geral','class' => 'form-control','type'=> 'text','readOnly' => 'true', 'v-model'=>'totalDesconto', 'v-money3'=>"config"]); ?>
            </div>
            <div class="col-md-2 mb-3">
                    <?= $this->Form->control('valor_total', ['label' => 'Valor total','class' => 'form-control','type' => 'text','readOnly' => 'readOnly','v-model' => 'totalLiquido','v-money3' => "config"]); ?>
            </div>
            <div class="col-md-12 mb-3">
                    <?= $this->Form->control('observacoes',['label' => 'Observações gerais','class' => 'form-control',]); ?>
            </div>

            <div class="row">
                <div class="mt-3">
<?php if($orcamento->status == 'aberto'){?>
    <button type="button" class="btn btn-primary mb-3" @click="addItem">
        + Adicionar Serviço
    </button>
<?php } ?>
    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th width="30%">Serviço</th>
                <th width="30%">Profissional</th>
                <th width="8%">Qtd</th>
                <th width="10%">Desconto</th>                
                <th width="10%">Valor</th>
                <th width="10%">Total</th>
                <th width="50"></th>
            </tr>
        </thead>

        <tbody>
            <tr v-for="(item, index) in itens" :key="index">
            
                <!-- SERVIÇO -->
                <td>
<input type="hidden" :name="'orcamentos_servicos['+index+'][id]'" v-model="item.id">
                    <select class="form-select" @change="selecionarProcedimento(item, index)" :name="'orcamentos_servicos['+index+'][servico_id]'" v-model="item.servico_id">
                        <option value="">Selecione</option>
                        <?php foreach($servicos as $id => $nome): ?>
                            <option value="<?= $id ?>"><?= $nome ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>

                <!-- PROFISSIONAL -->
                <td>
                    <select class="form-select" :name="'orcamentos_servicos['+index+'][profissional_id]'" v-model="item.profissional_id">
                        <option value="">Selecione</option>
                        <?php foreach($profissionais as $id => $nome): ?>
                            <option value="<?= $id ?>"><?= $nome ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>

                <!-- QTD -->
                <td>
                    <input type="number" min="1" class="form-control" :name="'orcamentos_servicos['+index+'][quantidade]'" v-model="item.quantidade">
                </td>
                <!-- DESCONTO -->
                <td>
                    <input type="text" class="form-control" :name="'orcamentos_servicos['+index+'][desconto]'" v-model="item.desconto" v-money3="config" @keyup="onMoneyInput(item, 'desconto', $event)">
                </td>
                <!-- VALOR -->
                <td>
                    <input type="text" class="form-control" :name="'orcamentos_servicos['+index+'][valor_unitario]'" v-model="item.valor_unitario" v-money3="config" @keyup="onMoneyInput(item, 'valor_unitario', $event)">
                </td>



                <!-- TOTAL LINHA -->
                <td>
                     {{ formatMoney(calcularTotalItem(item)) }}
                </td>

                <!-- REMOVER -->
                <td class="text-center">
<?php if($orcamento->status == 'aberto'){?>
                    <button type="button" class="btn btn-danger btn-sm"
                        @click="removerItem(item, index)">
                        X
                    </button>
<?php } ?>
                </td>
            </tr>
        </tbody>

        <tfoot>
            <tr>
                <td colspan="5" class="text-end fw-bold">Total Geral:</td>
                <td colspan="2" class="fw-bold">
                    {{ formatMoney(totalLiquido) }}
                </td>
            </tr>
        </tfoot>
    </table>
</div>
            </div>
    <div class="col-md-12">
    <div class="row">
<?php if($orcamento->status == 'aberto'){?>
        <div class="col-md-2 offset-md-10  mb-3">
    <?= $this->Form->button(__('Salvar'),['class' => 'justify-content-center w-100 btn btn-success d-flex align-items-center']) ?>
        </div>
<?php } ?>
    <?= $this->Form->end() ?>
    </div>
    </div>

</div>

        </div>
    </div>
</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<?= $this->Html->script('vue/generic-modal.js') ?>
<?= $this->Html->script('vue/vue-masks.js') ?>
<?= $this->Html->script('vue/form-cadastrar.js') ?>
<?= $this->Html->script('vue/cep-autocomplete.js') ?>

<?= $this->Html->script([
    'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js',
    'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js',
    'https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.19/locales-all.global.min.js',
    'https://code.jquery.com/ui/1.14.1/jquery-ui.min.js',
    'jquery.form.min.js',
    'ui.datepicker-pt-BR.js',
    'plugins/bootstrap-select-min.js',
    'https://unpkg.com/v-money3@3.24.1/dist/v-money3.umd.js'
]); ?>

<?= $this->Html->css(['https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.14.1/themes/base/jquery-ui.min.css', 'plugins/bootstrap-select.css']); ?>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
const { createApp } = Vue;
const { Money3Directive } = window['v-money3'];
const dadosBackend = <?= json_encode($orcamento->orcamentos_servicos) ?>;

createApp({
    data() {
        return {
            itens: [this.novoItem()],
            config: {
                prefix: '',
                suffix: '',
                thousands: '.',
                decimal: ',',
                precision: 2,
                disableNegative: true,
                
            }
        }
    },

    directives: {
        money3: Money3Directive
    },

    methods: {
        onMoneyInput(item, campo, event) {
            // força atualização imediata do valor digitado
            item[campo] = event.target.value;
        },
        //  conversão 100% segura
        toNumber(valor) {

            if (valor === null || valor === undefined || valor === '') return 0;

            if (typeof valor === 'number') return valor;

            let v = valor.toString().trim();

            //  se tem vírgula, é formato BR
            if (v.includes(',')) {
                v = v.replace(/\./g, ''); // remove milhar
                v = v.replace(',', '.');  // decimal
            }

            //  se NÃO tem vírgula, já é número puro
            const n = Number(v);

            return isNaN(n) ? 0 : n;
        },

        //  formatação padrão BR
        formatMoney(valor) {
            return this.toNumber(valor).toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        },

        novoItem() {
            return {
                servico_id: '',
                profissional_id: '',
                quantidade: 1,
                valor_unitario: '0,00',
                desconto: '0,00'
            }
        },

        addItem() {
            this.itens.push(this.novoItem());
            this.$forceUpdate();
        },

        removeItem(index) {
            this.itens.splice(index, 1);
            this.$forceUpdate();
        },

        calcularTotalItem(item) {

            const qtd = this.toNumber(item.quantidade);
            const valor = this.toNumber(item.valor_unitario);
            const desconto = this.toNumber(item.desconto);

            return (qtd * valor) - desconto;
        },

        selecionarProcedimento(item, index) {
            if (!item.servico_id) return;

            axios.get(
                "<?= $this->Url->build(['controller' => 'Servicos', 'action' => 'view'], ['fullBase' => true]); ?>/" + item.servico_id + '.json',
                {
                    headers: {
                        "X-CSRF-Token": "<?= $this->request->getAttribute('csrfToken') ?>"
                    }
                }
            )
            .then((response) => {
                //  já formata corretamente ao preencher
                this.itens[index].valor_unitario = response.data.servico.valor;
            })
            .catch((error) => {
                console.error(error);
            });
        },
        removerItem(item, index) {

            // se ainda não foi salvo
            if (!item.id) {
                this.itens.splice(index, 1);
                return;
            }

            Swal.fire({
                title: "Excluir item?",
                text: "Este item será removido permanentemente.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sim, excluir",
                cancelButtonText: "Cancelar"
            }).then(result => {

                if (!result.isConfirmed) return;

                Swal.fire({
                    title: 'Removendo...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => Swal.showLoading()
                });

                axios.delete(
                    "<?= $this->Url->build(['controller' => 'OrcamentosServicos', 'action' => 'delete'], ['fullBase' => true]); ?>/" + item.id + ".json",
                    {
                        headers: {
                            'X-CSRF-Token': "<?= $this->request->getAttribute('csrfToken') ?>"
                        }
                    }
                )
                .then(response => {

                    Swal.close();

                    if (response.data && response.data.success) {

                        this.itens.splice(index, 1);

                        Swal.fire({
                            title: "Removido!",
                            text: response.data.message || "Item removido com sucesso.",
                            icon: "success"
                        });

                    } else {

                        Swal.fire({
                            title: "Erro",
                            text: response.data.message || "Erro ao remover item.",
                            icon: "warning"
                        });

                    }

                })
                .catch((error) => {

                    Swal.close();

                    console.error(error);

                    Swal.fire({
                        title: "Erro",
                        text: error.response?.data?.message || "Erro ao remover o item.",
                        icon: "error"
                    });

                });

            });
        }
    },

    computed: {

        totalBruto() {
            return this.itens.reduce((total, item) => {
                return total + (
                    this.toNumber(item.quantidade) *
                    this.toNumber(item.valor_unitario)
                );
            }, 0);
        },

        totalDesconto() {
            const soma = this.itens.reduce((total, item) => {
                return total + this.toNumber(item.desconto);
            }, 0);

            return this.formatMoney(soma);
        },

        totalLiquido() {
            return this.formatMoney(this.toNumber(this.totalBruto) - this.toNumber(this.totalDesconto));
        }
    },
    mounted() {

        if (typeof dadosBackend !== 'undefined' && dadosBackend.length) {

            this.itens = dadosBackend.map(item => {
                return {
                    id: item.id || null,
                    servico_id: item.servico_id || '',
                    profissional_id: item.profissional_id || '',
                    quantidade: item.quantidade || 1,

                    // importante: converter para formato que o input usa
                    valor_unitario: this.formatMoney(item.valor_unitario),
                    desconto: this.formatMoney(item.desconto)
                }
            });

        } else {
            this.itens = [this.novoItem()];
        }
    }

}).mount('#orcamento-itens-app');
</script>

<script>
    $.datepicker.setDefaults($.datepicker.regional['pt-BR']);
    $(function () {

        var ac_config_cliente = {
            source: function (request, response) {
                $.getJSON("<?= $this->Url->build(['controller' => 'Clientes', 'action' => 'localizar'], ['fullBase' => true]); ?>?termo=" + request.term, function (data) {
                    response($.map(data, function (value, key) {

                        return {
                            id: value.id,
                            value: value.label,
                            aniversario: value.aniversario
                        };

                    }));
                });
            },
            select: function (event, ui) {
                $('#cliente-id').val(ui.item.id);
            },
            autoFocus: true,
            minLength: 1

        };

        $.ui.autocomplete.prototype._renderMenu = function (ul, items) {
            var self = this;
            //table definitions
            $.each(items, function (index, item) {
                self._renderItemData(ul, item);
            });
        };

        $.ui.autocomplete.prototype._renderItemData = function (ul, item) {
            return this._renderItem(ul, item).data("ui-autocomplete-item", item);
        };

        $.ui.autocomplete.prototype._renderItem = function (ul, item) {

            var avatar = '<?= $this->Url->build('/img/profile/', ['fullBase' => true]); ?>usuario-padrao.png';

            if (item.avatar != null)
                avatar = '<?= $this->Url->build('/img/', ['fullBase' => true]); ?>' + item.avatar;

            var aniversario = '';
            var felicitacoes = '';

            if (item.aniversario) {
                felicitacoes = 'Feliz aniversário!!!';
                aniversario = 'aniversario';
            }


            return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<table class='" + aniversario + "' style='width:100%;'><tr><td style='width: 20%; padding-left: 5px; padding-top: 5px; padding-bottom: 5px;'><img class='rounded-circle' width='64' height='64' src='" + avatar + "'></td><td style='text-align: left;'>" + item.value + "<br>" + felicitacoes + "</td></tr></table>")
                .appendTo(ul);
        };


        $("#titulo").autocomplete(ac_config_cliente);
    });

</script>

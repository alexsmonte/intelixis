<?php

$this->BreadcrumbsPlus->add([['title' => 'Orcamentos']]);
$statusOrcamento    =   ['aberto' => 'Aberto', 'NAprovado' => 'Não aprovado','aprovado' => 'Aprovado'];
?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div id="appOrcamento" class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">

            <!-- HEADER -->
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Orcamentos') ?></h4>
                <div class="ms-auto flex-shrink-0">
                        <button class="btn btn-novo bg-primary-subtle text-primary border-primary d-flex align-items-center justify-content-center" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<iconify-icon icon="solar:list-arrow-down-bold" class="fs-7"></iconify-icon> 
                        </button>

                    <ul class="dropdown-menu">
                        <li><?= $this->Html->link('<i class="fa fa-plus"></i> Novo Orcamento', ['action' => 'novo'], ['class' => 'dropdown-item','escape'=>false]) ?></li>
                    </ul>
                </div>
            </div>

            <!-- FILTROS -->
            <div class="row">
                <div class="col-md-4 mb-3">
                    <?= $this->Form->control('nome', ['class'=>'form-control rounded-pill','label'=>'Buscar por nome do cliente, cpf, celular','type'=>'text','empty'=>true,'v-model'=>'filtros.q']); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('profissional_id', ['class'=>'form-select  rounded-pill','label'=>'Profissionais','options'=>$profissionais,'empty'=>true,'v-model'=>'filtros.profissional_id']); ?>
                </div>
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('servico_id', ['label'=>'Serviços','options'=>$servicos, 'class'=>'form-select  rounded-pill','v-model'=>'filtros.servico_id'
                    ]); ?>
                </div>
                <div class="col-md-2 mb-3">
                    <?= $this->Form->control('status', ['label'=>'Status','class'=>'form-select rounded-pill','v-model'=>'filtros.status', 'options' => $statusOrcamento]); ?>
                </div>
                <div class="col-md-12 mb-3">
                    <div class="row">
                        <div class="col-md-2 offset-md-10">
                            <button type="button" class="btn btn-primary w-100" @click="onSearch(1)">Pesquisar</button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- TABELA -->
            <div class="table-responsive mb-4 border rounded-1" style="min-height: 250px;">
                <table class="table table-striped text-nowrap mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Validade</th>
                            <th>Status</th>
                            <th>Desconto</th>
                            <th>Valor Total</th>
                            <th>Criado em</th>
                            <th>Opções</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr v-if="orcamentos.length === 0">
                            <td colspan="7" class="text-center">
                                Nenhum registro encontrado
                            </td>
                        </tr>

                        <tr v-for="orcamento in orcamentos" :key="orcamento.id">
                            <td>{{ orcamento.cliente?.nome }}</td>
                            <td>{{ new Date(orcamento.validade + 'T00:00:00').toLocaleDateString('pt-BR', {
                                    day:'2-digit',
                                    month:'2-digit',
                                    year:'numeric',
                                }) }}</td>
                            <td>
                                <span class="mb-1 badge rounded-pill" :class="getStatusBadge(orcamento.status).class"> {{ getStatusBadge(orcamento.status).text }}</span>
                            </td>
                            <td>R$ {{ orcamento.desconto }}</td>
                            <td>R$ {{ orcamento.valor_total }}</td>
                            <td>
                                {{ new Date(orcamento.created).toLocaleDateString('pt-BR', {
                                    day:'2-digit',
                                    month:'2-digit',
                                    year:'numeric',
                                    hour:'2-digit',
                                    minute:'2-digit'
                                }) }}
                            </td>

                            <td>
<div class="dropdown">
<button class="btn  bg-transparent text-primary  d-inline-flex align-items-center justify-content-center" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-bars"></i></button>
<ul class="dropdown-menu dropdown-menu-end">
<li class="dropdown-item">
<a class="link-state link-primary2"
:href="`<?= $this->Url->build(['action'=>'editar'], ['fullBase'=>true]) ?>/${orcamento.id}`">
<iconify-icon icon="solar:document-add-bold-duotone" class="fs-7"></iconify-icon>Editar</a>
</li>

    <li class="dropdown-item" v-if="orcamento.status !== 'aprovado'">
    <a :href="`<?= $this->Url->build(['action'=>'imprimir'], ['fullBase'=>true]) ?>/${orcamento.id}`" target="_blank" class="link-state link-primary2" title="Imprimir"><iconify-icon icon="solar:printer-bold-duotone" class="fs-7"></iconify-icon>Imprimir</a>
    </li>
<li class="dropdown-item" v-if="orcamento.status !== 'aprovado'"><a href="#" class="link-state link-danger2" title="Excluir" @click.prevent="onDelete(orcamento.id)">
<iconify-icon icon="solar:trash-bin-minimalistic-bold-duotone" class="fs-7"></iconify-icon> Excluir</a></li>
<li class="dropdown-item" v-if="orcamento.status === 'aberto'">
    <a href="#"
       class="link-state link-success"
       @click.prevent="abrirModalAprovar(orcamento)">
       
        <iconify-icon icon="solar:checklist-minimalistic-bold-duotone" class="fs-7"></iconify-icon>
        Aprovar
    </a>
</li>
</ul>
</div>
                            </td>
                        </tr>

                    </tbody>
                </table>

                <!-- PAGINADOR -->
                <nav aria-label="Page navigation example" v-if="paginator.pageCount > 1">
                    <ul class="pagination justify-content-center">

                        <!-- ANTERIOR -->
                        <li class="page-item" :class="{ disabled: paginator.page === 1 }">
                            <a class="page-link"
                               href="javascript:void(0)"
                               @click="onSearch(paginator.page - 1)">
                                Anterior
                            </a>
                        </li>

                        <!-- PÁGINAS -->
                        <li class="page-item" v-for="page in paginas" :key="page" :class="{ active: page === paginator.page }">
                            <a class="page-link"
                               href="javascript:void(0)"
                               @click="onSearch(page)">
                                {{ page }}
                            </a>
                        </li>

                        <!-- PRÓXIMO -->
                        <li class="page-item" :class="{ disabled: paginator.page === paginator.pageCount }">
                            <a class="page-link"
                               href="javascript:void(0)"
                               @click="onSearch(paginator.page + 1)">
                                Próximo
                            </a>
                        </li>

                    </ul>
                </nav>
            </div>

        </div>
    </div>

<div v-if="modalAprovar.show" class="modal fade show d-block">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Aprovar Orçamento</h5>
                <button class="btn-close" @click="modalAprovar.show = false"></button>
            </div>

            <div class="modal-body">

                <p><strong>Cliente:</strong> {{ modalAprovar.orcamento?.cliente?.nome }}</p>

                <!-- ESCOLHA -->
                <div class="mb-3">
                    <label>
                        <input type="radio" value="existente" v-model="modalAprovar.modo">
                        Usar consulta existente
                    </label>

                    <label class="ms-3">
                        <input type="radio" value="nova" v-model="modalAprovar.modo">
                        Nova consulta
                    </label>
                </div>

                <!-- LISTA CONSULTAS -->
                <div v-if="modalAprovar.modo === 'existente'">
                    <select class="form-select" v-model="modalAprovar.consultaSelecionada">
                        <option v-for="c in modalAprovar.consultas" :value="c.id">
                            Consulta #{{ c.id }} - {{ c.created }}
                        </option>
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" @click="modalAprovar.show = false">Cancelar</button>
                <button class="btn btn-success" @click="confirmarAprovacao">Confirmar</button>
            </div>

        </div>
    </div>
</div>

</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>

<script>
const orcamentosInit = <?= json_encode($orcamentos->toArray()) ?>;

Vue.createApp({
    data() {
        return {
            profissionalSelecionado: null,
            profissionais: [],
            modalAprovar: {
                show: false,
                orcamento: null,
                consultas: [],
                consultaSelecionada: null,
                modo: 'existente' // ou 'nova'
            },
            paginator: {
                  page: 1,
                  limit: 50,
                  total: 0,
                  pageCount: 1
            },
            filtros: {
                q: '',
                validade: '',
                profissional_id:'',
                status: '',
                servico_id: ''
            },
            orcamentos: orcamentosInit
        }
    },
    methods: {
        async confirmarAprovacao() {

            const payload = {
                orcamento_id: this.modalAprovar.orcamento.id,
                modo: this.modalAprovar.modo,
                consulta_id: this.modalAprovar.consultaSelecionada
            };

            // LOADING
            Swal.fire({
                title: 'Aguarde...',
                text: 'Aprovando orçamento...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => Swal.showLoading()
            });

            try {

                const res = await fetch('<?= $this->Url->build(['controller' => 'Orcamentos', 'action' => 'aprovar']); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?= $this->request->getAttribute('csrfToken') ?>'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();

                // FECHA LOADING
                Swal.close();

                if (data.success) {

                    await Swal.fire({
                        icon: 'success',
                        title: 'Aprovado com sucesso!',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    this.modalAprovar.show = false;

                    await this.onSearch(this.paginator.page);

                } else {
                    Swal.fire('Erro', data.message || 'Falha ao aprovar', 'error');
                }

            } catch (err) {

                Swal.close();

                Swal.fire('Erro', 'Falha na requisição', 'error');
            }
        },
        async abrirModalAprovar(orcamento) {

            this.modalAprovar.orcamento = orcamento;
            this.modalAprovar.show = true;

            // busca consultas do cliente

            const res = await fetch(`<?= $this->Url->build(['controller' => 'Consultas', 'action' => 'search']); ?>?cliente_id=${orcamento.cliente_id}`);
            const data = await res.json();

            this.modalAprovar.consultas = data.data || [];
        },
        getStatusBadge(status) {

            const map = {
                aberto: {
                    text: 'Aberto',
                    class: 'text-bg-primary'
                },
                NAprovado: {
                    text: 'Não aprovado',
                    class: 'text-bg-danger'
                },
                aprovado: {
                    text: 'Aprovado',
                    class: 'text-bg-success'
                }
            };

            return map[status] || {
                text: status,
                class: 'text-bg-secondary'
            };
        },
        async onSearch(page = 1) {

            // bloqueia páginas inválidas
            if (page < 1 || page > this.paginator.pageCount) {
                return;
            }

            try {
                const params = new URLSearchParams();

                Object.keys(this.filtros).forEach(key => {
                    const value = this.filtros[key];

                    if (value && value.length !== 0) {
                        if (Array.isArray(value)) {
                            value.forEach(v => params.append(`${key}[]`, v));
                        } else {
                            params.append(key, value);
                        }
                    }
                });

                params.append('page', page);
                params.append('limit', this.paginator.limit);

                const url = "<?= $this->Url->build(['controller' => 'Orcamentos', 'action' => 'search']); ?>?" + params.toString();

                const res = await fetch(url, {
                    credentials: 'same-origin'
                });

                const data = await res.json();

                this.orcamentos = data.data || [];
                this.paginator = data.paginator || {};

            } catch (err) {
                console.error(err);
                alert('Erro ao buscar orçamentos.');
            }
        },
        async onDelete(id) {

            const result = await Swal.fire({
                title: 'Confirmar exclusão',
                text: 'Deseja excluir este orçamento?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim',
                cancelButtonText: 'Cancelar'
            });

            if (!result.isConfirmed) return;

            try {
                const response = await fetch(`<?= $this->Url->build(['action'=>'delete']); ?>/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-Token': '<?= $this->request->getAttribute('csrfToken') ?>',
                        'X-HTTP-Method-Override': 'DELETE',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    await Swal.fire({
                        icon:'success',
                        title:'Excluído',
                        timer:1500,
                        showConfirmButton:false
                    });
                    //this.orcamentos = this.orcamentos.filter(o => o.id !== id);
                    await this.onSearch(this.paginator.page);
                } else {
                    Swal.fire('Erro', data.message, 'error');
                }

            } catch(e) {
                Swal.fire('Erro','Falha ao excluir','error');
            }
        }
    },
    computed: {
        paginas() {
            const total = this.paginator.pageCount;
            const atual = this.paginator.page;

            let inicio = Math.max(1, atual - 2);
            let fim = Math.min(total, atual + 2);

            const pages = [];
            for (let i = inicio; i <= fim; i++) {
                pages.push(i);
            }

            return pages;
        }
    }

}).mount('#appOrcamento');
</script>

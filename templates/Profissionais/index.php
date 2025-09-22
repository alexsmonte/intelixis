<?php
$this->BreadcrumbsPlus->add([['title' => 'Profissionais']]);
?>
<?= $this->element('barra-titulo', ['title' => $title]); ?>



<!-- Modal Visualizar Profissional -->
<div class="modal fade" id="view-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <div class="pb-1 mb-2 border-bottom w-100">
                    <h5>Detalhes do Profissional</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body text-start" id="modal-body-content">
                <i class="fa fa-spinner fa-spin fa-2x"></i>
            </div>
        </div>
    </div>
</div>


<div id="app" class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">

            



            <div class="row mb-3 align-items-center">
                <!-- Coluna da busca -->
                <div class="col-md-6">
                    <form @submit.prevent="onSearch" class="mb-0">
                        <div class="input-group mb-3">
                            <input v-model="query" type="text" class="form-control form-control-lg placeholdert"
                                placeholder="Buscar profissionais por nome ou email">
                            <button class="btn border border-primary-subtle btn-primary search-btn" type="submit"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Pesquisar">
                                <i class="ti ti-search fs-5"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Coluna do botão Novo -->
                <div class="col-md-6 d-flex justify-content-end">
                    <?= $this->Html->link(
                        '<i class="ti ti-user-plus"></i><span class="btn-text ms-2">Novo</span>',
                        ['action' => 'novo'],
                        [
                            'class' => 'btn btn-rounded btn-primary d-flex align-items-center',
                            'escape' => false
                        ]
                    ) ?>
                </div>
            </div>







            <!-- TABELA -->
            <div class="table-responsive mb-4 border rounded-1">
                <table class="table table-striped text-nowrap mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Estabelecimento</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Celular</th>
                            <th>Status</th>
                            <th>Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="profissional in profissionais" :key="profissional.id">
                            <td>{{ profissional.estabelecimento.razao_social || '' }}</td>
                            <td>{{ profissional.nome }}</td>
                            <td>{{ profissional.email }}</td>
                            <td>{{ profissional.celular }}</td>
                            <td><span v-if="profissional.ativar"
                                    class="badge bg-success-subtle text-success">Ativo</span>
                                <span v-else class="badge bg-danger-subtle text-danger">Inativo</span>
                            </td>

                            <td>
                                <div class="d-flex gap-3">
                                    <!-- Visualizar -->
                                    <a href="javascript:void(0);" @click="viewProfissional(profissional.id)"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Visualizar">
                                        <i class="ti ti-eye fs-5 text-info"></i>
                                    </a>

                                    <!-- Editar -->
                                    <a :href="`<?= $this->Url->build(['controller' => 'Profissionais', 'action' => 'editar']); ?>/${profissional.id}`"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Editar">
                                        <i class="ti ti-edit fs-5 text-warning"></i>
                                    </a>

                                    <!-- Excluir -->
                                    <form
                                        :action="'<?= $this->Url->build(['controller' => 'Profissionais', 'action' => 'delete']); ?>/' + profissional.id"
                                        method="post" @submit.prevent="confirmarExclusao($event)"
                                        style="display:inline;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_csrfToken" :value="csrfToken">
                                        <button type="submit" class="bg-transparent border-0 p-0"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Excluir">
                                            <i class="ti ti-trash fs-5 text-danger"></i>
                                        </button>
                                    </form>
                                </div>

                            </td>
                        </tr>
                        <tr v-if="profissionais.length === 0">
                            <td colspan="8" class="text-center">Nenhum profissional encontrado</td>
                        </tr>
                    </tbody>
                </table>





                <div class="paginator mt-4">
                    <ul class="pagination justify-content-center">
                        <?= $this->Paginator->first('<< ') ?>
                        <?= $this->Paginator->prev('< ') ?>
                        <?= $this->Paginator->numbers() ?>
                        <?= $this->Paginator->next(' >') ?>
                        <?= $this->Paginator->last(' >>') ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- vue  -->
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script>
    const profissionaisInit = <?= json_encode($profissionais->toArray()) ?>;

    Vue.createApp({
        data() {
            return {
                query: '',
                profissionais: profissionaisInit,
                profissionalId: null,
                csrfToken: "<?= $this->request->getAttribute('csrfToken'); ?>"
            }
        },
        methods: {
            async onSearch() {
                try {
                    // URL relativa do endpoint
                    const url = "<?= $this->Url->build(['controller' => 'Profissionais', 'action' => 'search']); ?>?q=" + encodeURIComponent(this.query);



                    const res = await fetch(url, {
                        credentials: 'same-origin' // envia cookies de sessão
                    });

                    // lê JSON mesmo que haja redirect
                    const data = await res.json();
                    this.profissionais = data.data || [];
                } catch (err) {
                    console.error(err);
                    alert('Erro ao buscar profissionais. Verifique console para detalhes.');
                }
            },
            async viewProfissional(id) {
                try {
                    const res = await fetch(`<?= $this->Url->build(['controller' => 'Profissionais', 'action' => 'visualizar']); ?>/${id}`, {
                        credentials: 'same-origin',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    if (!res.ok) throw new Error('Erro ao carregar profissional');

                    const html = await res.text();

                    // Adiciona o conteúdo no modal e inclui botão Editar
                    const modalEl = document.getElementById('view-modal');
                    modalEl.querySelector('#modal-body-content').innerHTML = `
            <div>
                ${html} <!-- conteúdo da view visualizar_tabela.php -->
                <div class="modal-footer mt-3">
                    <a href="<?= $this->Url->build(['controller' => 'Profissionais', 'action' => 'editar']); ?>/${id}" class="btn btn-primary">Editar</a>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        `;

                    // Inicializa e abre o modal
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();

                } catch (err) {
                    console.error(err);
                    alert('Erro ao carregar profissional.');
                }

            },

            confirmarExclusao(event) {
                event.preventDefault();
                const form = event.target;

                Swal.fire({
                    title: "Tem certeza que deseja excluir este Profissional?",
                    text: "Esta ação não pode ser desfeita.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Sim, excluir!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // só envia se confirmar
                    }
                });
            }

        }

    }).mount('#app');
</script>
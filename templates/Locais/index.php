<?php
$this->BreadcrumbsPlus->add([['title' => 'Locais']]);
?>
<?= $this->element('barra-titulo', ['title' => $title]); ?>

<!-- Modal Visualizar Local -->
<div class="modal fade" id="view-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <div class="pb-1 mb-2 border-bottom w-100">
                    <h5>Detalhes do Local</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body text-start" id="modal-body-content">
                <i class="ti ti-spinner fa-spin fa-2x"></i>
            </div>
        </div>
    </div>
</div>

<div id="app" class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">

            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Locais') ?></h4>

            </div>

            <!-- BUSCA -->
            <div class="row mb-3">
                <div class="col-md-6 d-flex">
                    <input v-model="query" type="text" class="form-control"
                        placeholder="Buscar locais por nome ou email">
                    <button class="btn btn-primary ms-2" @click="onSearch">Pesquisar</button>
                </div>
            </div>

            <!-- LAYOUT DE CARDS -->
            <div class="row g-3 mt-10">
                <div v-if="locais.length > 0" v-for="local in locais" :key="local.id" class="col-md-4">
                    <div class="card h-100 shadow-sm rounded-4">
                        <a :href="`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(local.endereco)}`"
                            target="_blank" class="map-link">
                            <div class="map-container">
                                <iframe width="100%" height="200" frameborder="0" style="border:0"
                                    :src="`https://maps.google.com/maps?q=${encodeURIComponent(local.endereco)}&z=15&output=embed`"
                                    allowfullscreen>
                                </iframe>
                            </div>
                        </a>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-truncate">{{ local.nome }}</h5>
                            <p class="card-text">
                                <i class="ti ti-map-pin text-primary me-2"></i><span style="white-space: pre-wrap;">{{
                                    local.endereco }}</span>
                            </p>
                            <span v-if="local.ativar" class="badge bg-success-subtle text-success mt-2">Ativo</span>
                            <span v-else class="badge bg-danger-subtle text-danger mt-2">Inativo</span>
                            <div class="mt-3 d-flex justify-content-end align-items-center">
                                <a :href="`<?= $this->Url->build(['controller' => 'Locais', 'action' => 'editar']); ?>/${local.id}`"
                                    class="btn btn-warning btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Editar Local">
                                    <i class="ti ti-edit"></i>
                                </a>




                                <button class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Excluir Local" @click="confirmarExclusao(local.id)">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card para Adicionar Novo Local -->
                <div class="col-md-4">
                    <div
                        class="card h-100 shadow-sm rounded-4 card-add d-flex justify-content-center align-items-center">
                        <?= $this->Html->link(
                            '<div class="d-flex flex-column align-items-center text-center">
                                <i class="ti ti-circle-plus ti-xl mb-2 text-primary"></i>
                                <span>Adicionar Novo Local</span>
                            </div>',
                            ['action' => 'novo'],
                            ['escape' => false, 'class' => 'text-decoration-none text-muted']
                        ) ?>
                    </div>
                </div>
                <div v-if="locais.length === 0" class="col-12">
                    <div class="alert alert-info text-center" role="alert">
                        Nenhum local encontrado.
                    </div>
                </div>
            </div>

            <!-- Paginator -->
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

<style>
    .map-container {
        position: relative;
        overflow: hidden;
        border-top-left-radius: calc(.25rem - 1px);
        border-top-right-radius: calc(.25rem - 1px);
    }

    .map-container iframe {
        /*pointer-events: none; Não precisamos mais disso*/
    }

    .card-title {
        font-size: 1.25rem;
    }

    .card-text {
        font-size: 1rem;
    }

    .card-add {
        border-style: dashed;
        border-color: #ced4da;
        background-color: #f8f9fa;
    }

    .card-add:hover {
        background-color: #e9ecef;
    }

    .ti-xl {
        font-size: 4rem;
    }
</style>

<!-- vue -->
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script>
    const locaisInit = <?= json_encode($locais->toArray()) ?>;

    Vue.createApp({
        data() {
            return {
                query: '',
                locais: locaisInit,
                localId: null,
                csrfToken: "<?= $this->request->getAttribute('csrfToken'); ?>"
            }
        },
        methods: {
            async onSearch() {
                try {
                    const url = "<?= $this->Url->build(['controller' => 'Locais', 'action' => 'search']); ?>?q=" + encodeURIComponent(this.query);
                    const res = await fetch(url, {
                        credentials: 'same-origin'
                    });
                    const data = await res.json();
                    this.locais = data.data || [];
                } catch (err) {
                    console.error(err);
                    alert('Erro ao buscar locais. Verifique console para detalhes.');
                }
            },
            async viewLocal(id) {
                try {
                    const res = await fetch(`<?= $this->Url->build(['controller' => 'Locais', 'action' => 'visualizar']); ?>/${id}`, {
                        credentials: 'same-origin',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (!res.ok) throw new Error('Erro ao carregar local');
                    const html = await res.text();
                    const modalEl = document.getElementById('view-modal');
                    modalEl.querySelector('#modal-body-content').innerHTML = `
            <div>
                ${html}
                <div class="modal-footer mt-3">
                    <a href="<?= $this->Url->build(['controller' => 'Locais', 'action' => 'editar']); ?>/${id}" class="btn btn-primary">Editar</a>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        `;
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                } catch (err) {
                    console.error(err);
                    alert('Erro ao carregar local.');
                }
            },
            confirmarExclusao(localId) {
                Swal.fire({
                    title: "Tem certeza que deseja excluir este Local?",
                    text: "Esta ação não pode ser desfeita.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Sim, excluir!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`<?= $this->Url->build(['controller' => 'Locais', 'action' => 'delete']); ?>/${localId}`, {
                            method: 'POST',
                            body: new URLSearchParams({
                                '_method': 'DELETE',
                                '_csrfToken': this.csrfToken
                            }),
                            credentials: 'same-origin'
                        }).then(response => {
                            if (response.ok) {
                                this.locais = this.locais.filter(local => local.id !== localId);
                                Swal.fire("Excluído!", "O local foi excluído com sucesso.", "success");
                            } else {
                                Swal.fire("Erro!", "Houve um problema ao excluir o local.", "error");
                            }
                        }).catch(err => {
                            console.error(err);
                            Swal.fire("Erro!", "Houve um problema ao excluir o local.", "error");
                        });
                    }
                });
            }
        }
    }).mount('#app');
</script>
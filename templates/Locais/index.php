<?php
$this->BreadcrumbsPlus->add([['title' => 'Locais']]);
?>
<?= $this->element('barra-titulo', ['title' => $title]); ?>

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
                        placeholder="Buscar locais por nome">
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



                                <form
                                    :action="'<?= $this->Url->build(['controller' => 'Locais', 'action' => 'delete']); ?>/' + local.id"
                                    method="post" @submit.prevent="confirmarExclusao($event)">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_csrfToken" :value="csrfToken">
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Excluir Local">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>


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

            confirmarExclusao(event) {
                event.preventDefault();
                const form = event.target;

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
                        form.submit(); // só envia se confirmar
                    }
                });
            }
        }
    }).mount('#app');
</script>
<?php

$this->BreadcrumbsPlus->add([['title' => 'Receituários']]);

use Cake\Core\Configure;

$categoriaMedicamento   =   Configure::read('categoriaMedicamento');

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden" id="appReceituario">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Receituários') ?></h4>
                <div class="ms-auto flex-shrink-0">
                    <button class="btn bg-primary-subtle text-primary "  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu animated flipInX">
                        <li><?= $this->Html->link('<i class="fa fa-plus" aria-hidden="true"></i> Novo Receituário', ['action' => 'novo'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                    </ul>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('categoria', ['label'=>'Categoria', 'class'=>'form-select', 'v-model'=>'buscar.categoria','empty'=>true, 'options'=> $categoriaMedicamento]); ?>
                </div>
                <div class="col-md-5 mb-3">
                    <?= $this->Form->control('titulo', ['label'=>'Título', 'class'=>'form-control', 'v-model'=>'buscar.titulo']); ?>
                </div>
                <div class="col-md-2 mt-3">
                    <button type="button" class="justify-content-center w-100 btn btn-primary d-flex align-items-center" @click="onSearch">Pesquisar</button>
                </div>
            </div>

            <div class="table-responsive mb-4 border rounded-1">
                <table class="table table-striped text-nowrap mb-0 align-middle">
                    <thead class="text-dark fs-4">
                    <tr>
                        <th style="width:30%;"><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('categoria') ?></h6></th>
                        <th style="width: 50%"><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('titulo','Título') ?></h6></th>
                        <th style="width: 10%"><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('created', 'Criado em') ?></h6></th>
                        <th style="width: 10%"><?= __('Opções') ?></th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr v-if="receituarios.length === 0">
                        <td colspan="4" class="text-center">
                            Nenhum registro encontrado
                        </td>
                    </tr>

                    <tr v-for="receituario in receituarios" :key="receituario.id">
                        <td><p class="mb-0 fw-normal fs-4">{{receituario.categoria}} </p></td>
                        <td><p class="mb-0 fw-normal fs-4">{{receituario.titulo}} </p></td>
                        <td><p class="mb-0 fw-normal fs-4">{{ new Date(receituario.created).toLocaleDateString('pt-BR', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                                }) }}</p></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    Opções
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><?= $this->Html->link('Visualizar','#',['@click'=>'viewCliente(receituario.id)','class'=>'dropdown-item','escape'=>false]) ?></li>
                                    <li><a class="dropdown-item" :href="`<?= $this->Url->build(['controller' => 'Receituarios', 'action' => 'editar'], ['fullBase' => true]); ?>/${receituario.id}`">Editar</a></li>
                                    <li><a class="dropdown-item" href="#" @click.prevent="onDelete(receituario.id, receituario.titulo)">Excluir</a></li>
                                </ul>
                            </div>
                        </td>
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

<script>

    const receituariosInit = <?= json_encode($receituarios->toArray()) ?>;

    Vue.createApp({
        data() {
            return {
                receituarios: receituariosInit,
                buscar: {categoria: '', titulo: ''},
                receituariosId: null,
            }
        },
        methods: {
            async onSearch() {
            try {
                const params = new URLSearchParams();

                if (this.buscar.titulo) {
                    params.append("titulo", this.buscar.titulo)
                }

                if (this.buscar.categoria) {
                    params.append("categoria", this.buscar.categoria)
                }

                // URL absoluta do endpoint
                const url = "<?= $this->Url->build(['controller' => 'Receituarios', 'action' => 'search']); ?>?" + params.toString();

                const res = await fetch(url, {
                    credentials: 'same-origin' // envia cookies de sessão
                });

                // lê JSON mesmo que haja redirect
                const data = await res.json();
                this.receituarios = data.data || [];

                } catch(err) {
                    alert('Erro ao buscar receituário.');
                }
            },
            async onDelete(id, nome) {
                const result = await Swal.fire({
                    title: 'Confirmar exclusão',
                    text: `Tem certeza que deseja excluir "${nome}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, excluir',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6'
                });

                if (!result.isConfirmed) {
                    return;
                }

                try {
                    const response = await fetch(`<?= $this->Url->build(['controller' => 'Receituarios', 'action' => 'delete']); ?>/${id}`, {
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
                            icon: 'success',
                            title: 'Excluído!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // Atualizar a lista
                        this.onSearch();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: data.message
                        });
                    }

                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro inesperado',
                        text: 'Não foi possível excluir o registro.'
                    });
                }
            },
            async viewCliente(id) {
                try {
                    const res = await fetch(`<?= $this->Url->build(['controller' => 'Receituarios', 'action' => 'visualizar']); ?>/${id}`, {
                        credentials: 'same-origin',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    if (!res.ok) throw new Error('Erro ao carregar usuário');

                    const html = await res.text();

                    // Adiciona o conteúdo no modal e inclui botão Editar
                    const modalEl = document.getElementById('view-modal');

                    modalEl.querySelector('#modal-body-content').innerHTML = `
          <div>
            ${html} <!-- conteúdo da view visualizar_tabela.php -->
                        <div class="modal-footer mt-3">
                        <a href="<?= $this->Url->build(['controller' => 'Receituarios', 'action' => 'editar'], ['fullBase' => true]); ?>/${id}"
                        class="btn btn-primary">Editar</a>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                        </div>
                        </div>
                        `;
                // Inicializa e abre o modal
                const modal = new bootstrap.Modal(modalEl);
                modal.show();

            } catch (err) {
                console.error(err);
                alert('Erro ao carregar receituario.');
            }
            }
        }
    }).mount('#appReceituario');

</script>
<?php

$this->BreadcrumbsPlus->add([['title' => 'Usuarios']]);

?>

<?= $this->element('barra-titulo',['title' => $title]); ?>


<!-- Modal Visualizar Cliente -->
<div class="modal fade" id="view-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <div class="pb-1 mb-2 border-bottom w-100">
                    <h5>Detalhes do usuário</h5>
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
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Usuários') ?></h4>
                <div class="ms-auto flex-shrink-0">
                    <button class="btn bg-primary-subtle text-primary "  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu animated flipInX">
                        <li><?= $this->Html->link('<i class="fa fa-plus" aria-hidden="true"></i> Novo Usuário', ['action' => 'novo'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                    </ul>
                </div>
            </div>

            <!-- BUSCA -->
            <div class="row mb-3">
                <div class="col-md-6 d-flex">
                    <input v-model="query" type="text" class="form-control" placeholder="Buscar usuários por nome, email">
                    <button class="btn btn-primary ms-2" @click="onSearch">Pesquisar</button>
                </div>
            </div>
            <div class="table-responsive mb-4 border rounded-1">
                <table class="table table-striped text-nowrap mb-0 align-middle">
                    <thead class="text-dark fs-4">
                    <tr>
                        <th style="width: 50%;"><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('nome') ?></h6></th>
                        <th style="width: 30%;"><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('email','E-Mail') ?></h6></th>
                        <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('ativar','Ativo') ?></h6></th>
                        <th><h6 class="fs-4 fw-semibold mb-0"><?= $this->Paginator->sort('created', 'Criado em') ?></h6></th>
                        <th><?= __('Opções') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr v-if="usuarios.length === 0">
                            <td colspan="5" class="text-center">
                                Nenhum registro encontrado
                            </td>
                        </tr>
                        <tr v-for="usuario in usuarios" :key="usuario.id">
                            <td><p class="mb-0 fw-normal fs-4">{{usuario.nome}}</p></td>
                            <td><p class="mb-0 fw-normal fs-4">{{usuario.email}}</p></td>
                            <td>
                                <span v-if="usuario.ativar == 1" class="badge bg-success-subtle text-success">Ativo</span>
                                <span v-else class="badge bg-warning-subtle text-warning">Não</span>
                            </td>
                            <td><p class="mb-0 fw-normal fs-4">
                                    {{ new Date(usuario.created).toLocaleDateString('pt-BR', {
                                    day: '2-digit',
                                    month: '2-digit',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                    }) }}
                                    </p></td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        Opções
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <li><?= $this->Html->link('Visualizar','#',['@click'=>'viewCliente(usuario.id)','class'=>'dropdown-item','escape'=>false]) ?></li>
                                        <li><a class="dropdown-item" :href="`<?= $this->Url->build(['controller' => 'Usuarios', 'action' => 'editar'], ['fullBase' => true]); ?>/${usuario.id}`">Editar</a></li>
                                        <li><a class="dropdown-item" href="#" @click.prevent="onDelete(usuario.id, usuario.nome)">Excluir</a></li>
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
    const usuariosInit = <?= json_encode($usuarios->toArray()) ?>;

    Vue.createApp({
        data() {
            return {
                query: '',
                usuarios: usuariosInit,
                usuarioId: null,
            }
        },
        methods: {
            async onSearch() {
        try {
            const params = new URLSearchParams()

            if (this.query) {
                params.append("q", this.query)
            }

            // URL absoluta do endpoint
            const url = "<?= $this->Url->build(['controller' => 'Usuarios', 'action' => 'search']); ?>?" + params.toString();

            const res = await fetch(url, {
                credentials: 'same-origin' // envia cookies de sessão
            });

            // lê JSON mesmo que haja redirect
            const data = await res.json();
            this.usuarios = data.data || [];
        } catch(err) {
            console.error(err);
            alert('Erro ao buscar usuarios. Verifique console para detalhes.');
        }
    },
    async onDelete(id, nome) {
        if (confirm(`Tem certeza que deseja excluir #${nome}?`)) {
            const response = await fetch(`<?= $this->Url->build(['controller' => 'Usuarios', 'action' => 'delete']); ?>/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-Token': '<?= $this->request->getAttribute('csrfToken') ?>',
                    'X-HTTP-Method-Override': 'DELETE',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                alert(data.message);
                // Atualizar a lista
                    this.onSearch();
            } else {
                alert('Erro: ' + data.message);
            }
        }
    },
    async viewCliente(id) {
        try {
            const res = await fetch(`<?= $this->Url->build(['controller' => 'Usuarios', 'action' => 'visualizar']); ?>/${id}`, {
                credentials: 'same-origin',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (!res.ok) throw new Error('Erro ao carregar cliente');

            const html = await res.text();

            // Adiciona o conteúdo no modal e inclui botão Editar
            const modalEl = document.getElementById('view-modal');

            modalEl.querySelector('#modal-body-content').innerHTML = `
  <div>
    ${html} <!-- conteúdo da view visualizar_tabela.php -->
                <div class="modal-footer mt-3">
                <a href="<?= $this->Url->build(['controller' => 'Usuarios', 'action' => 'editar'], ['fullBase' => true]); ?>/${id}"
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
        alert('Erro ao carregar cliente.');
    }
    }
    }
}).mount('#app');
</script>
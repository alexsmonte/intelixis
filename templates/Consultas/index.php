<?php

$this->BreadcrumbsPlus->add([['title' => 'Consultas']]);

?>

<?= $this->element('barra-titulo', ['title' => $title]); ?>

<!-- Modal Visualizar Usuario -->
<div class="modal fade" id="novaConsulta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <?= $this->Form->create($consulta, ['url' => ['controller' => 'Consultas', 'action' => 'novo']])?>
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title">Nova consulta</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start" id="modal-body-content">

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <?= $this->Form->control('cliente_id', ['label' => 'Cliente', 'class' => 'selectpicker form-select', 'data-live-search' => '1', 'data-remote-url' => $this->Url->build(['controller' => 'Clientes', 'action' => 'localizar'], ['fullBase' => true])]); ?>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-danger-subtle text-danger " data-bs-dismiss="modal">
                    Fechar
                </button>
                <button type="submit" class="btn btn-success">
                    Salvar
                </button>
            </div>
            <?= $this->Form->end()?>
        </div>
    </div>
</div>



<div id="app" class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0">
                    <?= __('Consultas')?>
                </h4>
                <div class="ms-auto flex-shrink-0">
                    <button class="btn bg-primary-subtle text-primary " data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu animated flipInX">
                        <li>
                            <?= $this->Html->link('<i class="fa fa-plus" aria-hidden="true"></i> Novo Consulta', '#', ['class' => 'dropdown-item', 'data-bs-toggle' => 'modal', 'data-bs-target' => '#novaConsulta', 'escape' => false])?>
                        </li>
                        <li>
                            <?= $this->Html->link('<i class="fa fa-print" aria-hidden="true"></i> Imprimir esta página', ['action' => 'imprimir'], ['class' => 'dropdown-item', 'escape' => false])?>
                        </li>
                        <li>
                            <?= $this->Html->link('<i class="fa fa-print" aria-hidden="true"></i> Imprimir tudo', ['action' => 'imprimir'], ['class' => 'dropdown-item', 'escape' => false])?>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 mb-3">
                    <?= $this->Form->control('nome', ['label' => 'Buscar por nome do cliente, cpf, celular', 'type' => 'text', 'class' => 'form-control', 'v-model' => 'query']); ?>
                </div>
                <div class="col-md-4 mb-3">
                    <?= $this->Form->control('profissional_id', ['label' => 'Profissional', 'class' => 'form-select', 'v-model' => 'profissional_id', 'empty' => true, 'options' => $profissionais]); ?>
                </div>
                <div class="col-md-12 mb-3">
                    <div class="row">
                        <div class="col-md-2 offset-md-10  mb-3">
                            <button type="button"
                                class="justify-content-center w-100 btn btn-primary d-flex align-items-center"
                                @click="onSearch">Pesquisar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive mb-4 border rounded-1">
                <table class="table table-striped text-nowrap mb-0 align-middle">
                    <thead class="text-dark fs-4">
                        <tr>
                            <th style="width:40%;">
                                <h6 class="fs-4 fw-semibold mb-0">
                                    <?= $this->Paginator->sort('cliente_id', 'Cliente')?>
                                </h6>
                            </th>
                            <th style="width:10%;">
                                <h6 class="fs-4 fw-semibold mb-0">
                                    <?= $this->Paginator->sort('created', 'Criado em')?>
                                </h6>
                            </th>
                            <th style="width:10%;">
                                <?= __('Opções')?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="consultas.length === 0">
                            <td colspan="5" class="text-center">
                                Nenhum registro encontrado
                            </td>
                        </tr>
                        <tr v-for="consulta in consultas" :key="consulta.id">
                            <td>
                                <p class="mb-0 fw-normal fs-4">{{consulta.cliente.nome}}</p>
                            </td>
                            <td>
                                <p class="mb-0 fw-normal fs-4">
                                    {{ new Date(consulta.created).toLocaleDateString('pt-BR', {
                                    day: '2-digit',
                                    month: '2-digit',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                    }) }}
                                </p>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-warning dropdown-toggle" type="button"
                                        id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        Opções
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <li>
                                            <?= $this->Html->link('Visualizar', '#', ['@click' => 'viewCliente(consulta.id)', 'class' => 'dropdown-item', 'escape' => false])?>
                                        </li>
                                        <li><a class="dropdown-item"
                                                :href="`<?= $this->Url->build(['controller' => 'Consultas', 'action' => 'editar'], ['fullBase' => true]); ?>/${consulta.id}`">Editar</a>
                                        </li>
                                        <li><a class="dropdown-item" href="#"
                                                @click.prevent="onDelete(consulta.id, consulta.cliente.nome)">Excluir</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="paginator mt-4">
                    <ul class="pagination justify-content-center">
                        <?= $this->Paginator->first('<< ')?>
                        <?= $this->Paginator->prev('< ')?>
                        <?= $this->Paginator->numbers()?>
                        <?= $this->Paginator->next(' >')?>
                        <?= $this->Paginator->last(' >>')?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>



<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<?= $this->Html->script(['plugins/bootstrap-select-min.js']); ?>
<?= $this->Html->css(['plugins/bootstrap-select.css']); ?>
<script>
    const consultasInit = <?= json_encode($consultas->toArray()) ?>;

    const modalNovaConsulta = document.getElementById('novaConsulta');
    // Inicializa e abre o modal modalNovaConsulta
    const mdlNovaConsulta = new bootstrap.Modal(modalNovaConsulta);


    Vue.createApp({
        data() {
            return {
                query: '',
                profissional_id: '',
                consultas: consultasInit,
                consultaId: null,
            }
        },
        methods: {
            async onSearch() {
                try {
                    const params = new URLSearchParams()

                    if (this.query)
                        params.append("q", this.query);

                    if (this.profissional_id)
                        params.append("profissional_id", this.profissional_id);

                    // URL absoluta do endpoint
                    const url = "<?= $this->Url->build(['controller' => 'Consultas', 'action' => 'search']); ?>?" + params.toString();

                    const res = await fetch(url, {
                        credentials: 'same-origin' // envia cookies de sessão
                    });

                    // lê JSON mesmo que haja redirect
                    const data = await res.json();
                    this.consultas = data.data || [];
                } catch (err) {
                    console.error(err);
                    alert('Erro ao buscar usuarios. Verifique console para detalhes.');
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
                    const response = await fetch(`<?= $this->Url->build(['controller' => 'Consultas', 'action' => 'delete']); ?>/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-Token': '<?= $this->request->getAttribute('csrfToken')?>',
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
                    const res = await fetch(`<?= $this->Url->build(['controller' => 'Consultas', 'action' => 'visualizar']); ?>/${id}`, {
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
                <a href="<?= $this->Url->build(['controller' => 'Consultas', 'action' => 'editar'], ['fullBase' => true]); ?>/${id}"
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
                    alert('Erro ao carregar usuário.');
                }
            }
        }
    }).mount('#app');
</script>
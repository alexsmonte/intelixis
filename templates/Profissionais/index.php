<?php
$this->BreadcrumbsPlus->add([['title' => 'Profissionais']]);
?>
<?= $this->element('barra-titulo', ['title' => $title]); ?>

<!-- Modal Visualizar Profissional -->
<div class="modal fade" id="view-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Detalhes do Profissional</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body text-start" id="modal-body-content">
                <i class="fa fa-spinner fa-spin fa-2x"></i>
            </div>
        </div>
    </div>
</div>

<!-- Vue App Container -->
<div id="app" data-entity='<?= json_encode($profissionais->toArray(), JSON_HEX_APOS | JSON_HEX_QUOT) ?>'
    data-csrf="<?= $this->request->getAttribute('csrfToken'); ?>" data-entity-name="o Profissional" data-routes='<?= json_encode([
          'search' => $this->Url->build(['controller' => 'Profissionais', 'action' => 'search']),
          'view' => $this->Url->build(['controller' => 'Profissionais', 'action' => 'visualizar']),
          'edit' => $this->Url->build(['controller' => 'Profissionais', 'action' => 'editar']),
          'delete' => $this->Url->build(['controller' => 'Profissionais', 'action' => 'delete'])
      ], JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>

    <!-- Card centralizado -->
    <div class="d-flex justify-content-center mt-4">
        <div class="card shadow-sm w-100" style="max-width:1200px;">
            <div class="card-body">

                <!-- Busca e botão Novo -->
                <div class="row mb-3 align-items-center">
                    <div class="col-md-6">
                        <form @submit.prevent="onSearch" class="mb-0">
                            <div class="input-group mb-3">
                                <input v-model="query" type="text" class="form-control form-control-lg placeholdert"
                                    placeholder="Buscar profissionais por nome ou email">
                                <button class="btn btn-primary" type="submit" data-bs-toggle="tooltip"
                                    title="Pesquisar"> <i class="ti ti-search fs-5"></i> </button>
                                </input>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        <?= $this->Html->link(
                            '<i class="ti ti-user-plus"></i><span class="ms-2">Novo</span>',
                            ['action' => 'novo'],
                            ['class' => 'btn btn-rounded btn-primary d-flex align-items-center', 'escape' => false]
                        ) ?>
                    </div>
                </div>

                <!-- Tabela -->
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
                            <tr v-for="item in filteredItems" :key="item.id">
                                <td>{{item.estabelecimento.razao_social}}</td>
                                <td> {{ item.nome }} </td>
                                <td>{{ item.email }}</td>
                                <td>{{ item.celular }}</td>
                                <td>
                                    <span v-if="item.ativar" class="badge bg-success-subtle text-success">Ativo</span>
                                    <span v-else class="badge bg-danger-subtle text-danger">Inativo</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="javascript:void(0)" @click="viewEntity(item.id)"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Visualizar">
                                            <i class="ti ti-eye fs-7 text-info"></i>
                                        </a>
                                        <a :href="`${routes.edit}/${item.id}`" title="Editar">
                                            <i class="ti ti-edit fs-7 text-warning"></i>
                                        </a>
                                        <form :action="`${routes.delete}/${item.id}`" method="post"
                                            @submit.prevent="confirmarExclusao($event, item)">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_csrfToken" :value="csrfToken">
                                            <button type="submit" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Excluir" class="btn  btm-rounded bg-transparent border-0">
                                                <i class="ti ti-trash fs-7 text-danger"></i>
                                            </button>
                                         
                                            <button type="submit" data-bs-toggle="dropdown" data-bs-placement="top"
                                                title="Mais" class="btn btn-outline  bg-ligth">
                                                <i class="ti ti-chevron-down fs-7 text-Mais"></i>
                                               
                                            </button>
                     
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                            data-bs-target="#modalEditarEmpresa">Editar Empresa</a></li>
                                                    <li><a class="dropdown-item" href="#">Cadastrar Chat</a></li>
                                                    <li><a class="dropdown-item" href="#">Criar Bot IA</a></li>
                                                </ul>
                      
                                          
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="items.length === 0">
                                <td colspan="8" class="text-center text-muted">Nenhum profissional encontrado</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Paginador CakePHP -->
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
</div>

<!-- Vue 3  -->
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>

<!-- Import do JS genérico -->
<?= $this->Html->script('vue/app') ?>
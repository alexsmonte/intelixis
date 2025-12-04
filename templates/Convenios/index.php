<?php
$this->BreadcrumbsPlus->add([['title' => 'Convênios']]);
?>
<?= $this->element('barra-titulo', ['title' => $title]); ?>
<?= $this->Html->css('index') ?>

<!-- Modal Visualizar convenio -->
<div class="modal fade" id="view-modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <div class="pb-1 mb-2 border-bottom w-100">
          <h5>Detalhes do Convênio</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body text-start" id="modal-body-content">
        <i class="fa fa-spinner fa-spin fa-2x"></i>
      </div>
    </div>
  </div>
</div>

<!-- Vue App Container -->
<div id="app" data-entity='<?= json_encode($convenios->toArray(), JSON_HEX_APOS | JSON_HEX_QUOT) ?>'
    data-csrf="<?= $this->request->getAttribute('csrfToken'); ?>" data-entity-name="o Convênio" data-routes='<?= json_encode([
          'search' => $this->Url->build(['controller' => 'Convenios', 'action' => 'search']),
          'view' => $this->Url->build(['controller' => 'Convenios', 'action' => 'visualizar']),
          'edit' => $this->Url->build(['controller' => 'Convenios', 'action' => 'editar']),
          'delete' => $this->Url->build(['controller' => 'Convenios', 'action' => 'delete'])
      ], JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>

    <div class="card w-100 position-relative overflow-hidden">
        <div class="px-4 py-3 border-bottom">
            <div class="card-body p-4">

                <!-- Título e menu -->
                <div class="d-flex mb-3 align-items-center">
                    <h4 class="card-title mb-0"><?= __('Convênios') ?></h4>

                </div>

                <!-- Busca -->
                <!-- Busca -->
                <div class="row mb-3 align-items-center">
                    <div class="col-md-6">
                        <form @submit.prevent="onSearch" class="mb-0">
                            <div class="input-group">
                                <input v-model="query" type="text" class="form-control form-control-lg placeholdert"
                                    placeholder="Buscar por nome ou email">
                                <button class="btn bg-primary-subtle text-primary border-primary" type="submit"
                                    data-bs-toggle="tooltip" title="Pesquisar">
                                    <i class="ti ti-search fs-5"></i>
                                </button>
                            </div>
                        </form>
                    </div>
 
                    <div class="col-md-6 d-flex justify-content-end">
                        <button class="btn btn-novo bg-primary-subtle text-primary border-primary" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars" aria-hidden="true"></i>
                        </button>
                        <ul class="dropdown-menu animated flipInX">
                            <li><?= $this->Html->link('<i class="fa fa-plus" aria-hidden="true"></i> Novo Convenio', ['action' => 'novo'], ['class' => 'dropdown-item', 'escape' => false]) ?>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Tabela -->
                <div class="table-responsive-sm mb-4 border rounded-1">
                    <table class="table table-striped text-nowrap mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Registro ANS</th>
                                <th>CNPJ</th>
                                <th>Telefone</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in filteredItems" :key="item.id">
                                <td style="width:40%;">{{ item.nome }}</td>
                                <td>{{ item.registro_ans }}</td>
                                <td>{{ item.cnpj }}</td>
                                <td>{{ item.telefone }}</td>
                                <td>{{ item.email }}</td>
                                <td>
                                    <span v-if="item.ativar"
                                        class="badge bg-success-subtle border border-success text-success">
                                        Ativo
                                    </span>
                                    <span v-else class="badge bg-danger-subtle border border-danger text-danger">
                                        Inativo
                                    </span>
                                </td>
                                <td style="width:8%;">
                                    <div class="d-flex  align-items-center gap-2" style="min-width:120px;">





                                        <button
                                            class="btn  bg-transparent text-primary  d-inline-flex align-items-center justify-content-center"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fs-5 fa fa-bars"></i>
                                        </button>
                                        <ul class="dropdown-menu animated flipInX">

                                            <li>
                                                <button class="dropdown-item btn btn-primary btn-md"
                                                    @click="viewEntity(item.id)">Visualizar</button>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" :href="`${routes.edit}/${item.id}`">Editar</a>
                                            </li>
                                            <li>
                                                <form :action="`${routes.delete}/${item.id}`" method="post"
                                                    @submit.prevent="confirmarExclusao($event,item)">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_csrfToken" :value="csrfToken">
                                                    <button type="submit"
                                                        class="dropdown-item btn btn-primary btn-md">Excluir</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="items.length === 0">
                                <td colspan="8" class="text-center text-muted">Nenhum convenio encontrado</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Paginador -->
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
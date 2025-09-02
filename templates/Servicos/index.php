<?php
$this->BreadcrumbsPlus->add([['title' => 'Servicos']]);
?>
<?= $this->element('barra-titulo',['title' => $title]); ?>

 



<!-- Modal Visualizar Servico -->
<div class="modal fade" id="view-modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <div class="pb-1 mb-2 border-bottom w-100">
          <h5>Detalhes do Servico</h5>
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
                <h4 class="card-title mb-0"><?= __('Servicos') ?></h4>
                <div class="ms-auto flex-shrink-0">
                    <button class="btn bg-primary-subtle text-primary "  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu animated flipInX">
                        <li><?= $this->Html->link('<i class="fa fa-plus" aria-hidden="true"></i> Novo Servico', ['action' => 'novo'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                    </ul>
                </div>
            </div>


            <!-- BUSCA -->
            <div class="row mb-3">
                <div class="col-md-6 d-flex">
                    <input v-model="query" type="text" class="form-control" placeholder="Buscar Serviços por nome">
                    <button class="btn btn-primary ms-2" @click="onSearch">Pesquisar</button>
                </div>
            </div>

            <!-- TABELA -->
            <div class="table-responsive mb-4 border rounded-1">
                <table class="table table-striped text-nowrap mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Estabelecimento</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Duração</th>
                            <th>Status</th>
                            <th>Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="servico in Servicos" :key="servico.id">
                            <td>{{ servico.estabelecimento.razao_social || '' }}</td>
                            <td>{{ servico.nome }}</td>
                            <td>{{ servico.descricao }}</td>
                            <td>{{ servico.duracao }}</td>
                            <td><span v-if="servico.ativar" class="badge bg-success-subtle text-success">Ativo</span>
                                <span v-else class="badge bg-danger-subtle text-danger">Inativo</span>
                            </td>
                            
                            
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-warning dropdown-toggle" type="button" data-bs-toggle="dropdown">Opções</button>
                                    <ul class="dropdown-menu">
                                     <li>
                                        <button class="dropdown-item btn btn-primary btn-md" @click="viewServico(servico.id)">Visualizar</button>
           
                                    </li>
                                        <li><a class="dropdown-item" :href="`<?= $this->Url->build(['controller' => 'Servicos', 'action' => 'editar']);?>/${servico.id}`">Editar</a></li>

                                    <li>
                                        <form :action="'<?= $this->Url->build(['controller' => 'Servicos', 'action' => 'delete']);?>/' + servico.id" method="post" @submit.prevent="confirmarExclusao($event)">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_csrfToken" :value="csrfToken">
                                            <button type="submit" class="dropdown-item btn btn-primary btn-md">Excluir</button>
                                        </form>
                                    </li>


                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="Servicos.length === 0">
                            <td colspan="8" class="text-center">Nenhum servico encontrado</td>
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
const servicosInit = <?= json_encode($servicos->toArray()) ?>;


const csrfToken = document.querySelector('meta[name="csrfToken"]').getAttribute('content');
Vue.createApp({
    data() {
        return {
            query: '',
            Servicos: servicosInit,
            servicoId: null,
            csrfToken: csrfToken
        }
    },
    methods: {
        async onSearch() {
            try {
                // URL relativa do endpoint
                const url = "<?= $this->Url->build(['controller' => 'Servicos', 'action' => 'search']); ?>?q=" + encodeURIComponent(this.query);
               


                const res = await fetch(url, {
                    credentials: 'same-origin' // envia cookies de sessão
                });

                // lê JSON mesmo que haja redirect
                const data = await res.json();
                this.Servicos = data.data || [];
            } catch(err) {
                console.error(err);
                alert('Erro ao buscar Servicos. Verifique console para detalhes.');
            }
        },

        async viewServico(id) {
            try {
        const res = await fetch(`<?= $this->Url->build(['controller' => 'Servicos', 'action' => 'visualizar']);?>/${id}`, {
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        if (!res.ok) throw new Error('Erro ao carregar servico');

        const html = await res.text();

        // Adiciona o conteúdo no modal e inclui botão Editar
        const modalEl = document.getElementById('view-modal');
        modalEl.querySelector('#modal-body-content').innerHTML = `
            <div>
                ${html} <!-- conteúdo da view visualizar_tabela.php -->
                <div class="modal-footer mt-3">
                    <a href="<?= $this->Url->build(['controller' => 'Servicos', 'action' => 'editar']);?>/${id}" class="btn btn-primary">Editar</a>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        `;

        // Inicializa e abre o modal
        const modal = new bootstrap.Modal(modalEl);
        modal.show();

    } catch (err) {
        console.error(err);
        alert('Erro ao carregar servico.');
    }

    },

        confirmarExclusao(event) {
            event.preventDefault();
            const form = event.target;

            Swal.fire({
                title: "Tem certeza que deseja excluir este Servico?",
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


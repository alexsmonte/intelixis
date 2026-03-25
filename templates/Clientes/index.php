<?php
$this->BreadcrumbsPlus->add([['title' => 'Clientes']]);
?>
<?= $this->element('barra-titulo',['title' => $title]); ?>
<meta name="csrfToken" content="<?= $this->request->getAttribute('csrfToken') ?>">


<button id="btnAbrirModal" class="btn btn-primary">+ Cadastrar</button>

<div data-vue="generic-modal"
     data-title="Cadastrar Cliente"
     data-submit-url="<?= $this->Url->build(['controller' => 'Clientes', 'action' => 'novo']) ?>"
     data-trigger-btn="#btnAbrirModal"
     data-modal-size="modal-xl"
     data-fields='[
        {"name":"nome","label":"Nome Completo","type":"text","id":"nome","col":6,"placeholder":"Digite o nome"},
        {"name":"data_nascimento","label":"Nascimento","type":"date","id":"data_nascimento","col":3},
        {"name":"documento","label":"CPF","type":"text","id":"documento","col":3},
        {"name":"email","label":"Email","type":"email","id":"email","col":6},
        {"name":"telefone","label":"Telefone","type":"text","id":"telefone","col":3},
        {"name":"celular","label":"Celular","type":"text","id":"celular","col":3},
        {"name":"cep","label":"CEP","vueComponent":"cep-autocomplete","id":"cep","col":3},
        {"name":"logradouro","label":"Logradouro","type":"text","id":"logradouro","col":7,"autocompleteField":true},
        {"name":"numero","label":"Número","type":"text","id":"numero", "col":2},
         {"name":"bairro","label":"Bairro","type":"text","id":"bairro","col":5,"autocompleteField":true},
        {"name":"cidade","label":"Cidade","type":"text","id":"cidade","col":5,"autocompleteField":true},
        {"name":"uf","label":"UF","type":"text","id":"uf","col":2,"autocompleteField":true}
     ]'>
</div>

<!-- Modal Visualizar Cliente -->
<div class="modal fade" id="view-modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <div class="pb-1 mb-2 border-bottom w-100">
          <h5>Detalhes do Cliente</h5>
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
                <h4 class="card-title mb-0"><?= __('Clientes') ?></h4>
                <div class="ms-auto flex-shrink-0">
                    <button class="btn bg-primary-subtle text-primary "  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu animated flipInX">
                        <li><?= $this->Html->link('<i class="fa fa-plus" aria-hidden="true"></i> Novo Cliente', ['action' => 'novo'], ['class' => 'dropdown-item', 'escape' =>false]) ?></li>
                    </ul>
                </div>
            </div>


            <!-- BUSCA -->
            <div class="row mb-3">
                <div class="col-md-6 d-flex">
                    <input v-model="query" type="text" class="form-control" placeholder="Buscar clientes por nome, documento ou email">
                    <button class="btn btn-primary ms-2" @click="onSearch">Pesquisar</button>
                </div>
            </div>

            <!-- TABELA -->
            <div class="table-responsive mb-4 border rounded-1">
                <table class="table table-striped text-nowrap mb-0 align-middle">
                    <thead>
                        <tr>

                            <th>Nome</th>
                            <th>Documento</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Celular</th>
                            <th>Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cliente in clientes" :key="cliente.id">


                            <td>{{ cliente.nome }}</td>
                            <td>{{ cliente.documento }}</td>
                            <td>{{ cliente.email }}</td>
                            <td>{{ cliente.telefone }}</td>
                            <td>{{ cliente.celular }}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-warning dropdown-toggle" type="button" data-bs-toggle="dropdown">Opções</button>
                                    <ul class="dropdown-menu">
                                    <li>
                                        <button class="dropdown-item btn btn-primary btn-sm" @click="viewCliente(cliente.id)">Visualizar</button>
           
                                    </li>
                                        <li><a class="dropdown-item" :href="`/painel/clientes/editar/${cliente.id}`">Editar</a></li>
                                        <li>
                                            <form :action="`/painel/clientes/delete/${cliente.id}`" method="post" @submit.prevent="confirmDelete($event)">
                                                <button type="submit" class="dropdown-item">Excluir</button>
                                               
                                            </form>

                                            
                                            
                                         

                                    </ul>
                                </div>
                            </td>
                        </tr>

                        
                        


                        <tr v-if="clientes.length === 0">
                            <td colspan="8" class="text-center">Nenhum cliente encontrado</td>
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


<!-- vue -->
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<?= $this->Html->script('vue/generic-modal.js') ?>
<?= $this->Html->script('vue/form-cadastrar.js') ?>
<?= $this->Html->script('vue/cep-autocomplete.js') ?>
<script>
const clientesInit = <?= json_encode($clientes->toArray()) ?>;

Vue.createApp({
    data() {
        return {
            query: '',
            clientes: clientesInit,
            clienteId: null,
        }
    },
    methods: {
        async onSearch() {
            try {
                // URL absoluta do endpoint
                const url = "/painel/clientes/search?q=" + encodeURIComponent(this.query);

                const res = await fetch(url, {
                    credentials: 'same-origin' // envia cookies de sessão
                });

                // lê JSON mesmo que haja redirect
                const data = await res.json();
                this.clientes = data.data || [];
            } catch(err) {
                console.error(err);
                alert('Erro ao buscar clientes. Verifique console para detalhes.');
            }
        },
        confirmDelete(event) {
            if(!confirm('Tem certeza que deseja excluir este cliente?')) {
                event.preventDefault();
            } else {
                event.target.submit();
            }
        },
        async viewCliente(id) {
            try {
        const res = await fetch(`/painel/clientes/visualizar/${id}`, {
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
                    <a href="/painel/clientes/editar/${id}" class="btn btn-primary">Editar</a>
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

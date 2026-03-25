<?= $this->element('barra-titulo', ['title' => $title]); ?>
<?= $this->Flash->render() ?>

<meta name="csrfToken" content="<?= $this->request->getAttribute('csrfToken') ?>">
<!-- Solar Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@icon/solar/solar.css">

<style>
    /* (MANTENDO TODOS OS ESTILOS CSS ORIGINAIS) */
    /* Estilos gerais para os filtros */
    .form-control-lg,
    .form-select-lg {
        height: 48px;
        font-size: 1rem;
    }

    /* Input de busca com lupa */
    .position-relative .form-control-lg.rounded-pill {
        padding-left: 3rem !important;
    }

    /* Grupo de datas - CORREÇÃO COMPLETA */
    .grupo-datas-container {
        position: relative;
        z-index: 2;
    }

    .grupo-datas {
        display: flex;
        align-items: center;
        height: 48px;
        width: 100%;
    }

    /* Inputs de data */
    .grupo-datas .data-input {
        flex: 1;
        min-width: 0;
    }

    .grupo-datas .data-input:first-child input {
        border-right: none;
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        border-color: #dee2e6;
    }

    .grupo-datas .data-input:last-child input {
        border-left: none;
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        border-color: #dee2e6;
    }

    .grupo-datas input {
        height: 48px !important;
        width: 100% !important;
        border-radius: 50rem !important;
    }

    /* Separador "até" */
    .grupo-datas .separador-ate {
        background-color: #fff;
        border-top: 1px solid #dee2e6;
        border-bottom: 1px solid #dee2e6;
        height: 48px;
        min-width: 50px;
        max-width: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        color: #6c757d;
        z-index: 1;
        margin: 0 -1px;
        position: relative;
    }

    /* Quando em foco */
    .grupo-datas input:focus {
        z-index: 3;
        position: relative;
    }

    .grupo-datas input:focus+.separador-ate,
    .grupo-datas .separador-ate+input:focus {
        border-color: #86b7fe !important;
        z-index: 2;
    }

    /* Garantir que próximos elementos não sobreponham */
    .col-xl-2,
    .col-lg-2 {
        position: relative;
        z-index: 1;
    }

    /* Responsividade */
    @media (max-width: 992px) {
        .grupo-datas-container {
            margin-bottom: 1rem;
        }

        .grupo-datas {
            flex-wrap: wrap;
            height: auto;
        }

        .grupo-datas .data-input {
            width: calc(50% - 25px) !important;
            margin-bottom: 0.5rem;
        }

        .grupo-datas .separador-ate {
            width: 100% !important;
            max-width: 100% !important;
            height: 40px;
            margin: 0.5rem 0 !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 0.375rem;
            order: -1;
        }

        .grupo-datas input {
            border: 1px solid #dee2e6 !important;
            border-radius: 0.375rem !important;
        }
    }

    /* ESTILOS PARA SELEÇÃO */
    .select-checkbox {
        width: 50px;
        text-align: center;
    }

    .select-checkbox input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        border-color: red;
    }

    .feedback-selecao {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1050;
        min-width: 300px;
        max-width: 400px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        border-radius: 12px;
        animation: slideInUp 0.3s ease;
    }

    @keyframes slideInUp {
        from {
            transform: translateY(100px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .fade-out {
        animation: fadeOut 0.3s ease forwards;
    }

    @keyframes fadeOut {
        from {
            transform: translateY(0);
            opacity: 1;
        }

        to {
            transform: translateY(100px);
            opacity: 0;
        }
    }

    tr.selected {
        background-color: #f0f9ff !important;
    }

    tr.selected td {
        border-color: #cfe2ff;
    }

    .modal-baixa .form-control:disabled {
        background-color: #f8f9fa;
        opacity: 0.8;
    }

    .resumo-baixa {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
        border-left: 4px solid #0d6efd;
    }

    .valor-total {
        font-size: 1rem;
        font-weight: 600;
        color: #198754;
    }

    .select-checkbox input:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    tr.selected td {
        background-color: #f0f9ff !important;
    }

    /* Destaque para linhas já baixadas */
    tr td:first-child input:disabled~* {
        opacity: 0.7;
    }
</style>

<!-- Busca-->
<div id="app" data-routes='<?= json_encode([
    'search' => $this->Url->build(['controller' => 'Financas', 'action' => 'search'])
], JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>
</div>

<div id="app-lancamentos" v-cloak>
    <!-- CARD DOS FILTROS -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body p-4">
            <!-- LINHA COMPLETA REAJUSTADA -->
            <div class="row align-items-center g-2">
                <!-- BUSCA TEXTUAL -->
                <div class="col-xl-4 col-lg-3 col-md-6 mb-2 mb-md-0">
                    <div class="position-relative">
                        <input v-model="query" type="text" class="form-control form-control-lg rounded-pill ps-5"
                            placeholder="Buscar..." @input="debouncedSearch">
                        <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">
                            <i class="ti ti-search"></i>
                        </span>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-6 mb-2 mb-md-0 grupo-datas-container">
                    <div class="grupo-datas">
                        <!-- Data Início -->
                        <div class="data-input">
                            <input v-model="dataInicio" type="date"
                                class="form-control form-control-lg rounded-start-pill" placeholder="Início"
                                @change="aplicarFiltros">
                        </div>

                        <!-- Separador "até" -->
                        <div class="separador-ate">
                            <span>até</span>
                        </div>

                        <!-- Data Fim -->
                        <div class="data-input">
                            <input v-model="dataFim" type="date" class="form-control form-control-lg rounded-end-pill"
                                placeholder="Fim" @change="aplicarFiltros">
                        </div>
                    </div>
                </div>

                <!-- Categoria (reduzida) -->
                <div class="col-xl-2 col-lg-2 col-md-6 mb-2 mb-md-0"
                    v-if="abaAtivaTipo === 'despesas' && modalCategorias.length > 0">
                    <select v-model="categoriaFiltroId" class="form-select form-select-lg rounded-pill h-100 w-100"
                        @change="aplicarFiltros">
                        <option value="">Todas categorias</option>
                        <option v-for="cat in modalCategorias" :key="cat.id" :value="cat.id">
                            {{ cat.name }}
                        </option>
                    </select>
                </div>

                <!-- Situação (reduzida) -->
                <div class="col-xl-2 col-lg-2 col-md-6 mb-2 mb-md-0">
                    <select v-model="situacaoFiltro" class="form-select form-select-lg rounded-pill h-100 w-100"
                        @change="aplicarFiltros">
                        <option value="">Todas situações</option>
                        <option value="aberto">Em aberto</option>
                        <option value="pago">Pago</option>
                        <option value="vencido">Vencido</option>
                    </select>
                </div>
            </div>

            <!-- LINHA DE FILTROS ATIVOS (RESTAURADA) -->
            <div v-if="temFiltrosAtivos" class="mt-4 pt-3 border-top">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                    <!-- FILTROS ATIVOS -->
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <small class="text-muted me-2">Filtros ativos:</small>

                        <!-- TEXTO -->
                        <div v-if="query"
                            class="badge bg-light text-dark border d-flex align-items-center gap-1 py-2 px-3">
                            <i class="ti ti-search fs-6"></i>
                            <span class="ms-1">{{ query.length > 15 ? query.substring(0, 15) + '...' : query }}</span>
                            <button type="button" class="btn-close btn-close-sm ms-2" @click="removerFiltro('texto')"
                                style="width: 0.2rem; height: 0.2rem;">
                            </button>
                        </div>

                        <!-- DATAS -->
                        <div v-if="dataInicio || dataFim"
                            class="badge bg-light text-dark border d-flex align-items-center gap-1 py-2 px-3">
                            <i class="ti ti-calendar fs-6"></i>
                            <span class="ms-1">
                                {{ dataInicio ? formatarDataSimples(dataInicio) : 'Início' }}
                                {{ dataInicio && dataFim ? '-' : '' }}
                                {{ dataFim ? formatarDataSimples(dataFim) : 'Fim' }}
                            </span>
                            <button type="button" class="btn-close btn-close-sm ms-2" @click="removerFiltro('data')"
                                style="width: 0.5rem; height: 0.5rem;">
                            </button>
                        </div>

                        <!-- CATEGORIA -->
                        <div v-if="categoriaFiltroId"
                            class="badge bg-light text-dark border d-flex align-items-center gap-1 py-2 px-3">
                            <i class="ti ti-category fs-6"></i>
                            <span class="ms-1">{{ nomeCategoriaSelecionada }}</span>
                            <button type="button" class="btn-close btn-close-sm ms-2"
                                @click="removerFiltro('categoria')" style="width: 0.5rem; height: 0.5rem;">
                            </button>
                        </div>

                        <!-- SITUAÇÃO -->
                        <div v-if="situacaoFiltro"
                            class="badge bg-light text-dark border d-flex align-items-center gap-1 py-2 px-3">
                            <i class="ti ti-cash fs-6"></i>
                            <span class="ms-1">{{ textoSituacao }}</span>
                            <button type="button" class="btn-close btn-close-sm ms-2" @click="removerFiltro('situacao')"
                                style="width: 0.5rem; height: 0.5rem;">
                            </button>
                        </div>
                    </div>

                    <!-- BOTÃO LIMPAR TODOS (RESTAURADO) -->
                    <button @click="limparTodosFiltros"
                        class="btn btn-outline-danger d-flex align-items-center gap-2 mt-2 mt-md-0"
                        style="height: 38px;">
                        <i class="ti ti-filter-x"></i>
                        <span>Limpar todos</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card w-100 position-relative">
        <div class="px-4 py-3 border-bottom">
            <div class="card-body p-4">
                <div class="container mt-4">

                    <!-- NAV TABS -->
                    <ul class="nav nav-tabs" id="financasTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" :class="{active: abaAtiva === 'receitas'}"
                                @click="selecionarAba('receitas', 1, 'Receitas')">
                                Receitas
                            </button>
                        </li>
                        <li class="nav-item" role="presentation" v-for="aba in abasDespesas" :key="aba.id">
                            <button class="nav-link" :class="{active: abaAtiva === 'despesas_' + aba.id}"
                                @click="selecionarAba('despesas', aba.id, aba.name)">
                                {{ aba.name }}
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content mt-3">
                        <!-- Loading -->
                        <div v-if="carregando" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Carregando...</span>
                            </div>
                            <p class="mt-2">Carregando lançamentos...</p>
                        </div>

                        <div v-else>
                            <!-- Botão Novo/Editar -->
                            <div class="text-center my-3">
                                <button class="btn waves-effect waves-light"
                                    :class="abaAtivaTipo === 'despesas' ? 'btn-outline-danger' : 'btn-outline-success'"
                                    @click="abrirModal" style="font-weight:500; letter-spacing:0.5px; max-width:700px;">
                                    <i class="fas"
                                        :class="abaAtivaTipo === 'despesas' ? 'fa-minus-circle' : 'fa-plus-circle'"></i>
                                    <span class="d-none d-md-inline">
                                        {{' Novo Lançamento em ' + abaAtivaNome }}
                                    </span>
                                    <span class="d-inline d-md-none">{{ editando ? 'Editar' : 'Novo' }}</span>
                                </button>
                            </div>

                            <!-- Tabela -->
                            <div v-if="!carregando && lancamentos.length > 0">
                                <div class="table-responsive-sm mb-4 border rounded-1">
                                    <table class="table table-striped text-wrap mb-0 align-middle">
                                        <thead>
                                            <tr>
                                                <th>Baixar</th>
                                                <th>Descrição</th>
                                                <th>{{ abaAtivaTipo === 'receitas' ? 'Recebido de' : 'Pago Para' }}</th>
                                                <th>Vencimento</th>
                                                <th>Valor</th>
                                                <th>Pago em</th>
                                                <th>{{ abaAtivaTipo === 'receitas' ? 'Valor Recebido' : 'Valor Pago' }}
                                                </th>
                                                <th>Status</th>
                                                <th style="width: 100px;">Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="lan in lancamentos" :key="lan.id"
                                                :class="{'selected': selecionados.includes(lan.id)}">
                                                <!-- CHECKBOX DE SELEÇÃO INDIVIDUAL -->
                                                <td class="select-checkbox">
                                                    <input type="checkbox" :value="lan.id" v-model="selecionados"
                                                        @change="atualizarFeedback" :disabled="lan.data_baixa"
                                                        :title="lan.data_baixa ? 'Lançamento já baixado' : ''"
                                                        :class="{'disabled-checkbox': lan.data_baixa}">
                                                </td>
                                                <td>{{ lan.descricao }}</td>
                                                <td>{{ abaAtivaTipo === 'receitas' ? (lan.cliente?.nome || '') :
                                                    (lan.profissional?.nome || '')
                                                    }}</td>
                                                <td>{{ formatarData(lan.data_vencimento) }}</td>
                                                <td>{{ formatarValor(lan.valor) }}</td>
                                                <td>{{ formatarData(lan.data_baixa) }}</td>
                                                <td>{{ lan.valor_recebido ? formatarValor(lan.valor_recebido) : '' }}
                                                </td>
                                                <td>
                                                    <span class="badge" :class="
                                                                lan.data_baixa 
                                                                    ? 'bg-success-subtle text-success' 
                                                                    : lan.data_vencimento < new Date().toISOString().split('T')[0]
                                                                        ? 'bg-danger-subtle text-danger' 
                                                                        : 'bg-warning-subtle text-warning'
                                                     ">
                                                        {{
                                                        lan.data_baixa
                                                        ? 'Pago'
                                                        : lan.data_vencimento < new Date().toISOString().split('T')[0]
                                                            ? 'Vencido' : 'aberto' }} </span>
                                                </td>
                                                <td>
                                                    <!-- Botões de ação -->
                                                    <div class="dropdown">
                                                        <button
                                                            class="btn bg-transparent text-primary d-inline-flex align-items-center justify-content-center"
                                                            type="button" :id="'dropdownMenu-' + lan.id"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-bars"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end"
                                                            :aria-labelledby="'dropdownMenu-' + lan.id">
                                                            <li>
                                                                <button type="button" class="dropdown-item"
                                                                    @click="editarLancamento(lan)">
                                                                    <i class="ti ti-edit me-2"></i> Editar
                                                                </button>
                                                            </li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li>
                                                                <button type="button" class="dropdown-item text-danger"
                                                                    @click="confirmarExclusao(lan)">
                                                                    <i class="ti ti-trash me-2"></i> Excluir
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                               
                                <!-- PAGINAÇÃO  -->
                                <nav v-if="Number(pagination.pageCount) > 0" aria-label="Paginação">
                                    <ul class="pagination justify-content-center">
                                        <li class="page-item" :class="{disabled: pagination.page === 1}">
                                            <a class="page-link" href="#" @click.prevent="carregarPagina(1)"
                                                aria-label="Primeira">
                                                <span aria-hidden="true">&laquo;&laquo;</span>
                                            </a>
                                        </li>

                                        <li class="page-item" :class="{disabled: pagination.page === 1}">
                                            <a class="page-link" href="#"
                                                @click.prevent="carregarPagina(pagination.page - 1)"
                                                aria-label="Anterior">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>

                                        <!-- CORREÇÃO: Garantir que getPaginasVisiveis() retorne números -->
                                        <li v-for="p in getPaginasVisiveis()" :key="p" class="page-item"
                                            :class="{active: pagination.page === p}">
                                            <a class="page-link" href="#" @click.prevent="carregarPagina(p)">{{ p }}</a>
                                        </li>

                                        <li class="page-item"
                                            :class="{disabled: pagination.page === pagination.pageCount}">
                                            <a class="page-link" href="#"
                                                @click.prevent="carregarPagina(pagination.page + 1)"
                                                aria-label="Próxima">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>

                                        <li class="page-item"
                                            :class="{disabled: pagination.page === pagination.pageCount}">
                                            <a class="page-link" href="#"
                                                @click.prevent="carregarPagina(pagination.pageCount)"
                                                aria-label="Última">
                                                <span aria-hidden="true">&raquo;&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="text-center text-muted small mt-2">
                                        Página {{ pagination.page }} de {{ pagination.pageCount }} ({{ pagination.total
                                        }} registros)
                                    </div>
                                </nav>
                            </div>

                            <!-- Quando não tem resultados -->
                            <div v-else-if="!carregando" class="text-center py-4">
                                Nenhum lançamento encontrado
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FEEDBACK DE SELEÇÃO -->
    <div v-if="mostrarFeedback" class="card feedback-selecao" :class="{'fade-out': fechandoFeedback}">
        <div class="card-body p-3">
            <div class="d-flex flex-column">
                <div class="mb-2">
                    <h6 class="mb-1" style="font-size: 0.95rem;">Você selecionou {{ selecionados.length }} conta(s)</h6>
                    <p class="mb-2 text-muted" style="font-size: 0.85rem;">
                        Valor total: R$ <strong>{{ formatarValor(totalSelecionado) }}</strong>
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary btn-sm rounded-pill flex-fill" @click="abrirModalBaixa"
                        style="font-size: 0.8rem; padding: 0.25rem 0.5rem;">
                        {{ abaAtivaTipo === 'receitas' ? 'Receber' : 'Pagar' }}
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill flex-fill"
                        @click="limparSelecao" style="font-size: 0.8rem; padding: 0.25rem 0.5rem;">
                        Limpar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL ORIGINAL DE NOVO/EDITAR -->
    <div class="modal fade" id="modalFinanca" tabindex="-1" aria-labelledby="modalFinancaLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFinancaLabel">
                        {{ editando ? 'Editar Lançamento em ' + modalAbaNome : 'Novo Lançamento em ' + modalAbaNome }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        @click="fecharModal"></button>
                </div>

                <form method="post" @submit.prevent="salvarLancamento">
                    <div class="modal-body">
                        <!-- CSRF -->
                        <input type="hidden" name="_csrfToken" :value="csrfToken">
                        <input v-if="editando" type="hidden" name="_method" value="PUT">
                        <input v-if="editando" type="hidden" name="id" :value="registroEditandoId">
                        <input type="hidden" name="ccustos_id" :value="form.ccustos_id">
                        <input type="hidden" name="tipo_aba" :value="form.tipo_aba">

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="descricao" class="form-label">Descrição *</label>
                                <input type="text" class="form-control" id="descricao" name="descricao"
                                    v-model.trim="form.descricao" :class="{'is-invalid': erros.descricao}" required>
                                <div class="invalid-feedback" v-if="erros.descricao">{{ erros.descricao }}</div>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label for="data_vencimento" class="form-label">Vencimento *</label>
                                <input type="date" class="form-control" id="data_vencimento" name="data_vencimento"
                                    v-model="form.data_vencimento" :class="{'is-invalid': erros.data_vencimento}"
                                    required>
                                <div class="invalid-feedback" v-if="erros.data_vencimento">{{ erros.data_vencimento }}
                                </div>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label for="valor" class="form-label">Valor *</label>
                                <input type="text" class="form-control" id="valor" name="valor" v-model="form.valor"
                                    @focus="prepararParaEdicao('valor', $event, 'form')"
                                    @blur="finalizarFormatacao('valor', 'form')"
                                    @input="formatarCampoMonetario('valor', $event, 'form')" placeholder="0,00"
                                    :class="{'is-invalid': erros.valor}" required>
                                <div class="invalid-feedback" v-if="erros.valor">{{ erros.valor }}</div>
                            </div>

                            <div class="col-md-4 mb-3" v-if="abaAtivaTipo === 'despesas'">
                                <label for="profissional_id" class="form-label">Pago para</label>
                                <select class="form-control" id="profissional_id" name="profissional_id"
                                    v-model="form.profissional_id" :class="{'is-invalid': erros.profissional_id}">
                                    <option value="">Selecione...</option>
                                    <option v-for="(nome, id) in profissionaisList" :key="id" :value="id">
                                        {{ nome }}
                                    </option>
                                </select>
                                <div class="invalid-feedback" v-if="erros.profissional_id">{{ erros.profissional_id }}
                                </div>
                            </div>

                            <div class="col-md-4 mb-3" v-if="abaAtivaTipo === 'receitas'">
                                <label for="cliente_id" class="form-label">Recebido de</label>
                                <select class="form-control" id="cliente_id" name="cliente_id" v-model="form.cliente_id"
                                    :class="{'is-invalid': erros.cliente_id}">
                                    <option value="">Selecione...</option>
                                    <option v-for="(nome, id) in clientesList" :key="id" :value="id">
                                        {{ nome }}
                                    </option>
                                </select>
                                <div class="invalid-feedback" v-if="erros.cliente_id">{{ erros.cliente_id }}</div>
                            </div>

                            <!-- Campo categoria SOMENTE para despesas -->
                            <div class="col-md-4 mb-3" v-if="abaAtivaTipo === 'despesas'">
                                <label for="categoria_id" class="form-label">Categoria *</label>
                                <select class="form-control" id="categoria_id" name="categoria_id"
                                    v-model="form.categoria_id" :class="{'is-invalid': erros.categoria_id}"
                                    :required="abaAtivaTipo === 'despesas'">
                                    <option value="">Selecione uma categoria...</option>
                                    <option v-for="cat in modalCategorias" :key="cat.id" :value="cat.id">
                                        {{ cat.name }}
                                    </option>
                                </select>
                                <div class="invalid-feedback" v-if="erros.categoria_id">{{ erros.categoria_id }}</div>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label for="data_baixa" class="form-label">Pago em</label>
                                <input type="date" class="form-control" id="data_baixa" name="data_baixa"
                                    v-model="form.data_baixa" :class="{'is-invalid': erros.data_baixa}">
                                <div class="invalid-feedback" v-if="erros.data_baixa">{{ erros.data_baixa }}</div>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label for="desconto" class="form-label">Desconto</label>
                                <input type="text" class="form-control" id="desconto" name="desconto"
                                    v-model="form.desconto" @focus="prepararParaEdicao('desconto', $event, 'form')"
                                    @blur="finalizarFormatacao('desconto', 'form')"
                                    @input="formatarCampoMonetario('desconto', $event, 'form')" placeholder="0,00"
                                    :class="{'is-invalid': erros.desconto}">
                                <div class="invalid-feedback" v-if="erros.desconto">{{ erros.desconto }}</div>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label for="juros" class="form-label">Juros e Multa</label>
                                <input type="text" class="form-control" id="juros" name="juros" v-model="form.juros"
                                    @focus="prepararParaEdicao('juros', $event, 'form')"
                                    @blur="finalizarFormatacao('juros', 'form')"
                                    @input="formatarCampoMonetario('juros', $event, 'form')" placeholder="0,00"
                                    :class="{'is-invalid': erros.juros}">
                                <div class="invalid-feedback" v-if="erros.juros">{{ erros.juros }}</div>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label for="valor_recebido" class="form-label">
                                    {{ abaAtivaTipo === 'receitas' ? 'Valor Recebido' : 'Valor Pago' }}
                                </label>
                                <input type="text" class="form-control" id="valor_recebido" name="valor_recebido"
                                    v-model="form.valor_recebido"
                                    @focus="prepararParaEdicao('valor_recebido', $event, 'form')"
                                    @blur="finalizarFormatacao('valor_recebido', 'form')"
                                    @input="formatarCampoMonetario('valor_recebido', $event, 'form')" placeholder="0,00"
                                    :class="{'is-invalid': erros.valor_recebido}">
                                <div class="invalid-feedback" v-if="erros.valor_recebido">{{ erros.valor_recebido }}
                                </div>
                            </div>
                            
                            <div class="col-md-12 my-2 border-top pt-3" v-if="!editando">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="repetir_lancamento" v-model="form.repetir_lancamento" @change="gerarRecorrencias">
                                    <label class="form-check-label" for="repetir_lancamento">Repetir lançamento?</label>
                                </div>
                            </div>

                            <template v-if="form.repetir_lancamento && !editando">
                                <div class="col-md-3 mb-3">
                                    <label for="periodo_recorrencia" class="form-label">Período</label>
                                    <select class="form-select" id="periodo_recorrencia" name="periodo_recorrencia" v-model="form.periodo_recorrencia" @change="gerarRecorrencias">
                                        <option value="semanal">Semanalmente</option>
                                        <option value="quinzenal">Quinzenalmente</option>
                                        <option value="mensal">Mensalmente</option>
                                        <option value="trimestral">Trimestralmente</option>
                                        <option value="semestral">Semestralmente</option>
                                        <option value="anual">Anualmente</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="ocorrencia_recorrencia" class="form-label">Ocorrência</label>
                                    <input type="number" class="form-control" id="ocorrencia_recorrencia" name="ocorrencia_recorrencia" v-model.number="form.ocorrencia_recorrencia" min="1" @input="gerarRecorrencias" placeholder="Quantidade">
                                </div>

                                <div class="col-md-12 mb-3" v-if="listaRecorrencias.length > 0">
                                    <label style="color:#6c757d; font-weight:400;">Lançamentos Recorrentes:</label>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th style="width: 50px;">Nº</th>
                                                    <th>Descrição</th>
                                                    <th style="width: 150px;">Vencimento</th>
                                                    <th style="width: 150px;">Valor</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(rec, index) in listaRecorrencias" :key="index">
                                                    <td>{{ index + 1 }}</td>
                                                    <td><input type="text" class="form-control form-control-sm" v-model="rec.descricao"></td>
                                                    <td><input type="date" class="form-control form-control-sm" v-model="rec.data_vencimento"></td>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm text-end" v-model="rec.valor" 
                                                            @focus="prepararParaEdicao('valor', $event, 'recorrencia', rec)"
                                                            @blur="finalizarFormatacao('valor', 'recorrencia', rec)"
                                                            @input="formatarCampoMonetario('valor', $event, 'recorrencia', rec)" placeholder="0,00" disabled>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn bg-danger-subtle text-danger" data-bs-dismiss="modal"
                            @click="fecharModal">Fechar</button>
                        <button type="submit" class="btn btn-success" :disabled="salvando">
                            <span v-if="salvando">
                                <span class="spinner-border spinner-border-sm me-1" role="status"
                                    aria-hidden="true"></span>
                                {{ editando ? 'Atualizando...' : 'Salvando...' }}
                            </span>
                            <span v-else>{{ editando ? 'Atualizar' : 'Salvar' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL DE BAIXA -->
    <div class="modal fade modal-baixa" id="modalBaixa" tabindex="-1" aria-labelledby="modalBaixaLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBaixaLabel">
                        {{ abaAtivaTipo === 'receitas' ? 'Baixar Receitas' : 'Baixar Despesas' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form @submit.prevent="liquidarLancamentos">
                    <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Descrição</th>
                                        <th>{{ abaAtivaTipo === 'receitas' ? 'Recebido de' : 'Pago para' }}</th>
                                        <th>Vencimento</th>
                                        <th>Valor</th>
                                        <th>Desconto</th>
                                        <th>{{ abaAtivaTipo === 'receitas' ? 'Valor Recebido' : 'Valor Pago' }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="lan in lancamentosSelecionados" :key="lan.id">
                                        <td>{{ lan.descricao }}</td>
                                        <td>{{ abaAtivaTipo === 'receitas' ? (lan.cliente?.nome || '') :
                                            (lan.profissional?.nome || '') }}</td>
                                        <td>{{ formatarData(lan.data_vencimento) }}</td>
                                        <td>{{ formatarValor(lan.valor) }}</td>
                                        <td class="col-md-2 mb-3">
                                            <input type="text"
                                                class="form-control form-control-sm rounded-pill text-end"
                                                v-model="lan.descontoBaixa"
                                                @focus="prepararParaEdicao('descontoBaixa', $event, 'baixa', lan)"
                                                @blur="finalizarFormatacao('descontoBaixa', 'baixa', lan)"
                                                @input="formatarCampoMonetario('descontoBaixa', $event, 'baixa', lan)"
                                                placeholder="0,00">
                                        </td>
                                        <td class="col-md-2 mb-3">
                                            <input type="text"
                                                class="form-control form-control-sm rounded-pill text-end"
                                                v-model="lan.valorRecebidoBaixa"
                                                @focus="prepararParaEdicao('valorRecebidoBaixa', $event, 'baixa', lan)"
                                                @blur="finalizarFormatacao('valorRecebidoBaixa', 'baixa', lan)"
                                                @input="formatarCampoMonetario('valorRecebidoBaixa', $event, 'baixa', lan)"
                                                placeholder="0,00" required>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Footer com resumo fixo e data de baixa -->
                    <div class="modal-footer border-top pt-3 d-flex flex-column">
                        <!-- Resumo e Data de Baixa -->
                        <div class="w-100 mb-3">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <!-- Resumo -->
                                <div class="d-flex flex-wrap align-items-center gap-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="small text-muted">Quantidade:</span>
                                        <div class="rounded-pill text-center bg-light px-3 py-1 border"
                                            style="width: 50px;">
                                            <span class="fw-medium">{{ lancamentosSelecionados.length }}</span>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center gap-2">
                                        <span class="small text-muted">Total:</span>
                                        <div class="rounded-pill text-center bg-light px-3 py-1 border"
                                            style="width: 100px;">
                                            <span class="fw-medium">{{ formatarValor(valorTotalOriginal) }}</span>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center gap-2">
                                        <span class="small text-muted">Descontos:</span>
                                        <div class="rounded-pill text-center bg-light px-3 py-1 border"
                                            style="width: 100px;">
                                            <span class="fw-medium">{{ formatarValor(totalDesconto) }}</span>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center gap-2">
                                        <span class="small text-muted">{{ abaAtivaTipo === 'receitas' ? 'Receber:' :
                                            'Pagar:' }}</span>
                                        <div class="rounded-pill text-center fw-bold px-3 py-1 border"
                                            :class="abaAtivaTipo === 'receitas' ? 'text-success bg-success-subtle border-success' : 'text-danger bg-danger-subtle border-danger'"
                                            style="width: 100px;">
                                            {{ formatarValor(valorTotalBaixa) }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Data de Baixa -->
                                <div class="d-flex align-items-center gap-2">
                                    <span class="small text-muted">Data da Baixa:</span>
                                    <input type="date" id="dataBaixaGeral" class="form-control rounded-pill border"
                                        style="width: 150px; border-radius: 50rem !important;" v-model="dataBaixaGeral"
                                        required>
                                </div>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="w-100 border-top pt-3">
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn bg-danger-subtle text-danger" data-bs-dismiss="modal">
                                    Cancelar
                                </button>
                                <button type="submit" class="btn btn-success" :disabled="processandoBaixa">
                                    <span v-if="processandoBaixa">
                                        <span class="spinner-border spinner-border-sm me-1" role="status"
                                            aria-hidden="true"></span>
                                        Processando...
                                    </span>
                                    <span v-else>Liquidar</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Vue 3 -->
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>

<script>
    (function () {
        const { createApp } = Vue;

        // URLs
        const URLS = {
            search: '<?= $this->Url->build("/financas/search") ?>',
            //carregarLancamentos: '<?= $this->Url->build("/financas/carregarLancamentos") ?>//',
            salvarLancamento: '<?= $this->Url->build("/financas/novo") ?>',
            editarBase: '<?= $this->Url->build("/financas/editar") ?>',
            excluirBase: '<?= $this->Url->build("/financas/delete") ?>',
            categoriasBase: '<?= $this->Url->build("/financas/getCategorias") ?>',
            baixaLancamentos: '<?= $this->Url->build("/financas/baixarLancamentos") ?>'
        };

        const TODAY = new Date().toISOString().split('T')[0];

        const app = createApp({
            data() {
                return {
                    // Estado principal
                    abaAtiva: 'receitas',
                    abaAtivaId: 1,
                    abaAtivaNome: 'Receitas',
                    abaAtivaTipo: 'receitas',
                    categoriaFiltroId: '',
                    situacaoFiltro: '',
                    carregando: false,
                    salvando: false,

                    // Filtros de busca
                    query: '',
                    dataInicio: '',
                    dataFim: '',

                    // Dados
                    abasDespesas: <?= json_encode($abasDespesas ?? []) ?>,
                    lancamentos: <?= json_encode($lancamentosIniciais ?? []) ?>,
                    clientesList: <?= json_encode($clientes ?? []) ?>,
                    profissionaisList: <?= json_encode($profissionais ?? []) ?>,
                    csrfToken: document.querySelector('meta[name="csrfToken"]')?.getAttribute('content') || '',

                    // Modal
                    modalAbaNome: '',
                    modalCategorias: [],
                    editando: false,
                    registroEditandoId: null,
                    form: {
                        descricao: '',
                        data_vencimento: TODAY,
                        valor: '0,00',
                        cliente_id: '',
                        profissional_id: '',
                        categoria_id: '',
                        ccustos_id: '',
                        tipo_aba: '',
                        data_baixa: '',
                        desconto: '0,00',
                        juros: '0,00',
                        valor_recebido: '0,00',
                        repetir_lancamento: false,
                        periodo_recorrencia: 'mensal',
                        ocorrencia_recorrencia: 1
                    },
                    listaRecorrencias: [],
                    erros: {},
                    modalInstance: null,

                    // Paginação (RESTAURADA)
                    pagination: <?= json_encode($paginacaoInicial ?? [
                        'page' => 1,
                        'pageCount' => 1,
                        'total' => 0,
                        'limit' => 10
                    ]) ?>,

                    // Controle de busca
                    buscaTimeout: null,

                    // Controle de seleção
                    selecionados: [],
                    selecionarTodos: false,
                    mostrarFeedback: false,
                    fechandoFeedback: false,
                    feedbackTimeout: null,

                    // Modal de baixa
                    modalBaixaInstance: null,
                    lancamentosSelecionados: [],
                    dataBaixaGeral: TODAY,
                    processandoBaixa: false,
                    valorTotalOriginal: 0,
                    totalDesconto: 0,
                    valorTotalBaixa: 0
                };
            },

            computed: {
                temFiltrosAtivos() {
                    return this.query ||
                        this.dataInicio ||
                        this.dataFim ||
                        this.categoriaFiltroId ||
                        this.situacaoFiltro;
                },

                nomeCategoriaSelecionada() {
                    if (!this.categoriaFiltroId) return '';
                    const categoria = this.modalCategorias.find(cat => cat.id == this.categoriaFiltroId);
                    return categoria ? categoria.name : '';
                },

                textoSituacao() {
                    const textos = {
                        'aberto': 'Em aberto',
                        'pago': 'Pago',
                        'vencido': 'Vencido'
                    };
                    return textos[this.situacaoFiltro] || '';
                },

                totalSelecionado() {
                    if (this.selecionados.length === 0) return 0;

                    return this.lancamentos
                        .filter(lan => this.selecionados.includes(lan.id))
                        .reduce((total, lan) => total + parseFloat(lan.valor || 0), 0);
                }
            },

            watch: {
                'form.valor': function(newVal) {
                    if (this.form.repetir_lancamento && !this.editando) {
                        this.gerarRecorrencias();
                    }
                },
                'form.data_vencimento': function(newVal) {
                    if (this.form.repetir_lancamento && !this.editando) {
                        this.gerarRecorrencias();
                    }
                },
                'form.descricao': function(newVal) {
                    if (this.form.repetir_lancamento && !this.editando) {
                        this.gerarRecorrencias();
                    }
                }
            },

            mounted() {
                console.log('Vue montado - iniciando...');

                // Inicializa modais Bootstrap
                const modalEl = document.getElementById('modalFinanca');
                if (modalEl && window.bootstrap) {
                    this.modalInstance = new bootstrap.Modal(modalEl);
                }

                // Inicializa modal de baixa
                const modalBaixaEl = document.getElementById('modalBaixa');
                if (modalBaixaEl && window.bootstrap) {
                    this.modalBaixaInstance = new bootstrap.Modal(modalBaixaEl);
                }

                //Carrega dados iniciais
                if ((!this.lancamentos || this.lancamentos.length === 0) && !this.carregando) {
                    this.carregarLancamentos();
                }

                if (this.abaAtivaTipo === 'despesas') {
                    this.carregarCategorias();
                }

                this.configurarBuscaGlobal();
            },

            methods: {
                // ========== MÉTODOS DE FORMATAÇÃO MONETÁRIA ÚNICOS ==========

                /**
                 * Formata campo monetário em tempo real - CENTAVOS PRIMEIRO
                 */
                formatarCampoMonetario(campo, evento, contexto = 'form', item = null) {
                    let valor = evento.target.value;

                    // Remove tudo exceto números
                    valor = valor.replace(/[^\d]/g, '');

                    // Se estiver vazio, define como 0,00
                    if (valor === '') {
                        if (contexto === 'form') {
                            this.form[campo] = '0,00';
                        } else if (contexto === 'baixa' && item) {
                            item[campo] = '0,00';
                        }
                        if (contexto === 'baixa') this.calcularTotalBaixa();
                        return;
                    }

                    // Completa com zeros à esquerda para ter pelo menos 3 dígitos
                    while (valor.length < 3) {
                        valor = '0' + valor;
                    }

                    // Separa centavos (2 últimos dígitos) e inteiros (restante)
                    const centavos = valor.slice(-2);
                    let inteiros = valor.slice(0, -2) || '0';

                    // Remove zeros à esquerda dos inteiros
                    inteiros = inteiros.replace(/^0+/g, '');
                    if (inteiros === '') inteiros = '0';

                    // Adiciona separador de milhar para valores com mais de 3 dígitos
                    if (inteiros.length > 3) {
                        inteiros = inteiros.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }

                    const valorFormatado = inteiros + ',' + centavos;

                    // Atualiza no contexto correto
                    if (contexto === 'form') {
                        this.form[campo] = valorFormatado;
                    } else if (contexto === 'baixa' && item) {
                        item[campo] = valorFormatado;
                    }

                    // Recalcula totais se for modal de baixa
                    if (contexto === 'baixa') {
                        this.calcularTotalBaixa();
                    }
                },

                /**
                 * Prepara campo para edição - remove formatação temporariamente
                 */
                prepararParaEdicao(campo, evento, contexto = 'form', item = null) {
                    let valor;

                    if (contexto === 'form') {
                        valor = this.form[campo] || '0,00';
                        // Remove pontos para edição
                        this.form[campo] = valor.replace(/\./g, '');
                    } else if (contexto === 'baixa' && item) {
                        valor = item[campo] || '0,00';
                        item[campo] = valor.replace(/\./g, '');
                    }

                    // Seleciona todo o texto para facilitar edição
                    setTimeout(() => {
                        evento.target.select();
                    }, 10);
                },

                /**
                 * Formata campo quando perde o foco - garante formato final correto
                 */
                finalizarFormatacao(campo, contexto = 'form', item = null) {
                    let valor;

                    if (contexto === 'form') {
                        valor = this.form[campo] || '0,00';
                    } else if (contexto === 'baixa' && item) {
                        valor = item[campo] || '0,00';
                    } else {
                        return;
                    }

                    // Garante que tenha formato 00,00
                    if (!valor.includes(',')) {
                        valor = valor + ',00';
                    }

                    // Se for modal de baixa, recalcula totais
                    if (contexto === 'baixa') {
                        this.calcularTotalBaixa();
                    }
                },

                // ========== CONVERSÃO PARA ENVIO ==========

                prepararValorParaEnvio(valorFormatado) {
                    if (!valorFormatado || valorFormatado === '') return '0,00';

                    // Remove pontos de milhar, mantém vírgula
                    return valorFormatado.toString().replace(/\./g, '');
                },

                converterParaBrasileiro(valorDecimal) {
                    if (!valorDecimal || valorDecimal === '') return '0,00';

                    const numero = parseFloat(valorDecimal);
                    if (isNaN(numero)) return '0,00';

                    return numero.toLocaleString('pt-BR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                },

                // ========== MÉTODOS DE SELEÇÃO ==========
                toggleSelecionarTodos() {
                    if (this.selecionarTodos) {
                        this.selecionados = this.lancamentos
                            .filter(lan => !lan.data_baixa)
                            .map(lan => lan.id);
                    } else {
                        this.selecionados = [];
                    }
                    this.atualizarFeedback();
                },

                atualizarFeedback() {
                    const lancamentosAbertos = this.lancamentos.filter(lan => !lan.data_baixa);

                    if (lancamentosAbertos.length > 0) {
                        this.selecionarTodos = this.selecionados.length === lancamentosAbertos.length;
                    } else {
                        this.selecionarTodos = false;
                    }

                    if (this.selecionados.length > 0) {
                        this.mostrarFeedback = true;
                        this.fechandoFeedback = false;

                        if (this.feedbackTimeout) {
                            clearTimeout(this.feedbackTimeout);
                        }
                    } else {
                        this.ocultarFeedback();
                    }
                },

                ocultarFeedback() {
                    this.fechandoFeedback = true;
                    setTimeout(() => {
                        this.mostrarFeedback = false;
                        this.fechandoFeedback = false;
                    }, 300);
                },

                limparSelecao() {
                    this.selecionados = [];
                    this.selecionarTodos = false;
                    this.ocultarFeedback();
                },

                // ========== MODAL DE BAIXA ==========
                async abrirModalBaixa() {
                    if (this.selecionados.length === 0) {
                        alert('Selecione pelo menos um lançamento para baixar.');
                        return;
                    }

                    this.lancamentosSelecionados = this.lancamentos
                        .filter(lan => this.selecionados.includes(lan.id))
                        .map(lan => ({
                            ...lan,
                            descontoBaixa: this.converterParaBrasileiro(lan.desconto || '0'),
                            valorRecebidoBaixa: lan.valor_recebido ? this.converterParaBrasileiro(lan.valor_recebido) : '0,00'
                        }));

                    this.calcularTotalBaixa();

                    if (this.modalBaixaInstance) {
                        this.modalBaixaInstance.show();
                    }
                },

                calcularTotalBaixa() {
                    this.valorTotalOriginal = this.lancamentosSelecionados
                        .reduce((total, lan) => total + parseFloat(lan.valor || 0), 0);

                    this.totalDesconto = this.lancamentosSelecionados
                        .reduce((total, lan) => {
                            const descontoStr = this.prepararValorParaEnvio(lan.descontoBaixa || '0,00');
                            const desconto = parseFloat(descontoStr.replace(',', '.'));
                            return total + (isNaN(desconto) ? 0 : desconto);
                        }, 0);

                    this.valorTotalBaixa = this.lancamentosSelecionados
                        .reduce((total, lan) => {
                            const valorStr = this.prepararValorParaEnvio(lan.valorRecebidoBaixa || '0,00');
                            const valor = parseFloat(valorStr.replace(',', '.'));
                            return total + (isNaN(valor) ? 0 : valor);
                        }, 0);
                },

                async liquidarLancamentos() {
                    if (!this.dataBaixaGeral) {
                        alert('Informe a data da baixa.');
                        return;
                    }

                    const lancamentosSemValor = this.lancamentosSelecionados.filter(
                        lan => {
                            const valorStr = String(lan.valorRecebidoBaixa || '');
                            return valorStr.trim() === '' || valorStr === '0,00';
                        }
                    );

                    if (lancamentosSemValor.length > 0) {
                        alert('Preencha o valor recebido/pago para todos os lançamentos selecionados.');
                        return;
                    }

                    this.processandoBaixa = true;

                    try {
                        const lancamentosBaixa = this.lancamentosSelecionados.map(lan => {
                            return {
                                id: lan.id,
                                data_baixa: this.dataBaixaGeral,
                                desconto: this.prepararValorParaEnvio(lan.descontoBaixa || '0,00'),
                                valor_recebido: this.prepararValorParaEnvio(lan.valorRecebidoBaixa)
                            };
                        });

                        const formData = new FormData();
                        formData.append('_csrfToken', this.csrfToken);
                        formData.append('lancamentos', JSON.stringify(lancamentosBaixa));

                        const response = await fetch(URLS.baixaLancamentos, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();

                            if (data.success) {
                                if (this.modalBaixaInstance) {
                                    this.modalBaixaInstance.hide();
                                }

                                this.limparSelecao();
                                await this.carregarLancamentos();

                                if (typeof Swal !== 'undefined') {
                                    Swal.fire({
                                        title: 'Sucesso!',
                                        text: `Baixa realizada para ${data.processados} lançamento(s).`,
                                        icon: 'success',
                                        confirmButtonText: 'OK',
                                        timer: 3000
                                    });
                                } else {
                                    alert(`Baixa realizada para ${data.processados} lançamento(s).`);
                                }
                            } else {
                                throw new Error(data.message || 'Erro ao processar baixa');
                            }
                        } else {
                            const errorText = await response.text();
                            throw new Error(`Erro ${response.status}: ${errorText}`);
                        }
                    } catch (error) {
                        console.error('Erro ao liquidar:', error);
                        alert('Erro ao processar baixa: ' + error.message);
                    } finally {
                        this.processandoBaixa = false;
                    }
                },

                // ========== MÉTODO SALVAR LANÇAMENTO ==========
                async salvarLancamento(e) {
                    e.preventDefault();
                    console.log('Salvando lançamento...');

                    // SEM VALIDAÇÃO NO FRONTEND conforme solicitado

                    if (this.form.categoria_id) {
                        this.form.ccustos_id = this.form.categoria_id;
                    } else if (this.abaAtivaTipo === 'receitas') {
                        this.form.ccustos_id = 1;
                    } else {
                        this.form.ccustos_id = this.abaAtivaId;
                    }

                    this.salvando = true;

                    try {
                        const formData = new FormData();
                        formData.append('_csrfToken', this.csrfToken);

                        let url = URLS.salvarLancamento;
                        if (this.editando) {
                            url = `${URLS.editarBase}/${this.registroEditandoId}`;
                            formData.append('_method', 'PUT');
                        }

                        const camposTexto = [
                            'descricao', 'data_vencimento', 'cliente_id', 'profissional_id',
                            'categoria_id', 'ccustos_id', 'tipo_aba', 'data_baixa'
                        ];

                        camposTexto.forEach(key => {
                            if (this.form[key] !== null && this.form[key] !== undefined && this.form[key] !== '') {
                                formData.append(key, this.form[key]);
                            }
                        });

                        // Campos monetários - ENVIA NO FORMATO 00,00
                        const camposMonetarios = ['valor', 'desconto', 'juros', 'valor_recebido'];
                        camposMonetarios.forEach(key => {
                            const valorParaEnvio = this.prepararValorParaEnvio(this.form[key] || '0,00');
                            formData.append(key, valorParaEnvio);
                            console.log(`Campo ${key}: ${this.form[key]} -> ${valorParaEnvio}`);
                        });
                        
                        // Recorrências
                        if (this.form.repetir_lancamento && !this.editando && this.listaRecorrencias.length > 0) {
                            formData.append('repetir_lancamento', 1);
                            
                            const recorrenciasPuras = this.listaRecorrencias.map(rec => ({
                                descricao: rec.descricao,
                                data_vencimento: rec.data_vencimento,
                                valor: this.prepararValorParaEnvio(rec.valor)
                            }));
                            formData.append('lista_recorrencias', JSON.stringify(recorrenciasPuras));
                        }

                        const response = await fetch(url, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (response.ok) {
                            const result = await response.json();

                            if (result.success) {
                                console.log('Salvo com sucesso');
                                this.fecharModal();
                                await this.carregarLancamentos();

                                if (typeof Swal !== 'undefined') {
                                    Swal.fire({
                                        title: 'Sucesso!',
                                        text: 'Lançamento salvo com sucesso.',
                                        icon: 'success',
                                        confirmButtonText: 'OK',
                                        timer: 3000
                                    });
                                }
                            } else {
                                if (result.errors) {
                                    this.erros = result.errors;
                                    if (typeof Swal !== 'undefined') {
                                        Swal.fire({
                                            title: 'Ooops!',
                                            text: 'Erro ao salvar lançamento: ' + error.message,
                                            icon: 'error',
                                            confirmButtonText: 'OK',
                                            timer: 3000
                                        });
                                    }
                                } else {
                                    throw new Error(result.message || 'Erro ao salvar');
                                }
                            }
                        } else {
                            const errorText = await response.text();
                            throw new Error(`Erro ${response.status}: ${errorText}`);
                        }
                    } catch (error) {
                        console.error('Erro:', error);
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Ooops!',
                                text: 'Erro ao salvar lançamento: ' + error.message,
                                icon: 'error',
                                confirmButtonText: 'OK',
                                timer: 3000
                            });
                        }
                    } finally {
                        this.salvando = false;
                    }
                },

                // ========== EDITAR LANÇAMENTO ==========
                async editarLancamento(lan) {
                    console.log('Editando lançamento:', lan.id);
                    this.editando = true;
                    this.registroEditandoId = lan.id;
                    this.modalAbaNome = this.abaAtivaNome;

                    if (this.abaAtivaTipo === 'despesas') {
                        await this.carregarCategorias();
                    } else {
                        this.modalCategorias = [];
                    }

                    this.form = {
                        descricao: lan.descricao || '',
                        data_vencimento: this.formatarDataParaInput(lan.data_vencimento) || TODAY,
                        valor: this.converterParaBrasileiro(lan.valor) || '0,00',
                        cliente_id: lan.cliente_id || '',
                        profissional_id: lan.profissional_id || '',
                        categoria_id: this.abaAtivaTipo === 'despesas' ? (lan.ccustos_id || '') : '',
                        ccustos_id: lan.ccustos_id || '',
                        tipo_aba: this.abaAtivaTipo,
                        data_baixa: lan.data_baixa || '',
                        desconto: this.converterParaBrasileiro(lan.desconto) || '0,00',
                        juros: this.converterParaBrasileiro(lan.juros) || '0,00',
                        valor_recebido: this.converterParaBrasileiro(lan.valor_recebido) || '0,00',
                        repetir_lancamento: false,
                        periodo_recorrencia: 'mensal',
                        ocorrencia_recorrencia: 1
                    };
                    this.listaRecorrencias = [];
                    this.erros = {};

                    if (this.modalInstance) {
                        this.modalInstance.show();
                    }
                },

                gerarRecorrencias() {
                    if (!this.form.repetir_lancamento || !this.form.ocorrencia_recorrencia || this.form.ocorrencia_recorrencia < 1 || !this.form.data_vencimento) {
                        this.listaRecorrencias = [];
                        return;
                    }
                    
                    this.listaRecorrencias = [];
                    let dataBase = new Date(this.form.data_vencimento + 'T12:00:00'); // Evitar problemas de timezone
                    
                    for (let i = 1; i <= this.form.ocorrencia_recorrencia; i++) {
                        let novaData = new Date(dataBase);
                        
                        if (this.form.periodo_recorrencia === 'semanal') {
                            novaData.setDate(novaData.getDate() + (7 * i));
                        } else if (this.form.periodo_recorrencia === 'quinzenal') {
                            novaData.setDate(novaData.getDate() + (14 * i));
                        } else if (this.form.periodo_recorrencia === 'mensal') {
                            novaData.setMonth(novaData.getMonth() + i);
                        } else if (this.form.periodo_recorrencia === 'trimestral') {
                            novaData.setMonth(novaData.getMonth() + (3 * i));
                        } else if (this.form.periodo_recorrencia === 'semestral') {
                            novaData.setMonth(novaData.getMonth() + (6 * i));
                        } else if (this.form.periodo_recorrencia === 'anual') {
                            novaData.setFullYear(novaData.getFullYear() + i);
                        }
                        
                        let dataFormatada = novaData.toISOString().split('T')[0];
                        
                        this.listaRecorrencias.push({
                            //descricao: this.form.descricao + ' (' + (i+1) + '/' + (this.form.ocorrencia_recorrencia+1) + ')',
                            descricao: this.form.descricao,
                            data_vencimento: dataFormatada,
                            valor: this.form.valor
                        });
                    }
                },

                // ========== MÉTODOS DE FILTROS E BUSCA (RESTAURADOS) ==========
                async aplicarFiltros() {
                    console.log('=== FILTROS APLICADOS ===');
                    this.carregando = true;
                    this.pagination.page = 1;

                    try {
                        let url = `${URLS.search}?tipo=${this.abaAtivaTipo}&aba_id=${this.abaAtivaId}&page=1&limit=${this.pagination.limit || 10}`;

                        if (this.query && this.query.trim()) {
                            url += `&q=${encodeURIComponent(this.query.trim())}`;
                        }

                        if (this.dataInicio && this.dataInicio.trim()) {
                            url += `&data_inicio=${this.dataInicio.trim()}`;
                        }
                        if (this.dataFim && this.dataFim.trim()) {
                            url += `&data_fim=${this.dataFim.trim()}`;
                        }

                        if (this.categoriaFiltroId && this.categoriaFiltroId !== '') {
                            url += `&categoria_id=${this.categoriaFiltroId}`;
                        }

                        if (this.situacaoFiltro && this.situacaoFiltro !== '') {
                            url += `&situacao=${this.situacaoFiltro}`;
                        }

                        const resp = await fetch(url, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (!resp.ok) {
                            throw new Error(`Erro ${resp.status}: ${await resp.text()}`);
                        }

                        const data = await resp.json();

                        // No método aplicarFiltros() e carregarLancamentos()
                        // Substitua as linhas que atualizam a paginação:

                        if (data && data.data && Array.isArray(data.data)) {
                            this.lancamentos = data.data;
                            this.limparSelecao();

                            if (data.paginator) {
                                // CORREÇÃO: usar total ao invés de count
                                this.pagination.page = Number(data.paginator.page) || 1;
                                this.pagination.limit = Number(data.paginator.limit) || 10;
                                this.pagination.total = Number(data.paginator.total) || 0;  // ← MUDOU AQUI
                                this.pagination.pageCount = Number(data.paginator.pageCount) || 1;  // ← MUDOU AQUI

                                console.log('Paginação recebida:', data.paginator);
                                console.log('Total calculado:', this.pagination.total);
                                console.log('PageCount calculado:', this.pagination.pageCount);
                            }
                        } else {
                            this.lancamentos = [];
                            this.pagination = { page: 1, pageCount: 1, total: 0, limit: 10 };
                        }

                    } catch (err) {
                        console.error('Erro na busca:', err);
                        this.lancamentos = [];
                        this.pagination = { page: 1, pageCount: 1, total: 0, limit: 10 };
                    } finally {
                        this.carregando = false;
                    }
                },

                onSearch(e) {
                    if (e) e.preventDefault();
                    clearTimeout(this.buscaTimeout);
                    this.aplicarFiltros();
                },

                removerFiltro(tipo) {
                    switch (tipo) {
                        case 'texto':
                            this.query = '';
                            break;
                        case 'data':
                            this.dataInicio = '';
                            this.dataFim = '';
                            break;
                        case 'categoria':
                            this.categoriaFiltroId = '';
                            break;
                        case 'situacao':
                            this.situacaoFiltro = '';
                            break;
                    }
                    this.aplicarFiltros();
                },

                limparTodosFiltros() {
                    this.query = '';
                    this.dataInicio = '';
                    this.dataFim = '';
                    this.categoriaFiltroId = '';
                    this.situacaoFiltro = '';
                    this.aplicarFiltros();

                    // FEEDBACK DE SUCCESS (RESTAURADO)
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Filtros removidos',
                            text: 'Todos os filtros foram limpos.',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                },

                configurarBuscaGlobal() {
                    const buscaGlobal = document.querySelector('#app input[placeholder*="Buscar"], #app input[placeholder*="buscar"]');
                    if (buscaGlobal && !buscaGlobal.dataset.financasConfigurado) {
                        buscaGlobal.addEventListener('input', (e) => {
                            this.query = e.target.value;
                            this.debouncedSearch();
                        });
                        buscaGlobal.dataset.financasConfigurado = 'true';
                    }
                },

                debouncedSearch() {
                    clearTimeout(this.buscaTimeout);
                    this.buscaTimeout = setTimeout(() => {
                        this.pagination.page = 1;
                        this.limparSelecao();
                        this.aplicarFiltros();
                    }, 500);
                },

                // ========== CARREGAMENTO DE DADOS ==========
                async carregarLancamentos() {
                    console.log('Carregando lançamentos...');
                    this.carregando = true;

                    try {
                        let url = `${URLS.search}?tipo=${this.abaAtivaTipo}&aba_id=${this.abaAtivaId}&page=${this.pagination.page}&limit=${this.pagination.limit || 10}`;

                        if (this.query && this.query.trim()) {
                            url += `&q=${encodeURIComponent(this.query.trim())}`;
                        }
                        if (this.dataInicio && this.dataInicio.trim()) {
                            url += `&data_inicio=${this.dataInicio.trim()}`;
                        }
                        if (this.dataFim && this.dataFim.trim()) {
                            url += `&data_fim=${this.dataFim.trim()}`;
                        }
                        if (this.categoriaFiltroId && this.categoriaFiltroId !== '') {
                            url += `&categoria_id=${this.categoriaFiltroId}`;
                        }
                        if (this.situacaoFiltro && this.situacaoFiltro !== '') {
                            url += `&situacao=${this.situacaoFiltro}`;
                        }

                        console.log('URL de busca:', url); // Para debug

                        const resp = await fetch(url, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (!resp.ok) {
                            throw new Error(`HTTP ${resp.status}: ${await resp.text()}`);
                        }

                        const data = await resp.json();
                        console.log('Dados recebidos:', data); // Para debug

                        if (data && data.data && Array.isArray(data.data)) {
                            this.lancamentos = data.data;

                            if (data.paginator) {
                                // CORREÇÃO AQUI TAMBÉM
                                this.pagination.page = Number(data.paginator.page) || 1;
                                this.pagination.limit = Number(data.paginator.limit) || 10;
                                this.pagination.total = Number(data.paginator.total) || 0;  // ← total, não count
                                this.pagination.pageCount = Number(data.paginator.pageCount) || 1;  // ← pageCount, não calcular

                                console.log('Paginação atualizada:', this.pagination);
                            }
                        } else {
                            this.lancamentos = [];
                            this.pagination = { page: 1, pageCount: 1, total: 0, limit: 10 };
                        }

                    } catch (err) {
                        console.error('Erro ao carregar lançamentos:', err);
                        this.lancamentos = [];
                        this.pagination = { page: 1, pageCount: 1, total: 0, limit: 10 };
                    } finally {
                        this.carregando = false;
                    }
                },

                async selecionarAba(tipo, id, nome) {
                    this.abaAtiva = tipo === 'despesas' ? `despesas_${id}` : tipo;
                    this.abaAtivaId = id;
                    this.abaAtivaNome = nome;
                    this.abaAtivaTipo = tipo;

                    this.categoriaFiltroId = '';
                    this.situacaoFiltro = '';
                    this.limparSelecao();

                    if (tipo === 'despesas') {
                        await this.carregarCategorias();
                    } else {
                        this.modalCategorias = [];
                    }

                    this.pagination.page = 1;
                    await this.carregarLancamentos();
                },

                // ========== PAGINAÇÃO (RESTAURADA) ==========
                carregarPagina(p) {
                    console.log('Carregando página:', p);
                    if (p < 1 || p > this.pagination.pageCount) return;

                    this.pagination.page = p;
                    this.limparSelecao();
                    this.carregarLancamentos();
                },

                getPaginasVisiveis() {
                    const pageCount = this.pagination.pageCount;
                    if (pageCount <= 1) return [];

                    const pages = [];
                    const maxVisible = 5;
                    const currentPage = this.pagination.page;

                    let start = Math.max(1, currentPage - Math.floor(maxVisible / 2));
                    let end = Math.min(pageCount, start + maxVisible - 1);

                    if (end - start + 1 < maxVisible) {
                        start = Math.max(1, end - maxVisible + 1);
                    }

                    for (let i = start; i <= end; i++) {
                        pages.push(i);
                    }

                    return pages;
                },

                // ========== MODAL DE LANÇAMENTO ==========
                async abrirModal() {
                    console.log('Abrindo modal para novo lançamento');
                    this.editando = false;
                    this.registroEditandoId = null;
                    this.modalAbaNome = this.abaAtivaNome;

                    if (this.abaAtivaTipo === 'despesas') {
                        await this.carregarCategorias();
                    } else {
                        this.modalCategorias = [];
                    }

                    this.form = {
                        descricao: '',
                        data_vencimento: TODAY,
                        valor: '0,00',
                        cliente_id: '',
                        profissional_id: '',
                        categoria_id: '',
                        ccustos_id: '',
                        tipo_aba: this.abaAtivaTipo,
                        data_baixa: '',
                        desconto: '0,00',
                        juros: '0,00',
                        valor_recebido: '0,00'
                    };
                    this.erros = {};

                    if (this.modalInstance) {
                        this.modalInstance.show();
                    }
                },

                async carregarCategorias() {
                    if (this.abaAtivaTipo !== 'despesas') return;

                    try {
                        const url = `${URLS.categoriasBase}/${this.abaAtivaId}`;
                        const resp = await fetch(url, {
                            headers: { 'Accept': 'application/json' }
                        });

                        if (resp.ok) {
                            const data = await resp.json();
                            if (typeof data === 'object' && data !== null) {
                                this.modalCategorias = Object.entries(data).map(([id, name]) => ({
                                    id: parseInt(id),
                                    name
                                }));
                            }
                        }
                    } catch (e) {
                        console.error('Erro ao carregar categorias:', e);
                        this.modalCategorias = [];
                    }
                },

                fecharModal() {
                    if (this.modalInstance) {
                        this.modalInstance.hide();
                    }
                    this.erros = {};
                },

                validarFormulario() {
                    // SEM VALIDAÇÃO conforme solicitado
                    this.erros = {};
                    return true;
                },

                async confirmarExclusao(item) {
                    const descricao = item.descricao || 'este lançamento';

                    if (typeof Swal !== 'undefined') {
                        const result = await Swal.fire({
                            title: 'Tem certeza?',
                            html: `Excluir o lançamento "<strong>${descricao}</strong>"?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Sim, excluir!',
                            cancelButtonText: 'Cancelar'
                        });

                        if (result.isConfirmed) {
                            await this.executarExclusao(item.id);
                        }
                    } else if (window.confirm(`Excluir "${descricao}"?`)) {
                        await this.executarExclusao(item.id);
                    }
                },

                async executarExclusao(id) {
                    try {
                        const response = await fetch(`${URLS.excluirBase}/${id}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: new URLSearchParams({
                                '_method': 'DELETE',
                                '_csrfToken': this.csrfToken
                            })
                        });

                        if (response.ok) {
                            console.log('Excluído com sucesso');
                            await this.carregarLancamentos();

                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    title: 'Sucesso!',
                                    text: 'Lançamento excluído com sucesso.',
                                    icon: 'success',
                                    timer: 3000
                                });
                            }
                        } else {
                            throw new Error('Erro ao excluir');
                        }
                    } catch (error) {
                        console.error('Erro ao excluir:', error);
                        alert('Erro ao excluir lançamento');
                    }
                },

                // ========== FORMATAÇÃO ==========
                formatarData(d) {
                    if (!d) return '';
                    try {
                        return new Date(d + 'T00:00:00').toLocaleDateString('pt-BR');
                    } catch {
                        return '';
                    }
                },

                formatarDataSimples(data) {
                    if (!data) return '';
                    try {
                        const [year, month, day] = data.split('-');
                        return `${day}/${month}`;
                    } catch {
                        return data;
                    }
                },

                formatarDataParaInput(d) {
                    if (!d) return '';
                    try {
                        const date = new Date(d);
                        return date.toISOString().split('T')[0];
                    } catch {
                        return '';
                    }
                },

                formatarValor(v) {
                    if (!v || v === '') return '0,00';

                    const numero = parseFloat(v);
                    if (isNaN(numero)) return '0,00';

                    return numero.toLocaleString('pt-BR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            },

            beforeUnmount() {
                if (this.buscaTimeout) {
                    clearTimeout(this.buscaTimeout);
                }
                if (this.feedbackTimeout) {
                    clearTimeout(this.feedbackTimeout);
                }
            }
        });

        // Monta a aplicação
        app.mount('#app-lancamentos');
    })();
</script>
/**
 * vue-index.js — Reusable Vue 3 Index Component
 */

const FiltroPeriodo = {
    props: ['inicio', 'fim'],
    emits: ['update:inicio', 'update:fim', 'change'],
    template: `
        <div class="grupo-datas">
            <div class="data-input">
                <input :value="inicio" type="date" 
                    @input="$emit('update:inicio', $event.target.value)" 
                    @change="$emit('change')">
            </div>
            <div class="separador-ate">até</div>
            <div class="data-input">
                <input :value="fim" type="date" 
                    @input="$emit('update:fim', $event.target.value)" 
                    @change="$emit('change')">
            </div>
        </div>
    `
};

const FiltroBusca = {
    props: ['modelValue', 'placeholder'],
    emits: ['update:modelValue', 'search'],
    template: `
        <div class="position-relative">
            <input :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" 
                type="text" class="form-control rounded-pill ps-5" 
                :placeholder="placeholder || 'Buscar...'" @keyup.enter="$emit('search')">
            <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">
                <i class="ti ti-search"></i>
            </span>
        </div>
    `
};

const FiltroBadges = {
    props: ['query', 'dataInicio', 'dataFim', 'temFiltrosAtivos'],
    emits: ['removerFiltro', 'limparFiltros'],
    methods: {
        formatarDataSimples(data) {
            if (!data) return '';
            const [y, m, d] = data.split('-');
            return `${d}/${m}`;
        }
    },
    mounted() {
        if (typeof Iconify !== 'undefined') {
            Iconify.scan();
        }
    },
    template: `
        <div v-if="temFiltrosAtivos"
            class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3 mb-4 pt-3 border-top">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="text-muted me-1" style="font-size:0.82rem;">Filtros ativos:</span>

                <div v-if="query" class="filtro-badge">
                    <i class="ti ti-search" style="font-size:0.8rem;"></i>
                    <span>{{ query.length > 15 ? query.substring(0, 15) + '...' : query }}</span>
                    <button class="btn-remover" @click="$emit('removerFiltro', 'texto')">✕</button>
                </div>

                <div v-if="dataInicio || dataFim" class="filtro-badge">
                    <i class="ti ti-calendar" style="font-size:0.8rem;"></i>
                    <span>
                        {{ dataInicio ? formatarDataSimples(dataInicio) : '—' }}
                        &nbsp;-&nbsp;
                        {{ dataFim ? formatarDataSimples(dataFim) : '—' }}
                    </span>
                    <button class="btn-remover" @click="$emit('removerFiltro', 'data')">✕</button>
                </div>

                <slot></slot>
            </div>

            <button v-if="temFiltrosAtivos" class="btn-limpar-todos" @click="$emit('limparFiltros')" title="Limpar todos os filtros">
                <span class="iconify" data-icon="tabler:filter-2-x" style="font-size:1.3rem;"></span>
            </button>
        </div>
    `
};

function createVueIndex(extraConfig = {}) {
    const elApp = document.getElementById('app');
    const csrfToken = elApp.getAttribute('data-csrf');
    const routes = JSON.parse(elApp.getAttribute('data-routes') || '{}');
    const entityName = elApp.getAttribute('data-entity-name') || 'o item';

    // ── Merge helper ──
    function mergeObjects(base, extra) {
        const merged = { ...base };
        for (const key in extra) {
            if (typeof merged[key] === 'object' && typeof extra[key] === 'object' && !Array.isArray(merged[key])) {
                merged[key] = { ...merged[key], ...extra[key] };
            } else {
                merged[key] = extra[key];
            }
        }
        return merged;
    }

    // ── Base config ──
    const customFiltersMap = extraConfig.customFilters || {};
    const baseData = {
        lancamentos: [],
        items: [],
        csrfToken,
        routes,
        entityName,

        query: '',
        dataInicio: '',
        dataFim: '',
        situacaoFiltro: '',

        customFiltersMap, // Registramos o mapa globalmente para computed e methods

        carregando: false,
        buscaTimeout: null,

        pagination: {
            page: 1,
            limit: 10,
            total: 0,
            pageCount: 1
        },

        // Aggregates (optional, for pages that need them)
        valorTotal: 0,
        totalTaxas: 0
    };

    // Auto-injetar os customFilters como campos em baseData com valores vazios ('')
    for (const key in customFiltersMap) {
        baseData[key] = '';
    }

    const baseComputed = {
        filteredItems() {
            return this.lancamentos;
        },
        temFiltrosAtivos() {
            let temBase = !!(this.query || this.dataInicio || this.dataFim || this.situacaoFiltro);
            if (temBase) return true;
            for (const key in this.customFiltersMap) {
                if (this[key] !== '' && this[key] !== null && this[key] !== undefined) return true;
            }
            return false;
        },
        textoSituacao() {
            return {
                aberto: 'Em aberto',
                fechado: 'Fechado',
                '1': 'Ativo',
                '0': 'Inativo'
            }[this.situacaoFiltro] || '';
        }
    };

    const baseMethods = {
        debouncedSearch() {
            clearTimeout(this.buscaTimeout);
            this.buscaTimeout = setTimeout(() => {
                this.pagination.page = 1;
                this.carregarDados();
            }, 500);
        },

        aplicarFiltros() {
            this.pagination.page = 1;
            this.carregarDados();
        },

        removerFiltro(tipo) {
            if (tipo === 'texto') this.query = '';
            else if (tipo === 'data') { this.dataInicio = ''; this.dataFim = ''; }
            else if (tipo === 'situacao') this.situacaoFiltro = '';
            else if (this[tipo] !== undefined) Object.assign(this, { [tipo]: '' });

            this.aplicarFiltros();
        },

        limparTodosFiltros() {
            this.query = '';
            this.dataInicio = '';
            this.dataFim = '';
            this.situacaoFiltro = '';

            for (const key in this.customFiltersMap) {
                if (this[key] !== undefined) this[key] = '';
            }

            this.aplicarFiltros();
        },

        // Alias usado pelo componente filtro-badges
        limparFiltros() {
            this.limparTodosFiltros();
        },

        /**
         * Build the fetch URL. Override `buildSearchParams()` in extraConfig
         * to add custom query params for your specific page.
         */
        buildSearchUrl() {
            let url = `${this.routes.search}?page=${this.pagination.page}&limit=${this.pagination.limit}`;
            if (this.query && this.query.trim()) url += `&q=${encodeURIComponent(this.query.trim())}`;
            if (this.dataInicio) url += `&data_inicio=${this.dataInicio} 00:00:00`;
            if (this.dataFim) url += `&data_fim=${this.dataFim} 23:59:59`;
            if (this.situacaoFiltro) url += `&situacao=${this.situacaoFiltro}`;

            for (const key in this.customFiltersMap) {
                if (this[key] !== '' && this[key] !== null && this[key] !== undefined) {
                    url += `&${this.customFiltersMap[key]}=${encodeURIComponent(this[key])}`;
                }
            }

            // Allow pages to add extra params se ainda precisarem de alguma customização não-padrão
            if (typeof this.buildSearchParams === 'function') {
                const extra = this.buildSearchParams();
                if (extra) url += extra;
            }
            return url;
        },

        async carregarDados() {
            this.carregando = true;
            try {
                const url = this.buildSearchUrl();
                const resp = await fetch(url, {
                    credentials: 'same-origin',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });

                if (resp.ok) {
                    const data = await resp.json();
                    if (data && data.data) {
                        this.lancamentos = data.data;
                        this.items = data.data;
                        if (data.paginator) {
                            this.pagination.page = Number(data.paginator.page) || 1;
                            this.pagination.limit = Number(data.paginator.limit) || 10;
                            this.pagination.total = Number(data.paginator.total) || 0;
                            this.pagination.pageCount = Number(data.paginator.pageCount) || 1;
                        }
                        // Aggregates
                        if (data.aggregates) {
                            this.valorTotal = data.aggregates.valor_total || 0;
                            this.totalTaxas = data.aggregates.total_taxas || 0;
                        }
                    } else {
                        this.lancamentos = [];
                    }
                }
            } catch (err) {
                console.error('Erro ao carregar dados:', err);
            } finally {
                this.carregando = false;
            }
        },

        carregarPagina(p) {
            if (p < 1 || p > this.pagination.pageCount) return;
            this.pagination.page = p;
            this.carregarDados();
        },

        getPaginasVisiveis() {
            const { pageCount, page } = this.pagination;
            if (pageCount <= 1) return [];
            const maxVisible = 5;
            let start = Math.max(1, page - Math.floor(maxVisible / 2));
            let end = Math.min(pageCount, start + maxVisible - 1);
            if (end - start + 1 < maxVisible) start = Math.max(1, end - maxVisible + 1);
            const pages = [];
            for (let i = start; i <= end; i++) pages.push(i);
            return pages;
        },

        // ── Formatting utilities ──
        formatarDataHoraBR(dataStr) {
            if (!dataStr) return '';
            return new Date(dataStr).toLocaleString('pt-BR');
        },

        formatarDataBR(dataStr) {
            if (!dataStr) return '';
            const d = new Date(dataStr + 'T00:00:00');
            return d.toLocaleDateString('pt-BR');
        },

        formatarMoeda(valor) {
            if (!valor && valor !== 0) return '0,00';
            return parseFloat(valor).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },

        formatarDataSimples(dataStr) {
            if (!dataStr) return '';
            const [ano, mes, dia] = dataStr.split('-');
            return `${dia}/${mes}`;
        },

        capitalizar(str) {
            if (!str) return '';
            return str.charAt(0).toUpperCase() + str.slice(1);
        },

        async confirmarExclusao(item) {
            // Guarda: protege contra cache do navegador (JS antigo passava o Event como 1º arg)
            // ou contra chamada fora do v-for scope.
            if (!item || item instanceof Event || typeof item.id === 'undefined') {
                console.error('[vue-index] confirmarExclusao: item inválido recebido.', item);
                return;
            }

            const itemId = item.id;
            // Tenta achar um campo de nome legível; fallback para #id
            const itemNome = item.nome || item.name || item.descricao || item.titulo || `#${itemId}`;
            // Capitaliza o entityName (ex: "o Caixa" → exibe como está, ou "o caixa")
            const entidade = this.entityName || 'o registro';

            const swalConfirm = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-danger ms-2',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            });

            const result = await swalConfirm.fire({
                title: `Excluir ${entidade}?`,
                html: `Você está prestes a excluir <strong>${itemNome}</strong>.<br>
                       <small class="text-muted">Esta ação não pode ser desfeita.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="ti ti-trash me-1"></i> Sim, excluir',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                focusCancel: true
            });

            if (!result.isConfirmed) return;

            try {
                const url = `${this.routes.delete}/${itemId}`;
                const resp = await fetch(url, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `_method=DELETE&_csrfToken=${encodeURIComponent(this.csrfToken)}`
                });

                if (resp.ok) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Excluído!',
                        text: `"${itemNome}" foi excluído com sucesso.`,
                        timer: 1800,
                        showConfirmButton: false
                    });
                    this.carregarDados();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro ao excluir',
                        text: 'Não foi possível excluir o registro. Tente novamente.'
                    });
                }
            } catch (err) {
                console.error('Erro ao excluir:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de conexão',
                    text: 'Verifique sua conexão e tente novamente.'
                });
            }
        }
    };

    // ── Merge extra config ──
    const extraData = extraConfig.data ? extraConfig.data() : {};
    const mergedComputed = mergeObjects(baseComputed, extraConfig.computed || {});
    const mergedMethods = mergeObjects(baseMethods, extraConfig.methods || {});

    const appConfig = {
        ...extraConfig,
        data() {
            return { ...baseData, ...extraData };
        },
        computed: mergedComputed,
        methods: mergedMethods,
        mounted() {
            this.carregarDados();
            if (typeof extraConfig.onMounted === 'function') {
                extraConfig.onMounted.call(this);
            }
        }
    };

    const app = Vue.createApp(appConfig);
    app.component('filtro-periodo', FiltroPeriodo);
    app.component('filtro-busca', FiltroBusca);
    app.component('filtro-badges', FiltroBadges);
    app.mount('#app');
    return app;
}

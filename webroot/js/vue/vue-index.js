/**
 * vue-index.js — Reusable Vue 3 Index Component
 *
 * Provides a factory function `createVueIndex(extraConfig)` that creates
 * a standard Vue index app with search, filters, pagination, and utilities.
 *
 * Usage:
 *   <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
 *   <script src="/js/vue/vue-index.js"></script>
 *   <script>
 *       createVueIndex({
 *           data() { return { myExtra: true }; },
 *           computed: { ... },
 *           methods: { ... }
 *       });
 *   </script>
 */
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

    const baseComputed = {
        filteredItems() {
            return this.lancamentos;
        },
        temFiltrosAtivos() {
            return !!(this.query || this.dataInicio || this.dataFim || this.situacaoFiltro);
        },
        textoSituacao() {
            return { aberto: 'Em aberto', fechado: 'Fechado' }[this.situacaoFiltro] || '';
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
            if (tipo === 'data') { this.dataInicio = ''; this.dataFim = ''; }
            if (tipo === 'situacao') this.situacaoFiltro = '';
            this.aplicarFiltros();
        },

        limparTodosFiltros() {
            this.query = '';
            this.dataInicio = '';
            this.dataFim = '';
            this.situacaoFiltro = '';
            this.aplicarFiltros();
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
            if (this.situacaoFiltro) url += `&status_caixa=${this.situacaoFiltro}`;

            // Allow pages to add extra params
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

        confirmarExclusao(e, item) {
            if (!confirm(`Tem certeza que deseja excluir #${item.id}?`)) e.preventDefault();
        }
    };

    // ── Merge extra config ──
    const extraData = extraConfig.data ? extraConfig.data() : {};
    const mergedComputed = mergeObjects(baseComputed, extraConfig.computed || {});
    const mergedMethods = mergeObjects(baseMethods, extraConfig.methods || {});

    const appConfig = {
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
    app.mount('#app');
    return app;
}

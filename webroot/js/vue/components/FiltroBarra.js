<div class="row align-items-center g-2 mb-2">
    <!-- Busca textual -->
    <div :class="colBusca || 'col-xl-4 col-lg-4 col-md-12'">
        <div class="position-relative">
            <input v-model="query" type="text"
                class="form-control form-control-lg rounded-pill ps-5"
                placeholder="Buscar..."
                @input="debouncedSearch">
            <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">
                <i class="ti ti-search"></i>
            </span>
        </div>
    </div>

    <!-- Grupo datas -->
    <div :class="colDatas || 'col-xl-4 col-lg-4 col-md-12'">
        <filtro-periodo v-model:inicio="dataInicio" v-model:fim="dataFim" @change="aplicarFiltros"></filtro-periodo>
    </div>

    <!-- Slot para filtros extras (Situacao, Categoria, etc) -->
    <slot name="extras"></slot>
</div>

<!-- Filtros ativos (Badges) -->
<div v-if="temFiltrosAtivos"
    class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3 mb-4 pt-3 border-top">
    <div class="d-flex flex-wrap align-items-center gap-2">
        <span class="text-muted me-1" style="font-size:0.82rem;">Filtros ativos:</span>

        <div v-if="query" class="filtro-badge">
            <i class="ti ti-search" style="font-size:0.8rem;"></i>
            <span>{{ query.length > 15 ? query.substring(0, 15) + '...' : query }}</span>
            <button class="btn-remover" @click="removerFiltro('texto')">✕</button>
        </div>

        <div v-if="dataInicio || dataFim" class="filtro-badge">
            <i class="ti ti-calendar" style="font-size:0.8rem;"></i>
            <span>
                {{ dataInicio ? formatarDataSimples(dataInicio) : '—' }}
                &nbsp;-&nbsp;
                {{ dataFim ? formatarDataSimples(dataFim) : '—' }}
            </span>
            <button class="btn-remover" @click="removerFiltro('data')">✕</button>
        </div>

        <!-- Slot para badges extras -->
        <slot name="badges"></slot>
    </div>

    <button v-if="temFiltrosAtivos" class="btn-limpar-todos" @click="limparFiltros">
        Limpar todos
    </button>
</div>

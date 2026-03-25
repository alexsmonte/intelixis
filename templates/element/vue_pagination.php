<?php
/**
 * @var \App\View\AppView $this
 */
?>
<!-- Paginador Vue -->
<div class="row mt-4" v-if="pagination.pageCount > 1">
    <div class="col-12 d-flex justify-content-center">
        <nav aria-label="Navegação de página">
            <ul class="pagination pagination-sm mb-0">
                <!-- Anterior -->
                <li class="page-item" :class="{ disabled: pagination.page <= 1 }">
                    <button class="page-link" @click.prevent="carregarPagina(pagination.page - 1)"
                        aria-label="Anterior">
                        <span aria-hidden="true">&laquo;</span>
                    </button>
                </li>

                <!-- Páginas -->
                <li v-for="p in getPaginasVisiveis()" :key="p" class="page-item"
                    :class="{ active: p === pagination.page }">
                    <button class="page-link" @click.prevent="carregarPagina(p)">{{ p }}</button>
                </li>

                <!-- Próxima -->
                <li class="page-item" :class="{ disabled: pagination.page >= pagination.pageCount }">
                    <button class="page-link" @click.prevent="carregarPagina(pagination.page + 1)"
                        aria-label="Próximo">
                        <span aria-hidden="true">&raquo;</span>
                    </button>
                </li>
            </ul>
        </nav>
    </div>
    <div class="col-12 text-center mt-2 text-muted" style="font-size: 0.85rem;">
        Mostrando {{ lancamentos.length }} de {{ pagination.total }} registros (Página {{ pagination.page }} de
        {{ pagination.pageCount }})
    </div>
</div>

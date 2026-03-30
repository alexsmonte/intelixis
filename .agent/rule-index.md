# Rule: Padrão para Views Index

Todo arquivo `index.php` de listagem de entidades **deve seguir exatamente** o padrão estabelecido em `templates/Caixas/index.php`. Esta regra garante consistência visual, de comportamento e de código entre todas as views de listagem do painel.

---

## 1. Estrutura Geral do Arquivo

A ordem de elementos no arquivo deve ser:

```php
<?php
$this->BreadcrumbsPlus->add([['title' => 'Nome da Entidade']]);
?>

<!-- 1. CDN do Iconify -->
<script src="https://cdn.jsdelivr.net/npm/@iconify/iconify@3/dist/iconify.min.js"></script>

<!-- 2. Elemento barra de título -->
<?= $this->element('barra-titulo', ['title' => $title]); ?>

<!-- 3. CSS: index.css e barra-filtro.css -->
<?= $this->Html->css('index') ?>
<?= $this->Html->css('barra-filtro') ?>

<!-- 4. Vue App Container -->
<div id="app" v-cloak ...>
    <!-- conteúdo da listagem -->
</div>

<!-- 5. Vue 3 CDN + script vue-index.js + inicialização -->
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<?= $this->Html->script('vue/vue-index') ?>
<script>
    createVueIndex();
</script>
```

---

## 2. Elemento `barra-titulo`

Sempre incluir no topo, passando `$title` (variável enviada pelo Controller):

```php
<?= $this->element('barra-titulo', ['title' => $title]); ?>
```

O elemento exibe o título da página e o breadcrumb automático via `BreadcrumbsPlus`.

---

## 3. CSS Obrigatórios

Incluir **ambos** os arquivos CSS, nesta ordem:

```php
<?= $this->Html->css('index') ?>
<?= $this->Html->css('barra-filtro') ?>
```

| Arquivo | Responsabilidade |
|---|---|
| `webroot/css/index.css` | Ajustes responsivos mobile (tabela, botões, form-control) |
| `webroot/css/barra-filtro.css` | Estilização dos filtros, badges, botão limpar, grupo de datas |

**Não duplicar** essas regras CSS inline ou em arquivos separados por entidade.

---

## 4. Vue App Container (`#app`)

O `<div id="app">` deve conter os seguintes atributos obrigatórios:

```html
<div id="app" v-cloak
    data-csrf="<?= $this->request->getAttribute('csrfToken'); ?>"
    data-entity-name="o NomeDaEntidade"
    data-routes='<?= json_encode([
        'search'     => $this->Url->build(['controller' => 'NomeController', 'action' => 'search']),
        'visualizar' => $this->Url->build(['controller' => 'NomeController', 'action' => 'visualizar']),
        'edit'       => $this->Url->build(['controller' => 'NomeController', 'action' => 'edit']),
        'delete'     => $this->Url->build(['controller' => 'NomeController', 'action' => 'delete'])
    ], JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>
```

- `v-cloak`: evita flash de conteúdo não compilado pelo Vue.
- `data-csrf`: token CSRF, usado nas requisições e formulários de exclusão.
- `data-entity-name`: nome exibido em mensagens de confirmação (ex: `"o Caixa"`, `"o Pagamento"`).
- `data-routes`: JSON com as URLs das actions que o Vue precisará usar. Inclua apenas as actions utilizadas pela view.

---

## 5. Estrutura Interna do Card

Dentro de `#app`, obrigatoriamente usar a estrutura:

```html
<div class="card w-100 position-relative overflow-x-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">

            <!-- Título -->
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Nome da Entidade') ?></h4>
            </div>

            <!-- Linha de filtros -->
            <!-- Filtros ativos -->
            <!-- Tabela -->
            <!-- Paginação (dentro do .table-responsive-sm) -->

        </div>
    </div>
</div>
```

---

## 6. Barra de Filtros

### 6.1 Container dos filtros

```html
<div class="row align-items-center g-2 mb-2">
    <!-- cada filtro em um col-xl-4 col-lg-4 col-md-12 -->
</div>
```

### 6.2 Campo de busca textual (obrigatório em todas as views)

```html
<div class="col-xl-4 col-lg-4 col-md-12">
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
```

- Deve usar `v-model="query"` e `@input="debouncedSearch"` (sem botão "Procurar").
- A busca é reativa com debounce de 500ms, definido em `vue-index.js`.

### 6.3 Grupo de datas (quando aplicável)

```html
<div class="col-xl-4 col-lg-4 col-md-12">
    <div class="grupo-datas">
        <div class="data-input">
            <input v-model="dataInicio" type="date" @change="aplicarFiltros">
        </div>
        <div class="separador-ate">até</div>
        <div class="data-input">
            <input v-model="dataFim" type="date" @change="aplicarFiltros">
        </div>
    </div>
</div>
```

- Usar as classes `.grupo-datas`, `.data-input`, `.separador-ate` definidas em `barra-filtro.css`.
- Sempre usar `@change="aplicarFiltros"` (reativo, sem botão).

### 6.4 Select de situação / outros selects

```html
<div class="col-xl-4 col-lg-4 col-md-12">
    <select v-model="situacaoFiltro"
        class="form-select select-situacao w-100"
        @change="aplicarFiltros">
        <option value="">Todas situações</option>
        <option value="aberto">Em aberto</option>
        <option value="fechado">Fechado</option>
    </select>
</div>
```

- Usar a classe `.select-situacao` ou `.select-filtro` (definidas em `barra-filtro.css`).
- Sempre usar `@change="aplicarFiltros"` (reativo).

---

## 7. Filtros Ativos (Badges)

Exibir os filtros ativos abaixo da barra de filtros, **sempre** com esta estrutura:

```html
<div v-if="temFiltrosAtivos" class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-3 mb-4 pt-2 border-top">

    <div class="d-flex flex-wrap align-items-center gap-2">
        <span class="text-muted me-1" style="font-size:0.82rem;">Filtros ativos:</span>

        <!-- Badge de texto -->
        <div v-if="query" class="filtro-badge">
            <i class="ti ti-search" style="font-size:0.8rem;"></i>
            <span>{{ query.length > 15 ? query.substring(0, 15) + '...' : query }}</span>
            <button class="btn-remover" @click="removerFiltro('texto')">✕</button>
        </div>

        <!-- Badge de datas -->
        <div v-if="dataInicio || dataFim" class="filtro-badge">
            <i class="ti ti-calendar" style="font-size:0.8rem;"></i>
            <span>
                {{ dataInicio ? formatarDataSimples(dataInicio) : '—' }}
                &nbsp;-&nbsp;
                {{ dataFim ? formatarDataSimples(dataFim) : '—' }}
            </span>
            <button class="btn-remover" @click="removerFiltro('data')">✕</button>
        </div>

        <!-- Badge de situação -->
        <div v-if="situacaoFiltro" class="filtro-badge">
            <i class="ti ti-cash" style="font-size:0.8rem;"></i>
            <span>{{ textoSituacao }}</span>
            <button class="btn-remover" @click="removerFiltro('situacao')">✕</button>
        </div>

        <!-- Adicionar badges adicionais para outros filtros específicos da entidade -->
    </div>

    <!-- Botão limpar todos -->
    <button class="btn-limpar-todos" @click="limparTodosFiltros">
        Limpar todos
    </button>
</div>
```

- As classes `.filtro-badge`, `.btn-remover` e `.btn-limpar-todos` estão em `barra-filtro.css`.
- Cada filtro ativo tem seu próprio badge com botão `✕`.
- `temFiltrosAtivos` e `textoSituacao` são computeds providos por `vue-index.js`.

---

## 8. Tabela

```html
<div class="table-responsive-sm mb-4 border rounded-1 position-relative">

    <!-- Overlay de carregamento -->
    <div v-if="carregando"
        class="position-absolute w-100 h-100 d-flex justify-content-center align-items-center bg-white"
        style="opacity:0.7; z-index:10;">
        <i class="fa fa-spinner fa-spin fa-3x text-primary"></i>
    </div>

    <table class="table table-striped text-nowrap mb-0 align-middle">
        <thead>
            <tr>
                <!-- colunas da entidade -->
                <th>Opções</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in filteredItems" :key="item.id">
                <!-- células da entidade -->
                <td style="width:8%;">
                    <!-- Dropdown de opções (ver seção 9) -->
                </td>
            </tr>
            <tr v-if="filteredItems.length === 0 && !carregando">
                <td colspan="N" class="text-center text-muted py-4">Nenhum registro encontrado</td>
            </tr>
        </tbody>
    </table>

    <!-- Paginação -->
    <div class="p-3">
        <?= $this->element('vue_pagination') ?>
    </div>
</div>
```

- O overlay de loading com `fa-spinner` é obrigatório.
- A linha vazia (`v-if="filteredItems.length === 0 && !carregando"`) é obrigatória.
- Ajustar `colspan` ao número real de colunas.
- O elemento `vue_pagination` é sempre incluído **dentro** do `.table-responsive-sm`, no final.

---

## 9. Dropdown de Opções por Linha

Cada linha da tabela deve ter um dropdown de ações no padrão:

```html
<td style="width:8%;">
    <div class="dropdown">
        <button
            class="btn bg-transparent text-primary d-inline-flex align-items-center justify-content-center"
            type="button" :id="'dropdownMenu-' + item.id"
            data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-bars"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" :aria-labelledby="'dropdownMenu-' + item.id">

            <!-- Visualizar -->
            <li>
                <a class="dropdown-item" :href="`${routes.visualizar}/${item.id}`">
                    <span class="iconify text-gray" data-icon="solar:eye-bold-duotone" style="font-size:15px;"></span> Visualizar
                </a>
            </li>

            <!-- Editar -->
            <li>
                <a class="dropdown-item" :href="`${routes.edit}/${item.id}`">
                    <span class="iconify text-gray" data-icon="solar:pen-bold-duotone" style="font-size:15px;"></span> Editar
                </a>
            </li>

            <li><hr class="dropdown-divider"></li>

            <!-- Excluir -->
            <li>
                <button type="button" class="dropdown-item text-danger"
                    @click="confirmarExclusao(item)">
                    <span class="iconify text-danger" data-icon="solar:trash-bin-minimalistic-bold-duotone" style="font-size:15px;"></span> Excluir
                </button>
            </li>

        </ul>
    </div>
</td>
```

- Usar ícones Solar via Iconify os ícones: `solar:eye-bold-duotone`, `solar:pen-bold-duotone`, `solar:trash-bin-minimalistic-bold-duotone`.
- **Exclusão via `confirmarExclusao(item)`** — NÃO usar formulário HTML. O botão excluir deve chamar diretamente o método do Vue.
- O método abre um diálogo SweetAlert2 de confirmação e faz o `fetch` DELETE com CSRF automaticamente via `vue-index.js`.
- **Não usar** `@submit.prevent`, `<form>`, `<input type="hidden">` para ação de exclusão.

---

## 10. Vue 3: Inicialização com `createVueIndex()`

```html
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<?= $this->Html->script('vue/vue-index') ?>
<script>
    createVueIndex();
</script>
```

A função `createVueIndex()` (em `webroot/js/vue/vue-index.js`) fornece automaticamente:

| Data | Descrição |
|---|---|
| `query` | Texto de busca |
| `dataInicio` / `dataFim` | Filtros de data |
| `situacaoFiltro` | Filtro de situação/status |
| `carregando` | Estado de loading |
| `pagination` | Objeto com `page`, `limit`, `total`, `pageCount` |
| `lancamentos` / `items` | Array de dados retornados da API |

| Computed | Descrição |
|---|---|
| `filteredItems` | Alias de `lancamentos` |
| `temFiltrosAtivos` | `true` se algum filtro estiver ativo |
| `textoSituacao` | Label do filtro de situação |

| Método | Descrição |
|---|---|
| `debouncedSearch()` | Busca com debounce de 500ms |
| `aplicarFiltros()` | Reinicia página e recarrega dados |
| `removerFiltro(tipo)` | Remove filtro específico (`'texto'`, `'data'`, `'situacao'`) |
| `limparTodosFiltros()` | Remove todos os filtros |
| `carregarDados()` | Faz fetch na action `search` |
| `carregarPagina(p)` | Navega para a página `p` |
| `formatarDataHoraBR(str)` | Formata data e hora em pt-BR |
| `formatarDataBR(str)` | Formata apenas data em pt-BR |
| `formatarMoeda(valor)` | Formata número como moeda pt-BR |
| `formatarDataSimples(str)` | Retorna `dd/mm` sem o ano |
| `capitalizar(str)` | Primeira letra maiúscula |
| `confirmarExclusao(item)` | Abre SweetAlert2 de confirmação e executa fetch DELETE com CSRF |

### Extensão via `extraConfig`

Para adicionar filtros, dados ou comportamentos específicos à entidade, passar um objeto `extraConfig`:

```js
createVueIndex({
    data() {
        return {
            meuFiltroExtra: ''
        };
    },
    computed: {
        temFiltrosAtivos() {
            // sobrescreve o computed base
            return !!(this.query || this.dataInicio || this.dataFim || this.situacaoFiltro || this.meuFiltroExtra);
        }
    },
    methods: {
        buildSearchParams() {
            // parâmetros extras na URL de busca
            let params = '';
            if (this.meuFiltroExtra) params += `&meu_filtro=${encodeURIComponent(this.meuFiltroExtra)}`;
            return params;
        },
        removerFiltro(tipo) {
            if (tipo === 'meuFiltro') this.meuFiltroExtra = '';
            this.aplicarFiltros();
        }
    }
});
```

---

## 11. Elemento `vue_pagination`

Sempre incluir via element do CakePHP **dentro** do `.table-responsive-sm`:

```php
<div class="p-3">
    <?= $this->element('vue_pagination') ?>
</div>
```

O elemento `templates/element/vue_pagination.php` renderiza a paginação completa usando os dados de `this.pagination` e os métodos `carregarPagina()` e `getPaginasVisiveis()` do `vue-index.js`. Não recriar paginação inline.

---

## 12. Elemento `barra-titulo` e Breadcrumbs

```php
<?php
$this->BreadcrumbsPlus->add([['title' => 'Nome da Entidade']]);
?>
<?= $this->element('barra-titulo', ['title' => $title]); ?>
```

- Adicionar o breadcrumb via `BreadcrumbsPlus->add()` antes de incluir o elemento.
- O `$title` deve ser enviado pelo Controller (via `$this->set('title', 'Título')` ou equivalente).

---

## 13. Checklist de Conformidade

Antes de fazer commit de uma nova view index, verificar:

- [ ] `BreadcrumbsPlus->add()` no topo do arquivo
- [ ] CDN Iconify incluído (`jsdelivr.net/npm/@iconify/iconify@3`)
- [ ] Elemento `barra-titulo` com `$title`
- [ ] `<?= $this->Html->css('index') ?>`
- [ ] `<?= $this->Html->css('barra-filtro') ?>`
- [ ] `<div id="app" v-cloak>` com `data-csrf`, `data-entity-name`, `data-routes`
- [ ] Estrutura de card com classes corretas
- [ ] Campo de busca com `v-model="query"` e `@input="debouncedSearch"` (sem botão de submit)
- [ ] Filtros reativos (`@change="aplicarFiltros"`) — sem botão "Procurar"
- [ ] Grupo de datas com `.grupo-datas` / `.data-input` / `.separador-ate`
- [ ] Selects com classe `.select-situacao` ou `.select-filtro`
- [ ] Seção de filtros ativos com `.filtro-badge` e `.btn-limpar-todos`
- [ ] Overlay de loading com `fa-spinner`
- [ ] Linha de "nenhum resultado" com `v-if="filteredItems.length === 0 && !carregando"`
- [ ] Dropdown de opções com ícones Solar/Iconify
- [ ] Botão excluir com `@click="confirmarExclusao(item)"` — **sem** formulário HTML
- [ ] SweetAlert2 disponível globalmente (`Swal`) via CDN no layout
- [ ] Element `vue_pagination` dentro do `.table-responsive-sm`
- [ ] Vue 3 CDN + `vue/vue-index` script + `createVueIndex()` no final

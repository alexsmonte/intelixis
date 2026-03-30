/*
 Bootstrap Select (Modern, Bootstrap 5, No jQuery)
 Lightweight reimplementation inspired by the original bootstrap-select (silviomoreto)
 Features implemented (core):
 - Single and multiple select support
 - Searchable dropdown
 - Optgroup support
 - Keyboard navigation
 - Methods: refresh(), destroy(), select(value), deselect(value), render()
 - Auto-initialize for <select class="selectpicker"> elements on DOMContentLoaded

 Usage:
 Include this script AFTER Bootstrap 5's JS. Add class "selectpicker" to your <select> elements.
 You can also initialize programmatically:
 const bs = new BootstrapSelect(document.querySelector('select'));
 bs.refresh();

 This file exports a global BootstrapSelect class and auto-initializes elements with class 'selectpicker'.
 */

(function (global) {
    'use strict';

    const defaults = {
        noneSelectedText: 'Nada selecionado',
        noneResultsText: 'Nenhum resultado encontrado',
        showTick: true,
        liveSearch: true,
        size: 10,
        selectOnClick: true
    };

    function createEl(tag, attrs = {}, children = []) {
        const el = document.createElement(tag);
        for (const k in attrs) {
            if (k === 'class') el.className = attrs[k];
            else if (k === 'html') el.innerHTML = attrs[k];
            else if (k === 'text') el.textContent = attrs[k];
            else el.setAttribute(k, attrs[k]);
        }
        (Array.isArray(children) ? children : [children]).forEach(c => c && el.appendChild(c));
        return el;
    }

    class BootstrapSelect {
        constructor(select, options = {}) {
            if (!(select instanceof HTMLSelectElement)) throw new Error('Element must be a select');
            this.select = select;
            this.options = Object.assign({}, defaults, options);
            // suporte a busca remota via data-remote-url
            if (this.select.dataset.remoteUrl) {
                this.options.remoteUrl = this.select.dataset.remoteUrl;
            }
            this.multiple = select.multiple;
            this.disabled = select.disabled;
            this._onDocumentClick = this._onDocumentClick.bind(this);
            this._onKeyDown = this._onKeyDown.bind(this);
            this._onSearch = this._onSearch.bind(this);
            this._onItemClick = this._onItemClick.bind(this);

            // ler data-* do select e sobrescrever opções
            const ds = this.select.dataset || {};

// mapear data attributes para options (conversões simples)
            if (ds.noneSelectedText) this.options.noneSelectedText = ds.noneSelectedText;
            if (ds.noneResultsText) this.options.noneResultsText = ds.noneResultsText;

// liveSearch aceita "1" / "true" / "false"
            if (ds.liveSearch !== undefined) {
                const v = ds.liveSearch;
                this.options.liveSearch = (v === '1' || v === 'true');
            }

// tamanho (size) se fornecido
            if (ds.size) {
                const n = parseInt(ds.size, 10);
                if (!Number.isNaN(n)) this.options.size = n;
            }

// showTick
            if (ds.showTick !== undefined) {
                const v = ds.showTick;
                this.options.showTick = (v === '1' || v === 'true');
            }



            this._build();
            this.refresh();
        }

        _build() {
            // hide original select but keep it in the DOM
            this.select.style.display = 'none';
            this.select.classList.add('d-none');

            // wrapper
            this.container = createEl('div', { class: 'bootstrap-select bs5-select' });
            this.select.parentNode.insertBefore(this.container, this.select.nextSibling);

            // button
            this.button = createEl('button', { type: 'button', class: 'form-select dropdown-toggle', 'aria-haspopup': 'listbox', 'aria-expanded': 'false' });
            this.buttonText = createEl('span', { class: 'btn-text' });
            this.button.appendChild(this.buttonText);
            this.container.appendChild(this.button);

            this.buttonText.innerHTML = '&nbsp;';

            // dropdown menu
            this.menu = createEl('div', { class: 'dropdown-menu p-2', role: 'listbox' });
            this.menu.style.minWidth = '250px';

            // search
            if (this.options.liveSearch) {
                this.searchInput = createEl('input', { type: 'search', class: 'form-control form-control-md mb-2', placeholder: 'Pesquisar...' });
                this.searchInput.addEventListener('input', this._onSearch);
                this.menu.appendChild(this.searchInput);
            }

            // list
            this.list = createEl('div', { class: 'select-list', role: 'presentation' });
            this.menu.appendChild(this.list);

            this.container.appendChild(this.menu);

            // event handlers
            this.button.addEventListener('click', (e) => {
                e.stopPropagation();
            this.toggle();
            });
            this.menu.addEventListener('click', (e) => e.stopPropagation());

            // keyboard
            this.container.addEventListener('keydown', this._onKeyDown);

            // close on outside click
            document.addEventListener('click', this._onDocumentClick);
        }

        _buildList() {
            this.list.innerHTML = '';

            const children = Array.from(this.select.children);
            children.forEach(node => {
                if (node.tagName === 'OPTGROUP') {
                const groupLabel = createEl('div', { class: 'dropdown-header', text: node.label });
                this.list.appendChild(groupLabel);
                node.querySelectorAll('option').forEach(opt => this._createItem(opt));
            } else if (node.tagName === 'OPTION') {
                this._createItem(node);
            }
        });
        }

        _createItem(option) {
            const value = option.value;
            const text = option.textContent;
            const disabled = option.disabled;

            const item = createEl('button', { type: 'button', class: 'dropdown-item d-flex align-items-center', 'data-value': value });
            if (disabled) item.classList.add('disabled');
            const label = createEl('span', { class: 'flex-grow-1', text });
            item.appendChild(label);
            if (this.options.showTick) {
                const tick = createEl('span', {
                    class: 'ms-2 checkmark',
                    html: '<svg width="24" height="24"><circle cx="15" cy="15" r="7" fill="var(--bs-dropdown-link-hover-color)"/></svg>'
                });
                tick.style.display = 'none';
                item.appendChild(tick);
            }
            item.addEventListener('click', this._onItemClick);
            this.list.appendChild(item);
        }

        _onItemClick(e) {
            const btn = e.currentTarget;
            if (btn.classList.contains('disabled')) return;
            const val = btn.getAttribute('data-value');
            const option = Array.from(this.select.options).find(o => o.value === val);
            if (!option) return;

            if (this.multiple) {
                option.selected = !option.selected;
            } else {
                if (option.selected && this.options.selectOnClick) {
                    // keep closed or toggle?
                }
                Array.from(this.select.options).forEach(o => o.selected = false);
                option.selected = true;
            }
            this._syncFromSelect();
            this.select.dispatchEvent(new Event('change', { bubbles: true }));

            if (!this.multiple) this.hide();
        }

        _localSearch(query) {
            const q = query.toLowerCase();
            const items = Array.from(this.list.querySelectorAll('[data-value]'));
            let visible = 0;

            items.forEach(it => {
                const txt = it.textContent.trim().toLowerCase();
            const match = txt.includes(q);
            it.classList.toggle('hidden', !match);
            if (match) visible++;
        });

            if (visible === 0) {
                if (!this._noResults) {
                    this._noResults = createEl('div', { class: 'text-muted small mt-2', text: this.options.noneResultsText });
                    this.menu.appendChild(this._noResults);
                }
            } else if (this._noResults) {
                this._noResults.remove();
                this._noResults = null;
            }
        }


        _remoteSearch(query) {
            clearTimeout(this.remoteTimer);

            if (this._noResults) {
                this._noResults.remove();
                this._noResults = null;
            }

            this.remoteTimer = setTimeout(() => {
                if (query.length < 2)
                    return;

            fetch(this.options.remoteUrl + '?termo=' + encodeURIComponent(query))
                .then(resp => resp.json())
        .then(items => {

                // limpar select original
                this.select.innerHTML = '';

            items.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.textContent = item.label;
                this.select.appendChild(opt);
            });

            this.select.querySelectorAll('option').forEach(o => o.selected = false);

            this._buildList();
            this._syncFromSelect();
        })
        .catch(err => console.error('Erro busca remota:', err));

        }, 300);
        }

        _onSearch() {
            const query = this.searchInput.value.trim();

            if (this.options.remoteUrl) {
                this._remoteSearch(query);
                return;
            }

            this._localSearch(query);
        }

        _onDocumentClick(e) {
            if (!this.container.contains(e.target)) this.hide();
        }

        _onKeyDown(e) {

            const key = e.key;
            const items = Array.from(this.list.querySelectorAll('[data-value]'))
                    .filter(i => !i.classList.contains('hidden') && !i.classList.contains('disabled'));

            if (items.length === 0) return;

            const activeIndex = items.findIndex(i => i.classList.contains('active'));

            if (key === 'ArrowDown') {
                e.preventDefault();
                const next = (activeIndex + 1) % items.length;
                this._focusItem(items[next]);
            } else if (key === 'ArrowUp') {
                e.preventDefault();
                const prev = (activeIndex - 1 + items.length) % items.length;
                this._focusItem(items[prev]);
            } else if (key === 'Enter') {
                e.preventDefault();
                if (items[activeIndex]) items[activeIndex].click();
            } else if (key === 'Escape') {
                this.hide();
            }
        }

        _focusItem(item) {
            Array.from(this.list.querySelectorAll('.active')).forEach(i => i.classList.remove('active'));
            if (!item) return;
            item.classList.add('active');
            item.scrollIntoView({ block: 'nearest' });
        }

        _syncFromSelect() {
            const selected = Array.from(this.select.selectedOptions);

            // --- Mostrar texto do botão ---
            if (selected.length === 0) {
                if (!this.options.noneSelectedText || this.options.noneSelectedText.trim() === '') {
                    this.buttonText.innerHTML = '&nbsp;';
                } else {
                    this.buttonText.textContent = this.options.noneSelectedText;
                }
            }
            else if (!this.multiple) {
                this.buttonText.textContent = selected[0].textContent;
            }
            else {
                this.buttonText.textContent = selected.map(s => s.textContent).join(', ');
            }

            // --- Atualizar ticks (bolinha selecionada) ---
            Array.from(this.list.querySelectorAll('[data-value]')).forEach(item => {
                const val = item.getAttribute('data-value');
            const isSelected = selected.some(o => o.value === val);

            if (this.options.showTick) {
                const tick = item.querySelector('.checkmark');
                if (tick) tick.style.display = isSelected ? '' : 'none';
            }
        });
        }


        refresh() {
            this._buildList();
            this._syncFromSelect();
        }

        show() {
            if (this.disabled) return;
            this.container.classList.add('show');
            this.menu.classList.add('show');
            this.button.setAttribute('aria-expanded', 'true');
            if (this.searchInput) this.searchInput.focus();
        }

        hide() {
            this.container.classList.remove('show');
            this.menu.classList.remove('show');
            this.button.setAttribute('aria-expanded', 'false');
        }

        toggle() {
            if (this.menu.classList.contains('show')) this.hide(); else this.show();
        }

        clear() {
            // Desmarca todas as opções
            Array.from(this.select.options).forEach(o => o.selected = false);

            // Atualiza o visual
            this._syncFromSelect();

            // Dispara evento 'change' para manter consistência
            this.select.dispatchEvent(new Event('change', { bubbles: true }));
        }


        selectValue(val) {
            const opt = Array.from(this.select.options).find(o => o.value === val);
            if (!opt) return;
            if (this.multiple) opt.selected = true; else {
                Array.from(this.select.options).forEach(o => o.selected = false);
                opt.selected = true;
            }
            this._syncFromSelect();
        }

        deselectValue(val) {
            const opt = Array.from(this.select.options).find(o => o.value === val);
            if (!opt) return;
            opt.selected = false;
            this._syncFromSelect();
        }

        destroy() {
            document.removeEventListener('click', this._onDocumentClick);
            this.container.remove();
            this.select.style.display = ''; // restore
        }
    }

    // auto init
    document.addEventListener('DOMContentLoaded', () => {
        const nodes = document.querySelectorAll('select.selectpicker');
    nodes.forEach(n => {
        if (!n._bsSelect) n._bsSelect = new BootstrapSelect(n);
});
});

    // export to global
    global.BootstrapSelect = BootstrapSelect;

})(window);
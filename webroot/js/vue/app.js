function createEntityApp() {
    const el = document.querySelector('#app');
    if (!el) return;

    const dataInit = JSON.parse(el.dataset.entity || '[]');
    const csrfToken = el.dataset.csrf;
    const routes = JSON.parse(el.dataset.routes || '{}');

    const app = Vue.createApp({
        data() {
            return {
                query: '',
                items: dataInit,
                csrfToken,
                routes,
                entityName: el.dataset.entityName || 'Registro' // <- aqui
            };
        },
        computed: {
            filteredItems() {
                if (!this.query) return this.items;
                const q = this.query.toLowerCase();
                return this.items.filter(i => i.nome.toLowerCase().includes(q) || (i.email && i.email.toLowerCase().includes(q)));
            }
        },
        methods: {
            async onSearch() {
                try {
                    const res = await fetch(routes.search + '?q=' + encodeURIComponent(this.query), {
                        credentials: 'same-origin'
                    });
                    const data = await res.json();
                    this.items = data.data || [];
                } catch (err) {
                    console.error(err);
                    alert('Erro ao buscar registros.');
                }
            },
            async viewEntity(id) {
                try {
                    const res = await fetch(`${routes.view}/${id}`, {
                        credentials: 'same-origin',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const html = await res.text();
                    const modalEl = document.getElementById('view-modal');
                    modalEl.querySelector('#modal-body-content').innerHTML = html;
                    new bootstrap.Modal(modalEl).show();
                } catch (err) {
                    console.error(err);
                }
            },
            confirmarExclusao(event, item) {
                event.preventDefault();
                const form = event.target;
            
                Swal.fire({
                    title: `Tem certeza que deseja excluir ${this.entityName}: <br> "${item.nome}"?`,
                    html: `<p style="font-size:0.95rem; color:#374151;">Esta ação não poderá ser desfeita.</p>`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sim, excluir!",
                    cancelButtonText: "Cancelar",
                    buttonsStyling: false,
                    customClass: {
                        popup: 'rounded-2xl shadow-lg border border-gray-200 p-6',
                        title: 'text-red-600 fw-bold fs-6',   // <- fs-6 é menor que o padrão
                        confirmButton: 'btn btn-danger rounded-pill px-4 py-2',
                        cancelButton: 'btn bg-primary-subtle text-primary rounded-pill px-4 py-2 ms-2'
                    }
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            }
            
            
            
        }
    });

    app.mount('#app');
}

document.addEventListener('DOMContentLoaded', createEntityApp);

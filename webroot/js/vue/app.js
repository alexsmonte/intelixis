// webroot/vue/app.js

const tableData = window.TABLE_DATA || [];
const controller = window.TABLE_CONTROLLER || '';
const csrfToken = window.CSRF_TOKEN;
console.log("Dados da tabela: ",tableData);
console.log("Controller: ",controller);
console.log("TOKEN: ",csrfToken);

Vue.createApp({
    data() {
        return {
            query: '',
            records: tableData,
            csrfToken: csrfToken
        }
    },
    methods: {
        async onSearch() {
            try {
                const url = `/painel/${controller.toLowerCase()}/search?q=` + encodeURIComponent(this.query);
                const res = await fetch(url, { credentials: 'same-origin' });
                const data = await res.json();
                this.records = data.data || [];
            } catch (err) {
                console.error(err);
                alert('Erro ao buscar registros.');
            }
        },
        async viewRecord(id) {
            try {
                const res = await fetch(`/painel/${controller.toLowerCase()}/visualizar/${id}`, {
                    credentials: 'same-origin',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!res.ok) throw new Error('Erro ao carregar registro');

                const html = await res.text();

                const modalEl = document.getElementById('view-modal');
                modalEl.querySelector('#modal-body-content').innerHTML = `
                    <div>
                        ${html}
                        <div class="modal-footer mt-3">
                            <a href="/painel/${controller.toLowerCase()}/editar/${id}" class="btn btn-primary">Editar</a>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                `;

                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            } catch (err) {
                console.error(err);
                alert('Erro ao carregar registro.');
            }
        },
        confirmarExclusao(event) {
            event.preventDefault();
            const form = event.target;

            Swal.fire({
                title: `Tem certeza que deseja excluir este ${controller.slice(0, -1)}?`,
                text: "Esta ação não pode ser desfeita.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sim, excluir!"
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        }
    }
}).mount('#app');

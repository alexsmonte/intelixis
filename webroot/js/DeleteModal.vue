/**
 * DeleteModal — Confirmação de exclusão via SweetAlert2
 *
 * Este arquivo define o objeto de componente Vue 3 compatível com CDN global (sem build step).
 * O SweetAlert2 deve estar carregado globalmente como `Swal` (já incluído no layout padrão).
 *
 * Uso no vue-index.js (já integrado via confirmarExclusao):
 *   Não precisa ser registrado manualmente — a lógica é incorporada em vue-index.js.
 *
 * Para usar standalone em qualquer app Vue 3 CDN:
 *
 *   const app = Vue.createApp({ ... });
 *   app.component('delete-modal', DeleteModal);
 *   app.mount('#app');
 *
 * Dispara o evento 'deleted' após exclusão bem-sucedida.
 */
const DeleteModal = {
    props: {
        csrfToken: { type: String, required: true },
        entityName: { type: String, default: 'o registro' }
    },

    emits: ['deleted'],

    methods: {
        /**
         * Abre o diálogo SweetAlert2 de confirmação e, se confirmado,
         * envia DELETE via fetch com o token CSRF.
         *
         * @param {string} url    - URL da action delete (ex: /painel/caixas/delete/5)
         * @param {object} item   - Objeto do item (precisa do .id)
         */
        async open(url, item) {
            const swalConfirm = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-danger ms-2',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            });

            const result = await swalConfirm.fire({
                title: 'Confirmar exclusão',
                html: `Tem certeza que deseja excluir <strong>#${item.id}</strong>?<br>
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
                const resp = await fetch(url, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-HTTP-Method-Override': 'DELETE',
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `_method=DELETE&_csrfToken=${encodeURIComponent(this.csrfToken)}`
                });

                if (resp.ok) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Excluído!',
                        text: `${this.entityName.charAt(0).toUpperCase() + this.entityName.slice(1)} foi excluído com sucesso.`,
                        timer: 1800,
                        showConfirmButton: false
                    });
                    this.$emit('deleted', item.id);
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
    },

    // Template vazio — este componente não renderiza HTML próprio,
    // toda a UI é gerada pelo SweetAlert2.
    template: `<span></span>`
};

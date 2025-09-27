
(function () {
    const { createApp, reactive } = Vue;

    const GenericModal = {
        name: "GenericModal",
        props: {
            title: { type: String, default: "Modal" },
            submitUrl: { type: String, required: true }, // ex: "/clientes/add"
            fields: { type: Array, default: () => [] },
            modalSize: { type: String, default: '' }
        },
        setup(props) {
            const form = reactive({});
            props.fields.forEach(f => form[f.name] = "");

            let bsModal = null;

            const abrirModal = () => { if (bsModal) bsModal.show(); };
            const fecharModal = () => { if (bsModal) bsModal.hide(); };

            const submitForm = async () => {
                try {
                    const data = new FormData();

                    // 1️⃣ Atualiza o form reativo com os inputs do DOM (CEP autocomplete etc.)
                    const modalEl = document.getElementById("genericModal");
                    if (modalEl) {
                        const inputs = modalEl.querySelectorAll("input, select, textarea");
                        inputs.forEach(input => {
                            // Se o input tem id e está no form reativo, atualiza
                            if (input.id && form.hasOwnProperty(input.id)) {
                                form[input.id] = input.value;
                            }
                        });
                    }

                    // 2️⃣ Adiciona todos os campos do form reativo no FormData
                    for (let key in form) {
                        data.append(key, form[key]);
                    }

                    // 3️⃣ Envia o formulário
                    const resp = await fetch(props.submitUrl, {
                        method: "POST",
                        body: data,
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-Token": document.querySelector('meta[name="csrfToken"]').getAttribute("content")
                        }
                    });

                    const result = await resp.json();

                    if (result.success) {
                        Swal.fire({ icon: "success", title: "Sucesso", text: result.message });
                        fecharModal();
                    } else {
                        Swal.fire({ icon: "error", title: "Erro", text: result.message });
                    }

                } catch (e) {
                    console.error("Erro ao enviar formulário:", e);
                    Swal.fire({ icon: "error", title: "Erro", text: "Não foi possível enviar o formulário." });
                }
            };



            const initBootstrap = () => {
                const modalEl = document.getElementById("genericModal");
                if (modalEl) {
                    bsModal = new bootstrap.Modal(modalEl, { backdrop: 'static' });
                }
            };

            return { form, abrirModal, fecharModal, submitForm, initBootstrap };
        },
        mounted() { this.initBootstrap(); },
        template: `
        <div>
            <div class="modal fade" id="genericModal" tabindex="-1">
                <div class="modal-dialog" :class="modalSize">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ title }}</h5>
                            <button type="button" class="btn-close" @click="fecharModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <template v-for="f in fields" :key="f.name">
                                    <!-- Se for cep-autocomplete apenas renderiza o div -->
                                    <div v-if="f.vueComponent" :class="'col-md-'+(f.col||3)" :data-vue="f.vueComponent"></div>

                                    <!-- Campos normais -->
                                    <div v-else :class="'col-md-'+(f.col||3)+' mb-3'">
                                        <label v-if="f.label" :for="f.id" class="form-label">{{ f.label }}</label>

                                        <!-- Se for autocompleteField: sem v-model -->
                                        <input v-if="f.autocompleteField"
                                            :type="f.type || 'text'"
                                            class="form-control"
                                            :id="f.id || f.name"
                                            :name="f.name"
                                            :placeholder="f.placeholder || ''" />

                                        <!-- Se não for autocompleteField: usa v-model -->
                                        <input v-else
                                            :type="f.type || 'text'"
                                            class="form-control"
                                            v-model="form[f.name]"
                                            :id="f.id || f.name"
                                            :name="f.name"
                                            :placeholder="f.placeholder || ''" />
                                 </div>
                                </template>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" @click="submitForm">Salvar</button>
                            <button type="button" class="btn bg-danger-subtle text-danger" @click="fecharModal">Cancelar</button>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `
    };

    if (typeof document !== "undefined") {
        document.addEventListener("DOMContentLoaded", () => {
            const nodes = document.querySelectorAll('[data-vue="generic-modal"]');
            nodes.forEach((el) => {
                const modalSize = el.dataset.modalSize || '';
                const submitUrl = el.dataset.submitUrl; // aqui você passa algo como "/clientes/add"
                const fields = el.dataset.fields ? JSON.parse(el.dataset.fields) : [];
                const title = el.dataset.title || "Modal";

                const app = createApp(GenericModal, { title, submitUrl, fields, modalSize });
                const vm = app.mount(el);

                // botão que abre o modal
                const btn = document.querySelector(el.dataset.triggerBtn);
                if (btn) btn.addEventListener("click", vm.abrirModal);
            });
        });
    }

    if (typeof module !== "undefined") module.exports = GenericModal;
})();


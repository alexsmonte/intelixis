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

            const abrirModal = () => { 
                if (bsModal) bsModal.show(); 
            };
            const fecharModal = () => { if (bsModal) bsModal.hide(); };

            const submitForm = async () => {
                try {
                    const formEl = document.getElementById("genericModalForm");
                    if (!formEl) return;
                    
                    const data = new FormData(formEl);

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
                        // Se houver erros de validação, exibe de forma amigável
                        let msg = result.message || "Erro de validação.";
                        if (result.errors) {
                            msg = Object.values(result.errors).map(e => Object.values(e).join(", ")).join("<br>");
                        }
                        Swal.fire({ icon: "error", title: "Erro", html: msg });
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
                    <form id="genericModalForm" @submit.prevent="submitForm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ title }}</h5>
                            <button type="button" class="btn-close" @click="fecharModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <template v-for="f in fields" :key="f.name">
                                   <template v-if="!f.dependsOn || form[f.dependsOn]">
                                    <!-- Campos full width ou dividers (type == 'html') -->
                                    <div v-if="f.type === 'html'" :class="'col-md-'+(f.col||12)" v-html="f.content"></div>
                                    
                                    <!-- Switch -->
                                    <div v-else-if="f.type === 'switch'" :class="'col-md-'+(f.col||12)+' mb-3 mt-2'">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" :id="f.id || f.name" v-model="form[f.name]">
                                            <label class="form-check-label fw-bold text-muted" :for="f.id || f.name">{{ f.label }}</label>
                                        </div>
                                    </div>

                                    <!-- Componente Vue customizado -->
                                    <div v-else-if="f.vueComponent" :class="'col-md-'+(f.col||3)" :data-vue="f.vueComponent" v-bind="f.dataAttributes || {}"></div>

                                    <!-- Campos normais -->
                                    <div v-else :class="'col-md-'+(f.col||3)+' mb-3'">
                                        <label v-if="f.label" :for="f.id || f.name" class="form-label">{{ f.label }}</label>

                                        <!-- Select -->
                                        <select v-if="f.type === 'select'"
                                            class="form-select"
                                            v-model="form[f.name]"
                                            :id="f.id || f.name"
                                            :name="f.name">
                                            <option v-if="f.empty" value="">{{ f.empty }}</option>
                                            <option v-for="(text, val) in f.options" :key="val" :value="val">{{ text }}</option>
                                        </select>
                                        
                                        <!-- Se for autocompleteField: sem v-model, apenas name e id -->
                                        <input v-else-if="f.autocompleteField"
                                            :type="f.type || 'text'"
                                            class="form-control"
                                            :id="f.id || f.name"
                                            :name="f.name"
                                            :placeholder="f.placeholder || ''"
                                            v-mask="f.mask" />

                                        <!-- Se não for autocompleteField nem select: usa v-model -->
                                        <input v-else
                                            :type="f.type || 'text'"
                                            class="form-control"
                                            v-model="form[f.name]"
                                            :id="f.id || f.name"
                                            :name="f.name"
                                            :placeholder="f.placeholder || ''"
                                            v-mask="f.mask" />
                                  </div>
                                 </template>
                                </template>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <button type="button" class="btn bg-danger-subtle text-danger" @click="fecharModal">Cancelar</button>
                        </div>
                    </div>
                    </form>
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
                
                // Registra a diretiva v-mask
                if (window.VueMasks && window.VueMasks.directive) {
                    app.directive('mask', window.VueMasks.directive);
                } else {
                    console.warn("window.VueMasks não encontrado. Certifique-se de importar vue-masks.js ANTES deste script ou que ele esteja rodando.");
                }

                const vm = app.mount(el);
                
                if (window.initConveniosCliente) {
                    window.initConveniosCliente();
                }

                // botão que abre o modal
                const btn = document.querySelector(el.dataset.triggerBtn);
                if (btn) btn.addEventListener("click", vm.abrirModal);
            });
        });
    }

    if (typeof module !== "undefined") module.exports = GenericModal;
})();


import sys

filename = '/opt/cakephp-app/painel/templates/Consultas/editar.php'
with open(filename, 'r', encoding='utf-8') as f:
    content = f.read()

replacements = [
    ("itemDocumento: null,", 
     "itemDocumento: null,\n                assinaturaPacienteDoc: null,"),
    ("computed: {", 
     """computed: {
            urlCurta() {
                if (!this.assinaturaPacienteDoc) return '';
                return "<?= \\Cake\\Routing\\Router::url('/assinar/', true) ?>" + this.assinaturaPacienteDoc.codigo_validacao;
            },"""),
    ("methods: {", 
     """methods: {
            abrirModalAssinarPaciente(doc) {
                this.assinaturaPacienteDoc = doc;
                const modal = new bootstrap.Modal(document.getElementById('bs-modal-assinatura-paciente'));
                modal.show();
            },
            copiarUrlCurta(event) {
                if (!this.urlCurta) return;
                const input = document.createElement('input');
                input.value = this.urlCurta;
                document.body.appendChild(input);
                input.select();
                document.execCommand('copy');
                document.body.removeChild(input);
                
                const btn = event.currentTarget;
                const orig = btn.innerHTML;
                btn.innerHTML = '<span class="nav-icon-hover-bg rounded-circle p-1"><iconify-icon icon="solar:check-bold" class="fs-7 align-middle text-success"></iconify-icon></span>';
                setTimeout(() => {
                    btn.innerHTML = orig;
                }, 2000);
            },"""),
    ("</div>\n\n<script src=\"https://unpkg.com/vue@2.7.16/dist/vue.js\"></script>",
     """    <!-- Modal Assinatura Paciente -->
    <div class="modal fade" id="bs-modal-assinatura-paciente" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white p-4">
                    <h5 class="mb-1 text-white d-flex align-items-center gap-2">
                        <iconify-icon icon="solar:shield-keyhole-bold" class="fs-6"></iconify-icon>
                        Link de Assinatura Eletrônica
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" v-if="assinaturaPacienteDoc">
                    
                    <div v-if="assinaturaPacienteDoc.assinatura_hash" class="alert alert-success d-flex align-items-center gap-2 mb-4">
                        <iconify-icon icon="solar:check-circle-bold" class="fs-5"></iconify-icon>
                        <span><strong>Documento já assinado!</strong> Este documento já possui assinatura eletrônica registrada.</span>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12 mb-3 mt-1">
                            <h6 class="border-bottom pb-2">Informações do Documento:</h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold mb-1">Tipo</label>
                            <div class="fw-medium text-dark">{{ assinaturaPacienteDoc.tipo }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold mb-1">Título</label>
                            <div class="fw-medium text-dark">{{ assinaturaPacienteDoc.titulo || '—' }}</div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold mb-1">Código</label>
                            <div class="fw-medium text-dark"><code class="text-primary">{{ assinaturaPacienteDoc.codigo_validacao }}</code></div>
                        </div>
                        <template v-if="assinaturaPacienteDoc.assinatura_hash">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small text-uppercase fw-bold mb-1">Assinado em</label>
                                <div class="fw-medium text-dark">{{ formatarData(assinaturaPacienteDoc.assinatura_data) }}</div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label text-muted small text-uppercase fw-bold mb-1">Hash SHA-256</label>
                                <div class="fw-medium text-dark"><code class="text-muted" style="word-break:break-all;">{{ assinaturaPacienteDoc.assinatura_hash }}</code></div>
                            </div>
                        </template>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-2 mt-2">
                            <h6 class="border-bottom pb-2 text-primary">Link para o Paciente Assinar:</h6>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <button class="btn btn-link p-0 text-secondary" type="button" 
                                @click="copiarUrlCurta($event)"
                                title="Copiar link">
                            <span class="nav-icon-hover-bg rounded-circle p-1">
                                <iconify-icon icon="flowbite:link-outline" class="fs-7 align-middle"></iconify-icon>
                            </span>
                        </button>

                        <button type="button" disabled 
                                class="btn btn-link p-0 text-success" 
                                title="Enviar via WhatsApp">
                            <span class="nav-icon-hover-bg rounded-circle p-1">
                                <iconify-icon icon="ic:baseline-whatsapp" class="fs-7 align-middle"></iconify-icon>
                            </span>
                        </button>

                        <button type="button" disabled 
                                class="btn btn-link p-0 text-dark" 
                                title="Enviar via Email">
                            <span class="nav-icon-hover-bg rounded-circle p-1">
                                <iconify-icon icon="streamline:send-email-remix" class="fs-7 align-middle"></iconify-icon>
                            </span>
                        </button>

                        <a :href="urlCurta" target="_blank" 
                           class="btn btn-link p-0 text-primary"
                           title="Abrir link para assinatura">
                            <span class="nav-icon-hover-bg rounded-circle p-1">
                                <iconify-icon icon="streamline-plump:web" class="fs-7 align-middle"></iconify-icon>
                            </span>
                        </a>
                    </div>

                    <div class="alert alert-info d-flex align-items-center mb-0">
                        <iconify-icon icon="solar:info-circle-bold" class="fs-4 me-2 flex-shrink-0"></iconify-icon>
                        <div style="font-size: 13px;">
                            <strong>Atenção:</strong> Certifique-se de que o documento foi <strong>impresso/gerado</strong> pelo menos uma vez antes de enviar este link ao paciente. O arquivo PDF precisa existir no servidor para que a assinatura seja processada.
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-danger-subtle text-danger rounded-pill fw-bold w-100" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/vue@2.7.16/dist/vue.js"></script>""")
]

for old, new in replacements:
    content = content.replace(old, new)

with open(filename, 'w', encoding='utf-8') as f:
    f.write(content)
print('Done!')

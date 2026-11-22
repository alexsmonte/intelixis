(function () {
    const { createApp, ref, nextTick } = Vue;

    (function injetarEstilos() {
        if (document.getElementById('convenios-cliente-styles')) return;
        const style = document.createElement('style');
        style.id = 'convenios-cliente-styles';
        style.textContent = `
            [data-vue="convenios-cliente"] .form-label {
                color: var(--bs-body-color) !important;
                font-weight: 400;
            }
            [data-vue="convenios-cliente"] .form-control::placeholder {
                color: var(--bs-body-color) !important;
                opacity: 0.5;
            }
            [data-vue="convenios-cliente"] .select2-selection__placeholder {
                color: var(--bs-body-color) !important;
                opacity: 0.5;
            }
        `;
        document.head.appendChild(style);
    })();

    let uidCounter = 0;

    function formatarCpf(valor) {
        const digits = String(valor || '').replace(/\D/g, '');
        if (digits.length === 11) {
            return digits.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
        }
        return valor;
    }

    function novaLinha(dados) {
        return {
            _uid: ++uidCounter,
            id:                 dados.id                 || null,
            convenio_id:        dados.convenio_id        ? String(dados.convenio_id) : '',
            numero_carteirinha: dados.numero_carteirinha || '',
            nome_titular:       dados.nome_titular       || '',
            cpf_titular:        dados.cpf_titular        ? formatarCpf(dados.cpf_titular) : '',
            is_titular:         dados.is_titular !== undefined ? Boolean(Number(dados.is_titular)) : false,
            grau_dependencia:   dados.grau_dependencia   || '',
            plano:              dados.plano              || '',
            data_inicio:        dados.data_inicio        || '',
            data_validade:      dados.data_validade      || '',
            acomodacao:         dados.acomodacao         || '',
            abrangencia:        dados.abrangencia        || '',
            status:             dados.status             || 'ATIVO',
            observacoes:        dados.observacoes        || '',
        };
    }

    function initSelect2NasLinhas(container, linhas) {
        nextTick(() => {
            $(container).find('.convenio-select2').each(function () {
                const $el = $(this);
                if ($el.hasClass('select2-hidden-accessible')) return;

                $el.select2({
                    placeholder: 'Selecione o convênio',
                    allowClear: true,
                    width: '100%',
                });

                const uid = parseInt($el.data('uid'));
                const linha = linhas.value.find(l => l._uid === uid);
                if (linha && linha.convenio_id) {
                    $el.val(String(linha.convenio_id)).trigger('change.select2');
                }

                $el.on('change', function () {
                    const currentUid = parseInt($el.data('uid'));
                    const currentLinha = linhas.value.find(l => l._uid === currentUid);
                    if (currentLinha) {
                        currentLinha.convenio_id = $(this).val() || '';
                    }
                });
            });
        });
    }

    const svgTrash = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2.75a2.25 2.25 0 0 0-2.122 1.5a.75.75 0 0 1-1.414-.5a3.751 3.751 0 0 1 7.073 0a.75.75 0 0 1-1.415.5A2.25 2.25 0 0 0 12 2.75M2.75 6a.75.75 0 0 1 .75-.75h17a.75.75 0 0 1 0 1.5h-17A.75.75 0 0 1 2.75 6m3.165 2.45a.75.75 0 1 0-1.497.1l.464 6.952c.085 1.282.154 2.318.316 3.132c.169.845.455 1.551 1.047 2.104s1.315.793 2.17.904c.822.108 1.86.108 3.146.108h.879c1.285 0 2.324 0 3.146-.108c.854-.111 1.578-.35 2.17-.904c.591-.553.877-1.26 1.046-2.104c.162-.814.23-1.85.316-3.132l.464-6.952a.75.75 0 0 0-1.497-.1l-.46 6.9c-.09 1.347-.154 2.285-.294 2.99c-.137.685-.327 1.047-.6 1.303c-.274.256-.648.422-1.34.512c-.713.093-1.653.095-3.004.095h-.774c-1.35 0-2.29-.002-3.004-.095c-.692-.09-1.066-.256-1.34-.512c-.273-.256-.463-.618-.6-1.303c-.14-.705-.204-1.643-.294-2.99z"/><path fill="currentColor" d="M9.425 10.254a.75.75 0 0 1 .821.671l.5 5a.75.75 0 0 1-1.492.15l-.5-5a.75.75 0 0 1 .671-.821m5.821.821a.75.75 0 0 0-1.492-.15l-.5 5a.75.75 0 0 0 1.492.15z"/></svg>`;

    window.initConveniosCliente = function() {
        document.querySelectorAll('[data-vue="convenios-cliente"]:not(.vue-initialized)').forEach(function (el) {
            el.classList.add('vue-initialized');
            const opcoesRaw   = el.dataset.conveniosOpcoes   ? JSON.parse(el.dataset.conveniosOpcoes)   : [];
            const iniciaisRaw = el.dataset.conveniosIniciais ? JSON.parse(el.dataset.conveniosIniciais) : [];

            const app = createApp({
                setup() {
                    const linhas = ref(iniciaisRaw.map(novaLinha));

                    function adicionarLinha() {
                        linhas.value.push(novaLinha({}));
                        initSelect2NasLinhas(el, linhas);
                    }

                    function removerLinha(idx) {
                        const uid = linhas.value[idx]._uid;
                        const $select = $(el).find(`.convenio-select2[data-uid="${uid}"]`);
                        if ($select.hasClass('select2-hidden-accessible')) {
                            $select.select2('destroy');
                        }
                        linhas.value.splice(idx, 1);
                    }

                    function onToggleTitular(linha) {
                        if (linha.is_titular) {
                            // Lê os valores diretamente do formulário do cliente no DOM
                            const formEl = el.closest('form');
                            if (formEl) {
                                const nomeEl = formEl.querySelector('input[name="nome"]');
                                const docEl  = formEl.querySelector('input[name="documento"]');
                                linha.nome_titular = nomeEl ? nomeEl.value.trim() : '';
                                linha.cpf_titular  = docEl  ? formatarCpf(docEl.value) : '';
                            }
                        } else {
                            linha.nome_titular = '';
                            linha.cpf_titular  = '';
                        }
                    }

                    nextTick(() => initSelect2NasLinhas(el, linhas));

                    return { linhas, opcoesRaw, adicionarLinha, removerLinha, onToggleTitular, svgTrash };
                },

                template: `
                    <div class="border rounded p-3 mb-3 w-100">

                        <div v-if="linhas.length === 0" class="text-muted fst-italic small mb-3">
                            Nenhum convênio cadastrado para este cliente.
                        </div>

                        <div
                            v-for="(linha, idx) in linhas"
                            :key="linha._uid"
                            class="mb-3 border rounded p-3"
                        >
                            <input v-if="linha.id" type="hidden" :name="'clientes_convenios[' + idx + '][id]'" :value="linha.id">

                            <!-- Cabeçalho -->
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                <span class="text-muted small">Convênio {{ idx + 1 }}</span>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger rounded-pill d-flex align-items-center gap-1"
                                    @click="removerLinha(idx)"
                                >
                                    <span v-html="svgTrash"></span> Remover
                                </button>
                            </div>

                            <div class="row g-3">

                                <!-- Linha 1: Convênio | Carteirinha | Plano | Status -->
                                <div class="col-md-4">
                                    <label class="form-label">Convênio <span class="text-danger">*</span></label>
                                    <select class="convenio-select2 form-select" :data-uid="linha._uid">
                                        <option value="">Selecione...</option>
                                        <option v-for="op in opcoesRaw" :key="op.id" :value="String(op.id)">{{ op.text }}</option>
                                    </select>
                                    <input type="hidden" :name="'clientes_convenios[' + idx + '][convenio_id]'" :value="linha.convenio_id">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Nº Carteirinha <span class="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        :name="'clientes_convenios[' + idx + '][numero_carteirinha]'"
                                        v-model="linha.numero_carteirinha"
                                        maxlength="50"
                                        placeholder="0012345678901"
                                    >
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Plano</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        :name="'clientes_convenios[' + idx + '][plano]'"
                                        v-model="linha.plano"
                                        maxlength="100"
                                        placeholder="Enfermaria Plus"
                                    >
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" :name="'clientes_convenios[' + idx + '][status]'" v-model="linha.status">
                                        <option value="ATIVO">Ativo</option>
                                        <option value="SUSPENSO">Suspenso</option>
                                        <option value="CANCELADO">Cancelado</option>
                                    </select>
                                </div>

                                <!-- Linha 2: Vigência Início | Validade | Acomodação | Abrangência -->
                                <div class="col-md-3">
                                    <label class="form-label">Vigência Início</label>
                                    <input type="date" class="form-control" :name="'clientes_convenios[' + idx + '][data_inicio]'" v-model="linha.data_inicio">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Validade</label>
                                    <input type="date" class="form-control" :name="'clientes_convenios[' + idx + '][data_validade]'" v-model="linha.data_validade">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Acomodação</label>
                                    <select class="form-select" :name="'clientes_convenios[' + idx + '][acomodacao]'" v-model="linha.acomodacao">
                                        <option value="">Não informado</option>
                                        <option value="ENFERMARIA">Enfermaria</option>
                                        <option value="QUARTO_INDIVIDUAL">Quarto Individual</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Abrangência</label>
                                    <select class="form-select" :name="'clientes_convenios[' + idx + '][abrangencia]'" v-model="linha.abrangencia">
                                        <option value="">Não informado</option>
                                        <option value="MUNICIPAL">Municipal</option>
                                        <option value="ESTADUAL">Estadual</option>
                                        <option value="NACIONAL">Nacional</option>
                                        <option value="INTERNACIONAL">Internacional</option>
                                    </select>
                                </div>

                                <!-- Separador -->
                                <div class="col-md-12"><hr class="my-1"></div>

                                <!-- Linha 3: Checkbox titular -->
                                <div class="col-md-12">
                                    <input type="hidden" :name="'clientes_convenios[' + idx + '][is_titular]'" :value="linha.is_titular ? 1 : 0">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            :id="'is_titular_' + linha._uid"
                                            v-model="linha.is_titular"
                                            @change="onToggleTitular(linha)"
                                        >
                                        <label class="form-check-label" :for="'is_titular_' + linha._uid">
                                            Cliente é o titular do plano
                                        </label>
                                    </div>
                                </div>

                                <!-- Linha 4: Nome Titular | CPF | (Grau se não for titular) -->
                                <div :class="linha.is_titular ? 'col-md-8' : 'col-md-6'">
                                    <label class="form-label">Nome do Titular <span class="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        :name="'clientes_convenios[' + idx + '][nome_titular]'"
                                        v-model="linha.nome_titular"
                                        v-mask="'nome'"
                                        maxlength="255"
                                        placeholder="Nome completo do titular"
                                    >
                                </div>

                                <div :class="linha.is_titular ? 'col-md-4' : 'col-md-3'">
                                    <label class="form-label">CPF do Titular <span class="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        :name="'clientes_convenios[' + idx + '][cpf_titular]'"
                                        v-model="linha.cpf_titular"
                                        v-mask="'cpf'"
                                        maxlength="14"
                                        placeholder="000.000.000-00"
                                    >
                                </div>

                                <div class="col-md-3" v-if="!linha.is_titular">
                                    <label class="form-label">Grau de Dependência</label>
                                    <select class="form-select" :name="'clientes_convenios[' + idx + '][grau_dependencia]'" v-model="linha.grau_dependencia">
                                        <option value="">Selecione...</option>
                                        <option value="conjuge">Cônjuge</option>
                                        <option value="filho">Filho(a)</option>
                                        <option value="pai">Pai</option>
                                        <option value="mae">Mãe</option>
                                        <option value="outro">Outro</option>
                                    </select>
                                </div>
                                <input v-else type="hidden" :name="'clientes_convenios[' + idx + '][grau_dependencia]'" value="">

                                <!-- Linha 5: Observações -->
                                <div class="col-md-12">
                                    <label class="form-label">Observações</label>
                                    <textarea
                                        class="form-control"
                                        :name="'clientes_convenios[' + idx + '][observacoes]'"
                                        v-model="linha.observacoes"
                                        rows="2"
                                        placeholder="Informações adicionais sobre este plano..."
                                    ></textarea>
                                </div>

                            </div>
                        </div>

                        <button type="button" class="btn bg-primary-subtle text-primary rounded-pill" @click="adicionarLinha">
                            <i class="ti ti-plus fs-4 me-2"></i>
                            Adicionar Convênio
                        </button>

                    </div>
                `,
            });

            if (window.VueMasks && window.VueMasks.directive) {
                app.directive('mask', window.VueMasks.directive);
            }

            app.mount(el);
        });
    };

    // Auto-init for elements already in DOM
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", window.initConveniosCliente);
    } else {
        window.initConveniosCliente();
    }
})();

/**
 * duracao-picker.js
 *
 * Componente Vue 3 para o campo de duração de serviços.
 *
 * Comportamento:
 *  - O usuário pode digitar em minutos (ex: "30", "90") ou em H:MM (ex: "1:30")
 *  - Um dropdown inline alterna o modo de exibição entre "min" e "h:mm"
 *  - Um <input type="hidden" name="duracao"> é mantido sincronizado com o total em minutos
 *  - Ao carregar na edição, lê o atributo data-duracao-inicial (inteiro em minutos)
 *    e exibe no formato correspondente ao modo selecionado
 *
 * Uso nos templates (CakePHP):
 * O form enviará:  duracao = 90  (inteiro, sempre em minutos)
 */
(function () {
    // Injetar estilos CSS para placeholders no head da página de forma nativa e robusta
    const styleId = 'duracao-picker-styles';
    if (!document.getElementById(styleId)) {
        const style = document.createElement('style');
        style.id = styleId;
        style.textContent = `
            /* Estilo padrão para o Modo Claro (e fallback quando data-bs-theme está ausente) */
            .duracao-picker-group .form-control::placeholder,
            .duracao-picker-group .form-control:focus::placeholder {
                color: #adb5bd !important;
                opacity: 1 !important;
                -webkit-text-fill-color: #adb5bd !important;
            }
            .duracao-picker-group .form-select.is-empty {
                color: #adb5bd !important;
                opacity: 1 !important;
            }

            /* Override para o Modo Escuro */
            [data-bs-theme=dark] .duracao-picker-group .form-control::placeholder,
            [data-bs-theme=dark] .duracao-picker-group .form-control:focus::placeholder {
                color: #7c8fac !important;
                opacity: 1 !important;
                -webkit-text-fill-color: #7c8fac !important;
            }
            [data-bs-theme=dark] .duracao-picker-group .form-select.is-empty {
                color: #7c8fac !important;
                opacity: 1 !important;
            }

            /* Garantir que as opções dentro do dropdown tenham cor normal */
            .duracao-picker-group .form-select option {
                color: var(--bs-body-color, #212529) !important;
                background-color: var(--bs-body-bg, #fff) !important;
                -webkit-text-fill-color: var(--bs-body-color, #212529) !important;
            }
        `;
        document.head.appendChild(style);
    }

    const { createApp } = Vue;

    document.querySelectorAll('[data-vue="duracao-picker"]').forEach(el => {
        const duracaoInicial = parseInt(el.getAttribute('data-duracao-inicial') || '0', 10) || 0;

        createApp({
            template: `
                <div class="duracao-picker-group">
                    <label>Duração</label>
                    <div class="input-group">
                        <input
                            type="text"
                            class="form-control"
                            :placeholder="modo === 'min' ? 'Ex: 30' : 'Ex: 1:30'"
                            v-model="displayValue"
                            @input="aoDigitar"
                            @blur="aoSair"
                            @keydown="filtrarTeclas"
                        />
                        <select 
                            class="form-select border-start-0" 
                            :class="{'is-empty': !displayValue}"
                            style="max-width: 130px; cursor: pointer;" 
                            v-model="modo" 
                            @change="aoMudarModo" 
                            title="Unidade de duração"
                        >
                            <option value="min">Minutos</option>
                            <option value="hmm">Horas</option>
                        </select>
                    </div>
                    <div class="form-text text-muted" v-if="totalMinutos > 0">
                        {{ resumo }}
                    </div>
                    <!-- Hidden field enviado ao form -->
                    <input type="hidden" name="duracao" :value="totalMinutos" />
                </div>
            `,

            data() {
                return {
                    modo: 'min',           // 'min' ou 'hmm'
                    totalMinutos: duracaoInicial,
                    displayValue: ''
                };
            },

            computed: {
                resumo() {
                    if (this.totalMinutos <= 0) return '';
                    const h = Math.floor(this.totalMinutos / 60);
                    const m = this.totalMinutos % 60;
                    if (h === 0) return `${m} minuto${m !== 1 ? 's' : ''}`;
                    if (m === 0) return `${h} hora${h !== 1 ? 's' : ''}`;
                    return `${h}h ${m}min`;
                }
            },

            mounted() {
                // Exibe o valor inicial vindo do banco
                if (this.totalMinutos > 0) {
                    this.displayValue = this.minutosParaDisplay(this.totalMinutos);
                }
            },

            methods: {
                /**
                 * Converte total de minutos para string de display conforme o modo atual.
                 */
                minutosParaDisplay(min) {
                    if (this.modo === 'min') {
                        return String(min);
                    } else {
                        const h = Math.floor(min / 60);
                        const m = min % 60;
                        return `${h}:${String(m).padStart(2, '0')}`;
                    }
                },

                /**
                 * Converte o display atual para total de minutos.
                 * Aceita "30", "90", "1:30", "1:00", etc.
                 */
                displayParaMinutos(val) {
                    val = (val || '').trim();
                    if (val === '') return 0;

                    if (val.includes(':')) {
                        // Formato H:MM
                        const parts = val.split(':');
                        const h = parseInt(parts[0], 10) || 0;
                        const m = parseInt(parts[1], 10) || 0;
                        return (h * 60) + m;
                    } else {
                        // Formato minutos simples
                        return parseInt(val.replace(/\D/g, ''), 10) || 0;
                    }
                },

                aoDigitar() {
                    // No modo h:mm, aplica máscara automática "H:MM" ao digitar
                    if (this.modo === 'hmm') {
                        let raw = this.displayValue.replace(/[^\d:]/g, '');

                        // Insere automaticamente ":" após o primeiro grupo de dígitos
                        if (!raw.includes(':')) {
                            if (raw.length > 2) {
                                raw = raw.slice(0, -2) + ':' + raw.slice(-2);
                            }
                        }

                        // Limita os minutos a 59
                        if (raw.includes(':')) {
                            let [h, m] = raw.split(':');
                            if (m !== undefined && m.length === 2) {
                                let mInt = parseInt(m, 10);
                                if (mInt > 59) m = '59';
                                raw = h + ':' + m;
                            }
                        }

                        this.displayValue = raw;
                    }

                    this.totalMinutos = this.displayParaMinutos(this.displayValue);
                },

                aoSair() {
                    // Normaliza o display ao perder o foco
                    if (this.totalMinutos > 0) {
                        this.displayValue = this.minutosParaDisplay(this.totalMinutos);
                    } else {
                        this.displayValue = '';
                    }
                },

                aoMudarModo() {
                    // Converte o display para o novo modo mantendo o mesmo total de minutos
                    if (this.totalMinutos > 0) {
                        this.displayValue = this.minutosParaDisplay(this.totalMinutos);
                    } else {
                        this.displayValue = '';
                    }
                },

                filtrarTeclas(e) {
                    // Permite: dígitos, Backspace, Delete, Tab, setas, ":" (no modo h:mm)
                    const permitidas = ['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'Home', 'End'];
                    if (permitidas.includes(e.key)) return;
                    if (this.modo === 'hmm' && e.key === ':') return;
                    if (!/^\d$/.test(e.key)) e.preventDefault();
                }
            }
        }).mount(el);
    });
})();

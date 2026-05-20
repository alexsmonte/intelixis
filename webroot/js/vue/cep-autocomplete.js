(function () {
    const { createApp, ref } = Vue;

    const CepAutocomplete = {
        props: ['cepInicial'],
        setup(props) {
            const cep = ref(props.cepInicial || "");
            const carregando = ref(false);
            const erro = ref(null);

            const aplicarMascara = (autoSearch = true) => {
                let valorLimpo = cep.value.replace(/\D/g, "");
                if (valorLimpo.length > 5) {
                    valorLimpo = valorLimpo.slice(0, 5) + "-" + valorLimpo.slice(5, 8);
                }
                cep.value = valorLimpo;

                // Busca automatica ao atingir 8 digitos
                if (autoSearch && valorLimpo.replace(/\D/g, "").length === 8) {
                    buscarCep();
                }
            };

            // Formata o CEP inicial sem disparar nova busca ViaCEP
            aplicarMascara(false);

            const setInputValue = (selector, value) => {
                const el = document.querySelector(selector);
                if (el) {
                    el.value = value || '';
                    el.dispatchEvent(new Event('input', { bubbles: true }));
                    el.dispatchEvent(new Event('change', { bubbles: true }));
                }
            };

            const buscarCep = async () => {
                if (carregando.value) return;
                erro.value = null;
                const cepLimpo = cep.value.replace(/\D/g, "");

                if (cepLimpo.length === 0) {
                    return; // Retorna sem erro se o campo estiver vazio
                }

                if (cepLimpo.length !== 8) {
                    erro.value = "CEP inválido.";
                    return;
                }

                carregando.value = true;

                try {
                    const response = await fetch(`https://viacep.com.br/ws/${cepLimpo}/json/`);
                    const data = await response.json();

                    if (data.erro) {
                        erro.value = "CEP não encontrado.";
                        setInputValue('#logradouro', '');
                        setInputValue('#bairro', '');
                        setInputValue('#cidade', '');
                        setInputValue('#uf', '');
                        setInputValue('#numero', '');
                    } else {
                        cep.value = data.cep; // Atualiza o Ref diretamente para evitar loop de evento
                        setInputValue('#logradouro', data.logradouro);
                        setInputValue('#bairro', data.bairro);
                        setInputValue('#cidade', data.localidade);
                        setInputValue('#uf', data.uf);
                        
                        const inputNumero = document.querySelector('#numero');
                        if (inputNumero) inputNumero.focus();
                    }
                } catch (e) {
                    setInputValue('#logradouro', '');
                    setInputValue('#bairro', '');
                    setInputValue('#cidade', '');
                    setInputValue('#uf', '');
                    setInputValue('#numero', '');
                    cep.value = ''; // Atualiza o Ref diretamente
                    erro.value = "Erro ao consultar o CEP.";
                } finally {
                    carregando.value = false;
                }
            };

            return { cep, carregando, erro, aplicarMascara, buscarCep };
        },

        mounted() {
            if (typeof Iconify !== 'undefined') {
                Iconify.scan(this.$el);
            }
        },

        template: `
        <div class="mb-3">
            <label for="cep">CEP</label>
            <div class="position-relative">
                <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">
                    <span class="iconify fs-5" data-icon="solar:map-point-linear"></span>
                </span>
                
                <input
                    id="cep"
                    name="cep" 
                    type="text"
                    class="form-control rounded-pill ps-5 pe-5"
                    v-model="cep"
                    @input="aplicarMascara"
                    @blur="buscarCep"
                    @keyup.enter="buscarCep"
                    placeholder="00000-000"
                    maxlength="9"
                />
                
                <span class="position-absolute top-50 end-0 translate-middle-y pe-3 text-muted">
                    <span v-show="carregando" class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>
                    <button v-show="!carregando" type="button" @click="buscarCep" class="btn btn-link p-0 text-muted hover-primary" title="Consultar CEP">
                        <span class="iconify fs-5" data-icon="solar:magnifer-linear"></span>
                    </button>
                </span>
            </div>
            <div v-if="erro" class="text-danger mt-1 small ms-3">{{ erro }}</div>
        </div>
        `
    };

    if (typeof document !== "undefined") {
        document.addEventListener("DOMContentLoaded", () => {
            const nodes = document.querySelectorAll('[data-vue="cep-autocomplete"]');
            nodes.forEach((el) => {
                const cepInicial = el.dataset.cep || "";
                createApp(CepAutocomplete, { cepInicial }).mount(el);
            });
        });
    }

    if (typeof module !== "undefined") module.exports = CepAutocomplete;
})();

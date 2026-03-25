(function () {
    const { createApp, ref } = Vue;

    const CepAutocomplete = {
        props: ['cepInicial'],
        setup(props) {
            const cep = ref(props.cepInicial || "");
            const carregando = ref(false);
            const erro = ref(null);

            const aplicarMascara = () => {
                let valorLimpo = cep.value.replace(/\D/g, "");
                if (valorLimpo.length > 5) {
                    valorLimpo = valorLimpo.slice(0, 5) + "-" + valorLimpo.slice(5, 8);
                }
                cep.value = valorLimpo;
            };

            const buscarCep = async () => {
                erro.value = null;
                const cepLimpo = cep.value.replace(/\D/g, "");

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
                        document.querySelector('#logradouro').value = '';
                        document.querySelector('#bairro').value = '';
                        document.querySelector('#cidade').value = '';
                        document.querySelector('#uf').value = '';
                        document.querySelector('#numero').value = '';
                    } else {
                        document.querySelector('#cep').value = data.cep;
                        document.querySelector('#logradouro').value = data.logradouro;
                        document.querySelector('#bairro').value = data.bairro;
                        document.querySelector('#cidade').value = data.localidade;
                        document.querySelector('#uf').value = data.uf;
                        document.querySelector('#cep').value = data.cep;
                        document.querySelector('#numero').focus();
                    }
                } catch (e) {
                    document.querySelector('#logradouro').value = '';
                    document.querySelector('#bairro').value = '';
                    document.querySelector('#cidade').value = '';
                    document.querySelector('#uf').value = '';
                    document.querySelector('#numero').value = '';
                    document.querySelector('#cep').value = '';
                    erro.value = "Erro ao consultar o CEP.";
                } finally {
                    carregando.value = false;
                }
            };

            return { cep, carregando, erro, aplicarMascara, buscarCep };
        },

        template: `
        <div class="mb-3">
            
           <label for="cep-input" class="form-label spike-label">CEP</label>

            <div class="input-group">
               
                <input
                    id="cep"
                    name="cep" 
                    type="text"
                    class="form-control"
                    v-model="cep"
                    @input="aplicarMascara"
                    @blur="buscarCep"
                    placeholder="00000-000"
                    maxlength="9"
                />
                <button type="button" @click="buscarCep" class="btn border border-primary-subtle btn-primary search-btn" 
                data-bs-toggle="tooltip" data-bs-placement="top" title="Consultar Cep" :disabled="carregando">
                    <span v-if="carregando" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span v-else><i class="ti ti-search"></i></span>
                </button>
            </div>
            <div v-if="erro" class="text-danger mt-1">{{ erro }}</div>
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

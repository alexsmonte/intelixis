(function () {
    // Registry de configurações do IMask
    const maskRegistry = {
        'nome': {
            mask: /^[a-zA-ZÀ-ÿ\s]*$/
        },
        'cpf': {
            mask: '000.000.000-00'
        },
        'telefone': {
            mask: '(00) 0000-0000'
        },
        'celular': {
            mask: '(00) 00000-0000'
        },
        'cep': {
            mask: '00000-000'
        },
        'uf': {
            mask: /^[A-Za-z]{0,2}$/,
            prepare: str => str.toUpperCase(),
            commit: function(value, masked) {
                masked._value = value.toUpperCase();
            }
        },
        'valor': {
            mask: Number,
            thousandsSeparator: '.',
            radix: ',',
            scale: 2,
            normalizeZeros: true,
            padFractionalZeros: true,
            lazy: false,
            prefix: 'R$ '
        },
        'numero': {
            mask: /^[0-9]*$/
        }
    };

    /**
     * Diretiva Vue 3 (v-mask) para aplicar IMask
     */
    const vueMaskDirective = {
        mounted(el, binding) {
            console.log('v-mask mounted no elemento:', el, 'com binding:', binding.value);
            if (typeof IMask === "undefined") {
                console.error("IMask não foi carregado.");
                return;
            }

            const maskType = binding.value;
            // Só aplica a máscara se um tipo for definido e existir no registro
            if (maskType && maskRegistry[maskType]) {
                console.log('Aplicando mask tipo:', maskType);
                // Cria a instância do IMask
                const maskInstance = IMask(el, maskRegistry[maskType]);
                
                // Salva a instância no elemento para limpeza posterior
                el._imaskInstance = maskInstance;

                // Força o Vue a atualizar o modelo quando o IMask aceita uma mudança
                maskInstance.on('accept', () => {
                    // Prevenir loop de eventos
                    if (el.value !== el._lastValue) {
                        el._lastValue = el.value;
                        el.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                });
            } else {
                console.warn('Tipo de mask inválido ou nao encontrado:', maskType);
            }
        },
        unmounted(el) {
            // Limpa a instância do IMask caso o elemento seja destruído
            if (el._imaskInstance) {
                el._imaskInstance.destroy();
                delete el._imaskInstance;
            }
        }
    };

    // Expor globalmente para ser usado
    window.VueMasks = {
        directive: vueMaskDirective,
        maskRegistry
    };

    if (typeof module !== "undefined") module.exports = window.VueMasks;
})();

// select2-vue.js funciona muito bem na versao 2.7+ do Vue
/***
 * Metodos de uso
 *
 * <select2 v-model="item.servico_id" :options="servicos" placeholder="Selecione"></select2>
 *
 * versao com multiplas selecoes
 *
 * <select2 v-model="item.servicos_ids" :options="servicos" multiple></select2>
 *
 */

Vue.component('select2', {
        props: {
            options: { type: Array, default: () => [] },
        value: null,
        placeholder: { type: String, default: '' },
        multiple: { type: Boolean, default: false }
    },
    template: '<select><slot></slot></select>',
    data() {
    return {
        _suppressChange: false
    };
},

mounted() {
    const vm = this;
    const $el = $(this.$el);

    if (this.multiple) {
        $el.attr('multiple', 'multiple');
    }

    $el.select2({
        data: this.options,
        placeholder: this.placeholder,
        allowClear: false,
        width: '100%'
    });

    this._applyValue(this.value);

    $el.on('change', function () {
        if (vm._suppressChange) return;

        if (vm.multiple) {
            const selected = $(this).val() || [];
            vm.$emit('input', selected);
        } else {
            vm.$emit('input', this.value);
        }
    });
},

watch: {
    value(newVal) {
        this._applyValue(newVal);
    },

    options(newOptions) {
        const $el = $(this.$el);
        const currentVal = $el.val();

        $el.empty().select2({
            data: newOptions,
            placeholder: this.placeholder,
            allowClear: !this.multiple,
            width: '100%'
        });

        this._applyValue(currentVal);
    }
},

beforeDestroy() {
    $(this.$el).off().select2("destroy");
},

methods: {
    _applyValue(val) {
        const $el = $(this.$el);

        this._suppressChange = true;

        if (this.multiple) {
            $el.val(Array.isArray(val) ? val : []).trigger('change.select2');
        } else {
            if (val === null || val === '' || typeof val === 'undefined') {
                $el.val(null).trigger('change.select2');
            } else {
                $el.val(val).trigger('change.select2');
            }
        }

        setTimeout(() => { this._suppressChange = false }, 0);
    }
}
});


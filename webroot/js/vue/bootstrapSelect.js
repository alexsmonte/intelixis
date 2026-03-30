const BootstrapSelect = {
    name: 'BootstrapSelect',

    props: {
        modelValue: [String, Number, Array, null],
        options: { type: Array, default: () => [] },
        placeholder: { type: String, default: '' },
        multiple: { type: Boolean, default: false },
        disabled: { type: Boolean, default: false },
        liveSearch: { type: Boolean, default: true },
        remoteUrl: { type: String, default: null }
    },

    emits: ['update:modelValue', 'change'],

    template: `
      <select
        ref="select"
        class="selectpicker"
        :multiple="multiple"
        :disabled="disabled"
        :data-live-search="liveSearch ? 'true' : null"
        :data-none-selected-text="placeholder"
        :data-remote-url="remoteUrl"
      >
        <option v-if="!multiple" value=""></option>

        <option v-for="opt in options" :key="opt.value" :value="opt.value">
          {{ opt.label }}
        </option>
      </select>
    `,

    mounted() {
        this.init();
    },

    beforeUnmount() {
        this.destroy();
    },

    watch: {
        options() { this.refresh(); },
        modelValue() { this.sync(); }
    },

    methods: {
        init() {
            this.bs = new window.BootstrapSelect(this.$refs.select);
            this.sync();
            this.$refs.select.addEventListener('change', this.onChange);
        },

        onChange() {
            let value;
            if (this.multiple) {
                value = Array.from(this.$refs.select.selectedOptions).map(o => o.value);
            } else {
                value = this.$refs.select.value || null;
            }

            this.$emit('update:modelValue', value);
            this.$emit('change', value);
        },

        sync() {
            if (!this.bs) return;

            this.bs.clear();

            if (this.multiple && Array.isArray(this.modelValue)) {
                this.modelValue.forEach(v => this.bs.selectValue(v));
            }
            else if (!this.multiple && this.modelValue != null) {
                this.bs.selectValue(this.modelValue);
            }

            this.bs.refresh();
        },

        refresh() {
            this.bs?.refresh();
        },

        destroy() {
            this.$refs.select.removeEventListener('change', this.onChange);
            this.bs?.destroy();
            this.bs = null;
        }
    }
};

export default BootstrapSelect;


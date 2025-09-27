(function () {
    const { createApp, ref } = Vue;

    document.addEventListener("DOMContentLoaded", () => {
        const nodes = document.querySelectorAll('[data-vue="form-cadastrar"]');
        nodes.forEach(el => {
            const clientesInicial = el.dataset.clientes ? JSON.parse(el.dataset.clientes) : [];
            const fields = el.dataset.fields ? JSON.parse(el.dataset.fields) : [];

            createApp({
                components: { GenericModal },
                setup() {
                    const selectedCliente = ref(null);
                    const clientes = ref(clientesInicial);

                    const modalRef = ref(null);

                    const onNewCliente = (novo) => {
                        clientes.value.push({ value: novo.id, label: novo.nome });
                        selectedCliente.value = novo.id;
                    };

                    const openModal = () => modalRef.value.open();

                    return { selectedCliente, clientes, fields, modalRef, openModal, onNewCliente };
                },
                template: `
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <select v-model="selectedCliente" class="form-select">
                                <option v-for="c in clientes" :key="c.value" :value="c.value">{{ c.label }}</option>
                            </select>
                            <button type="button" class="btn btn-outline-primary btn-icon" @click="openModal">
                                <i class="ti ti-plus"></i>
                            </button>
                        </div>
                        <generic-modal 
                            ref="modalRef"
                            title="Cadastrar"
                            :fields="fields"
                            submit-url="/clientes/add"
                            @submitted="onNewCliente"
                        ></generic-modal>
                    </div>
                `
            }).mount(el);
        });
    });
})();

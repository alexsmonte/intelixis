<template>
  <div class="modal fade" tabindex="-1" ref="modalEl">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirmação de Exclusão</h5>
          <button type="button" class="btn-close" @click="close"></button>
        </div>
        <div class="modal-body">
          <p>Tem certeza que deseja excluir este registro?</p>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" @click="close">Cancelar</button>
          <button class="btn btn-danger" @click="confirmDelete">Excluir</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return { url: null, clienteId: null, bsModal: null }
  },
  methods: {
    open(url, clienteId) {
      this.url = url;
      this.clienteId = clienteId;
      if(!this.bsModal) this.bsModal = new bootstrap.Modal(this.$refs.modalEl);
      this.bsModal.show();
    },
    close() {
      if(this.bsModal) this.bsModal.hide();
    },
    confirmDelete() {
      fetch(this.url, { method: 'POST', credentials: 'same-origin' })
        .then(res => {
            if(res.ok){
                this.$emit('deleted', this.clienteId);
                this.close();
            } else alert('Erro ao excluir');
        });
    }
  }
}
</script>

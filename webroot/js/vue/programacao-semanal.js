(function () {
  const { createApp, ref, computed, onMounted } = Vue;

  const ProgramacaoSemanal = {
    name: "ProgramacaoSemanal",
    props: {
      estabelecimentoId: {
        type: [String, Number],
        required: true,
      },
      programacaoInicial: {
        type: Array,
        default: () => [],
      },
    },
    setup(props) {
      const habilitar = ref(false);
      const dias = ref([
        { id: 1, nome: "Domingo", ativo: false, inicio: "", fim: "" },
        { id: 2, nome: "Segunda-feira", ativo: false, inicio: "", fim: "" },
        { id: 3, nome: "Terça-feira", ativo: false, inicio: "", fim: "" },
        { id: 4, nome: "Quarta-feira", ativo: false, inicio: "", fim: "" },
        { id: 5, nome: "Quinta-feira", ativo: false, inicio: "", fim: "" },
        { id: 6, nome: "Sexta-feira", ativo: false, inicio: "", fim: "" },
        { id: 7, nome: "Sábado", ativo: false, inicio: "", fim: "" },
      ]);

      const hojeStr = () => new Date().toISOString().split("T")[0];

      // Inicializa a programação caso venha do backend
      onMounted(() => {
       
        if (props.programacaoInicial.length > 0) {
          habilitar.value = true; // 🔹 já vem habilitado
          props.programacaoInicial.forEach((p) => {
            const dia = dias.value.find((d) => d.id === p.dia_semana);
            if (dia) {
              //dia.ativo = true;
              dia.ativo = p.status !== "indisponivel";
              dia.inicio = p.data_hora_inicio.slice(11, 16); // HH:MM
              dia.fim = p.data_hora_fim.slice(11, 16);
            }
          });
        }
      });

      const periodos = computed(() =>
        dias.value
          .filter((d) => d.ativo && d.inicio && d.fim)
          .map((d) => ({
            dia_semana: d.id,
            data_hora_inicio: `${hojeStr()}T${d.inicio}:00.000`,
            data_hora_fim: `${hojeStr()}T${d.fim}:00.000`,
            cadeia_id: "semanal",
            estabelecimento_id: props.estabelecimentoId,
          }))
      );

      return { habilitar, dias, periodos };
    },

    template: `
      <div class="border rounded p-3">
        <!-- Toggle principal -->
        <div class="form-group mb-4">
          <label class="form-label fw-bold d-block">Definir Programação Semanal</label>
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" v-model="habilitar">
            <label class="form-check-label">Habilitar Programação</label>
          </div>
        </div>

        <!-- Dias da semana -->
        <div v-if="habilitar" class="mt-3">
          <div class="row align-items-center mb-3 border-bottom pb-2" v-for="dia in dias" :key="dia.id">
            <div class="col-md-3">
              <div class="form-check form-switch">
                <input class="form-check-input toggle-dia" type="checkbox" v-model="dia.ativo">
                <label class="form-check-label">{{ dia.nome }}</label>
              </div>
            </div>
            <div class="col-md-4" v-if="dia.ativo">
              <label class="form-label small">Início</label>
              <input type="time" class="form-control hora-inicio" v-model="dia.inicio">
            </div>
            <div class="col-md-4" v-if="dia.ativo">
              <label class="form-label small">Fim</label>
              <input type="time" class="form-control hora-fim" v-model="dia.fim">
            </div>
          </div>
        </div>

        <!-- Hidden inputs -->
        <input type="hidden" name="habilitar_programacao" :value="habilitar ? 1 : 0">
        <template v-for="p in periodos" :key="p.dia_semana">
          <input type="hidden" :name="'periodos['+p.dia_semana+'][data_hora_inicio]'" :value="p.data_hora_inicio">
          <input type="hidden" :name="'periodos['+p.dia_semana+'][data_hora_fim]'" :value="p.data_hora_fim">
          <input type="hidden" :name="'periodos['+p.dia_semana+'][dia_semana]'" :value="p.dia_semana">
          <input type="hidden" :name="'periodos['+p.dia_semana+'][cadeia_id]'" :value="p.cadeia_id">
          <input type="hidden" :name="'periodos['+p.dia_semana+'][estabelecimento_id]'" :value="p.estabelecimento_id">
        </template>
      </div>
    `,
  };

  // Para uso standalone
  if (typeof document !== "undefined") {
    document.addEventListener("DOMContentLoaded", () => {
      const nodes = document.querySelectorAll('[data-vue="programacao-semanal"]');
      nodes.forEach((el) => {
        const estabelecimentoId = el.dataset.estabelecimentoId || "";
        const programacaoInicial = el.dataset.programacaoInicial
          ? JSON.parse(el.dataset.programacaoInicial)
          : [];
        createApp(ProgramacaoSemanal, {
          estabelecimentoId,
          programacaoInicial, // 🔹 agora realmente passa a programação inicial
        }).mount(el);
      });
    });
  }

  if (typeof module !== "undefined") module.exports = ProgramacaoSemanal;
})();

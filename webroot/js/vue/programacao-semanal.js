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
	habilitarInicial: {
		type: Boolean,
		default: false
	  }
    },
    setup(props) {
	  const habilitar = ref(props.habilitarInicial);
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
        // 1. Verifique se existe alguma programação semanal
        const temProgramacaoSemanal = props.programacaoInicial.some(p => p.cadeia_id === 'semanal');
        
        // 2. Habilite o toggle apenas se a condição for verdadeira
        if (temProgramacaoSemanal) {
            habilitar.value = true;
            
            // Agora, itere sobre os dados para preencher os dias
            props.programacaoInicial.forEach((p) => {
                if (p.cadeia_id === 'semanal') {
                    const dia = dias.value.find((d) => d.id === p.dia_semana);
                    if (dia) {
                        dia.ativo = p.status !== "indisponivel";
                        dia.inicio = p.data_hora_inicio.slice(11, 16); // HH:MM
                        dia.fim = p.data_hora_fim.slice(11, 16);
                    }
                }
            });
        }
    });

      const periodos = computed(() =>
        dias.value.filter((d) => habilitar.value && d.ativo && d.inicio && d.fim).map((d) => {
          if (habilitar.value && d.ativo && d.inicio && d.fim) {
            return {
              dia_semana: d.id,
              data_hora_inicio: `${hojeStr()}T${d.inicio}:00.000`,
              data_hora_fim: `${hojeStr()}T${d.fim}:00.000`,
              cadeia_id: "semanal",
              //status: "disponivel",
            };
          } else {
            return {

            };
          }
        })
      );
      

      return { habilitar, dias, periodos };
    },

    template: `
     <label class="form-label  d-block">Definir Programação Semanal</label>  
     <!-- Toggle principal -->
       
        <div class="form-group mb-4">
          
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" v-model="habilitar">
            <label class="form-check-label">Habilitar Programação</label>
          </div>
        </div>
    <div class="border rounded p-3">
        <!-- Dias da semana -->
        <div v-if="habilitar" class="mt-2">
          <div class="row align-items-center mb-3 border-bottom pb-2" v-for="(dia, key) in dias" :key="dia.id" >
            <div class="col-md-3">
              <div class="form-check form-switch">
                <input :id="'semanal-dia-'+key" class="form-check-input toggle-dia" type="checkbox" v-model="dia.ativo">
                <label class="form-check-label" :for="'semanal-dia-'+key">{{ dia.nome }}</label>
              </div>
            </div>
            <div class="col-md-2" v-if="dia.ativo">
              <label class="form-label small">Início</label>
              <input type="time" class="form-control hora-inicio" v-model="dia.inicio">
            </div>
            <div class="col-md-2" v-if="dia.ativo">
              <label class="form-label small">Fim</label>
              <input type="time" class="form-control hora-fim" v-model="dia.fim">
            </div>
          </div>
        </div>

        <!-- Hidden inputs -->
        <input type="hidden" name="habilitar_programacao" :value="habilitar ? 1 : 0">
        <div v-for="p in periodos" :key="p.dia_semana">
          <input type="hidden" :name="'periodos['+p.dia_semana+'][data_hora_inicio]'" :value="p.data_hora_inicio">
          <input type="hidden" :name="'periodos['+p.dia_semana+'][data_hora_fim]'" :value="p.data_hora_fim">
          <input type="hidden" :name="'periodos['+p.dia_semana+'][dia_semana]'" :value="p.dia_semana">
          <input type="hidden" :name="'periodos['+p.dia_semana+'][cadeia_id]'" :value="p.cadeia_id">
        </div>
      </div>
    `,
  };

  // Para uso standalone
  if (typeof document !== "undefined") {
    document.addEventListener("DOMContentLoaded", () => {
      const nodes = document.querySelectorAll('[data-vue="programacao-semanal"]');
      nodes.forEach((el) => {
        const programacaoInicial = el.dataset.programacaoInicial
          ? JSON.parse(el.dataset.programacaoInicial)
          : [];
		const habilitarInicial = el.dataset.habilitarInicial === 'true';
        createApp(ProgramacaoSemanal, {
          programacaoInicial, // 🔹 agora realmente passa a programação inicial
		  habilitarInicial
        }).mount(el);
      });
    });
  }
  if (typeof module !== "undefined") module.exports = ProgramacaoSemanal;
})();

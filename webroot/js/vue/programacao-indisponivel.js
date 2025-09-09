(function () {
  const { createApp, ref, computed, onMounted } = Vue;

  const DiasIndisponiveis = {
    name: "DiasIndisponiveis",
    props: {
      periodosIniciais: {
        type: Array,
        default: () => [],
      },
    },
    setup(props) {
      const indisponiveis = ref([]);
      const gerarHash = () => Math.random().toString(36).substring(2, 8);
      const hojeStr = () => new Date().toISOString().split("T")[0];

      // Inicializa com dados do backend
      // Dentro do DiasIndisponiveis

      // Inicializa com dados do backend
      onMounted(() => {
        if (props.periodosIniciais.length > 0) {
          const hoje = new Date(hojeStr());

          // 1. Filtra períodos indisponíveis, independente da data
          const filtrados = props.periodosIniciais.filter(p =>
            p.cadeia_id !== 'semanal' &&
            p.status === 'indisponivel'
          );

          // 2. Agrupa os períodos por cadeia_id
          const agrupado = {};
          filtrados.forEach(p => {
            if (!agrupado[p.cadeia_id]) agrupado[p.cadeia_id] = [];
            agrupado[p.cadeia_id].push(p);
          });

          // 3. Itera sobre os grupos para criar uma entrada para cada
          Object.values(agrupado).forEach(grupo => {
            // Ordena os dados por data_personalizada para garantir a ordem
            grupo.sort((a, b) => new Date(a.data_personalizada) - new Date(b.data_personalizada));

            // 4. Agrupa os dias consecutivos
            const gruposFinais = [];
            if (grupo.length > 0) {
              let grupoAtual = [grupo[0]];
              for (let i = 1; i < grupo.length; i++) {
                const dataAnterior = new Date(grupo[i - 1].data_personalizada);
                const dataAtual = new Date(grupo[i].data_personalizada);
                const diffEmDias = (dataAtual - dataAnterior) / (1000 * 60 * 60 * 24);

                if (diffEmDias === 1) {
                  grupoAtual.push(grupo[i]);
                } else {
                  gruposFinais.push(grupoAtual);
                  grupoAtual = [grupo[i]];
                }
              }
              gruposFinais.push(grupoAtual);
            }

            // 5. Mapeia os grupos finais para a estrutura de exibição e filtra os passados
            gruposFinais.forEach(finalGroup => {
              const primeiro = finalGroup[0];
              const ultimo = finalGroup[finalGroup.length - 1];
              const dataFimDoIntervalo = new Date(ultimo.data_personalizada);
              const dataInicioDoIntervalo = new Date(primeiro.data_personalizada);

              // Se o final do intervalo é menor que hoje, não exibe
              if (dataFimDoIntervalo < hoje) {
                return;
              }

              indisponiveis.value.push({
                id: primeiro.cadeia_id,
                cadeia_id: primeiro.cadeia_id,
                tipo: finalGroup.length > 1 ? 'intervalo' : 'unico',
                data_inicio: primeiro.data_personalizada?.slice(0, 10) || '',
                hora_inicio: primeiro.data_hora_inicio?.slice(11, 16) || '',
                data_fim: finalGroup.length > 1 ? ultimo.data_personalizada?.slice(0, 10) : "",
                hora_fim: ultimo.data_hora_fim?.slice(11, 16) || ''
              });
            });
          });
        }
      });

      const adicionar = () => {
        indisponiveis.value.push({
          id: `indisponivel-${gerarHash()}`,
          tipo: "unico",
          data_inicio: "",
          hora_inicio: "00:00",
          data_fim: "",
          hora_fim: "00:00",
        });
      };

      const remover = (index) => {
        indisponiveis.value.splice(index, 1);
      };

      const periodosIndisponiveis = computed(() => {
        const todos = [];
        indisponiveis.value.forEach(p => {
          if (!p.data_inicio || !p.hora_inicio || !p.hora_fim) return;
         
          const cadeia_id = p.id;
          console.log(cadeia_id);
          
          const dtInicioDate = new Date(`${p.data_inicio}T00:00:00`);
          const dtFimDate = (p.tipo === "intervalo" && p.data_fim)
            ? new Date(`${p.data_fim}T00:00:00`)
            : dtInicioDate;

          for (let d = new Date(dtInicioDate); d <= dtFimDate; d.setDate(d.getDate() +1)) {
            const diaStr = d.toISOString().split("T")[0];
            const diaSemana = d.getDay() + 1;

            todos.push({
              dia_semana: diaSemana,
              data_hora_inicio: `${diaStr}T${p.hora_inicio}:00.000`,
              data_hora_fim: `${diaStr}T${p.hora_fim}:00.000`,
              data_personalizada: `${diaStr}T00:00:00.000`,
              cadeia_id,
              status: "indisponivel",
              programacao_personalizada: 1
            });
          }
        });
        return todos;
      });

      return { indisponiveis, adicionar, remover, periodosIndisponiveis };
    },

    template: `
      <label class="form-label d-block">Datas Indisponíveis</label>
      <div class="border rounded p-3 mb-3">

        <button type="button" class="btn bg-danger-subtle text-danger mb-3" @click="adicionar">
           <i class="ti ti-plus fs-4 me-2"></i>
          Adicionar dia indisponível
        </button>

        <div v-for="(p,index) in indisponiveis" :key="p.id" class="mb-3 border rounded p-2">
          <div class="row g-2 align-items-end">

            <div class="col-md-2">
              <label class="form-label small">Tipo</label>
              <select class="form-select" v-model="p.tipo">
                <option value="unico">Único</option>
                <option value="intervalo">Intervalo</option>
              </select>
            </div>

            <div class="col-md-2">
              <label class="form-label small">Data Início</label>
              <input type="date" class="form-control" v-model="p.data_inicio">
            </div>
            <div class="col-md-2" v-if="p.tipo==='intervalo'">
              <label class="form-label small">Data Fim</label>
              <input type="date" class="form-control" v-model="p.data_fim">
            </div>
            
            

            <div class="col-md-1 text-end">
              <button type="button" class="btn bg-danger-subtle text-danger" @click="remover(index)"><i class="ti ti-trash fs-4 me-2"</button>
            </div>

          </div>
        </div>

        <!-- Hidden inputs para envio -->
        <div v-for="p in periodosIndisponiveis" :key="p.cadeia_id + '_' + p.data_personalizada">
          <input type="hidden" :name="'periodos['+p.cadeia_id+'][dia_semana][]'" :value="p.dia_semana">
           <input type="hidden" :name="'periodos['+p.cadeia_id+'][data_hora_inicio][]'" :value="p.data_hora_inicio">
            <input type="hidden" :name="'periodos['+p.cadeia_id+'][data_hora_fim][]'" :value="p.data_hora_fim">
          <input type="hidden" :name="'periodos['+p.cadeia_id+'][data_personalizada][]'" :value="p.data_personalizada">
          <input type="hidden" :name="'periodos['+p.cadeia_id+'][cadeia_id]'" :value="p.cadeia_id">
          <input type="hidden" :name="'periodos['+p.cadeia_id+'][status]'" :value="p.status">
          <input type="hidden" :name="'periodos['+p.cadeia_id+'][programacao_personalizada]'" value="1">
        </div>

      </div>
    `,
  };

  if (typeof document !== "undefined") {
    document.addEventListener("DOMContentLoaded", () => {
      const nodes = document.querySelectorAll('[data-vue="dias-indisponiveis"]');
      nodes.forEach((el) => {
        const periodosIniciais = el.dataset.periodosIniciais
          ? JSON.parse(el.dataset.periodosIniciais)
          : [];
        createApp(DiasIndisponiveis, { periodosIniciais }).mount(el);
      });
    });
  }

  if (typeof module !== "undefined") module.exports = DiasIndisponiveis;
})();

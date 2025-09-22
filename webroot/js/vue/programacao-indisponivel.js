(function () {
  const { createApp, ref, computed, onMounted } = Vue;

  const DiasIndisponiveis = {
    name: "DiasIndisponiveis",
    props: {
      periodosIniciais: {
        type: Array,
        default: () => [],
      },
      token: {
          type: String,
          required: false,
          default: null,
      },
      baseUrl: {
          type: String,
          default: '/',
      }
    },
    setup(props) {
      const indisponiveis = ref([]);
      const gerarHash = () => Math.random().toString(36).substring(2, 8);
      const hojeStr = () => new Date().toISOString().split("T")[0];

        // Função para converter dados vindos do backend em formato esperado no Vue
        function carregarPeriodosPersonalizados(dadosIniciais) {
            const todos = [];

            (dadosIniciais || []).forEach(p => {
            const inicio = p.data_hora_inicio ? new Date(p.data_hora_inicio) : null;
            const fim = p.data_hora_fim ? new Date(p.data_hora_fim) : null;
            const tipo = (inicio.getDate() === fim.getDate()) ? 'unico' : 'intervalo';

            if (!inicio || !fim) return;

            todos.push({
                id: p.id,
                tipo: tipo,
                profissional_id: p.profissional_id ?? null,
                servico_id: p.servico_id ?? null,
                local_id: p.local_id ?? null,
                convenio_id: p.convenio_id ?? null,
                data_inicio: inicio.toISOString().slice(0, 10), // yyyy-mm-dd
                hora_inicio: inicio.toISOString().slice(11, 16),
                data_fim: fim.toISOString().slice(0, 10),
                hora_fim: fim.toISOString().slice(11, 16),
                cadeia_id: p.cadeia_id || 3,
                status: p.status ? "1" : "0",
                programacao_personalizada: 1,
            });
        });

            return todos;
        }


        // Inicializa com dados do backend
      // Dentro do DiasIndisponiveis

      // Inicializa com dados do backend
        onMounted(() => {
            const inicial = props.periodosIniciais || [];
            const periodos = carregarPeriodosPersonalizados(inicial);
            indisponiveis.value = periodos;

        });



        const adicionar = () => {
        indisponiveis.value.push({
          id: ``,
          tipo: "unico",
          data_inicio: "",
          hora_inicio: "00:00",
          data_fim: "",
          hora_fim: "00:00",
        });
      };

      // const remover = (index) => {
      //   indisponiveis.value.splice(index, 1);
      // };

        const carregando    = ref(false);
        const mensagem      = ref(null);
        const tipoMensagem  = ref(null);

        // Função para remover item
        const remover = async (dia, index) => {

            if (dia.id) {
                carregando.value = true;
                mensagem.value = null;
                tipoMensagem.value = null;

                try {

                    Swal.fire({
                            title: "Aguarde...",
                            text: "Removendo o período...",
                            allowOutsideClick: false,
                            didOpen: () => {
                            Swal.showLoading();
                },
                });

                    // faz request para apagar no backend
                    const resp = await fetch(`${props.baseUrl}/${dia.id}`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-Token": props.token,
                            "X-HTTP-Method-Override": "DELETE",
                            "Accept": "application/json",
                        }
                    });

                    //if (!resp.ok) throw new Error("Erro ao apagar no servidor");

                    const data = await resp.json();
                    Swal.close();

                    if (data.success) {
                        // sucesso → limpa os campos do dia
                        dia.ativo = false;
                        dia.inicio = "";
                        dia.fim = "";
                        dia.registroId = null;
                        indisponiveis.value.splice(index, 1);
                        Swal.fire({
                            title: "Removido!",
                            text: data.message,
                            icon: "success",
                            draggable: true,
                        });

                    } else {

                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: data.message,
                        });
                    }
                } catch (e) {

                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Não foi possível remover a programação."
                    });
                }finally {
                    carregando.value = false;
                }
            } else {
                // não tem registro no backend, só limpa
                dia.ativo = false;
                dia.inicio = "";
                dia.fim = "";
                indisponiveis.value.splice(index, 1);
                Swal.fire({
                    title: "Removido!",
                    text: "O período foi excluído com sucesso.",
                    icon: "success",
                });
            }
        };


	// funcão utilitária
	const formatarDataBR = (data) => {
	  if (!data) return "";
	  // suporta "YYYY-MM-DD" ou "YYYY-MM-DD HH:MM:SS"
	  const partes = data.split(" ")[0].split("-"); 
	  if (partes.length === 3) {
		const [ano, mes, dia] = partes;
		return `${dia}/${mes}/${ano}`;
	  }
	  return data; 
	};

	const periodosIndisponiveis = computed(() => {
	  return indisponiveis.value
		.filter(p => p.data_inicio && p.hora_inicio && p.hora_fim)
		.map(p => {
		  const cadeia_id = p.id;

		  return {
            id: p.id,
            profissional_id: p.profissional_id,
            servico_id: p.servico_id,
            local_id: p.local_id,
            convenio_id: p.convenio_id,

            dia_semana: new Date(`${p.data_inicio} 00:00:00`).getDay() + 1,
		    data_hora_inicio: `${formatarDataBR(p.data_inicio)} ${p.hora_inicio}:00`,
		    data_hora_fim: `${(formatarDataBR(p.data_fim) || formatarDataBR(p.data_inicio))} ${p.hora_fim}:00.000`,
		    data_personalizada: `${formatarDataBR(p.data_inicio)} 00:00:00`,
		    cadeia_id,
		    status: "1",
		    programacao_personalizada: 1,
		  };
		});
	});


/***
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
***/
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
              <button type="button" class="btn btn-danger" @click="remover(p,  index)"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2.75a2.25 2.25 0 0 0-2.122 1.5a.75.75 0 0 1-1.414-.5a3.751 3.751 0 0 1 7.073 0a.75.75 0 0 1-1.415.5A2.25 2.25 0 0 0 12 2.75M2.75 6a.75.75 0 0 1 .75-.75h17a.75.75 0 0 1 0 1.5h-17A.75.75 0 0 1 2.75 6m3.165 2.45a.75.75 0 1 0-1.497.1l.464 6.952c.085 1.282.154 2.318.316 3.132c.169.845.455 1.551 1.047 2.104s1.315.793 2.17.904c.822.108 1.86.108 3.146.108h.879c1.285 0 2.324 0 3.146-.108c.854-.111 1.578-.35 2.17-.904c.591-.553.877-1.26 1.046-2.104c.162-.814.23-1.85.316-3.132l.464-6.952a.75.75 0 0 0-1.497-.1l-.46 6.9c-.09 1.347-.154 2.285-.294 2.99c-.137.685-.327 1.047-.6 1.303c-.274.256-.648.422-1.34.512c-.713.093-1.653.095-3.004.095h-.774c-1.35 0-2.29-.002-3.004-.095c-.692-.09-1.066-.256-1.34-.512c-.273-.256-.463-.618-.6-1.303c-.14-.705-.204-1.643-.294-2.99z"/><path fill="currentColor" d="M9.425 10.254a.75.75 0 0 1 .821.671l.5 5a.75.75 0 0 1-1.492.15l-.5-5a.75.75 0 0 1 .671-.821m5.821.821a.75.75 0 0 0-1.492-.15l-.5 5a.75.75 0 0 0 1.492.15z"/></svg></button>
            </div>

          </div>
        </div>

        <!-- Hidden inputs para envio -->
        <div v-for="(p, key) in periodosIndisponiveis">
			<input type="hidden" :name="'periodos[31'+key+'][id]'" :value="p.id">
			<input type="hidden" :name="'periodos[31'+key+'][profissional_id]'" :value="p.profissional_id">
			<input type="hidden" :name="'periodos[31'+key+'][servico_id]'" :value="p.servico_id">
			<input type="hidden" :name="'periodos[31'+key+'][local_id]'" :value="p.local_id">
			<input type="hidden" :name="'periodos[31'+key+'][convenio_id]'" :value="p.convenio_id">

			<input type="hidden" :name="'periodos[31'+key+'][dia_semana]'" :value="p.dia_semana">
			<input type="hidden" :name="'periodos[31'+key+'][data_hora_inicio]'" :value="p.data_hora_inicio">
			<input type="hidden" :name="'periodos[31'+key+'][data_hora_fim]'" :value="p.data_hora_fim">
			<input type="hidden" :name="'periodos[31'+key+'][data_personalizada]'" :value="p.data_personalizada">
			<input type="hidden" :name="'periodos[31'+key+'][cadeia_id]'" value="3">
			<input type="hidden" :name="'periodos[31'+key+'][status]'" value="1">
			<input type="hidden" :name="'periodos[31'+key+'][programacao_personalizada]'" value="1">
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
        const token = el.dataset.token || null;
        const baseUrl = el.dataset.baseUrl || '/';
        createApp(DiasIndisponiveis, { periodosIniciais, token, baseUrl }).mount(el);
      });
    });
  }

  if (typeof module !== "undefined") module.exports = DiasIndisponiveis;
})();

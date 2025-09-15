(function () {
    const { createApp, ref, computed, onMounted } = Vue;

    const ProgramacaoPersonalizada = {
            name: "ProgramacaoPersonalizada",
            props: {
                programacaoInicial: {
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
        const programacoes = ref([]);

        // Função para converter dados vindos do backend
        function carregarPeriodosPersonalizados(dadosIniciais) {
            const todos = [];

            (dadosIniciais || []).forEach(p => {


            const inicio = p.data_hora_inicio ? new Date(p.data_hora_inicio) : null;
            const fim = p.data_hora_fim ? new Date(p.data_hora_fim) : null;

            if (!inicio || !fim) return;

            const dataISO = inicio.toISOString().slice(0, 10); // yyyy-mm-dd
            const horaInicio = inicio.toISOString().slice(11, 16);
            const horaFim = fim.toISOString().slice(11, 16);

            todos.push({
                id: p.id,
                tipo: "unico",
                profissional_id: p.profissional_id ?? null,
                servico_id: p.servico_id ?? null,
                local_id: p.local_id ?? null,
                convenio_id: p.convenio_id ?? null,
                servico_id: p.servico_id ?? null,
                local_id: p.local_id ?? null,
                convenio_id: p.convenio_id ?? null,
                data_inicio: dataISO,
                hora_inicio: horaInicio,
                data_fim: fim ? fim.toISOString().slice(0, 10) : dataISO,
                hora_fim: horaFim,
                cadeia_id: p.cadeia_id || "disponivel",
                status: p.status ? "1" : "0",
                programacao_personalizada: 1,
            });
        });

            return todos;
        }

        // Carregar dados iniciais
        onMounted(() => {
            const inicial = props.programacaoInicial || [];
        const periodos = carregarPeriodosPersonalizados(inicial);
        programacoes.value = periodos;
    });

        const adicionar = () => {
            programacoes.value.push({
                id: "",
                tipo: "unico",
                data_inicio: "",
                hora_inicio: "",
                data_fim: "",
                hora_fim: "",
                profissional_id: null,
                servico_id: null,
                local_id: null,
                convenio_id: null,
                servico_id: null,
                local_id: null,
                convenio_id: null,
            });
        };

        // const remover = (index) => {
        //     programacoes.value.splice(index, 1);
        // };

        // Função utilitária
        const formatarDataBR = (data) => {
            if (!data) return "";
            const partes = data.split(" ")[0].split("-");
            if (partes.length === 3) {
                const [ano, mes, dia] = partes;
                return `${dia}/${mes}/${ano}`;
            }
            return data;
        };

        const periodosPersonalizados = computed(() => {
                const todos = [];
        programacoes.value.forEach(p => {
            if (!p.data_inicio || !p.hora_inicio || !p.hora_fim) return;

        todos.push({
            id: p.id,
            profissional_id: p.profissional_id,
            servico_id: p.servico_id,
            local_id: p.local_id,
            convenio_id: p.convenio_id,

            dia_semana: new Date(`${p.data_inicio} 00:00:00`).getDay() + 1,
            data_hora_inicio: `${formatarDataBR(p.data_inicio)} ${p.hora_inicio}:00`,
            data_hora_fim: `${formatarDataBR(p.data_fim || p.data_inicio)} ${p.hora_fim}:00`,
            data_personalizada: `${formatarDataBR(p.data_inicio)} 00:00:00`,
            cadeia_id: p.id,
            status: "1",
            programacao_personalizada: 1,
        });
    });
        return todos;
    });


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
                        programacoes.value.splice(index, 1);
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
                programacoes.value.splice(index, 1);
                Swal.fire({
                    title: "Removido!",
                    text: "O período foi excluído com sucesso.",
                    icon: "success",
                });
            }
        };

        return { programacoes, adicionar, remover, periodosPersonalizados };
    },

    template: `
        <label class="form-label d-block">Datas com Programação Personalizada</label>
        <div class="border rounded p-3 mb-3">
  
          <button type="button" class="btn bg-primary-subtle text-primary mb-3" @click="adicionar">
          <i class="ti ti-plus fs-4 me-2"></i>
            Adicionar Data / Intervalo
          </button>
  
          <div v-for="(p,index) in programacoes" :key="p.id" class="mb-3 border rounded p-2">
            <div class="row g-2 align-items-end">
  
              <div class="col-md-2">
                <label class="form-label small">Tipo</label>
                <select class="form-select" v-model="p.tipo">
                  <option value="unico">Único</option>
                  <option value="intervalo">Intervalo</option>
                </select>
              </div>
  
              <div class="col-md-2">
                <label class="form-label small">Data início</label>
                <input type="date" class="form-control" v-model="p.data_inicio">
              </div>
              <div class="col-md-2" v-if="p.tipo === 'intervalo'">
                 <label class="form-label small">Data Fim</label>
                     <input type="date" class="form-control" v-model="p.data_fim">
                </div>

              <div class="col-md-2">
                <label class="form-label small">Hora início</label>
                <input type="time" class="form-control" v-model="p.hora_inicio">
              </div>
              <div class="col-md-2">
                <label class="form-label small">Hora Fim</label>
                <input type="time" class="form-control" v-model="p.hora_fim">
              </div>
  
              <div class="col-md-1 text-end">
                <button type="button" class="btn btn-danger" @click="remover(p, index)"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2.75a2.25 2.25 0 0 0-2.122 1.5a.75.75 0 0 1-1.414-.5a3.751 3.751 0 0 1 7.073 0a.75.75 0 0 1-1.415.5A2.25 2.25 0 0 0 12 2.75M2.75 6a.75.75 0 0 1 .75-.75h17a.75.75 0 0 1 0 1.5h-17A.75.75 0 0 1 2.75 6m3.165 2.45a.75.75 0 1 0-1.497.1l.464 6.952c.085 1.282.154 2.318.316 3.132c.169.845.455 1.551 1.047 2.104s1.315.793 2.17.904c.822.108 1.86.108 3.146.108h.879c1.285 0 2.324 0 3.146-.108c.854-.111 1.578-.35 2.17-.904c.591-.553.877-1.26 1.046-2.104c.162-.814.23-1.85.316-3.132l.464-6.952a.75.75 0 0 0-1.497-.1l-.46 6.9c-.09 1.347-.154 2.285-.294 2.99c-.137.685-.327 1.047-.6 1.303c-.274.256-.648.422-1.34.512c-.713.093-1.653.095-3.004.095h-.774c-1.35 0-2.29-.002-3.004-.095c-.692-.09-1.066-.256-1.34-.512c-.273-.256-.463-.618-.6-1.303c-.14-.705-.204-1.643-.294-2.99z"/><path fill="currentColor" d="M9.425 10.254a.75.75 0 0 1 .821.671l.5 5a.75.75 0 0 1-1.492.15l-.5-5a.75.75 0 0 1 .671-.821m5.821.821a.75.75 0 0 0-1.492-.15l-.5 5a.75.75 0 0 0 1.492.15z"/></svg></button>
              </div>
            </div>
          </div>
  
          <!-- Hidden inputs para envio -->
          <div v-for="(p, key) in periodosPersonalizados">
			<input type="hidden" :name="'periodos[21'+key+'][id]'" :value="p.id">
			<input type="hidden" :name="'periodos[21'+key+'][profissional_id]'" :value="p.profissional_id">
			<input type="hidden" :name="'periodos[21'+key+'][servico_id]'" :value="p.servico_id">
			<input type="hidden" :name="'periodos[21'+key+'][local_id]'" :value="p.local_id">
			<input type="hidden" :name="'periodos[21'+key+'][convenio_id]'" :value="p.convenio_id">

            <input type="hidden" :name="'periodos[21'+key+'][dia_semana]'" :value="p.dia_semana">
            <input type="hidden" :name="'periodos[21'+key+'][data_hora_inicio]'" :value="p.data_hora_inicio">
            <input type="hidden" :name="'periodos[21'+key+'][data_hora_fim]'" :value="p.data_hora_fim">
            <input type="hidden" :name="'periodos[21'+key+'][data_personalizada]'" :value="p.data_personalizada">
            <input type="hidden" :name="'periodos[21'+key+'][cadeia_id]'" value="disponivel">
            <input type="hidden" :name="'periodos[21'+key+'][status]'" value="1">
            <input type="hidden" :name="'periodos[21'+key+'][programacao_personalizada]'" value="1">
          </div>
  
        </div>
      `,
};

    if (typeof document !== "undefined") {
        document.addEventListener("DOMContentLoaded", () => {
            const nodes = document.querySelectorAll('[data-vue="programacao-personalizada"]');


        nodes.forEach((el) => {
            const programacaoInicial = el.dataset.programacaoInicial
                ? JSON.parse(el.dataset.programacaoInicial)
                : [];
        const token = el.dataset.token || null;
        const baseUrl = el.dataset.baseUrl || '/';
        createApp(ProgramacaoPersonalizada, { programacaoInicial, token, baseUrl }).mount(el);
    });
    });
    }

    if (typeof module !== "undefined") module.exports = ProgramacaoPersonalizada;
})();

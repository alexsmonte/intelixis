// ANTIGO
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

            //const hojeStr = () => new Date().toISOString().split("T")[0];
            const hojeStr = () => {
                const hoje = new Date();
                const dia = String(hoje.getDate()).padStart(2, "0");
                const mes = String(hoje.getMonth() + 1).padStart(2, "0");
                const ano = hoje.getFullYear();
                return `${dia}/${mes}/${ano}`;
            };

            // Inicializa a programação caso venha do backend
            
             onMounted(() => {
                 // 1. Verifique se existe alguma programação semanal
               
                // const temProgramacaoSemanal = props.programacaoInicial.some(p => p.cadeia_id === 'semanal');
     
             // 2. Habilite o toggle apenas se a condição for verdadeira
             //if (temProgramacaoSemanal) {
            
               //  habilitar.value = true;
     
                 // Agora, itere sobre os dados para preencher os dias
                 props.programacaoInicial.forEach((p) => {
                     if (p.cadeia_id === '1') {
                     const dia = dias.value.find((d) => d.id === p.dia_semana);
                     if (dia) {
                         dia.ativo = p.status !== false;
                         dia.inicio = p.data_hora_inicio.slice(11, 16); // HH:MM
                         dia.fim = p.data_hora_fim.slice(11, 16);
                         dia.registroId = p.id;
                         dia.profissional_id  =  p.profissional_id ?? null;
                         dia.servico_id  =   p.servico_id ?? null;
                         dia.local_id  =   p.local_id ?? null;
                         dia.convenio_id  =   p.convenio_id ?? null;
                         dia.servico_id  =   p.servico_id ?? null;
                         dia.local_id  =   p.local_id ?? null;
                         dia.convenio_id  =   p.convenio_id ?? null;
                     }
                 }
                 });
            // }
         }); 

         
            const validarDia = (dia) => {
                if (!dia.inicio || !dia.fim) return; // só valida se ambos preenchidos

                const inicio = new Date(`${hojeStr().split("/").reverse().join("-")}T${dia.inicio}:00`);
                const fim = new Date(`${hojeStr().split("/").reverse().join("-")}T${dia.fim}:00`);

                if (fim <= inicio) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: `${dia.nome}: horário final deve ser maior que o inicial.`,
                    });
                }
            };

            const periodos = computed(() =>
                dias.value.filter((d) => habilitar.value && d.ativo && d.inicio && d.fim).map((d) => {
                    if (habilitar.value && d.ativo && d.inicio && d.fim) {
                        return {
                            id: d.registroId ?? null,
                            profissional_id: d.profissional_id ?? null,
                            servico_id: d.servico_id ?? null,
                            local_id: d.local_id ?? null,
                            convenio_id: d.convenio_id ?? null,
                            servico_id: d.servico_id ?? null,
                            local_id: d.local_id ?? null,
                            convenio_id: d.convenio_id ?? null,
                            dia_semana: d.id,
                            data_hora_inicio: `${hojeStr()} ${d.inicio}:00`,
                            data_hora_fim: `${hojeStr()} ${d.fim}:00`,
                            cadeia_id: "1",
                            //status: "disponivel",
                        };
                    } else {
                        return {};
                    }
                })
            );

            const carregando = ref(false);
            const mensagem = ref(null);
            const tipoMensagem = ref(null);

            // Função para remover item
            const removerDia = async (dia) => {
                if (dia.registroId) {
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
                        const resp = await fetch(`${props.baseUrl}/${dia.registroId}`, {
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
                        console.error("Erro ao remover programação:", e);
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Não foi possível remover a programação."
                        });
                    } finally {
                        carregando.value = false;
                    }
                } else {
                    // não tem registro no backend, só limpa
                    dia.ativo = false;
                    dia.inicio = "";
                    dia.fim = "";

                    Swal.fire({
                        title: "Removido!",
                        text: "O período foi excluído com sucesso.",
                        icon: "success",
                    });
                }
            };

            return { habilitar, dias, periodos, validarDia, removerDia, carregando, mensagem, tipoMensagem };
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
              <input type="time" class="form-control hora-inicio" v-model="dia.inicio" @blur="validarDia(dia)">
            </div>
            <div class="col-md-2" v-if="dia.ativo">
              <label class="form-label small">Fim</label>
              <input type="time" class="form-control hora-fim" v-model="dia.fim" @blur="validarDia(dia)">
            </div>
           <!--
            <div class="col-md-2 mt-4" v-if="dia.ativo">
            <button class="btn btn-danger" type="button" @click="removerDia(dia)"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2.75a2.25 2.25 0 0 0-2.122 1.5a.75.75 0 0 1-1.414-.5a3.751 3.751 0 0 1 7.073 0a.75.75 0 0 1-1.415.5A2.25 2.25 0 0 0 12 2.75M2.75 6a.75.75 0 0 1 .75-.75h17a.75.75 0 0 1 0 1.5h-17A.75.75 0 0 1 2.75 6m3.165 2.45a.75.75 0 1 0-1.497.1l.464 6.952c.085 1.282.154 2.318.316 3.132c.169.845.455 1.551 1.047 2.104s1.315.793 2.17.904c.822.108 1.86.108 3.146.108h.879c1.285 0 2.324 0 3.146-.108c.854-.111 1.578-.35 2.17-.904c.591-.553.877-1.26 1.046-2.104c.162-.814.23-1.85.316-3.132l.464-6.952a.75.75 0 0 0-1.497-.1l-.46 6.9c-.09 1.347-.154 2.285-.294 2.99c-.137.685-.327 1.047-.6 1.303c-.274.256-.648.422-1.34.512c-.713.093-1.653.095-3.004.095h-.774c-1.35 0-2.29-.002-3.004-.095c-.692-.09-1.066-.256-1.34-.512c-.273-.256-.463-.618-.6-1.303c-.14-.705-.204-1.643-.294-2.99z"/><path fill="currentColor" d="M9.425 10.254a.75.75 0 0 1 .821.671l.5 5a.75.75 0 0 1-1.492.15l-.5-5a.75.75 0 0 1 .671-.821m5.821.821a.75.75 0 0 0-1.492-.15l-.5 5a.75.75 0 0 0 1.492.15z"/></svg></button>
            
            </div> -->
           
          </div>
        </div>

        <!-- Hidden inputs -->
        <input type="hidden" name="habilitar_programacao" :value="habilitar ? 1 : 0">
        <div v-for="(p, key) in periodos" :key="p.dia_semana">
          <input type="hidden" :name="'periodos[11'+key+'][id]'" :value="p.id">
          <input type="hidden" :name="'periodos[11'+key+'][profissional_id]'" :value="p.profissional_id">
          <input type="hidden" :name="'periodos[11'+key+'][servico_id]'" :value="p.servico_id">
          <input type="hidden" :name="'periodos[11'+key+'][local_id]'" :value="p.local_id">
          <input type="hidden" :name="'periodos[11'+key+'][convenio_id]'" :value="p.convenio_id">
          <input type="hidden" :name="'periodos[11'+key+'][data_hora_inicio]'" :value="p.data_hora_inicio">
          <input type="hidden" :name="'periodos[11'+key+'][data_hora_fim]'" :value="p.data_hora_fim">
          <input type="hidden" :name="'periodos[11'+key+'][dia_semana]'" :value="p.dia_semana">
          <input type="hidden" :name="'periodos[11'+key+'][cadeia_id]'" :value="p.cadeia_id">
          <input type="hidden" :name="'periodos[11'+key+'][status]'" value="1">
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
                const token = el.dataset.token || null;
                const baseUrl = el.dataset.baseUrl || '/';

                createApp(ProgramacaoSemanal, {
                    programacaoInicial, // agora realmente passa a programação inicial
                    habilitarInicial,
                    token,
                    baseUrl
                }).mount(el);
            });
        });
    }
    if (typeof module !== "undefined") module.exports = ProgramacaoSemanal;
})();


//ANTIGO


/*(function () {
    const { createApp, ref, computed, onMounted, watch } = Vue;

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
                default: false,
            },
            token: {
                type: String,
                default: null,
            },
            baseUrl: {
                type: String,
                default: '/',
            },
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

            const hojeStr = () => {
                const hoje = new Date();
                const dia = String(hoje.getDate()).padStart(2, "0");
                const mes = String(hoje.getMonth() + 1).padStart(2, "0");
                const ano = hoje.getFullYear();
                return `${dia}/${mes}/${ano}`;
            };

            const carregando = ref(false);

            onMounted(() => {
                habilitar.value = !!props.habilitarInicial;

                props.programacaoInicial.forEach(p => {
                    if (p.cadeia_id === 'semanal') {
                        const dia = dias.value.find(d => d.id === p.dia_semana);
                        if (dia) {
                            dia.ativo = p.status !== false;
                            dia.inicio = p.data_hora_inicio.slice(11, 16);
                            dia.fim = p.data_hora_fim.slice(11, 16);
                            dia.registroId = p.id;
                            dia.profissional_id = p.profissional_id ?? null;
                            dia.servico_id = p.servico_id ?? null;
                            dia.local_id = p.local_id ?? null;
                            dia.convenio_id = p.convenio_id ?? null;
                        }
                    }
                });
            });

            const periodos = computed(() => {
                if (!habilitar.value) return [];
                return dias.value
                    .filter(d => d.ativo && d.inicio && d.fim)
                    .map(d => ({
                        id: d.registroId ?? null,
                        profissional_id: d.profissional_id ?? null,
                        servico_id: d.servico_id ?? null,
                        local_id: d.local_id ?? null,
                        convenio_id: d.convenio_id ?? null,
                        dia_semana: d.id,
                        data_hora_inicio: `${hojeStr()} ${d.inicio}:00`,
                        data_hora_fim: `${hojeStr()} ${d.fim}:00`,
                        cadeia_id: "semanal",
                        status: 1,
                    }));
            });

            const validarDia = (dia) => {
                if (!dia.inicio || !dia.fim) return;

                const inicio = new Date(`${hojeStr().split("/").reverse().join("-")}T${dia.inicio}:00`);
                const fim = new Date(`${hojeStr().split("/").reverse().join("-")}T${dia.fim}:00`);

                if (fim <= inicio) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: `${dia.nome}: horário final deve ser maior que o inicial.`,
                    });
                }
            };

            const removerDia = async (dia) => {
                if (!dia.registroId) {
                    dia.ativo = false;
                    dia.inicio = "";
                    dia.fim = "";
                    return;
                }

                carregando.value = true;
                try {
                    Swal.fire({ title: "Aguarde...", text: "Removendo o período...", allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                    const url = new URL(`${props.baseUrl}/${dia.registroId}`, window.location.origin);
                    url.protocol = "https:";

                    const resp = await fetch(url.toString(), {
                        method: "POST",
                        headers: {
                            "X-CSRF-Token": props.token,
                            "X-HTTP-Method-Override": "DELETE",
                            "Accept": "application/json",
                        },
                    });

                    const data = await resp.json();
                    Swal.close();

                    if (data.success) {
                        dia.ativo = false;
                        dia.inicio = "";
                        dia.fim = "";
                        dia.registroId = null;
                        Swal.fire({ title: "Removido!", text: data.message, icon: "success" });
                    } else {
                        Swal.fire({ icon: "error", title: "Oops...", text: data.message });
                    }
                } catch (e) {
                    console.error("Erro ao remover programação:", e);
                    Swal.fire({ icon: "error", title: "Oops...", text: "Não foi possível remover a programação." });
                } finally {
                    carregando.value = false;
                }
            };
            
            // AQUI: A lógica de remoção do backend foi removida
            // O frontend apenas limpa o estado local
            watch(habilitar, (val) => {
                if (!val) {
                    dias.value.forEach(dia => {
                        dia.ativo = false;
                        dia.inicio = "";
                        dia.fim = "";
                    });
                }
            });

            return { habilitar, dias, periodos, validarDia, removerDia, carregando };
        },

        template: `
        <label class="form-label d-block">Definir Programação Semanal</label>
        <div class="form-group mb-4">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" v-model="habilitar">
                <label class="form-check-label">Habilitar Programação</label>
            </div>
        </div>

        <div class="border rounded p-3" v-if="habilitar">
            <div class="row align-items-center mb-3 border-bottom pb-2" v-for="(dia, key) in dias" :key="dia.id">
                <div class="col-md-3">
                    <div class="form-check form-switch">
                        <input :id="'semanal-dia-'+key" class="form-check-input toggle-dia" type="checkbox" v-model="dia.ativo">
                        <label class="form-check-label" :for="'semanal-dia-'+key">{{ dia.nome }}</label>
                    </div>
                </div>
                <div class="col-md-2" v-if="dia.ativo">
                    <label class="form-label small">Início</label>
                    <input type="time" class="form-control hora-inicio" v-model="dia.inicio" @blur="validarDia(dia)">
                </div>
                <div class="col-md-2" v-if="dia.ativo">
                    <label class="form-label small">Fim</label>
                    <input type="time" class="form-control hora-fim" v-model="dia.fim" @blur="validarDia(dia)">
                </div>
            </div>
        </div>

        <input type="hidden" name="habilitar_programacao" :value="habilitar ? 1 : 0">

        <div v-for="(p, key) in periodos" :key="p.dia_semana">
            <input type="hidden" :name="'periodos[11'+key+'][id]'" :value="p.id">
            <input type="hidden" :name="'periodos[11'+key+'][profissional_id]'" :value="p.profissional_id">
            <input type="hidden" :name="'periodos[11'+key+'][servico_id]'" :value="p.servico_id">
            <input type="hidden" :name="'periodos[11'+key+'][local_id]'" :value="p.local_id">
            <input type="hidden" :name="'periodos[11'+key+'][convenio_id]'" :value="p.convenio_id">
            <input type="hidden" :name="'periodos[11'+key+'][data_hora_inicio]'" :value="p.data_hora_inicio">
            <input type="hidden" :name="'periodos[11'+key+'][data_hora_fim]'" :value="p.data_hora_fim">
            <input type="hidden" :name="'periodos[11'+key+'][dia_semana]'" :value="p.dia_semana">
            <input type="hidden" :name="'periodos[11'+key+'][cadeia_id]'" :value="p.cadeia_id">
            <input type="hidden" :name="'periodos[11'+key+'][status]'" value="1">
        </div>
    `,
    };

    if (typeof document !== "undefined") {
        document.addEventListener("DOMContentLoaded", () => {
            const nodes = document.querySelectorAll('[data-vue="programacao-semanal"]');
            nodes.forEach((el) => {
                const programacaoInicial = el.dataset.programacaoInicial ? JSON.parse(el.dataset.programacaoInicial) : [];
                const habilitarInicial = el.dataset.habilitarInicial === 'true';
                const token = el.dataset.token || null;
                const baseUrl = el.dataset.baseUrl || '/';

                createApp(ProgramacaoSemanal, { programacaoInicial, habilitarInicial, token, baseUrl }).mount(el);
            });
        });
    }

    if (typeof module !== "undefined") module.exports = ProgramacaoSemanal;
})();

*/
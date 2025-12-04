<?php
use Cake\I18n\FrozenTime;

$this->BreadcrumbsPlus->add([['title' => 'Agendamentos']]);
$segundos = ($estabelecimento->intervalo_atendimento ?? 30) * 60;
$retornoAtendimento = $this->request->getSession()
    ->read('Auth.Estabelecimento.retorno_atendimento');
//echo ("Retorno Atend: ".$retornoAtendimento);

?>
<meta name="csrfToken" content="<?= $this->request->getAttribute('csrfToken') ?>">

<div data-vue="generic-modal" data-title="Cadastrar Cliente"
    data-submit-url="<?= $this->Url->build(['controller' => 'Clientes', 'action' => 'novo'], ['fullBase' => true]) ?>"
    data-trigger-btn="#btnAbrirCadastroCliente" data-modal-size="modal-xl" data-fields='[
        {"name":"nome","label":"Nome Completo","type":"text","id":"nome","col":6,"placeholder":"Digite o nome"},
        {"name":"data_nascimento","label":"Nascimento","type":"date","id":"data_nascimento","col":3},
        {"name":"documento","label":"CPF","type":"text","id":"documento","col":3},
        {"name":"email","label":"Email","type":"email","id":"email","col":6},
        {"name":"telefone","label":"Telefone","type":"text","id":"telefone","col":3},
        {"name":"celular","label":"Celular","type":"text","id":"celular","col":3},
        {"name":"cep","label":"CEP","vueComponent":"cep-autocomplete","id":"cep","col":3},
        {"name":"logradouro","label":"Logradouro","type":"text","id":"logradouro","col":7,"autocompleteField":true},
        {"name":"numero","label":"Número","type":"text","id":"numero", "col":2},
         {"name":"bairro","label":"Bairro","type":"text","id":"bairro","col":5,"autocompleteField":true},
        {"name":"cidade","label":"Cidade","type":"text","id":"cidade","col":5,"autocompleteField":true},
        {"name":"uf","label":"UF","type":"text","id":"uf","col":2,"autocompleteField":true}
     ]'>
</div>

<div class="card">
    <div class="card-body calender-sidebar app-calendar">
        <div id="calendar"></div>
    </div>
</div>
<!-- BEGIN MODAL BUSCAR -->
<div class="modal fade" id="mdlBusca" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">
                    Buscar por
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <?= $this->Form->control('buscar_titulo', ['label' => 'Buscar por nome do cliente, cpf, telefone', 'class' => 'form-control']); ?>
                    </div>
                    <div class="col-md-12 mb-1">
                        <?= $this->Form->control('buscar_profissional', ['label' => 'Buscar por profissional', 'class' => 'selectpicker', 'multiple', 'data-live-search' => true, 'data-none-selected-text' => 'Meus profissionais', 'options' => $profissionais, 'empty' => true]); ?>
                    </div>
                    <div class="col-md-12 mb-1">
                        <?= $this->Form->control('buscar_servicos', ['label' => 'Buscar por serviços', 'class' => 'selectpicker', 'multiple', 'data-live-search' => true, 'data-none-selected-text' => 'Meus serviços', 'options' => $servicos, 'empty' => true]); ?>
                    </div>
                    <div class="col-md-12 mb-1">
                        <?= $this->Form->control('buscar_convenios', ['label' => 'Buscar por convénios', 'class' => 'selectpicker', 'multiple', 'data-live-search' => true, 'data-none-selected-text' => 'Meus convénios', 'options' => $convenios, 'empty' => true]); ?>
                    </div>
                    <div class="col-md-12 mb-1">
                        <?= $this->Form->control('buscar_local', ['label' => 'Buscar por local', 'class' => 'selectpicker', 'multiple', 'data-live-search' => true, 'data-none-selected-text' => 'Meus locais', 'options' => $locais, 'empty' => true]); ?>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-danger-subtle text-danger" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn bg-primary-subtle text-primary" id="limpar">Limpar</button>
                <button type="button" class="btn btn-success" id="buscar">Buscar</button>
            </div>
        </div>
    </div>
</div>
<!-- BEGIN MODAL FILTRAR -->
<div class="modal fade" id="mdlFiltrar" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">Encontrar horários disponíveis por</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="filtrosForm2" class="row g-3">

                    <div class="col-md-12">
                        <label class="form-label">Profissional</label>
                        <select class="form-select" id="filtrar-profissional">
                            <option value="">Todos os profissionais</option>
                            <?php foreach ($profissionais as $id => $nome): ?>
                                <option value="<?= $id ?>"><?= h($nome) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Serviço</label>
                        <select class="form-select" id="filtrar-servico">
                            <option value="">Todos os serviços</option>
                            <?php foreach ($servicos as $id => $nome): ?>
                                <option value="<?= $id ?>"><?= h($nome) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Local</label>
                        <select class="form-select" id="filtrar-local">
                            <option value="">Todos os locais</option>
                            <?php foreach ($locais as $id => $nome): ?>
                                <option value="<?= $id ?>"><?= h($nome) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Convênio</label>
                        <select class="form-select" id="filtrar-convenio">
                            <option value="">Todos os convênios</option>
                            <?php foreach ($convenios as $id => $nome): ?>
                                <option value="<?= h($nome) ?>"><?= h($nome) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 mt-12">



                        <div id="loading2" class="spinner-border spinner-border-sm text-primary ms-2 d-none"
                            role="status"><span class="visually-hidden">Carregando...</span></div>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn bg-danger-subtle text-danger" data-bs-dismiss="modal">
                    Fechar
                </button>

                <button type="button" class="btn bg-primary-subtle text-primary" id="btnLimparFiltros"
                    class="btn btn-secondary ms-2">
                    <i class="bi bi-x-circle"></i> Limpar
                </button>

                <button type="button" class="btn bg-success-subtle text-success" id="btnAplicarFiltros"
                    class="btn btn-primary">
                    <i class="bi bi-filter"></i> Filtrar
                </button>

            </div>

        </div>
    </div>
</div>
<!-- BEGIN MODAL AGENDAMENTO -->
<div class="modal fade" id="mdlAgendamento" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <?= $this->Form->create(null, ['url' => ['controller' => 'Agendamentos', 'action' => 'novo'], 'id' => 'frmAgendarConsulta']) ?>
    <?= $this->Form->control('id', ['type' => 'hidden', 'value' => '']); ?>
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">
                    Agendamento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <?= $this->Form->control('cliente_id', ['type' => 'hidden', 'value' => '']); ?>
                        <label for="titulo">Nome do cliente</label>
                        <div class="input-group text">
                            <input type="text" name="titulo" class="form-control form-control-lg" id="titulo"
                                autocomplete="off" aria-label="" aria-describedby="basic-addon1">
                            <button class="btn btn-primary" type="button" id="btnAbrirCadastroCliente"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Adicionar cliente">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                    <path d="M16 19h6" />
                                    <path d="M19 16v6" />
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <?= $this->Form->control('profissional_id', ['label' => 'Profissional', 'data-live-search' => '1', 'data-none-selected-text' => 'Meus profissionais', 'class' => 'form-select form-control-lg', 'options' => $profissionais, 'empty' => true]); ?>
                    </div>
                    <div class="col-md-4">
                        <?= $this->Form->control('servico_id', ['label' => 'Serviço', 'class' => 'form-select', 'options' => $servicos]); ?>
                    </div>
                    <div class="col-md-4">
                        <?= $this->Form->control('local_id', ['label' => 'Local', 'class' => 'form-select', 'options' => $locais, 'empty' => true]); ?>
                    </div>
                    <div class="col-md-4">
                        <?= $this->Form->control('convenio_id', ['label' => 'Convênio', 'class' => 'form-select', 'options' => $convenios, 'empty' => true]); ?>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <?php foreach ($status as $dadosStatus) { ?>
                                <div class="col-md-3 mb-4">
                                    <div class="form-check form-check-outline">
                                        <input
                                            class="form-check-input form-check-<?= str_replace(" ", "-", strtolower($dadosStatus->nome)) ?>"
                                            type="radio"
                                            id="check-<?= str_replace(" ", "-", strtolower($dadosStatus->nome)) ?>"
                                            name="statu_id" value="<?= $dadosStatus->id; ?>">
                                        <label class="form-check-label"
                                            for="check-<?= str_replace(" ", "-", strtolower($dadosStatus->nome)) ?>"><?= $dadosStatus->nome; ?></label>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <?= $this->Form->control('data_inicio', ['label' => 'Data', 'class' => 'form-control', 'type' => 'date', 'min' => date('Y-m-d')]); ?>
                    </div>
                    <div class="col-md-2">
                        <?= $this->Form->control('hora_inicio', ['label' => 'Hora início', 'class' => 'form-control', 'type' => 'time', 'min' => isset($estabelecimento->periodos[0]->data_hora_inicio) ? $estabelecimento->periodos[0]->data_hora_inicio->format("H:i") : '07:00', 'max' => isset($estabelecimento->periodos[0]->data_hora_fim) ? $estabelecimento->periodos[0]->data_hora_fim->format("H:i") : '18:00', 'step' => $segundos]); ?>
                    </div>
                    <div class="col-md-2">
                        <?= $this->Form->control('hora_fim', ['label' => 'Hora fim', 'class' => 'form-control', 'type' => 'time', 'min' => isset($estabelecimento->periodos[0]->data_hora_inicio) ? $estabelecimento->periodos[0]->data_hora_inicio->format("H:i") : '07:00', 'max' => isset($estabelecimento->periodos[0]->data_hora_fim) ? $estabelecimento->periodos[0]->data_hora_fim->format("H:i") : '18:00', 'step' => $segundos]); ?>
                    </div>
                    <div class="col-md-3">
                        <?= $this->Form->control('recorrencia', ['label' => 'Recorrência', 'class' => 'form-control', 'placeholder' => 'Exemplo: 4']); ?>
                    </div>
                    <div class="col-md-3">
                        <?= $this->Form->control('peridos', ['label' => 'Períodos', 'class' => 'form-select', 'options' => ['semanal' => 'Semanal', 'quinzenal' => 'Quinzenal', 'mensal' => 'Mensal', 'trimestral' => 'Trimestral', 'semestral' => 'Semestral', , 'anual' => 'Anual'], 'empty' => true]); ?>
                    </div>

                    <?php if ($retornoAtendimento > 0): ?>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chkRetorno">
                                <label class="form-check-label" for="chkRetorno">
                                    Retorno em <?= $retornoAtendimento ?> dias:
                                    : <br><br>
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="col-md-12 mb-3" id="box-retorno" style="display:none;">
                        <label style="color:#6c757d; font-weight:400;">Sugestão de Retorno:</label>
                        <div id="lista-retorno"></div>
                    </div>

                    <div class="col-md-12 mb-3" id="box-sugestoes" style="display:none;">
                        <label style="color:#6c757d; font-weight:400;">Sugestão de Recorrência:</label>
                        <div id="lista-sugestoes"></div>
                    </div>


                    <div class="col-md-12">
                        <?= $this->Form->control('descricao', ['label' => 'Observações', 'class' => 'form-control']); ?>
                    </div>
                    <div class="col-md-12">
                        <?= $this->Form->control('liberado', ['type' => 'hidden', 'class' => 'form-control']); ?>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-danger-subtle text-danger" data-bs-dismiss="modal">
                    Fechar
                </button>
                <button type="button" class="btn btn-success btn-salvar-agendamento"
                    data-fc-event-public-id="">Salvar</button>
            </div>
        </div>
    </div>

    <?= $this->Form->end() ?>



</div>
<!-- END MODAL -->


<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<?= $this->Html->script('vue/generic-modal.js') ?>
<?= $this->Html->script('vue/form-cadastrar.js') ?>
<?= $this->Html->script('vue/cep-autocomplete.js') ?>

<?= $this->Html->script([
    'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js',
    'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js',
    'https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.19/locales-all.global.min.js',
    'https://code.jquery.com/ui/1.14.1/jquery-ui.min.js',
    'jquery.form.min.js',
    'ui.datepicker-pt-BR.js',
    'plugins/bootstrap-select.js'
]); ?>

<?= $this->Html->css(['https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.14.1/themes/base/jquery-ui.min.css', 'plugins/bootstrap-select.css']); ?>

<script>



    // Aqui vai TODO o seu código de sugestões

</script>

<script>
    function toNullInt(value) {
        return value === "" || value === undefined || value === null
            ? null
            : Number(value);
    }

    document.addEventListener("DOMContentLoaded", function () {
        /*========Calender Js=========*/
        var newDate = new Date();

        function getDynamicMonth() {
            let getMonthValue = newDate.getMonth();
            let _getUpdatedMonthValue = getMonthValue + 1;
            return _getUpdatedMonthValue < 10 ? `0${_getUpdatedMonthValue}` : `${_getUpdatedMonthValue}`;
        }

        var salvarAgendamento = document.querySelector(".btn-salvar-agendamento");
        var calendarEl = document.querySelector("#calendar");

        var checkWidowWidth = function () {
            return window.innerWidth <= 1199;
        };

        var calendarHeaderToolbar = {
            left: "prev next today filtro buscar calendario",
            center: "title",
            right: "dayGridMonth,timeGridWeek,timeGridDay,listDay",
        };

        /**var salvarEventAgendamento = function (info) {

            var current_datetime_start = info.event?.start || info.startStr;
            var current_datetime_end = info.event?.end || info.endStr;

            let data_inicio = moment(current_datetime_start).format('YYYY-MM-DD');
            let hora_inicio = moment(current_datetime_start).format('HH:mm');
            let hora_fim = moment(current_datetime_end).format('HH:mm');

            // NÃO QUEBRA MAIS A EXECUÇÃO
            console.log("EVENT ID:", info.event?.id);

            let dados = {
                agendamento_id: toNullInt(info.event?.id),
                titulo: info.event?.title || '',
                data_inicio: data_inicio,
                hora_inicio: hora_inicio,
                hora_fim: hora_fim,

                cliente_id: toNullInt(info.event?.extendedProps?.cliente_id),
                profissional_id: toNullInt(info.event?.extendedProps?.profissional_id),
                servico_id: toNullInt(info.event?.extendedProps?.servico_id),
                local_id: toNullInt(info.event?.extendedProps?.local_id),
                convenio_id: toNullInt(info.event?.extendedProps?.convenio_id),
                statu_id: toNullInt(info.event?.extendedProps?.statu_id) ?? 1, // fallback 1

                descricao: info.event?.extendedProps?.descricao || '',
                liberado: info.event?.extendedProps?.liberado ?? false
            };

            $.ajax({
                url: "<?= $this->Url->build(['controller' => 'Agendamentos', 'action' => 'novo'], ['fullBase' => true]); ?>",
        type: "POST",
            data: dados,

                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', '<?= $this->request->getAttribute('csrfToken') ?>');

                },


        success: function (response) {

            alert(response.success);

            if (response.success === true) {

                Swal.fire({
                    title: 'Agendamento',
                    text: response.message ?? "Agendamento realizado com sucesso!",
                    icon: "success"
                });

            } else if (response.error_code == 2) {

                Swal.fire({
                    icon: "error",
                    title: "Horário indisponível",
                    text: response.message
                });

                if (typeof info.revert === 'function') info.revert();

            } else {

                Swal.fire({
                    icon: "error",
                    title: "Erro",
                    text: response.message ?? "Erro ao realizar agendamento."
                });

                if (typeof info.revert === 'function') info.revert();
            }
        },
        error: function (xhr) {
            console.log("XHR ERROR:", xhr.responseText);
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ocorreu um erro ao tentar agendar"
            });
            if (typeof info.revert === 'function') info.revert();
        }
    });
        };
             */

    var salvarEventAgendamento = function (info) {

        var current_datetime_start = info.event?.start || info.startStr;
        
        var current_datetime_end = info.event?.end || info.endStr;

        let data_inicio = moment(current_datetime_start).format('YYYY-MM-DD');
        let hora_inicio = moment(current_datetime_start).format('HH:mm');
        let hora_fim = moment(current_datetime_end).format('HH:mm');

         alert('info:', info);

        let dados = {
            agendamento_id: toNullInt(info.event?.id),
            titulo: info.event?.title || '',
            data_inicio: data_inicio,
            hora_inicio: hora_inicio,
            hora_fim: hora_fim,

            cliente_id: toNullInt(info.event?.extendedProps?.cliente_id),
            profissional_id: toNullInt(info.event?.extendedProps?.profissional_id),
            servico_id: toNullInt(info.event?.extendedProps?.servico_id),
            local_id: toNullInt(info.event?.extendedProps?.local_id),
            convenio_id: toNullInt(info.event?.extendedProps?.convenio_id),
            statu_id: toNullInt(info.event?.extendedProps?.statu_id) ?? 1,

            descricao: info.event?.extendedProps?.descricao || '',
            liberado: info.event?.extendedProps?.liberado ?? false,
            _csrfToken: "<?= $this->request->getAttribute('csrfToken') ?>"
        };

        $.ajax({
            url: "<?= $this->Url->build(['controller' => 'Agendamentos', 'action' => 'novo'], ['fullBase' => true]); ?>",
            type: "POST",
            data: dados,

            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', '<?= $this->request->getAttribute('csrfToken') ?>');
            },

            success: function (responseRaw) {

                let result;

                // 🔍 Parse seguro
                try {
                    result = typeof responseRaw === "string" ? JSON.parse(responseRaw) : responseRaw;
                } catch (e) {
                    Swal.fire("Oops...", "Retorno inválido do servidor.", "error");
                    if (info.revert) info.revert();
                    return;
                }

                //
                // ✅ SUCESSO NORMAL
                //
                if (result.success === true) {

                    Swal.fire({
                        title: 'Agendamento',
                        text: result.message || "Agendamento realizado com sucesso!",
                        icon: "success"
                    });

                    return;
                }

                //
                // ⚠️ HORÁRIO INDISPONÍVEL — error_code = 2
                //
                if (result.error_code === 2) {

                    Swal.fire({
                        title: 'Horário Indisponível',
                        text: result.message || "Este horário já está ocupado.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Agendar Mesmo Assim"
                    }).then(dialog => {

                        if (dialog.isConfirmed) {

                            // Reenvia com liberado=true
                            let dados2 = { ...dados, liberado: "true" };

                            $.ajax({
                                url: "<?= $this->Url->build(['controller' => 'Agendamentos', 'action' => 'novo'], ['fullBase' => true]); ?>",
                                type: "POST",
                                data: dados2,
                                success: function (r2) {

                                    if (r2.success) {
                                        Swal.fire("Pronto!", "Agendamento confirmado com sucesso.", "success");
                                    } else {
                                        Swal.fire("Erro", r2.message ?? "Erro ao confirmar agendamento.", "error");
                                        if (info.revert) info.revert();
                                    }
                                },
                                error: function () {
                                    Swal.fire("Erro", "Falha ao reenviar o agendamento.", "error");
                                    if (info.revert) info.revert();
                                }
                            });

                        } else {
                            if (info.revert) info.revert();
                        }

                    });

                    return;
                }

                //
                // ❌ ERROS DO MODEL / VALIDATION DO CAKEPHP
                //
                if (!result.success && result.errors) {

                    let mensagens = [];

                    Object.keys(result.errors).forEach(field => {
                        let msgs = Object.values(result.errors[field]).join("<br>");
                        mensagens.push(`<b>${field}</b>: ${msgs}`);
                    });

                    Swal.fire({
                        icon: "error",
                        title: "Erros no preenchimento",
                        html: mensagens.join("<br><br>")
                    });

                    if (info.revert) info.revert();
                    return;
                }

                //
                // ❗ OUTRO ERRO QUALQUER
                //
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: result.message ?? "Erro ao realizar agendamento."
                });

                if (info.revert) info.revert();
            },

            error: function (xhr) {
                console.log("XHR ERROR:", xhr.responseText);
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Ocorreu um erro ao tentar agendar."
                });
                if (info.revert) info.revert();
            }
        });
    };



    var calendarSelect = function (evento, info) {

      
        $('.btn-salvar-agendamento').prop('disabled', false);
        document.getElementById("frmAgendarConsulta").reset();
        // limpa sugestões e retorno
        $("#lista-sugestoes").html("");
        $("#lista-retorno").html("");

        // esconde as caixas
        $("#box-sugestoes").hide();
        $("#box-retorno").hide();

        // desmarca checkbox de retorno (se existir)
        $("#chkRetorno").prop("checked", false);

        let formAgendar = document.getElementById("frmAgendarConsulta");

        var current_datetime_start = info.event?.start || info.startStr;
        var current_datetime_end = info.event?.end || info.endStr;
        //alert(current_datetime_start);

        let data_inicio = moment(current_datetime_start).format('YYYY-MM-DD');
        let hora_inicio = moment(current_datetime_start).format('HH:mm');
        let hora_fim = moment(current_datetime_end).format('HH:mm');

        //alert(info.event.id);
        let dados = {
            //id: info.event?.id || '',
            //id: info.event?.extendedProps?.id || '',
            id: Number.isInteger(Number(info.event?.id)) ? Number(info.event.id) : '',
            titulo: info.event?.title || '',
            data_inicio: data_inicio,
            hora_inicio: hora_inicio,
            hora_fim: hora_fim,
            cliente_id: info.event?.extendedProps?.cliente_id || '',
            profissional_id: info.event?.extendedProps?.profissional_id || '',
            servico_id: info.event?.extendedProps?.servico_id || '',
            local_id: info.event?.extendedProps?.local_id || '',
            convenio_id: info.event?.extendedProps?.convenio_id || '',
            statu_id: info.event?.extendedProps?.statu_id || 1,
            descricao: info.event?.extendedProps?.descricao || '',
        };

        for (let campo in dados) {
            if (campo === 'statu_id') {
                // marcar o radio correspondente
                let radios = formAgendar.querySelectorAll(`input[name="statu_id"]`);
                radios.forEach(radio => {
                    radio.checked = (radio.value == dados[campo]);
                });
            } else if (formAgendar.elements[campo]) {
                formAgendar.elements[campo].value = dados[campo];
            }
        }

        if (evento === 'eventClick' || evento === 'select')
            mdlAgendamento.show();
    };

    var calendarBuscar = function () {
        mdlBusca.show();
    };

    var calendarFiltrar = function () {
        mdlFiltrar.show();
    };

    var calendarAbrir = function () {
        $('#dataPickerHidden').datepicker('show');
    };

    var calendarEventClick = function (info) {
        mdlAgendamento.show();
    };

    // CRIA O CALENDÁRIO


    function carregarEventos(fetchInfo, successCallback, failureCallback) {

        var url = '<?= $this->Url->build(["controller" => "Agendamentos", "action" => "view"], ["fullBase" => true]); ?>';

        // parâmetros do formulário "buscar"
        let buscar = {
            profissional: $('#buscar-profissional').val() || '',
            servico: $('#buscar-servicos').val() || '',
            convenio: $('#buscar-convenios').val() || '',
            local: $('#buscar-local').val() || '',
            titulo: $('#buscar-titulo').val() || '',
            status: $('.status:checkbox:checked')
                .map(function () { return $(this).val(); })
                .get()
                .join(',')
        };

        // parâmetros do formulário "filtrar"
        let filtrar = {
            profissional: $('#filtrar-profissional').val() || '',
            servico: $('#filtrar-servico').val() || '',
            convenio: $('#filtrar-convenio').val() || '',
            local: $('#filtrar-local').val() || '',
            titulo: $('#filtrar-titulo').val() || ''
        };

        // Verifica qual formulário está sendo usado: buscar ou filtrar
        let ativo = Object.values(buscar).some(v => v) ? buscar : filtrar;

        let params = {
            start: fetchInfo.startStr,
            end: fetchInfo.endStr,
            ...ativo,
            _ts: Date.now() // evita cache
        };

        fetch(url + '?' + new URLSearchParams(params))
            .then(response => response.json())
            .then(json => {
                // Se a API retorna { agenda: [...] }
                //successCallback(json.agenda || []);
                successCallback(json || []);
            })
            .catch(error => {
                console.error("Erro ao buscar eventos", error);
                failureCallback(error);
            });
    }
    
    //CALENDARIO

    var calendar = new FullCalendar.Calendar(calendarEl, {
        selectable: true,
        locale: 'pt-br',
        timeZone: 'local',
        displayEventTime: true,
        slotLabelFormat: [
            { hour: 'numeric', minute: '2-digit', meridiem: false, hour12: false },
            { weekday: 'long' }
        ],
        slotDuration: '<?= gmdate("H:i:s", $segundos); ?>',
        snapDuration: '<?= gmdate("H:i:s", $segundos); ?>',
        slotLabelInterval: '<?= gmdate("H:i:s", $segundos); ?>',
        slotMinTime: '06:00',
        slotMaxTime: '22:00',
        height: checkWidowWidth() ? 900 : 1052,
        initialView: checkWidowWidth() ? "listWeek" : "timeGridDay",
        nowIndicator: true,
        now: new Date(),
        navLinks: true,
        editable: true,
        allDaySlot: false,
        dayMaxEvents: 4,
        weekNumbers: false,
        selectLongPressDelay: 700,
        eventDisplay: 'block',
        headerToolbar: calendarHeaderToolbar,

        /* seus botões personalizados (mantidos) */
        customButtons: {
            buscar: {
                text: "",
                click: calendarBuscar,
            },
            calendario: {
                text: '',
                click: calendarAbrir
            },
            filtro: {
                text: "",
                click: calendarFiltrar,
            }
        },



        /* Substitua seu bloco events: { ... } por ESTE (debug + compatibilidade) */
        events: function (fetchInfo, successCallback, failureCallback) {
            carregarEventos(fetchInfo, successCallback, failureCallback);
        },

        
        /** Eventos de interação (mantidos) 
        eventClick: function (info) {
           
            if (info.event.extendedProps && info.event.extendedProps.tipo === 'disponivel') {
                calendarSelect("eventClick", info);
            } else {
                // para eventos normais também abre (se desejar)
                calendarSelect("eventClick", info);
            }
       },*/
       eventClick: (info) => calendarSelect("eventClick", info),

       /**  select: function (info) {
            
            alert('HORÁRIO INDISPONIVEL');
            
        },**/

        select: (info) => calendarSelect("select", info),

        /**eventResize: function (info) {
            salvarEventAgendamento(info);
        },*/
        eventResize: (info) => salvarEventAgendamento(info),

        //eventDrop: function (info) {
          //  salvarEventAgendamento(info);},
          eventDrop: (info) => salvarEventAgendamento(info),

        windowResize: function () {
            if (checkWidowWidth()) {
                calendar.changeView("listWeek");
                calendar.setOption("height", 900);
            } else {
                calendar.changeView("dayGridMonth");
                calendar.setOption("height", 1052);
            }
        }
  
    });

    /* ========== Event Source ADICIONAL para carregar os horários disponíveis corretamente ========== */
    // Mantém sua arquitetura (events.url é o principal) — aqui adicionamos separadamente os "disponiveis"
    calendar.addEventSource(function (fetchInfo, successCallback, failureCallback) {
        // montar filtros iguais aos que você já usa
        try {
            var profissional = $('#filtrar-profissional').val() || '';
            var servico = $('#filtrar-servico').val() || '';
            var convenio = $('#filtrar-convenio').val() || '';
            var local = $('#filtrar-local').val() || '';

            // garantir datas em formato YYYY-MM-DD
            var dataInicio = fetchInfo.startStr ? fetchInfo.startStr.split('T')[0] : new Date().toISOString().split('T')[0];
            var dataFim = fetchInfo.endStr ? fetchInfo.endStr.split('T')[0] : dataInicio;

            var fd = new FormData();
            fd.append('profissional_id', profissional);
            fd.append('servico_id', servico);
            fd.append('convenio_id', convenio);
            fd.append('local_id', local);
            fd.append('data_atual', dataInicio);
            fd.append('data_fim', dataFim);

            fetch('<?= $this->Url->build(["controller" => "Agendamentos", "action" => "getDisponiveis"], ["fullBase" => true]) ?>', {
                method: 'POST',
                body: fd,
                headers: { 'X-CSRF-Token': $('meta[name="csrfToken"]').attr('content') }
            })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    var eventosDisponiveis = [];
                    if (data && data.success && Array.isArray(data.horarios)) {

                        eventosDisponiveis = data.horarios.map(function (h, idx) {

                            // supondo h.dia (YYYY-MM-DD), h.horario_inicio (HH:mm) e h.horario_fim (HH:mm)
                            return {
                                id: 'disp-' + (h.id || idx) + '-' + h.dia + '-' + h.horario_inicio,
                                title: '', // você usava title vazio
                                start: (h.dia + 'T' + (h.horario_inicio.length === 5 ? h.horario_inicio : h.horario_inicio.substring(0, 5)) + ':00'),
                                end: (h.dia + 'T' + (h.horario_fim.length === 5 ? h.horario_fim : h.horario_fim.substring(0, 5)) + ':00'),


                                className: 'fc-event-disponivel',
                                extendedProps: Object.assign({}, h, { tipo: 'disponivel' })
                            };
                        });

                    }
                    // retorna apenas os disponíveis — o FullCalendar juntará com a outra fonte (events.url)
                    successCallback(eventosDisponiveis);
                })
                .catch(function (err) {
                    console.error('Erro ao carregar horários disponíveis:', err);
                    failureCallback(err);
                });
        } catch (e) {
            console.error(e);
            failureCallback(e);
        }
    });

    /* ========== Render e pós-render (ícones, inputs, datepicker) ========== */
    // DEFININDO O MODAL
    var mdlAgendamento = new bootstrap.Modal(document.getElementById("mdlAgendamento"));
    var mdlBusca = new bootstrap.Modal(document.getElementById("mdlBusca"));
    var mdlFiltrar = new bootstrap.Modal(document.getElementById("mdlFiltrar"));

    calendar.render();

    // adiciona input escondido (como você já fazia)
    const $input = $('<input>', { type: 'hidden', id: 'dataPickerHidden' }).appendTo('.fc-calendario-button');

    // re-adiciona os ícones/ svg nos botões (mantive exatamente seus svgs)
    $('.fc-filtro-button').append('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.36 20.213L9 21v-8.5L4.52 7.572A2 2 0 0 1 4 6.227V4h16v2.172a2 2 0 0 1-.586 1.414L15 12m0 6a3 3 0 1 0 6 0a3 3 0 1 0-6 0m5.2 2.2L22 22"/></svg>');
    $('.fc-buscar-button').append('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m19.6 21l-6.3-6.3q-.75.6-1.725.95T9.5 16q-2.725 0-4.612-1.888T3 9.5t1.888-4.612T9.5 3t4.613 1.888T16 9.5q0 1.1-.35 2.075T14.7 13.3l6.3 6.3zM9.5 14q1.875 0 3.188-1.312T14 9.5t-1.312-3.187T9.5 5T6.313 6.313T5 9.5t1.313 3.188T9.5 14"/></svg>');
    $('.fc-calendario-button').append('<svg id="calendario" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 128 128"><path fill="#fafafa" stroke="#bdbdbd" stroke-miterlimit="10" stroke-width="3" d="M111.42 113.34H16.58a4.88 4.88 0 0 1-4.88-4.88V42.03c0-7.27 5.65-13.16 12.62-13.16h79.37c6.97 0 12.62 5.89 12.62 13.16v66.43c0 2.7-2.19 4.88-4.89 4.88z"/><path fill="#f44336" d="M112.11 4h-4.8c-1 0-1.81.81-1.81 1.81V9.9c0 .67.38 1.25.95 1.6c2.01 1.2 3.24 3.57 2.71 6.17c-.45 2.2-2.21 3.98-4.41 4.44a5.788 5.788 0 0 1-7.03-5.65c0-2.12 1.14-3.97 2.85-4.97c.57-.34.94-.92.94-1.58v-4.1c0-1-.81-1.81-1.81-1.81H28.27c-.98 0-1.77.79-1.77 1.77v4.16c0 .65.37 1.22.93 1.55a5.78 5.78 0 0 1 2.73 6.18c-.45 2.2-2.21 3.98-4.41 4.44a5.788 5.788 0 0 1-7.03-5.65c0-2.13 1.16-3.98 2.87-4.99c.55-.32.91-.9.91-1.54V5.77c0-.98-.79-1.77-1.77-1.77h-4.85a5.75 5.75 0 0 0-5.75 5.75v37.52H117.7l.16-37.49A5.745 5.745 0 0 0 112.11 4"/><text id="calendar-day" fill="#333" font-weight="700" font-family="Roboto, Arial, sans-serif" font-size="50" text-anchor="middle" y="99" x="62.99506">31</text><text id="calendar-month" dominant-baseline="middle" fill="#fff" font-weight="700" font-family="Roboto, Arial, sans-serif" font-size="30" text-anchor="middle" y="30" x="62.99505">OUT</text></svg>');

    // botões externos que já existiam
    $('#buscar').click(function () {
        calendar.refetchEvents();
        mdlBusca.hide();
    });

    $('#limpar').click(function () {
        limpar();
    });

    $('#dataPickerHidden').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        numberOfMonths: 1,
        onSelect: function (dateText) { calendar.gotoDate(dateText); }
    });

    function buscar() {
        // sua função vazia mantida
    }

    function limpar() {
        // como você já tinha: limpa selects e título
        try {
            selectBuscarProfissional._bsSelect.clear();
            selectBuscarProfissional._bsSelect.clear();
            selectBuscarServicos._bsSelect.clear();
            selectBuscarConvenios._bsSelect.clear();
            selectBuscarLocais._bsSelect.clear();
        } catch (e) {
            // se alguma referência não existir, não quebra
        }
        try { buscarTitulo.value = ''; } catch (e) { }
    }

    /*=====================
     Função salvar()
     =====================*/
    function salvar(response, statusText, xhr, $form) {
        let result;

        //
        // 🔍 TENTAR PARSEAR JSON
        //
        try {
            result = typeof response === 'string' ? JSON.parse(response) : response;
        } catch (e) {

            $('.btn-salvar-agendamento').prop('disabled', false);
            Swal.fire("Oops...", "Retorno inválido do servidor.", "error");
            return;
        }

        //
        // 🔄 LIMPA ERROS ANTERIORES DO FORM
        //
        $form.find(".is-invalid").removeClass("is-invalid");
        $form.find(".invalid-feedback").remove();

        //
        // ✅ SUCESSO NORMAL
        //
        if (result.success === true) {

            Swal.fire({
                title: 'Agendamento',
                text: result.message || "Agendamento realizado com sucesso!",
                icon: "success"
            });

            return finalizarSalvamento();
        }

        //
        // ⚠️ HORÁRIO INDISPONÍVEL — error_code = 2
        //
        if (result.error_code === 2) {

            Swal.fire({
                title: 'Atenção',
                text: result.message || "Horário indisponível",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Agendar Mesmo Assim"
            }).then(dialog => {

                if (dialog.isConfirmed) {

                    let dados = $("#frmAgendarConsulta").serializeArray();
                    dados.push({ name: "liberado", value: "true" });

                    console.log("REENVIANDO COM LIBERADO=TRUE:", dados);

                    $.ajax({
                        url: '<?= $this->Url->build(["controller" => "Agendamentos", "action" => "novo"], ["fullBase" => true]); ?>',
                        type: "POST",
                        data: dados,
                        success: function (r2) {



                            if (r2.success) {
                                Swal.fire(
                                    "Pronto!",
                                    "Agendamento confirmado com sucesso.",
                                    "success"
                                );

                                finalizarSalvamento();
                            } else {
                                Swal.fire("Erro", r2.message ?? "Erro ao confirmar agendamento.", "error");
                            }
                        },
                        error: function (err) {
                            console.error(err);
                            Swal.fire("Erro", "Falha ao reenviar o agendamento.", "error");
                        }
                    });

                } else {
                    finalizarSalvamento();
                }

            });

            return;
        }

        //
        // ❌ ERROS DO MODEL / VALIDATIONS DO CAKEPHP
        //
        if (!result.success && result.errors) {

            Object.keys(result.errors).forEach(field => {
                let input = $form.find(`[name="${field}"]`);
                if (input.length) {

                    input.addClass("is-invalid");

                    let messages = Object.values(result.errors[field]).join("<br>");

                    let group = input.closest('.input-group');

                    if (group.length)
                        group.after(`<div class="invalid-feedback d-block">${messages}</div>`);
                    else
                        input.after(`<div class="invalid-feedback">${messages}</div>`);
                }
            });
            $('.btn-salvar-agendamento').prop('disabled', false);
            return;
        }

        //
        // ❗ OUTRO ERRO QUALQUER
        //
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: result.message ?? "Erro ao realizar agendamento."
        });

        finalizarSalvamento();
    }

    function finalizarSalvamento() {
        mdlAgendamento.hide();
        calendar.refetchEvents();
        $('.btn-salvar-agendamento').prop('disabled', false);
    }

    /*=====================
     Submit do formulário AJAX
     =====================*/
    $('#frmAgendarConsulta').on('submit', function (e) {
        e.preventDefault();

        var options = {
            type: 'post',
            url: '<?= $this->Url->build(["controller" => "Agendamentos", "action" => "novo"], ["fullBase" => true]); ?>',
            success: salvar,
        };

        $(this).ajaxSubmit(options);
        return false;
    });





    /*=====================
     Botão salvar manual
     =====================*/
    if (salvarAgendamento) {
        salvarAgendamento.addEventListener("click", function () {
            $('.btn-salvar-agendamento').prop('disabled', true);
            $('#frmAgendarConsulta').submit();
        });
    }

    (function () {
        const hoje = new Date();
        const dia = hoje.getDate();
        const meses = ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"];
        const mes = meses[hoje.getMonth()];
        const dayEl = document.getElementById("calendar-day");
        if (dayEl) dayEl.textContent = dia;
        const monthEl = document.getElementById("calendar-month");
        if (monthEl) monthEl.textContent = mes;
    })();

    const selectBuscarProfissional = document.querySelector('#buscar-profissional');
    const selectBuscarServicos = document.querySelector('#buscar-servicos');
    const selectBuscarConvenios = document.querySelector('#buscar-convenios');
    const selectBuscarLocais = document.querySelector('#buscar-local');
    const buscarTitulo = document.querySelector('#buscar-titulo');

    // ===================== BOTÃO APLICAR FILTROS =====================
    document.getElementById("btnAplicarFiltros")?.addEventListener("click", function () {
        mdlFiltrar.hide(); // fecha o modal
        calendar.refetchEvents(); // recarrega os eventos com os filtros aplicados
    });

    // ===================== BOTÃO LIMPAR FILTROS =====================
    document.getElementById("btnLimparFiltros")?.addEventListener("click", function () {
        document.getElementById("filtrar-profissional").value = "";
        document.getElementById("filtrar-servico").value = "";
        document.getElementById("filtrar-convenio").value = "";
        document.getElementById("filtrar-local").value = "";

        mdlFiltrar.hide(); // fecha o modal
        calendar.refetchEvents(); // recarrega sem filtros
    });

    });
</script>


<script>
    $.datepicker.setDefaults($.datepicker.regional['pt-BR']);
    $(function () {

        var ac_config_cliente = {
            source: function (request, response) {
                $.getJSON("<?= $this->Url->build(['controller' => 'Clientes', 'action' => 'localizar'], ['fullBase' => true]); ?>?termo=" + request.term, function (data) {
                    response($.map(data, function (value, key) {

                        return {
                            id: value.id,
                            value: value.label,
                            aniversario: value.aniversario
                        };

                    }));
                });
            },
            select: function (event, ui) {

                $('#cliente-id').val(ui.item.id);
            },
            autoFocus: true,
            minLength: 1

        };

        $.ui.autocomplete.prototype._renderMenu = function (ul, items) {
            var self = this;
            //table definitions
            $.each(items, function (index, item) {
                self._renderItemData(ul, item);
            });
        };

        $.ui.autocomplete.prototype._renderItemData = function (ul, item) {
            return this._renderItem(ul, item).data("ui-autocomplete-item", item);
        };

        $.ui.autocomplete.prototype._renderItem = function (ul, item) {

            var avatar = '<?= $this->Url->build('/img/profile/', ['fullBase' => true]); ?>usuario-padrao.png';

            if (item.avatar != null)
                avatar = '<?= $this->Url->build('/img/', ['fullBase' => true]); ?>' + item.avatar;

            var aniversario = '';
            var felicitacoes = '';

            if (item.aniversario) {
                felicitacoes = 'Feliz aniversário!!!';
                aniversario = 'aniversario';
            }


            return $("<li></li>")
                .data("item.autocomplete", item)
                .append("<table class='" + aniversario + "' style='width:100%;'><tr><td style='width: 20%; padding-left: 5px; padding-top: 5px; padding-bottom: 5px;'><img class='rounded-circle' width='64' height='64' src='" + avatar + "'></td><td style='text-align: left;'>" + item.value + "<br>" + felicitacoes + "</td></tr></table>")
                .appendTo(ul);
        };


        $("#titulo").autocomplete(ac_config_cliente);
    });

    document.addEventListener("show.bs.modal", function (event) {
        const opened = document.querySelectorAll('.modal.show').length;

        if (opened > 0) {
            event.target.classList.add('modal-stack');

            setTimeout(() => {
                const backdrops = document.querySelectorAll('.modal-backdrop');
                const lastBackdrop = backdrops[backdrops.length - 1]; // pega o último
                if (lastBackdrop) {
                    lastBackdrop.classList.add('modal-stack');
                }
            }, 10);
        }
    });





    //sugestões de horários

    $(document).ready(function () {

        $('#mdlAgendamento').on('shown.bs.modal', function () { });

        function carregarSugestoes() {

            let isRetorno = $("#chkRetorno").is(":checked");

            let params = {
                p_data_agendamento: $('input[name="data_inicio"]').val(),
                p_hora_agendamento: $('input[name="hora_inicio"]').val(),
                p_tipo: isRetorno ? 'retorno' : 'recorrencia',
                p_periodo: isRetorno ? null : $('select[name="peridos"]').val(),
                p_qtd_recorrencia: isRetorno ? null : $('input[name="recorrencia"]').val(),
                p_estabelecimento_id: <?= $estabelecimento->id ?>,
                p_profissional_id: $('select[name="profissional_id"]').val(),
                p_servico_id: $('select[name="servico_id"]').val(),
                p_local_id: $('select[name="local_id"]').val(),
                p_convenio_id: $('select[name="convenio_id"]').val()
            };


            /** ------------------------------
             * 1) TRATAMENTO IMEDIATO DOS BOXES
             * ------------------------------ */

            // Se desmarcar retorno → esconder + limpar imediatamente
            if (!isRetorno) {
                $("#box-retorno").hide();
                $("#lista-retorno").html("");
            }

            // Recorrência: se período ou quantidade vazios → esconder só recorrência
            if (!isRetorno && (!params.p_periodo || !params.p_qtd_recorrencia)) {
                $("#box-sugestoes").hide();
                $("#lista-sugestoes").html("");
                return; // NÃO CHAMA O AJAX
            }


            /** ------------------------------
             * 2) CHAMADA AJAX NORMAL
             * ------------------------------ */
            $.ajax({
                url: '/painel/agendamentos/sugestoes',
                method: 'GET',
                data: params,

                beforeSend: function () {
                    if (isRetorno) {
                        $("#box-retorno").show();
                        $("#lista-retorno").html(`
                        <div class="d-flex justify-content-center py-3">
                            <div class="spinner-border text-primary" role="status" style="width: 2.2rem; height: 2.2rem;">
                                <span class="visually-hidden">Carregando...</span>
                            </div>
                        </div>`);
                    } else {
                        $("#box-sugestoes").show();
                        $("#lista-sugestoes").html(`
                        <div class="d-flex justify-content-center py-3">
                            <div class="spinner-border text-primary" role="status" style="width: 2.2rem; height: 2.2rem;">
                                <span class="visually-hidden">Carregando...</span>
                            </div>
                        </div>
                    `);
                    }
                },

                dataType: "json",

                success: function (res) {

                    let tabela = `
                                    <div class="d-flex justify-content-center" style="padding-top: 15px;">

                                        <table class="table table-sm w-100 spike-table"
                                            style="
                                                max-width: 100%; 
                                                background:#fff; 
                                                border-radius:16px; 
                                                overflow:hidden; 
                                                box-shadow:0 3px 10px rgba(0,0,0,0.06);
                                            ">

                                            <thead 
                                                style="
                                                    background:#f4f6f9;
                                                    color:#6c757d;
                                                    font-weight:400;
                                                    border-bottom:1px solid #eceff3;
                                                ">
                                                <tr class="text-center">
                                                    <th style="padding:12px; font-size:14px; font-weight:400;">Dia da Semana</th>
                                                    <th style="padding:12px; font-size:14px; font-weight:400;">Atendimento</th>
                                                    <th style="padding:12px; font-size:14px; font-weight:400;">Data</th>
                                                    <th style="padding:12px; font-size:14px; font-weight:400;">Hora</th>
                                                </tr>
                                            </thead>

                                            <tbody class="spike-body">
                                    `;


                    let inputsHidden = "";

                    res.horarios.forEach((item, index) => {

                        const partes = item.dia.split("-");
                        const dataFormatada = `${partes[2]}/${partes[1]}/${partes[0]}`;

                        tabela += `
                                <tr class="text-center spike-row">
                                    <td style="padding:10px 6px;">${item.dia_semana_nome}</td>
                                    <td style="padding:10px 6px;">${isRetorno ? 'Retorno' : 'Sugestão'}</td>
                                    <td style="padding:10px 6px;">${dataFormatada}</td>
                                    <td style="padding:10px 6px;">${item.horario_inicio}</td>
                                </tr>
                        `;

                        if (isRetorno) {
                            inputsHidden += `
                        <input type="hidden" name="retorno[data_inicio]" value="${item.dia}">
                        <input type="hidden" name="retorno[hora_inicio]" value="${item.horario_inicio}">
                        <input type="hidden" name="retorno[hora_fim]" value="${item.horario_fim}">
                    `;
                        } else {
                            inputsHidden += `
                        <input type="hidden" name="agenda_recorrente[${index}][data_inicio]" value="${item.dia}">
                        <input type="hidden" name="agenda_recorrente[${index}][hora_inicio]" value="${item.horario_inicio}">
                        <input type="hidden" name="agenda_recorrente[${index}][hora_fim]" value="${item.horario_fim}">
                    `;
                        }
                    });

                    tabela += `
                    </tbody>
                </table>
            </div>
            `;

                    if (isRetorno) {
                        $("#lista-retorno").html(tabela + inputsHidden);
                    } else {
                        $("#lista-sugestoes").html(tabela + inputsHidden);
                    }
                },

                error: function () {
                    if (isRetorno)
                        $("#lista-retorno").html("Erro ao obter sugestão de retorno.");
                    else
                        $("#lista-sugestoes").html("Erro ao obter sugestões.");
                }
            });
        }

        // Dispara ao alterar qualquer coisa
        $(
            "input[name='recorrencia'], " +
            "select[name='peridos'], " +
            "input[name='data_inicio'], " +
            "input[name='hora_inicio'], " +
            "select[name='profissional_id'], " +
            "select[name='servico_id'], " +
            "select[name='local_id'], " +
            "select[name='convenio_id'], " +
            "#chkRetorno"
        ).on('change', function () {
            carregarSugestoes();
        });

    });








</script>



<style>
    .fc-today-button {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
        color: var(--bs-white);
        font-weight: 500;
    }

    .fc-time-grid-event.fc-short .fc-title {
        font-size: 12px;
    }

    .fc-time-grid-event .fc-time {
        font-size: 12px;
    }

    .fc .fc-event {
        font-size: 12px;
        border-left: 5px solid;
        border-top: none;
        border-right: none;
        border-bottom: none;
    }

    .fc .fc-event {
        padding: .2rem;
    }

    .fc-ltr .fc-time-grid .fc-event-container {
        margin-top: -5px;
    }

    .fc .fc-event .fc-title,
    .fc .fc-event .fc-time {
        color: #000000;
    }

    .fc-event,
    .fc-event:hover {
        color: #000000;
    }

    .fc-future,
    .fc-today {
        background-color: #ffffff;
    }

    .fc-event-main-frame {
        color: #000000;
    }

    .fc-h-event .fc-event-main-frame {
        display: block;
    }

    .fc .fc-non-business {
        background: rgba(128, 128, 128, .6);
        background: var(--fc-non-business-color, rgba(128, 128, 128, .6));
    }

    .app-calendar .fc-event-title {
        font-weight: 400;
    }

    .modal-stack {
        z-index: 1060 !important;
        /* acima do modal normal */
    }

    .modal-backdrop.modal-stack {
        z-index: 1055 !important;
        /* fica logo abaixo do modal-stack */
    }

    .ui-widget-content {
        background-color: var(--bs-gray-300);
    }

    .ui-autocomplete {
        z-index: 1070;
        /* Or a higher value if needed */
    }

    .form-check-input {
        width: 1.525em;
        height: 1.525em;
    }

    .form-check-label {
        padding-top: 3px;
        padding-left: 5px;
    }

    .fc table {
        font-family: arial;
    }

    <?php foreach ($status as $dadosStatus) { ?>

        .form-check-<?= str_replace(" ", "-", strtolower($dadosStatus->nome)) ?> {
            border-color:
                <?= $dadosStatus->cor ?>
            ;
            border: 4px solid
                <?= $dadosStatus->cor ?>
            ;
            !important;
        }

        .form-check-<?= str_replace(" ", "-", strtolower($dadosStatus->nome)) ?>:checked {
            background-color:
                <?= $dadosStatus->cor ?>
            ;
            border-color:
                <?= $dadosStatus->cor ?>
            ;
        }

    <?php } ?>
    #genericModal {
        z-index: 1200;
    }

    .swal2-container {
        z-index: 1100;
    }

    .form-control-lg {
        border-radius: .5rem;
        padding: 9px 16px !important;
        font-size: 1.125rem !important;
    }

    .form-control::placeholder {
        color: #d0d0d0;
        /* cinza bem claro */
        opacity: 1;
        /* força a cor */
    }

    .ui-state-highlight {
        height: auto !important;
        margin-bottom: auto !important;
        ;
        border-radius: 0 !important;
        ;
    }



    /* coloque NO FINAL do arquivo (depois de todos os CSS do tema) */

    #calendar .fc-event-disponivel,
    #calendar .fc-event-disponivel .fc-event-main-frame,
    #calendar .fc-event-disponivel .fc-event-main,
    #calendar .fc-event-disponivel .fc-event-title,
    #calendar .fc-event-disponivel .fc-event-time,
    #calendar .fc-event-disponivel .fc-event-title-container {
        background-color: #DEFCF3 !important;
        border-color: #69D18B !important;
        /* força o texto em TODAS as peças internas possíveis */
        color: #69D18B !important;
        fill: #69D18B !important;
        /* caso haja ícones/SVGs internos */
        -webkit-text-fill-color: #69D18B !important;
        /* Safari */
    }

    /* hover só altera o fundo */
    #calendar .fc-event-disponivel:hover,
    #calendar .fc-event-disponivel:hover .fc-event-main-frame,
    #calendar .fc-event-disponivel:hover .fc-event-main {
        background-color: #F1F5F9 !important;
    }
</style>
<?php

/**
 * @var \App\View\AppView $this
 * @var array $agendamentos
 */

?>

<!-- CSS do FullCalendar -->
<?= $this->Html->css('https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css') ?>
<?= $this->Html->css('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css') ?>
<?= $this->Html->css('https://bootstrapdemos.wrappixel.com/spike/dist/assets/css/styles.css') ?>


<!-- Custom CSS estilo Spike para calendar2 -->
<style>
/* Container do calendário */
#calendar2 {
    min-height: 600px;
    background-color: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.08);
    padding: 10px;
}

/* Cabeçalho */
#calendar2 .fc-header-toolbar {
    background: #ffffff;
    border-bottom: 1px solid #dee2e6;
    border-radius: 8px 8px 0 0;
    padding: 0.5rem;
}

#calendar2 .fc-toolbar-title {
    font-weight: 600;
    font-size: 1.25rem;
}

#calendar2 .fc-button {
    background-color: #fff;
    color: #495057;
    border: 1px solid #ced4da;
    border-radius: 4px;
    box-shadow: none;
}

#calendar2 .fc-button:hover {
    background-color: #e9ecef;
}

/* Slots de horário */
#calendar2 .fc-timegrid-slot {
    border-top: 1px solid #dee2e6;
}

#calendar2 .fc-timegrid-now-indicator {
    background-color: #ffc107;
    height: 2px;
}

/* Eventos */
#calendar2 .fc-event {
    border-radius: 6px;
    font-size: 0.85rem;
    padding: 2px 4px;
}

#calendar2 .fc-event-agendado {
    background-color: #dc3545 !important;
    border-color: #c82333 !important;
    color: #fff;
}

#calendar2 .fc-event-disponivel {
    background-color: #28a745 !important;
    border-color: #218838 !important;
    color: #fff;
}

/* Colunas */
#calendar2 .fc-col-header-cell-cushion {
    font-weight: 600;
}

/* Scroll horizontal para semanas grandes */
#calendar2 .fc-scroller {
    overflow-x: auto;
}

/* Responsivo */
@media (max-width: 1199px) {
    #calendar2 .fc-timeGridWeek-view, 
    #calendar2 .fc-dayGridMonth-view {
        height: 900px;
    }
}
</style>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0"><i class="bi bi-funnel"></i> Filtros de Agendamento</h5>
    </div>
    <div class="card-body">
        <form id="filtrosForm2" class="row g-3">

            <div class="col-md-3">
                <label class="form-label">Profissional</label>
                <select class="form-select" id="filtroProfissional2">
                    <option value="">Todos os profissionais</option>
                    <?php foreach ($profissionais as $id => $nome): ?>
                        <option value="<?= $id ?>"><?= h($nome) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Serviço</label>
                <select class="form-select" id="filtroServico2">
                    <option value="">Todos os serviços</option>
                    <?php foreach ($servicos as $id => $nome): ?>
                        <option value="<?= $id ?>"><?= h($nome) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Local</label>
                <select class="form-select" id="filtroLocal2">
                    <option value="">Todos os locais</option>
                    <?php foreach ($locais as $id => $nome): ?>
                        <option value="<?= $id ?>"><?= h($nome) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Convênio</label>
                <select class="form-select" id="filtroConvenio2">
                    <option value="">Todos os convênios</option>
                    <?php foreach ($convenios as $id => $nome): ?>
                        <option value="<?= h($nome) ?>"><?= h($nome) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12">
                <button type="button" id="btnAplicarFiltros2" class="btn btn-primary"><i class="bi bi-filter"></i> Aplicar Filtros</button>
                <button type="button" id="btnLimparFiltros2" class="btn btn-secondary ms-2"><i class="bi bi-x-circle"></i> Limpar Filtros</button>
                <div id="loading2" class="spinner-border spinner-border-sm text-primary ms-2 d-none" role="status"><span class="visually-hidden">Carregando...</span></div>
            </div>

        </form>
    </div>
</div>

<!-- Calendário -->
<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="card-title mb-0"><i class="bi bi-calendar-event"></i> Calendário de Agendamentos 2</h5>
    </div>
    <div class="card-body">
        <div id="calendar2"></div>
    </div>
</div>

<!-- Debug Info -->
<div class="card mt-4">
    <div class="card-header bg-info text-white">
        <h5 class="card-title mb-0">Debug Info Calendar 2</h5>
    </div>
    <div class="card-body">
        <pre id="debugInfo2" class="bg-light p-3"></pre>
    </div>
</div>

<!-- Scripts FullCalendar -->
<?= $this->Html->script('https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js') ?>
<?= $this->Html->script('https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/pt-br.js') ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let calendar2;
    const debugInfo2 = document.getElementById('debugInfo2');
    const csrfToken = '<?= $this->request->getAttribute('csrfToken') ?>';

    function inicializarCalendario2() {
        const calendarEl = document.getElementById('calendar2');
        if(calendar2) calendar2.destroy();

        calendar2 = new FullCalendar.Calendar(calendarEl, {
            locale: 'pt-br',
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            slotMinTime: '06:00:00',
            slotMaxTime: '22:00:00',
            slotDuration: '00:30:00',
            allDaySlot: false,
            nowIndicator: true,
            editable: false,
            selectable: true,
            eventMaxStack: 4,
            events: function(fetchInfo, successCallback) {
                carregarEventos2(fetchInfo, successCallback);
            },
            eventClick: function(info) {
                if(info.event.extendedProps.tipo === 'disponivel') {
                    alert('Agendar em ' + info.event.start.toLocaleString());
                }
            }
        });

        calendar2.render();
        debugInfo2.textContent = 'Calendário 2 inicializado\n';
    }

    function carregarEventos2(fetchInfo, successCallback) {
        debugInfo2.textContent = 'Carregando eventos...\n';
        const filtros = {
            profissional_id: document.getElementById('filtroProfissional2').value || null,
            servico_id: document.getElementById('filtroServico2').value || null,
            local_id: document.getElementById('filtroLocal2').value || null,
            convenio_id: document.getElementById('filtroConvenio2').value || null
        };
        debugInfo2.textContent += 'Filtros: ' + JSON.stringify(filtros) + '\n';

        document.getElementById('loading2').classList.remove('d-none');

        // Agendamentos PHP
        let eventosAgendados = <?= json_encode($agendamentos) ?>;
 
        if(filtros.profissional_id) {
          
            eventosAgendados = eventosAgendados.filter(ag => ag.profissional_id == filtros.profissional_id);
            //alert('Filtro ',filtros.profissional_id, ' - Agendado ', ag.profissional_id);
        
                
        }
        eventosAgendados = eventosAgendados.map(ag => ({
            title: ag.cliente_nome,
            start: ag.data_hora_inicio,
            end: ag.data_hora_fim,
            className: 'fc-event-agendado',
            extendedProps: { tipo: 'agendado' }
        }));

        // Disponíveis via AJAX
        const hoje = new Date().toISOString().split('T')[0];
        const data_inicio = fetchInfo.startStr.split('T')[0] >= hoje ? fetchInfo.startStr.split('T')[0] : hoje;
        const data_fim = fetchInfo.endStr.split('T')[0];
        const formData = new FormData();
        formData.append('profissional_id', filtros.profissional_id || '');
        formData.append('servico_id', filtros.servico_id || '');
        formData.append('local_id', filtros.local_id || '');
        formData.append('convenio_id', filtros.convenio_id || '');
        formData.append('data_inicio', data_inicio);
        formData.append('data_fim', data_fim);

        fetch('https://app.intelixis.com.br/painel/horarios/getDisponiveis', {
            method: 'POST',
            headers: { 'X-CSRF-Token': csrfToken },
            body: formData,
            credentials: 'same-origin'
        })
        .then(resp => resp.json())
        .then(data => {
            debugInfo2.textContent += 'JSON retornado:\n' + JSON.stringify(data, null, 2) + '\n';
            let eventosDisponiveis = [];
            if(data.success && Array.isArray(data.horarios)) {
                eventosDisponiveis = data.horarios.map(h => ({
                    title: '',
                    start: h.dia + 'T' + h.horario_inicio + ':00',
                    end: h.dia + 'T' + h.horario_fim + ':00',
                    className: 'fc-event-disponivel',
                    extendedProps: { tipo: 'disponivel' }
                }));
            }
            successCallback([...eventosAgendados, ...eventosDisponiveis]);
           
           
            debugInfo2.textContent += 'Horários disponíveis carregados: ' + eventosDisponiveis.length + '\n';
        })
        .catch(err => {
            console.error('Erro:', err);
            debugInfo2.textContent += 'Erro: ' + err + '\n';
            successCallback(eventosAgendados);
        })
        .finally(() => document.getElementById('loading2').classList.add('d-none'));
    }

    document.getElementById('btnAplicarFiltros2').addEventListener('click', () => calendar2.refetchEvents());
    document.getElementById('btnLimparFiltros2').addEventListener('click', () => {
        document.getElementById('filtroProfissional2').value = '';
        document.getElementById('filtroServico2').value = '';
        document.getElementById('filtroLocal2').value = '';
        document.getElementById('filtroConvenio2').value = '';
        calendar2.refetchEvents();
    });

    inicializarCalendario2();
});
</script>

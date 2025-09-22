<?php
/**
 * @var \App\View\AppView $this
 * @var array $agendamentos
 */
?>


<!-- CSS do FullCalendar -->
<?= $this->Html->css('https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css') ?>
<?= $this->Html->css('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css') ?>

<style>
#calendar {
    min-height: 600px;
    background: white;
}
.fc-event {
    cursor: pointer;
    font-size: 0.85rem;
    padding: 2px 4px;
}
.fc-event-disponivel {
    background-color: #28a745;
    border-color: #218838;
}
.fc-event-agendado {
    background-color: #dc3545;
    border-color: #c82333;
}
</style>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">
            <i class="bi bi-funnel"></i> Filtros de Agendamento
        </h5>
    </div>
    <div class="card-body">
        <form id="filtrosForm" class="row g-3">
            
            <div class="col-md-3">
                <label class="form-label">Profissional</label>
                <select class="form-select" id="filtroProfissional">
                    <option value="">Todos os profissionais</option>
                    <?php foreach ($profissionais as $id => $nome): ?>
                        <option value="<?= $id ?>"><?= h($nome) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Serviço</label>
                <select class="form-select" id="filtroServico">
                    <option value="">Todos os serviços</option>
                    <?php foreach ($servicos as $id => $nome): ?>
                        <option value="<?= $id ?>"><?= h($nome) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Local</label>
                <select class="form-select" id="filtroLocal">
                    <option value="">Todos os locais</option>
                    <?php foreach ($locais as $id => $nome): ?>
                        <option value="<?= $id ?>"><?= h($nome) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Convênio</label>
                <select class="form-select" id="filtroConvenio">
                    <option value="">Todos os convênios</option>
                    <?php foreach ($convenios as $id => $nome): ?>
                        <option value="<?= $id ?>"><?= h($nome) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Data Início</label>
                <input type="date" class="form-control" id="filtroDataInicio" value="<?= date('Y-m-d') ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Data Fim</label>
                <input type="date" class="form-control" id="filtroDataFim" value="<?= date('Y-m-d', strtotime('+7 days')) ?>">
            </div>

            <div class="col-12">
                <button type="button" id="btnAplicarFiltros" class="btn btn-primary">
                    <i class="bi bi-filter"></i> Aplicar Filtros
                </button>
                <button type="button" id="btnLimparFiltros" class="btn btn-secondary ms-2">
                    <i class="bi bi-x-circle"></i> Limpar Filtros
                </button>
                
                <div id="loading" class="spinner-border spinner-border-sm text-primary ms-2 d-none" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
            </div>

        </form>
    </div>
</div>

<!-- Calendário -->
<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="card-title mb-0">
            <i class="bi bi-calendar-event"></i> Calendário de Agendamentos
        </h5>
    </div>
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>

<!-- Debug Info -->
<div class="card mt-4">
    <div class="card-header bg-info text-white">
        <h5 class="card-title mb-0">Debug Info</h5>
    </div>
    <div class="card-body">
        <pre id="debugInfo" class="bg-light p-3"></pre>
    </div>
</div>

<!-- JavaScript do FullCalendar -->
<?= $this->Html->script('https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js') ?>
<?= $this->Html->script('https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/pt-br.js') ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let calendar;
    const debugInfo = document.getElementById('debugInfo');
    const csrfToken = '<?= $this->request->getAttribute('csrfToken') ?>';
    
    
    function inicializarCalendario() {
        const calendarEl = document.getElementById('calendar');
        
        if (calendar) {
            calendar.destroy();
        }
        
        calendar = new FullCalendar.Calendar(calendarEl, {
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
            eventClick: function(info) {
                if(info.event.extendedProps.tipo === 'disponivel') {
                    alert('Agendar em ' + info.event.start.toLocaleString());
                    // Aqui você pode abrir modal ou formulário para agendar
                }
            },
            events: function(fetchInfo, successCallback, failureCallback) {
                carregarEventos(fetchInfo, successCallback);
            }
        });
        
        calendar.render();
        debugInfo.textContent += 'Calendário inicializado\n';
        calendar.refetchEvents();
    }

    function carregarEventos(fetchInfo, successCallback) {
    debugInfo.textContent = 'Carregando eventos...\n';

    const filtros = obterFiltros();
    debugInfo.textContent += 'Filtros: ' + JSON.stringify(filtros) + '\n';

    const loading = document.getElementById('loading');
    loading.classList.remove('d-none');

    // Eventos agendados
    const eventosAgendados = <?= json_encode($agendamentos) ?>.map(ag => ({
        title: 'AGENDADO',
        start: ag.data_hora_inicio,
        end: ag.data_hora_fim,
        backgroundColor: '#dc3545',
        borderColor: '#c82333',
        extendedProps: { tipo: 'agendado' }
    }));

    // Eventos disponíveis via fetch
    const formData = new FormData();
    formData.append('profissional_id', filtros.profissional_id);
    formData.append('servico_id', filtros.servico_id);
    formData.append('local_id', filtros.local_id);
    formData.append('convenio_id', filtros.convenio_id);
    formData.append('data_inicio', fetchInfo.startStr.split('T')[0]);
    formData.append('data_fim', fetchInfo.endStr.split('T')[0]);

    fetch('https://app.intelixis.com.br/painel/horarios/getDisponiveis', {
        method: 'POST',
        headers: { 'X-CSRF-Token': csrfToken },
        body: formData,
        credentials: 'same-origin' // envia cookies da sessão
    })
    .then(resp => resp.json())
    .then(data => console.log(data)) // agora deve mostrar JSON puro
.catch(err => console.error(err))
    .then(horariosDisponiveis => {
        debugInfo.textContent += 'Horários disponíveis: ' + horariosDisponiveis.length + '\n';

        const eventosDisponiveis = horariosDisponiveis.map(h => ({
            title: 'DISPONÍVEL - ' + h.horario_inicio,
            start: h.dia + 'T' + h.horario_inicio + ':00',
            end: h.dia + 'T' + h.horario_fim + ':00',
            backgroundColor: '#28a745',
            borderColor: '#218838',
            extendedProps: { tipo: 'disponivel', dia: h.dia, horario_inicio: h.horario_inicio, horario_fim: h.horario_fim }
        }));

        successCallback([...eventosAgendados, ...eventosDisponiveis]);
    })
    .catch(err => {
        console.error('Erro ao carregar horários disponíveis:', err);
        successCallback(eventosAgendados);
    })
    .finally(() => loading.classList.add('d-none'));
}

    function obterFiltros() {
        return {
            profissional_id: document.getElementById('filtroProfissional').value || '',
            servico_id: document.getElementById('filtroServico').value || '',
            local_id: document.getElementById('filtroLocal').value || '',
            convenio_id: document.getElementById('filtroConvenio').value || ''
        };
    }

    document.getElementById('btnAplicarFiltros').addEventListener('click', () => {
        debugInfo.textContent = 'Aplicando filtros...\n';
        calendar.refetchEvents();
    });

    document.getElementById('btnLimparFiltros').addEventListener('click', () => {
        document.getElementById('filtroProfissional').value = '';
        document.getElementById('filtroServico').value = '';
        document.getElementById('filtroLocal').value = '';
        document.getElementById('filtroConvenio').value = '';
        debugInfo.textContent = 'Filtros limpos\n';
        calendar.refetchEvents();
    });

    inicializarCalendario();
});
</script>

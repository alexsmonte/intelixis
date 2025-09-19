<?php
$this->assign('title', 'Calendário Teste'); 
?>

<h1>Calendário Teste</h1>
<div id="calendar"></div>

<?php
// Incluindo FullCalendar CSS e JS
echo $this->Html->css('https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css');
echo $this->Html->script('https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js');
echo $this->Html->script('https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales-all.min.js');
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        allDaySlot: false,
        selectable: true,
        slotDuration: '00:30:00',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: '/agendamentos/calendario',
        select: function(info) {
            alert('Selecionou: ' + info.start.toLocaleString() + ' - ' + info.end.toLocaleString());
            calendar.unselect();
        }
    });
    calendar.render();
});
</script>

<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Servico $servico
 * @var \Cake\Collection\CollectionInterface|string[] $estabelecimentos
 * @var \Cake\Collection\CollectionInterface|string[] $convenios
 * @var \Cake\Collection\CollectionInterface|string[] $profissionais
 */
?>
<?php

$estabelecimento_id=1;



$this->BreadcrumbsPlus->add([['title' => 'Serviços', 'url'=>['controller' => 'Servicos', 'action' => 'index']],['title'=> 'Novo']]);

?>




<?= $this->element('barra-titulo',['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Cadastrar Serviço') ?></h4>
            </div>

            <?= $this->Form->create($servico) ?>
            <div class="col-md-3 mb-3">
                        <div class="form-check form-switch">
                                <?= $this->Form->checkbox('ativar', [
                                'class' => 'form-check-input',
                                'id' => 'ativar',
                                'default'=>true,
                                'hiddenField' => true, // evita campo hidden extra
                                ]) ?>
                                <?= $this->Form->label('ativar', 'Ativar', ['class' => 'form-check-label']) ?>
                        </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <?= $this->Form->control('estabelecimento_id', ['label' => 'Estabelecimento','class' => 'form-control','options' => $estabelecimentos]); ?>
                    </div>
            <div class="col-md-6 mb-3">
                    <?= $this->Form->control('nome',['label' => 'Nome do Serviço','class' => 'form-control',]); ?>
            </div>
            <div class="col-md-2 mb-3"> 
             <div class="position-relative">
                    <?= $this->Form->control('duracao', [
                        'type' => 'number',
                        'min' => 5,
                        'step' => 5,
                        'class' => 'form-control', // padding right para espaço do texto
                        'placeholder' => 'Ex: 30',
                        'label' => 'Duração'
                
                    ]) ?>
                    <span style="position: absolute; top: 50%; align: center; right: 60px; transform: translateY(0%); pointer-events: none; color: #6c757d;">Minutos</span>
        
                </div>

            </div>
           
         </div>
 
<div class="row"> 
     <div class="col-md-12 mb-3">
                    <?= $this->Form->control('descricao',['label' => 'Descrição','class' => 'form-control',]); ?>
            </div> 
            <!-- Convênios -->
                                         
            <div class="row"> 
            <div class="col-md-12 mb-3">
                <?= $this->Form->control('convenios._ids', [
                    'type' => 'select',
                    'multiple' => 'multiple',
                    'class' => 'select2-multiple form-control',
                    'options' => $convenios,
                    'label' => 'Convênios que cobrem este serviço'
            
                ]); ?>
            </div>

        </div>
        <div class="row"> 
            <div class="col-md-12 mb-3">
                <?= $this->Form->control('profissionais._ids', [
                    'type' => 'select',
                    'multiple' => 'multiple',
                    'class' => 'select2-multiple form-control',
                    'options' => $profissionais,
                    'label' => 'Profissionais que atendem este serviço'
            
                ]); ?>
            </div>

        </div>
       
        <div class="col-md-6 mb-3">
    <!-- Habilitar Programação -->
<div class="form-group">
  <label for="habilitar_programacao">Habilitar Programação</label>
  <div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" id="habilitar_programacao">
  </div>
</div>

<!-- Container dos dias da semana -->
<div id="dias_semana" style="display: none;">

  <?php
  $dias = [
    1 => 'Domingo',
    2 => 'Segunda-feira',
    3 => 'Terça-feira',
    4 => 'Quarta-feira',
    5 => 'Quinta-feira',
    6 => 'Sexta-feira',
    7 => 'Sábado'
  ];
  foreach ($dias as $num => $nome): ?>
    <div class="card p-2 mb-2">
      <div class="d-flex justify-content-between align-items-center">
        <strong><?= $nome ?></strong>
        <div class="form-check form-switch">
          <input class="form-check-input toggle-dia" type="checkbox" id="dia_<?= $num ?>" data-dia="<?= $num ?>">
        </div>
      </div>
      <div class="row mt-2 horarios-dia" id="horarios_<?= $num ?>" style="display:none;">
        <div class="col">
          <label>Início</label>
          <input type="time" class="form-control hora-inicio" data-dia="<?= $num ?>">
        </div>
        <div class="col">
          <label>Fim</label>
          <input type="time" class="form-control hora-fim" data-dia="<?= $num ?>">
        </div>
      </div>
    </div>
  <?php endforeach; ?>

</div>

<!-- Hidden inputs que vão para o controller -->
<div id="hidden_inputs"></div>


        </div>


    <div class="row">
        <div class="col-md-2 offset-md-10  mb-3">
    <?= $this->Form->button(__('Salvar'),['class' => 'justify-content-center w-100 btn btn-success d-flex align-items-center']) ?>
        </div>
    <?= $this->Form->end() ?>
    </div>
    </div>

</div>

        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const habilitar = document.getElementById("habilitar_programacao");
  const diasSemana = document.getElementById("dias_semana");
  const hiddenInputs = document.getElementById("hidden_inputs");

  // Toggle principal
  habilitar.addEventListener("change", function () {
    diasSemana.style.display = this.checked ? "block" : "none";
  });

  // Toggle individual de cada dia
  document.querySelectorAll(".toggle-dia").forEach(toggle => {
    toggle.addEventListener("change", function () {
      const dia = this.dataset.dia;
      const horarios = document.getElementById("horarios_" + dia);
      horarios.style.display = this.checked ? "flex" : "none";
      if (!this.checked) {
        horarios.querySelector(".hora-inicio").value = "";
        horarios.querySelector(".hora-fim").value = "";
        atualizarHidden();
      }
    });
  });

  // Atualiza os hidden inputs no formato esperado
  document.querySelectorAll(".hora-inicio, .hora-fim").forEach(input => {
    input.addEventListener("change", atualizarHidden);
  });

  function atualizarHidden() {
    hiddenInputs.innerHTML = "";
    const hoje = new Date().toISOString().split("T")[0]; // data de hoje

    document.querySelectorAll(".toggle-dia:checked").forEach(toggle => {
      const dia = toggle.dataset.dia;
      const inicio = document.querySelector(`.hora-inicio[data-dia='${dia}']`).value;
      const fim = document.querySelector(`.hora-fim[data-dia='${dia}']`).value;

      if (inicio && fim) {
        // Monta valores no formato: 2025-08-22T15:36:17.08
        const inicioISO = `${hoje}T${inicio}:00.000`;
        const fimISO = `${hoje}T${fim}:00.000`;

        hiddenInputs.innerHTML += `
          <input type="hidden" name="periodos[${dia}][data_hora_inicio]" value="${inicioISO}">
          <input type="hidden" name="periodos[${dia}][data_hora_fim]" value="${fimISO}">
          <input type="hidden" name="periodos[${dia}][dia_semana]" value="${dia}">
          <input type="hidden" name="periodos[${dia}][cadeia_id]" value="semanal">
          <input type="hidden" name="periodos[${dia}][estabelecimento_id]" value="<?=$estabelecimento_id?>">
           
        `;
      }
    });
  }
});
</script>
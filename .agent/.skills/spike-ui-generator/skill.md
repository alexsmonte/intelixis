---
name: spike-ui-generator
description: Skill especialista em gerar interfaces CRUD (Views) seguindo os padrões do tema Spike, CakePHP 4/5 e Vue 3.
---

# Instruções de Automação

Sempre que o usuário solicitar a criação de um formulário, modal ou tela de edição/cadastro, esta skill deve aplicar estritamente os padrões definidos na regra `ui-standards.md`.

## 1. Estrutura de Botões (Obrigatório)
Sempre gerar botões com a classe `.rounded-pill`.
- **Salvar:** `<button type="submit" class="btn btn-success rounded-pill">Salvar</button>`
- **Cancelar:** `<button type="button" class="btn bg-danger-subtle text-danger rounded-pill">Cancelar</button>`
- **Geral:** `<button type="button" class="btn bg-primary-subtle text-primary rounded-pill">Confirmar</button>`

## 2. Formulários CakePHP (SSR)
- Usar `$this->Form->control` dentro de uma estrutura de grid `.row` e `.col-md-*`.
- **Selects Simples:** Adicionar obrigatoriamente a classe `.form-select` e o ID `#single-select-field`.
- **Selects Múltiplos:** Obrigatório conter no topo botões em javascript inline para "Selecionar Todos" e "Desmarcar Todos" interagindo via Select2 (ex: `$('#meu-select-id > option').prop('selected', true); $('#meu-select-id').trigger('change');`). Ver `rule-interface.md` para o mockup HTML completo.
- **Máscaras:** Injetar as classes `.mask-money`, `.mask-cpf`, etc., conforme o tipo de dado.

## 3. Integração Vue 3 e Máscaras
Se a interface for um componente Vue ou Modal:
- Usar a diretiva `v-mask`.
- Para campos financeiros, usar a lógica de centavos para a esquerda definida em `webroot/js/vue/vue-masks.js`.
- Adicionar validação de `minlength="3"` para campos de nome/texto.

## 4. Layout e Organização
- Agrupar campos logicamente usando:
  ```html
  <div class="col-md-12 mb-3 mt-3">
      <h6 class="border-bottom pb-2">Título da Seção</h6>
  </div>

## 5. Exemplo de Output (Template Base)  
<div class="row">
    <div class="col-md-12 mb-3 mt-3">
        <h6 class="border-bottom pb-2">Dados do Cadastro</h6>
    </div>
    <div class="col-md-6 mb-3">
        <?= $this->Form->control('nome', ['label' => 'Nome', 'class' => 'form-control', 'placeholder' => 'Mínimo 3 caracteres']) ?>
    </div>
    <div class="col-md-6 mb-3">
        <?= $this->Form->control('valor', ['label' => 'Valor', 'class' => 'form-control mask-money']) ?>
    </div>
    <div class="col-md-12 text-end">
        <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-success rounded-pill']) ?>
        <?= $this->Form->button(__('Cancelar'), ['type' => 'button', 'class' => 'btn bg-danger-subtle text-danger rounded-pill']) ?>
    </div>
</div>

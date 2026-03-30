# RRule: Interface, Formulários e Elementos Visuais (Padrão Spike)
Este documento define os padrões visuais, a construção de formulários e a disposição de elementos visuais no sistema para garantir a consistência de UI/UX, acessibilidade e facilitar a manutenção.

---

## 1. Estrutura Base de Formulários (MVC CakePHP)
Sempre que a tela for um formulário gerado pelas Views do CakePHP, o uso do FormHelper nativo é obrigatório. A estrutura deve respeitar estritamente o grid do Bootstrap 5 (.row, .col-md-*) e os espaçamentos padrão (.mb-3).


### 1.1. Inputs de Texto, E-mail e Numéricos
Utilizar sempre a classe .form-control. O rótulo (label) pode ser definido diretamente no array de opções do helper. Não adicione divs desnecessárias se o form-control der conta do recado.

```php
<div class="col-md-6 mb-3">
    <?= $this->Form->control('nome', ['label' => 'Nome Completo', 'class' => 'form-control']); ?>
</div>
<div class="col-md-3 mb-3">
    <?= $this->Form->control('telefone', ['label' => 'Telefone', 'class' => 'form-control']); ?>
</div>
```

### 1.2. Selects e Select Multiple (Select2)
Selects simples devem usar a classe .form-select (padrão do Bootstrap) e obrigatoriamente o ID #single-select-field para compatibilidade com o tema Spike. Use apenas se se for um select com poucas opções. Se for um select com muitas opções, como o de convênios, profissionais, clientes, e demais opções vinda do banco de dados use o select2 com pesquisa.

```php
html
<!-- Exemplo Select Single Padrão Spike -->
<select class="form-select" id="single-select-field" data-placeholder="Escolha uma opção">
    <option></option>
    <option>Opção 1</option>
    <option>Opção 2</option>
</select>
```
Para selects com pesquisa embutida ou de seleção múltipla, adote a classe .select2-multiple ou o Select2 básico em conjunto com .form-control. Obrigatório: todos os selects múltiplos devem conter botões para "Selecionar Todos" e "Desmarcar Todos" acima do campo em uma estrutura de flexbox, desativando a label nativa do CakePHP para evitar duplicação.
```php
<!-- Exemplo Select Múltiplo com Pesquisa (Select2) e Ações Globais -->
<div class="col-md-12 mb-3">
    <div class="d-flex justify-content-between align-items-end mb-1">
        <label class="form-label mb-0" for="meu-campo-ids">Título do Campo Múltiplo</label>
        <div>
            <button type="button" class="btn btn-sm btn-link text-primary p-0 me-2" onclick="$('#meu-campo-ids > option').prop('selected', true); $('#meu-campo-ids').trigger('change');">Selecionar Todos</button>
            <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="$('#meu-campo-ids').val(null).trigger('change');">Desmarcar Todos</button>
        </div>
    </div>
    <?= $this->Form->control('meu_campo._ids', [
        'type' => 'select',
        'multiple' => 'multiple',
        'class' => 'select2-multiple form-control',
        'options' => $opcoes,
        'label' => false,
        'id' => 'meu-campo-ids'
    ]); ?>
</div>
```

### 1.3. Checkboxes e Switches (Booleans)
O sistema adota os switches do Bootstrap 5 para lidar com campos de ativação / desativação ou checkboxes binários. Utilize o padrão `.form-check` e `.form-switch` juntamente com `.form-check-input`.

```php
<div class="col-md-3 mb-3">
    <div class="form-check form-switch">
        <?= $this->Form->checkbox('ativar', [
            'class' => 'form-check-input',
            'id' => 'ativar',
            'default' => true,
            'hiddenField' => true,
        ]) ?>
        <?= $this->Form->label('ativar', 'Ativar', ['class' => 'form-check-label']) ?>
    </div>
</div>
```

---
## 2. Botões e Ações (Padrão Pilúla Spike)
Todos os botões de ação devem obrigatoriamente utilizar o formato pilúla (.rounded-pill) e seguir a paleta de cores do tema:
Salvar / Sucesso: btn btn-success rounded-pill
Cancelar / Fechar: btn bg-danger-subtle text-danger rounded-pill
Ações Gerais (Confirmar, Limpar, etc): btn bg-primary-subtle text-primary rounded-pill

## 3. Separação de Seções e Agrupamentos (Layout)

Para separar visualmente categorias de dados dentro de um mesmo formulário ou card (ex: "Dados Pessoais", "Endereço", "Serviços"), deve-se utilizar títulos (`h5`, `h6`) com borda inferior e margens bem definidas. **Não construa múltiplos forms para os mesmos dados da Entity, apenas separe visualmente**.

```php
<div class="col-md-12">
    <div class="row">
        <div class="col-md-12 mb-3 mt-3">
            <h6 class="border-bottom pb-2">Endereço:</h6>
        </div>
    </div>
</div>
```
*Dica:* Para divisórias vazias que atuam apenas como espaçadores ou quebras lógicas discretas, pode-se usar um `<h5 class="border-bottom pb-2"></h5>` isolado.

---

## 4. Integração de Componentes Vue 3 no CakePHP

Sempre que a interface precisar de interatividade isolada (como autocompletar CEP, carregar programações dinamicamente com base em selects), a funcionalidade deve ser encapsulada em pequenos aplicativos Vue usando o atributo `data-vue` ou um `id` específico. 

Estes componentes nunca devem quebrar a fluidez de inserção (eles injetam `input[type="hidden"]` ou manipulam visualmente os campos para persistência via POST/PATCH do CakePHP):

```html
<!-- Exemplo de injeção de componente na tela (ver templates/Profissionais/novo.php) -->
<div class="col-md-3" data-vue="cep-autocomplete"></div>
```
*(O app correspondente é importado como `<script>` no final do arquivo)*

---

## 5. Regras de Máscaras em Formulários e Modais (Obrigatório)

Campos que exigem formatação padronizada e restrições de entrada de caracteres (CPF, CNPJ, CEP, Telefone, Celular, Valores Financeiros e Datas) **devem obrigatoriamente** possuir máscaras validadoras para controle de input.

### 5.1. Máscaras Reutilizáveis em Componentes Reactivos Vue 3 (`v-mask`)
Em modais centralizadas, tabelas dinâmicas ou componentes instanciados pelo Vue no sistema, todas as máscaras devem aproveitar a biblioteca **`vue-mask`** (via `IMask` ou equivalente já configurado como plugin global da instância Vue local).
Esta regra evita duplicação de lógicas de formatação ou falhas de ciclo de vida onde bibliotecas jQuery antigas não detectam DOM montado do Vue.
Utilizar as configurações centralizadas em webroot/js/vue/vue-masks.js.
Financeiro (PT-BR): Preenchimento obrigatório de centavos para a esquerda.
CPF/CNPJ: Máscaras padrão brasileiras.
Texto/Nome: Mínimo de 3 caracteres.
Números: Diferenciar Número Inteiro de Número Decimal

**Regras para o Vue-Mask:**
1. Em arquivos `.js` que levantam configs (como campos dinâmicos via JSON Arrays), passe o padrão desejado de máscara no atributo `mask` (ex: `mask: '###.###.###-##'`).
2. Utilize diretamente no template HTML usando a diretiva registrada (e.g. `v-mask`).
3. Somente use os tokens suportados pela implementação local de `vue-mask`.

```html
<!-- Máscara de CPF direta no input do vue -->
<input v-model="cliente.cpf" 
       v-mask="'###.###.###-##'" 
       placeholder="000.000.000-00" 
       class="form-control" />

<!-- Máscara em iterações de formulários JSON com V-Model Dinâmico -->
<div class="mb-3">
    <label class="form-label">{{ field.label }}</label>
    <input v-model="formData[field.name]" 
           v-mask="field.mask" 
           v-if="field.mask"
           class="form-control" />
</div>
```

### 5.2. Máscaras em Telas SSR Tradicionais (Sem Vue)
Se a página roda usando apenas a renderização tradicional (Server Side / `$this->Form->control` simples), a máscara não precisa do Vue. Ela deve utilizar classes CSS descritivas que disparam as formatações via biblioteca jQuery Mask no arquivo principal de layout/scripts do sistema.

**Padrão de Classes Permitidas:**
- `.mask-cpf`
- `.mask-cnpj`
- `.mask-celular`
- `.mask-telefone`
- `.mask-cep`
- `.mask-money`

```php
<!-- Exemplo CakePHP sem Vue -->
<?= $this->Form->control('cpf', [
    'label' => 'CPF', 
    'class' => 'form-control mask-cpf',
    'placeholder' => '000.000.000-00'
]); ?>
```
### 5.3. Validações de Model
Toda validação deve ocorrer no Model. O formulário (Telas de cadastro/edição ou Modais) deve estar configurado para exibir o retorno das validações do CakePHP automaticamente logo abaixo do input correspondente.
# Regras Gerais de Desenvolvimento (CakePHP 5)

Este documento define as diretrizes arquiteturais e boas práticas a serem seguidas em **todas** as implementações do projeto, tanto no frontend (views) quanto no backend (controllers, tables e entities). O objetivo é usar sempre o **padrão nativo do CakePHP 5**, garantindo flexibilidade, segurança e a correta aplicação do multi-tenant.

---

## 1. Isolamento por Tenant (Estabelecimento)

O sistema opera no modelo multi-tenant. Todo usuário pertence a um `estabelecimento_id`. **NUNCA** construa queries, listagens, edições ou exclusões sem garantir o filtro pelo estabelecimento logado na sessão atual. Isso evita vazamento ou alteração indevida de dados.

### 1.1 Obtendo o ID do Tenant da Sessão
Use sempre:
```php
$estabelecimentoId = $this->getRequest()->getSession()->read('Auth.Estabelecimento.id');
```

### 1.2 Leitura (Find / Get)
Todas as buscas devem ter a condicional restritiva de segurança:
```php
// Para listagens (find)
$query = $this->Entidades->find()
    ->where(['estabelecimento_id' => $estabelecimentoId]);

// Para o get único (Visualização, Edição, Exclusão)
$entidade = $this->Entidades->get($id, [
    'conditions' => ['estabelecimento_id' => $estabelecimentoId]
]);
```

### 1.3 Inserção (Novo Registro)
Ao criar ou atualizar dados agregados, force sempre a injeção do `estabelecimento_id` antes de executar o patch, evitando injeções indevidas na request:
```php
$data = $this->request->getData();
$data['estabelecimento_id'] = $estabelecimentoId;

$entidade = $this->Entidades->patchEntity($entidade, $data);
$this->Entidades->save($entidade);
```

---

## 2. Manipulação de Dados (Salvar e Atualizar)

### 2.1 Uso Exclusivo do `patchEntity`
Para garantir a sanitização e aplicar as regras de validação declaradas na Model, **NUNCA** faça atribuições diretas massivas (como `$entidade->campo = $valor`). Utilize sempre o `patchEntity` com os dados da requisição.

```php
// Correto - Passando pela validação nativa:
$entidade = $this->Entidades->patchEntity($entidade, $this->request->getData());
$this->Entidades->save($entidade);
```

### 2.2 Associações no Patch e Save
Ao salvar dados que contêm HasMany ou BelongsToMany, explicitar a opção `associated` salva você de salvar partes do form mas não seus filhos.
```php
$entidade = $this->Entidades->patchEntity($entidade, $data, [
    'associated' => ['Servicos', 'Profissionais']
]);
$this->Entidades->save($entidade, ['associated' => ['Servicos', 'Profissionais']]);
```

---

## 3. Padrões de Frontend (Views e Assets)

**A pasta base (root) e o subdiretório de instalação da aplicação podem mudar.** Portanto, caminhos `hardcoded` arruínam a escalabilidade. **Sempre use as bibliotecas e helpers nativos do CakePHP**.

### 3.1 Inclusão de Assets (JS / CSS) e Evitando Hardcodes
**NUNCA** inclua um script dessa forma manuais:
```html
<!-- INCORRETO -->
<script src="/painel/js/vue/vue-index.js?v=<?= filemtime(WWW_ROOT . 'js/vue/vue-index.js') ?>"></script>
```

**Sempre use o `HtmlHelper`**:
O helper detecta dinamicamente a `App.base` da configuração global da aplicação e formata o caminho físico e URLs virtuais corretamente.

```php
<!-- CORRETO (Com cache-busting se a configuração nativa Asset.timestamp estiver ativada) -->
<?= $this->Html->script('vue/vue-index') ?>

<!-- CORRETO (Para ter quebra de cache com o helper de rotas, sem quebrar os caminhos) -->
<?= $this->Html->script('vue/vue-index.js?v=' . filemtime(WWW_ROOT . 'js/vue/vue-index.js')) ?>
```

### 3.2 Geração de URLs e Rotas
Idem para tags de link, botões, requisições AJAX (`fetch`), nunca encadeie `/painel/meucontroller`.

```php
// Action Link (HTML)
<?= $this->Html->link('Novo', ['controller' => 'Entidades', 'action' => 'novo'], ['class' => 'btn']) ?>

// Obter string da URL apenas (ex: via Vue / data-attributes)
<?= $this->Url->build(['controller' => 'Entidades', 'action' => 'delete']) ?>
```

---

## 4. Padrões de Exclusão (Delete)

As exclusões do CakePHP exigem uso correto dos verbos HTTP para evitar manipulação de URLs ou deleção não-intencional por crawlers (`GET`).

```php
public function delete($id = null)
{
    // Permite apenas método POST e DELETE
    $this->request->allowMethod(['post', 'delete']);

    // Garantir Tenant Check!
    $estabelecimentoId = $this->request->getSession()->read('Auth.Estabelecimento.id');
    $entidade = $this->Entidades->get($id, [
        'conditions' => ['estabelecimento_id' => $estabelecimentoId] // ESSENCIAL
    ]);
    
    // Execução da exclusão
    if ($this->Entidades->delete($entidade)) {
        // ...
    }
}
```
Nas views associadas, execute o submit pela tag form ou use requisições AJAX com Method Delete e com o o Helper global `vue-index.js` onde já está consolidado.

---

## 5. Helpers e APIs do CakePHP 5

- **Buscar Modelos Avulsos no Controller**: Use `$this->fetchTable('OutroModel')` no invés de métodos legados.
- **Desativação de Layout Automático em AJAX**: Se estiver servindo ou respondendo a retornos de JSON puros:
  ```php
  $this->autoRender = false;
  return $this->response->withType('application/json')
                        ->withStringBody(json_encode(['success' => true]));
  ```

<?php
use Cake\I18n\Number;
use Cake\Collection\Collection;

$collection = new Collection($estabelecimento->periodos);
$agrupados = $collection->groupBy('cadeia_id')->toArray();

$semanal = $agrupados['1'] ?? [];
$disponiveis = $agrupados['2'] ?? [];
$indisponiveis = $agrupados['3'] ?? [];
$this->BreadcrumbsPlus->add([['title' => 'Estabelecimento']]);

?>
<?= $this->element('barra-titulo',['title' => $title]); ?>
<?= $this->Form->create($estabelecimento) ?>
<div class="card">
    <ul class="nav nav-pills user-profile-tab" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3 active" id="pills-account-tab" data-bs-toggle="pill" data-bs-target="#pills-account" type="button" role="tab" aria-controls="pills-account" aria-selected="true">
                <i class="ti ti-user-circle me-2 fs-6"></i>
                <span class="d-none d-md-block">Dados do estabelecimento</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3" id="pills-notifications-tab" data-bs-toggle="pill" data-bs-target="#pills-notifications" type="button" role="tab" aria-controls="pills-notifications" aria-selected="false" tabindex="-1">
                <i class="ti ti-bell me-2 fs-6"></i>
                <span class="d-none d-md-block">Funcionamento</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3" id="pills-bills-tab" data-bs-toggle="pill" data-bs-target="#pills-bills" type="button" role="tab" aria-controls="pills-bills" aria-selected="false" tabindex="-1">
                <i class="ti ti-article me-2 fs-6"></i>
                <span class="d-none d-md-block">Mensalidade</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3" id="pills-security-tab" data-bs-toggle="pill" data-bs-target="#pills-security" type="button" role="tab" aria-controls="pills-security" aria-selected="false" tabindex="-1">
                <i class="ti ti-lock me-2 fs-6"></i>
                <span class="d-none d-md-block">Security</span>
            </button>
        </li>
    </ul>
    <div class="card-body">
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade active show" id="pills-account" role="tabpanel" aria-labelledby="pills-account-tab" tabindex="0">
                <div class="row">
                    <div class="col-lg-6 d-flex align-items-stretch">
                        <div class="card w-100 border position-relative overflow-hidden">
                            <div class="card-body p-4">
                                <h4 class="card-title">Logomarca</h4>
                                <p class="card-subtitle mb-4">Altere a logo da sua empresa</p>
                                <div class="text-center">
                                    <?= $this->Html->image('profile/user-1.jpg', ['alt'=>'Logo', 'class' => 'img-fluid', 'width'=>'120'])?>
                                    <div class="d-flex align-items-center justify-content-center my-4 gap-6">
                                        <button class="btn btn-primary">Escolher foto</button>
                                        <button class="btn bg-danger-subtle text-danger">Reset</button>
                                    </div>
                                    <p class="mb-0">Envie no formato JPG ou PNG. Maximo de 800K</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 d-flex align-items-stretch">
                        <div class="card w-100 border position-relative overflow-hidden">
                            <div class="card-body p-4">
                                <h4 class="card-title">Dados da assinatura</h4>
                                <p class="card-subtitle mb-4">Abaixo os dados da sua assinatura</p>
                                <div class="d-flex align-items-center justify-content-between mt-7 mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-package text-dark d-block fs-7" width="22" height="22"></i>
                                        </div>
                                        <div>
                                            <p class="mb-0"><?= $estabelecimento->assinatura->plano->nome; ?></p>
                                            <h5 class="fs-4 fw-semibold">R$ <?= $estabelecimento->assinatura->plano->valor; //Number::precision($estabelecimento->assinatura->plano->valor,2); ?></h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-7 mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-credit-card text-dark d-block fs-7" width="22" height="22"></i>
                                        </div>
                                        <div>
                                            <p class="mb-0">Próximo vencimento</p>
                                            <h5 class="fs-4 fw-semibold"><?= $estabelecimento->assinatura->data_vencimento; ?></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card w-100 border position-relative overflow-hidden mb-0">
                            <div class="card-body p-4">
                                <h4 class="card-title">Dados do seu estabelecimento</h4>
                                <p class="card-subtitle mb-4">Para alterar seus dados pessoais, edite e salve aqui.</p>
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <div class="mb-3">
                                                <?= $this->Form->control('razao_social',['label' => 'Razão social','class' => 'form-control',]); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <?= $this->Form->control('nome_fantasia',['label' => 'Nome fantasia','class' => 'form-control',]); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <?= $this->Form->control('cpf_cnpj',['label' => 'CPF/CNPJ','class' => 'form-control',]); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-2">
                                            <div class="mb-3">
                                                <?= $this->Form->control('cep',['label' => 'CEP','class' => 'form-control',]); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-5">
                                            <div class="mb-3">
                                                <?= $this->Form->control('logradouro',['label' => 'Logradouro','class' => 'form-control',]); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="mb-3">
                                                <?= $this->Form->control('numero',['label' => 'Número','class' => 'form-control',]); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <?= $this->Form->control('complemento',['label' => 'Complemento','class' => 'form-control',]); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <div class="mb-3">
                                                <?= $this->Form->control('email',['label' => 'E-mail','class' => 'form-control',]); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <?= $this->Form->control('telefone',['label' => 'Telefone fixo','class' => 'form-control',]); ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <?= $this->Form->control('celular',['label' => 'Celular','class' => 'form-control',]); ?>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex align-items-center justify-content-end mt-4 gap-6">
                                                <button type="submit" class="btn btn-primary">Salvar</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-notifications" role="tabpanel" aria-labelledby="pills-notifications-tab" tabindex="0">
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <div class="card border p-4">
                                <div data-vue="programacao-semanal"
                                        data-programacao-inicial='<?= json_encode($semanal ?? []) ?>'
                                        data-habilitar-inicial="<?= $estabelecimento->habilitar_programacao ? 'true' : 'false' ?>"
                                        data-token="<?= h($this->request->getAttribute('csrfToken')) ?>"
                                        data-base-url="<?= $this->Url->build(['controller' => 'Periodos', 'action' => 'delete'], ['fullBase' => false]) ?>">
                                        <!-- Inputs hidden gerados pelo Vue ficarão aqui dentro do form -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <div class="card border p-4">
                                <div data-vue="programacao-personalizada"
                                        data-programacao-inicial='<?= htmlspecialchars(json_encode($disponiveis ?? []), ENT_QUOTES, 'UTF-8') ?>'
                                        data-token="<?= h($this->request->getAttribute('csrfToken')) ?>"
                                        data-base-url="<?= $this->Url->build(['controller' => 'Periodos', 'action' => 'delete'], ['fullBase' => false]) ?>">
                                        <!-- Inputs hidden gerados pelo Vue ficarão aqui dentro do form -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <div class="card border p-4">
                                <div data-vue="dias-indisponiveis"
                                        data-periodos-iniciais='<?= json_encode($indisponiveis ?? []) ?>'
                                        data-token="<?= h($this->request->getAttribute('csrfToken')) ?>"
                                        data-base-url="<?= $this->Url->build(['controller' => 'Periodos', 'action' => 'delete'], ['fullBase' => false]) ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-end mt-4 gap-6">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </div>
                </div>

            <div class="tab-pane fade" id="pills-bills" role="tabpanel" aria-labelledby="pills-bills-tab" tabindex="0">
                <div class="row justify-content-center">
-->
                </div>
            </div>
            <div class="tab-pane fade" id="pills-security" role="tabpanel" aria-labelledby="pills-security-tab" tabindex="0">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card border shadow-none">
                            <div class="card-body p-4">
                                <h4 class="card-title mb-3">Two-factor Authentication</h4>
                                <div class="d-flex align-items-center justify-content-between pb-7">
                                    <p class="card-subtitle mb-0">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Corporis sapiente
                                        sunt earum officiis laboriosam ut.</p>
                                    <button class="btn btn-primary">Enable</button>
                                </div>
                                <div class="d-flex align-items-center justify-content-between py-3 border-top">
                                    <div>
                                        <h5 class="fs-4 fw-semibold mb-0">Authentication App</h5>
                                        <p class="mb-0">Google auth app</p>
                                    </div>
                                    <button class="btn bg-primary-subtle text-primary">Setup</button>
                                </div>
                                <div class="d-flex align-items-center justify-content-between py-3 border-top">
                                    <div>
                                        <h5 class="fs-4 fw-semibold mb-0">Another e-mail</h5>
                                        <p class="mb-0">E-mail to send verification link</p>
                                    </div>
                                    <button class="btn bg-primary-subtle text-primary">Setup</button>
                                </div>
                                <div class="d-flex align-items-center justify-content-between py-3 border-top">
                                    <div>
                                        <h5 class="fs-4 fw-semibold mb-0">SMS Recovery</h5>
                                        <p class="mb-0">Your phone number or something</p>
                                    </div>
                                    <button class="btn bg-primary-subtle text-primary">Setup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="text-bg-light rounded-1 p-6 d-inline-flex align-items-center justify-content-center mb-3">
                                    <i class="ti ti-device-laptop text-primary d-block fs-7" width="22" height="22"></i>
                                </div>
                                <h4 class="card-title mb-0">Devices</h4>
                                <p class="mb-3">Lorem ipsum dolor sit amet consectetur adipisicing elit Rem.</p>
                                <button class="btn btn-primary mb-4">Sign out from all devices</button>
                                <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="ti ti-device-mobile text-dark d-block fs-7" width="26" height="26"></i>
                                        <div>
                                            <h5 class="fs-4 fw-semibold mb-0">iPhone 14</h5>
                                            <p class="mb-0">London UK, Oct 23 at 1:15 AM</p>
                                        </div>
                                    </div>
                                    <a class="text-dark fs-6 d-flex align-items-center justify-content-center bg-transparent p-2 fs-4 rounded-circle" href="javascript:void(0)">
                                        <i class="ti ti-dots-vertical"></i>
                                    </a>
                                </div>
                                <div class="d-flex align-items-center justify-content-between py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="ti ti-device-laptop text-dark d-block fs-7" width="26" height="26"></i>
                                        <div>
                                            <h5 class="fs-4 fw-semibold mb-0">Macbook Air</h5>
                                            <p class="mb-0">Gujarat India, Oct 24 at 3:15 AM</p>
                                        </div>
                                    </div>
                                    <a class="text-dark fs-6 d-flex align-items-center justify-content-center bg-transparent p-2 fs-4 rounded-circle" href="javascript:void(0)">
                                        <i class="ti ti-dots-vertical"></i>
                                    </a>
                                </div>
                                <button class="btn bg-primary-subtle text-primary w-100 py-1">Need Help ?</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-end gap-6">
                            <button class="btn btn-primary">Salvar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>
<!-- Vue e componentes programacao.js -->
<?= $this->Html->script('vue/programacao-semanal.js') ?>
<?= $this->Html->script('vue/programacao-personalizada.js') ?>
<?= $this->Html->script('vue/programacao-indisponivel.js') ?>
<?= $this->Html->script('vue/cep-autocomplete.js') ?>

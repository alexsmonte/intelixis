<?php
use Cake\I18n\Number;

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
                    <div class="col-lg-9">
                        <div class="card border shadow-none">
                            <div class="card-body p-4">
                                <h4 class="card-title">Notification Preferences</h4>
                                <p class="card-subtitle mb-4">
                                    Select the notificaitons ou would like to receive via email. Please note that you cannot opt
                                    out of receving service
                                    messages, such as payment, security or legal notifications.
                                </p>
                                <form class="mb-7">
                                    <label for="exampleInputtext5" class="form-label">Email Address*</label>
                                    <input type="text" class="form-control" id="exampleInputtext5" placeholder="" required="">
                                    <p class="mb-0">Required for notificaitons.</p>
                                </form>
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                                                <i class="ti ti-article text-dark d-block fs-7" width="22" height="22"></i>
                                            </div>
                                            <div>
                                                <h5 class="fs-4 fw-semibold">Our newsletter</h5>
                                                <p class="mb-0">We'll always let you know about important changes</p>
                                            </div>
                                        </div>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked">
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                                                <i class="ti ti-checkbox text-dark d-block fs-7" width="22" height="22"></i>
                                            </div>
                                            <div>
                                                <h5 class="fs-4 fw-semibold">Order Confirmation</h5>
                                                <p class="mb-0">You will be notified when customer order any product</p>
                                            </div>
                                        </div>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked1" checked="">
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                                                <i class="ti ti-clock-hour-4 text-dark d-block fs-7" width="22" height="22"></i>
                                            </div>
                                            <div>
                                                <h5 class="fs-4 fw-semibold">Order Status Changed</h5>
                                                <p class="mb-0">You will be notified when customer make changes to the order</p>
                                            </div>
                                        </div>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked2" checked="">
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                                                <i class="ti ti-truck-delivery text-dark d-block fs-7" width="22" height="22"></i>
                                            </div>
                                            <div>
                                                <h5 class="fs-4 fw-semibold">Order Delivered</h5>
                                                <p class="mb-0">You will be notified once the order is delivered</p>
                                            </div>
                                        </div>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked3">
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                                                <i class="ti ti-mail text-dark d-block fs-7" width="22" height="22"></i>
                                            </div>
                                            <div>
                                                <h5 class="fs-4 fw-semibold">Email Notification</h5>
                                                <p class="mb-0">Turn on email notificaiton to get updates through email</p>
                                            </div>
                                        </div>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked4" checked="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="card border shadow-none">
                            <div class="card-body p-4">
                                <h4 class="card-title">Date &amp; Time</h4>
                                <p class="card-subtitle">Time zones and calendar display settings.</p>
                                <div class="d-flex align-items-center justify-content-between mt-7">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-clock-hour-4 text-dark d-block fs-7" width="22" height="22"></i>
                                        </div>
                                        <div>
                                            <p class="mb-0">Time zone</p>
                                            <h5 class="fs-4 fw-semibold">(UTC + 02:00) Athens, Bucharet</h5>
                                        </div>
                                    </div>
                                    <a class="text-dark fs-6 d-flex align-items-center justify-content-center bg-transparent p-2 fs-4 rounded-circle" href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Download">
                                        <i class="ti ti-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="card border shadow-none">
                            <div class="card-body p-4">
                                <h4 class="card-title">Ignore Tracking</h4>
                                <div class="d-flex align-items-center justify-content-between mt-7">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-player-pause text-dark d-block fs-7" width="22" height="22"></i>
                                        </div>
                                        <div>
                                            <h5 class="fs-4 fw-semibold">Ignore Browser Tracking</h5>
                                            <p class="mb-0">Browser Cookie</p>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked5">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-end gap-6">
                            <button class="btn btn-primary">Save</button>
                            <button class="btn bg-danger-subtle text-danger">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-bills" role="tabpanel" aria-labelledby="pills-bills-tab" tabindex="0">
                <div class="row justify-content-center">
                    <div class="col-lg-9">
                        <div class="card border shadow-none">
                            <div class="card-body p-4">
                                <h4 class="card-title mb-3">Billing Information</h4>
                                <form>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="exampleInputtext6" class="form-label">Business
                                                    Name*</label>
                                                <input type="text" class="form-control" id="exampleInputtext6" placeholder="Visitor Analytics">
                                            </div>
                                            <div class="mb-3">
                                                <label for="exampleInputtext7" class="form-label">Business
                                                    Address*</label>
                                                <input type="text" class="form-control" id="exampleInputtext7" placeholder="">
                                            </div>
                                            <div>
                                                <label for="exampleInputtext8" class="form-label">First Name*</label>
                                                <input type="text" class="form-control" id="exampleInputtext8" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="exampleInputtext9" class="form-label">Business
                                                    Sector*</label>
                                                <input type="text" class="form-control" id="exampleInputtext9" placeholder="Arts, Media &amp; Entertainment">
                                            </div>
                                            <div class="mb-3">
                                                <label for="exampleInputtext10" class="form-label">Country*</label>
                                                <input type="text" class="form-control" id="exampleInputtext10" placeholder="Romania">
                                            </div>
                                            <div>
                                                <label for="exampleInputtext11" class="form-label">Last Name*</label>
                                                <input type="text" class="form-control" id="exampleInputtext11" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="card border shadow-none">
                            <div class="card-body p-4">
                                <h4 class="card-title">Current Plan : <span class="text-success">Executive</span>
                                </h4>
                                <p class="card-subtitle">Thanks for being a premium member and supporting our development.</p>
                                <div class="d-flex align-items-center justify-content-between mt-7 mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-package text-dark d-block fs-7" width="22" height="22"></i>
                                        </div>
                                        <div>
                                            <p class="mb-0">Current Plan</p>
                                            <h5 class="fs-4 fw-semibold">750.000 Monthly Visits</h5>
                                        </div>
                                    </div>
                                    <a class="text-dark fs-6 d-flex align-items-center justify-content-center bg-transparent p-2 fs-4 rounded-circle" href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Add">
                                        <i class="ti ti-circle-plus"></i>
                                    </a>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <button class="btn btn-primary">Change Plan</button>
                                    <button class="btn bg-danger-subtle text-danger">Reset Plan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="card border shadow-none">
                            <div class="card-body p-4">
                                <h4 class="card-title">Payment Method</h4>
                                <p class="card-subtitle">On 26 December, 2024</p>
                                <div class="d-flex align-items-center justify-content-between mt-7">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-credit-card text-dark d-block fs-7" width="22" height="22"></i>
                                        </div>
                                        <div>
                                            <h5 class="fs-4 fw-semibold">Visa</h5>
                                            <p class="mb-0 text-dark">*****2102</p>
                                        </div>
                                    </div>
                                    <a class="text-dark fs-6 d-flex align-items-center justify-content-center bg-transparent p-2 fs-4 rounded-circle" href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit">
                                        <i class="ti ti-pencil-minus"></i>
                                    </a>
                                </div>
                                <p class="my-2">If you updated your payment method, it will only be dislpayed here after your
                                    next billing cycle.</p>
                                <div class="d-flex align-items-center gap-3">
                                    <button class="btn bg-danger-subtle text-danger">Cancel Subscription</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-end gap-6">
                            <button class="btn btn-primary">Save</button>
                            <button class="btn bg-danger-subtle text-danger">Cancel</button>
                        </div>
                    </div>
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
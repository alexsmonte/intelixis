<?php

$this->BreadcrumbsPlus->add([['title' => 'Consultas', 'url' => ['controller' => 'Consultas', 'action' => 'index']], ['title' => 'Realizando consulta']]);

?>

<?= $this->element('barra-titulo', ['title' => $title]); ?>

<div class="card w-100 position-relative overflow-hidden" id="appConsulta">
    <div class="px-4 py-3 border-bottom">
        <div class="card-body p-4">
            <div class="d-flex mb-3 align-items-center">
                <h4 class="card-title mb-0"><?= __('Dados da consulta') ?></h4>
            </div>
            <?= $this->Form->create($consulta) ?>
            <div class="row mb-5">
                <div class="col-md-12 mb-3">
                    <?= $this->Form->control('cliente_id', ['label' => 'Cliente', 'class' => 'form-control', 'options' => $clientes]); ?>
                </div>
            </div>

            <div class="row">
                <!-- Item original (fixo) - MANTENHA COMO ESTÁ -->
                <!-- ITENS DINÂMICOS ADICIONADOS VIA VUE -->

                <div v-for="(item, index) in itens" :key="item.id" class="col-lg-12 d-flex align-items-stretch pb-5"
                    :id="'procedimento-' + item.id">
                    <div class="card w-100 border position-relative overflow-hidden boxed-layout">
                        <div class="card-body p-4">
                            <div class="d-flex mb-4 justify-content-between align-items-center">
                                <h4 class="card-title mb-0">{{ servicoLabel(item.servico_id) }} </h4>
                                <div class="dropdown">
                                    <!-- Links comuns -->
                                    <?= $this->Html->link('<iconify-icon icon="solar:document-add-bold-duotone" class="fs-9 aside-icon"></iconify-icon>', ['#'], ['class' => 'sidebar-link primary-hover-bg', 'aria-expanded' => 'false', 'alt' => 'Registrar anamnese', 'title' => 'Registrar anamnese', 'escape' => false, 'v-show' => 'item.id && item.aprovar == 1', '@click.prevent' => 'abrirModalAnamnese(item)']) ?>
                                    <?= $this->Html->link('<iconify-icon icon="solar:notebook-bold-duotone" class="fs-9 aside-icon"></iconify-icon>', ['#'], ['class' => 'sidebar-link primary-hover-bg', 'aria-expanded' => 'false', 'alt' => 'Registrar evolução', 'title' => 'Registrar evolução', 'escape' => false, 'v-show' => 'item.id && item.aprovar == 1', '@click.prevent' => 'abrirModalAnotacao(item)']) ?>

                                    <?= $this->Html->link('<iconify-icon icon="solar:book-bookmark-bold-duotone" class="fs-9 aside-icon"></iconify-icon>', ['#'], ['class' => 'sidebar-link primary-hover-bg', 'aria-expanded' => 'false', 'alt' => 'Emitir receita', 'title' => 'Emitir receita', 'escape' => false, 'v-show' => 'item.id && item.aprovar == 1', '@click.prevent' => 'abrirModalReceita(item)']) ?>

                                    <?= $this->Html->link('<iconify-icon icon="solar:document-medicine-bold-duotone" class="fs-9 aside-icon"></iconify-icon>', ['#'], ['class' => 'sidebar-link primary-hover-bg', 'aria-expanded' => 'false', 'alt' => 'Laudo', 'title' => 'Laudo', 'escape' => false, 'v-show' => 'item.id && item.aprovar == 1', '@click.prevent' => 'abrirModalLaudo(item)']) ?>

                                    <?= $this->Html->link('<iconify-icon icon="solar:medical-kit-bold-duotone" class="fs-9 aside-icon"></iconify-icon>', ['#'], ['class' => 'sidebar-link primary-hover-bg', 'aria-expanded' => 'false', 'alt' => 'Documentos', 'title' => 'Documentos', 'escape' => false, 'v-show' => 'item.id && item.aprovar == 1', '@click.prevent' => 'abrirModalDocumento(item)']) ?>

                                    <?= $this->Html->link('<iconify-icon icon="solar:microphone-2-bold-duotone" class="fs-9 aside-icon"></iconify-icon>', ['#'], ['class' => 'sidebar-link primary-hover-bg', 'aria-expanded' => 'false', 'alt' => 'Gravar', 'title' => 'Gravar', 'escape' => false, 'v-show' => 'item.id && item.aprovar == 1']) ?>
                                    <a href="#" class="sidebar-link primary-hover-bg" @click.prevent="abrirUpload(item)"
                                        v-show="item.id && item.aprovar == 1" title="Enviar arquivo"><iconify-icon
                                            icon="solar:cloud-upload-bold-duotone"
                                            class="fs-9 aside-icon"></iconify-icon></a>
                                    <!-- Botão excluir COM VUE - CORRIGIDO -->
                                    <a href="#" class="sidebar-link danger-hover-bg"
                                        @click.prevent="removerItem(item, index)"
                                        :title="'Excluir procedimento ' + servicoLabel(item.servico_id)">
                                        <iconify-icon icon="solar:trash-bin-minimalistic-bold-duotone"
                                            class="fs-9 aside-icon"></iconify-icon>
                                    </a>
                                    <a href="#" class="sidebar-link primary-hover-bg"
                                        @click.prevent="item.aberto = !item.aberto" v-show="item.id">
                                        <iconify-icon
                                            :icon="item.aberto ? 'solar:minimize-square-bold-duotone':'solar:maximize-square-bold-duotone'"
                                            class="fs-9 aside-icon"></iconify-icon>
                                    </a>
                                </div>
                            </div>

                            <div class="col-lg-12 mb-3">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="input mb-3 select required">
                                            <label for="procedimento">Procedimento</label>
                                            <select2 v-model='item.servico_id' class="form-select" :options="servicos"
                                                @input="selecionarProcedimento(item, index)" :disabled="item.aprovar"
                                                required></select2>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <?= $this->Form->control('profissional_id', ['label' => 'Profissional', 'class' => 'form-select', 'options' => $profissionais, 'v-model' => 'item.profissional_id', 'empty' => true, ':disabled' => 'item.aprovar']); ?>
                                    </div>
                                    <div class="col-lg-1" style="height: 70px;">
                                        <div class="input mb-3 text">
                                            <label for="quantidade">Quantidade</label>
                                            <input type="text" name="quantidade" id="quantidade" class="form-control"
                                                v-model="item.quantidade" @input="atualizarParcelas(item)"
                                                :disabled="item.aprovar">
                                            <input type="hidden" name="id" id="id" v-model="item.id">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <?= $this->Form->control('valor_unitario', ['label' => 'Valor unitário', 'class' => 'form-control', 'v-model' => 'item.valor_unitario', 'v-money' => 'money', '@input' => 'atualizarParcelas(item)', ':disabled' => 'item.aprovar']); ?>
                                    </div>

                                </div>
                            </div>

                            <div class="row mb-5" v-show="item.id && item.aprovar == 1 && item.aberto == 1"
                                style="height: 350px;">
                                <div class="col-lg-12">
                                    <ul class="nav2" role="tablist">
                                        <li class="nav2-item" role="presentation">
                                            <a class="nav2-link active" data-bs-toggle="tab"
                                                :href="'#anamnese-' + item.id" role="tab"
                                                :aria-controls="'anamnese-' + item.id" aria-selected="true">Anamnese
                                                <!-- badge com quantidade -->
                                                <span v-if="item.anamneses && item.anamneses.length > 0"
                                                    class="badge bg-primary ms-2">
                                                    {{ item.anamneses.length }}
                                                </span>
                                            </a>
                                        </li>
                                        <li class="nav2-item" role="presentation">
                                            <a class="nav2-link" data-bs-toggle="tab"
                                                :href="'#evolucao-clinica-' + item.id" role="tab"
                                                :aria-controls="'evolucao-clinica-' + item.id"
                                                aria-selected="true">Evolução Clínica
                                                <!-- badge com quantidade -->
                                                <span v-if="item.anotacoes && item.anotacoes.length > 0"
                                                    class="badge bg-primary ms-2">
                                                    {{ item.anotacoes.length }}
                                                </span>
                                            </a>
                                        </li>
                                        <li class="nav2-item" role="presentation">
                                            <a class="nav2-link" data-bs-toggle="tab" :href="'#receita-' + item.id"
                                                role="tab" :aria-controls="'receita-' + item.id"
                                                aria-selected="true">Receitas
                                                <!-- badge com quantidade -->
                                                <span v-if="item.receitas && item.receitas.length > 0"
                                                    class="badge bg-primary ms-2">
                                                    {{ item.receitas.length }}
                                                </span>
                                            </a>
                                        </li>
                                        <li class="nav2-item" role="presentation">
                                            <a class="nav2-link" data-bs-toggle="tab" :href="'#laudo-' + item.id"
                                                role="tab" :aria-controls="'laudo-' + item.id"
                                                aria-selected="true">Laudo
                                                <!-- badge com quantidade -->
                                                <span v-if="item.laudos && item.laudos.length > 0"
                                                    class="badge bg-primary ms-2">
                                                    {{ item.laudos.length }}
                                                </span>
                                            </a>
                                        </li>
                                        <li class="nav2-item" role="presentation">
                                            <a class="nav2-link" data-bs-toggle="tab" :href="'#documento-' + item.id"
                                                role="tab" :aria-controls="'documento-' + item.id"
                                                aria-selected="true">Documento
                                                <!-- badge com quantidade -->
                                                <span v-if="item.docs && item.docs.length > 0"
                                                    class="badge bg-primary ms-2">
                                                    {{ item.docs.length }}
                                                </span>
                                            </a>
                                        </li>

                                        <li class="nav2-item" role="presentation">
                                            <a class="nav2-link" data-bs-toggle="tab" :href="'#anexo-' + item.id"
                                                role="tab" :aria-controls="'anexo-' + item.id" aria-selected="false">
                                                Anexos
                                                <!-- badge com quantidade -->
                                                <span v-if="item.anexos && item.anexos.length > 0"
                                                    class="badge bg-primary ms-2">
                                                    {{ item.anexos.length }}
                                                </span>
                                            </a>
                                        </li>
                                        <li class="nav2-item" role="presentation">
                                            <a class="nav2-link" data-bs-toggle="tab" :href="'#pagamento-' + item.id"
                                                role="tab" :aria-controls="'pagamento-' + item.id"
                                                aria-selected="true">Pagamento</a>
                                        </li>

                                    </ul>
                                    <div class="tab-content mt-3">
                                        <div class="tab-pane fade show active" :id="'anamnese-' + item.id"
                                            role="tabpanel"
                                            style="max-height: 50vh; min-height:200px; overflow-y: auto;">
                                            <!--- Anamnese --->
                                            <table class="table table-hover  mb-0 align-middle">
                                                <thead class="text-dark fs-4">
                                                    <tr>
                                                        <th style="width: 30%;">
                                                            <h6 class="fs-4 fw-semibold mb-0">Descrição</h6>
                                                        </th>
                                                        <th style="width: 15%; text-align: center;">
                                                            <h6 class="fs-4 fw-semibold mb-0">Feito em</h6>
                                                        </th>
                                                        <th style="width: 25%; text-align: center;">
                                                            <h6 class="fs-4 fw-semibold mb-0">Assinaturas</h6>
                                                        </th>
                                                        <th style="width: 15%; text-align: center;">
                                                            <h6 class="fs-4 fw-semibold mb-0">Opções</h6>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody v-if="item.anamneses && item.anamneses.length">
                                                    <tr v-for="(anamnese, aaIndex) in item.anamneses"
                                                        :key="anamnese.id">
                                                        <td style="white-space: normal;"
                                                            :title="resumoReceita(anamnese.texto, 500)">{{
                                                            resumoReceita(anamnese.texto, 120) }}</td>
                                                        <td style="text-align: center;">{{
                                                            formatarData(anamnese.created) }}</td>

                                                        <!-- Status Anamnese -->
                                                        <td style="text-align: center;">
                                                            <template v-if="anamnese.assinatura_prof_hash">
                                                                <iconify-icon icon="solar:lock-keyhole-bold-duotone"
                                                                    class="fs-7 text-success cursor-pointer"
                                                                    @click="abrirLogsDocumento(anamnese)"
                                                                    title="Concluído - Clique para ver detalhes"></iconify-icon>
                                                            </template>
                                                            <template
                                                                v-else-if="anamnese.exigir_assinatura_paciente && !anamnese.assinatura_hash">
                                                                <span
                                                                    class="badge d-inline-flex align-items-center gap-1 px-2 py-1 cursor-pointer bg-warning-subtle text-warning"
                                                                    @click="abrirLogsDocumento(anamnese)"
                                                                    style="border:1px solid rgba(251,191,36,0.3);border-radius:8px;font-size:11px;"
                                                                    title="Clique para ver detalhes">
                                                                    <iconify-icon icon="solar:handshake-bold-duotone"
                                                                        class="fs-6"></iconify-icon> Aguardando Paciente
                                                                </span>
                                                            </template>
                                                            <template
                                                                v-else-if="anamnese.exigir_assinatura_profissional && !anamnese.assinatura_prof_hash">
                                                                <span
                                                                    class="badge d-inline-flex align-items-center gap-1 px-2 py-1 cursor-pointer bg-info-subtle text-info"
                                                                    @click="abrirLogsDocumento(anamnese)"
                                                                    style="border:1px solid rgba(59,130,246,0.3);border-radius:8px;font-size:11px;"
                                                                    title="Clique para ver detalhes">
                                                                    Aguardando Profissional
                                                                </span>
                                                            </template>
                                                            <template v-else-if="anamnese.assinatura_hash">
                                                                <iconify-icon icon="solar:lock-keyhole-bold-duotone"
                                                                    class="fs-7 text-success cursor-pointer"
                                                                    @click="abrirLogsDocumento(anamnese)"
                                                                    title="Concluído - Clique para ver detalhes"></iconify-icon>
                                                            </template>
                                                            <template v-else>
                                                                <span
                                                                    class="badge d-inline-flex align-items-center gap-1 px-2 py-1 cursor-pointer bg-light text-muted"
                                                                    @click="abrirLogsDocumento(anamnese)"
                                                                    style="border:1px solid rgba(148,163,184,0.3);border-radius:8px;font-size:11px;"
                                                                    title="Clique para ver detalhes">
                                                                    Não Exigida
                                                                </span>
                                                            </template>
                                                        </td>

                                                        <td>
                                                            <div class="dropdown">
                                                                <button
                                                                    class="btn  bg-transparent text-primary  d-inline-flex align-items-center justify-content-center"
                                                                    type="button" :id="'dropdownMenu-' + anamnese.id"
                                                                    data-bs-toggle="dropdown" aria-expanded="false"><i
                                                                        class="fas fa-bars"></i></button>
                                                                <ul class="dropdown-menu dropdown-menu-end"
                                                                    :aria-labelledby="'dropdownMenu-' + anamnese.id">
                                                                    <!-- Assinar Paciente -->
                                                                    <li class="dropdown-item" v-if="!anamnese.assinatura_hash">
                                                                        <a :href="`<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'assinarPaciente'], ['fullBase' => true]) ?>/${anamnese.id}`"
                                                                            class="link-state link-success2 d-flex align-items-center gap-2"
                                                                            title="Gerar link de assinatura do paciente">
                                                                            <iconify-icon icon="solar:user-hand-up-bold-duotone" class="fs-7"></iconify-icon> Assinar Paciente
                                                                        </a>
                                                                    </li>
                                                                    <!-- Assinar Profissional -->
                                                                    <li class="dropdown-item"
                                                                        v-if="!anamnese.assinatura_prof_hash && anamnese.exigir_assinatura_profissional"
                                                                        :class="{ 'disabled opacity-50': anamnese.exigir_assinatura_paciente && !anamnese.assinatura_hash }">
                                                                        <a :href="anamnese.exigir_assinatura_paciente && !anamnese.assinatura_hash ? 'javascript:void(0)' : `<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'assinarProfissional'], ['fullBase' => true]) ?>/${anamnese.id}`"
                                                                            class="link-state link-primary2 d-flex align-items-center gap-2"
                                                                            :title="anamnese.exigir_assinatura_paciente && !anamnese.assinatura_hash ? 'A assinatura do paciente é obrigatória.' : 'Assinar digitalmente'">
                                                                            <iconify-icon
                                                                                icon="solar:pen-new-square-bold-duotone"
                                                                                class="fs-7"></iconify-icon> Assinar
                                                                            Profissional
                                                                        </a>
                                                                    </li>
                                                                    <li
                                                                        v-if="!anamnese.assinatura_prof_hash && anamnese.exigir_assinatura_profissional">
                                                                        <hr class="dropdown-divider">
                                                                    </li>

                                                                    <li class="dropdown-item">
                                                                        <a :href="`<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'imprimir'], ['fullBase' => true]) ?>/${anamnese.id}`"
                                                                            target="_blank"
                                                                            class="link-state link-primary2"
                                                                            title="Imprimir documento">
                                                                            <iconify-icon
                                                                                icon="solar:printer-bold-duotone"
                                                                                class="fs-7"></iconify-icon>Imprimir
                                                                            documento
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <hr class="dropdown-divider">
                                                                    </li>
                                                                    <li class="dropdown-item">
                                                                        <a href="#" class="link-state link-info2"
                                                                            @click.stop="abrirLogsDocumento(anamnese)">
                                                                            <iconify-icon
                                                                                icon="solar:info-circle-bold-duotone"
                                                                                class="fs-7"></iconify-icon> Detalhes
                                                                        </a>
                                                                    </li>
                                                                    <li v-if="!anamnese.assinatura_prof_hash && !anamnese.assinatura_hash">
                                                                        <hr class="dropdown-divider">
                                                                    </li>
                                                                    <li class="dropdown-item" v-if="!anamnese.assinatura_prof_hash && !anamnese.assinatura_hash">
                                                                        <a :href="`#anamnese-${anamnese.id}`"
                                                                            class="link-state link-danger2"
                                                                            title="Excluir documento"
                                                                            @click.stop="excluirDocumento(item, anamnese, aaIndex, 'anamneses')">
                                                                            <iconify-icon
                                                                                icon="solar:trash-bin-minimalistic-bold-duotone"
                                                                                class="fs-7"></iconify-icon> Excluir
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tbody v-else>
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">Nenhum registro
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" :id="'evolucao-clinica-' + item.id" role="tabpanel"
                                            style="max-height: 50vh; min-height:200px; overflow-y: auto;">
                                            <!--- Anotação --->
                                            <table class="table table-hover  mb-0 align-middle">
                                                <thead class="text-dark fs-4">
                                                    <tr>
                                                        <th style="width: 30%;">
                                                            <h6 class="fs-4 fw-semibold mb-0">Descrição</h6>
                                                        </th>
                                                        <th style="width: 15%; text-align: center;">
                                                            <h6 class="fs-4 fw-semibold mb-0">Feito em</h6>
                                                        </th>
                                                        <th style="width: 25%; text-align: center;">
                                                            <h6 class="fs-4 fw-semibold mb-0">Assinaturas</h6>
                                                        </th>
                                                        <th style="width: 15%; text-align: center;">
                                                            <h6 class="fs-4 fw-semibold mb-0">Opções</h6>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody v-if="item.anotacoes && item.anotacoes.length">
                                                    <tr v-for="(anotacao, aaaIndex) in item.anotacoes"
                                                        :key="anotacao.id">
                                                        <td style="white-space: normal;"
                                                            :title="resumoReceita(anotacao.texto, 500)">{{
                                                            resumoReceita(anotacao.texto, 120) }}
                                                        </td>

                                                        <td style="text-align: center;">{{
                                                            formatarData(anotacao.created) }}</td>

                                                        <!-- Status Anotação -->
                                                        <td style="text-align: center;">
                                                            <template v-if="anotacao.assinatura_prof_hash">
                                                                <iconify-icon icon="solar:lock-keyhole-bold-duotone"
                                                                    class="fs-7 text-success cursor-pointer"
                                                                    @click="abrirLogsDocumento(anotacao)"
                                                                    title="Concluído - Clique para ver detalhes"></iconify-icon>
                                                            </template>
                                                            <template
                                                                v-else-if="anotacao.exigir_assinatura_paciente && !anotacao.assinatura_hash">
                                                                <span
                                                                    class="badge d-inline-flex align-items-center gap-1 px-2 py-1 cursor-pointer bg-warning-subtle text-warning"
                                                                    @click="abrirLogsDocumento(anotacao)"
                                                                    style="border:1px solid rgba(251,191,36,0.3);border-radius:8px;font-size:11px;"
                                                                    title="Clique para ver detalhes">
                                                                    <iconify-icon icon="solar:handshake-bold-duotone"
                                                                        class="fs-6"></iconify-icon> Aguardando Paciente
                                                                </span>
                                                            </template>
                                                            <template
                                                                v-else-if="anotacao.exigir_assinatura_profissional && !anotacao.assinatura_prof_hash">
                                                                <span
                                                                    class="badge d-inline-flex align-items-center gap-1 px-2 py-1 cursor-pointer bg-info-subtle text-info"
                                                                    @click="abrirLogsDocumento(anotacao)"
                                                                    style="border:1px solid rgba(59,130,246,0.3);border-radius:8px;font-size:11px;"
                                                                    title="Clique para ver detalhes">
                                                                    Aguardando Profissional
                                                                </span>
                                                            </template>
                                                            <template v-else-if="anotacao.assinatura_hash">
                                                                <iconify-icon icon="solar:lock-keyhole-bold-duotone"
                                                                    class="fs-7 text-success cursor-pointer"
                                                                    @click="abrirLogsDocumento(anotacao)"
                                                                    title="Concluído - Clique para ver detalhes"></iconify-icon>
                                                            </template>
                                                            <template v-else>
                                                                <span
                                                                    class="badge d-inline-flex align-items-center gap-1 px-2 py-1 cursor-pointer bg-light text-muted"
                                                                    @click="abrirLogsDocumento(anotacao)"
                                                                    style="border:1px solid rgba(148,163,184,0.3);border-radius:8px;font-size:11px;"
                                                                    title="Clique para ver detalhes">
                                                                    Não Exigida
                                                                </span>
                                                            </template>
                                                        </td>

                                                        <td>
                                                            <div class="dropdown">
                                                                <button
                                                                    class="btn  bg-transparent text-primary  d-inline-flex align-items-center justify-content-center"
                                                                    type="button" :id="'dropdownMenu-' + anotacao.id"
                                                                    data-bs-toggle="dropdown" aria-expanded="false"><i
                                                                        class="fas fa-bars"></i></button>
                                                                <ul class="dropdown-menu dropdown-menu-end"
                                                                    :aria-labelledby="'dropdownMenu-' + anotacao.id">
                                                                    <!-- Assinar Paciente -->
                                                                    <li class="dropdown-item" v-if="!anotacao.assinatura_hash">
                                                                        <a :href="`<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'assinarPaciente'], ['fullBase' => true]) ?>/${anotacao.id}`"
                                                                            class="link-state link-success2 d-flex align-items-center gap-2"
                                                                            title="Gerar link de assinatura do paciente">
                                                                            <iconify-icon icon="solar:user-hand-up-bold-duotone" class="fs-7"></iconify-icon> Assinar Paciente
                                                                        </a>
                                                                    </li>
                                                                    <!-- Assinar Profissional -->
                                                                    <li class="dropdown-item"
                                                                        v-if="!anotacao.assinatura_prof_hash && anotacao.exigir_assinatura_profissional"
                                                                        :class="{ 'disabled opacity-50': anotacao.exigir_assinatura_paciente && !anotacao.assinatura_hash }">
                                                                        <a :href="anotacao.exigir_assinatura_paciente && !anotacao.assinatura_hash ? 'javascript:void(0)' : `<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'assinarProfissional'], ['fullBase' => true]) ?>/${anotacao.id}`"
                                                                            class="link-state link-primary2 d-flex align-items-center gap-2"
                                                                            :title="anotacao.exigir_assinatura_paciente && !anotacao.assinatura_hash ? 'A assinatura do paciente é obrigatória.' : 'Assinar digitalmente'">
                                                                            <iconify-icon
                                                                                icon="solar:pen-new-square-bold-duotone"
                                                                                class="fs-7"></iconify-icon> Assinar
                                                                            Profissional
                                                                        </a>
                                                                    </li>
                                                                    <li
                                                                        v-if="!anotacao.assinatura_prof_hash && anotacao.exigir_assinatura_profissional">
                                                                        <hr class="dropdown-divider">
                                                                    </li>

                                                                    <li class="dropdown-item">
                                                                        <a :href="`<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'imprimir'], ['fullBase' => true]) ?>/${anotacao.id}`"
                                                                            target="_blank"
                                                                            class="link-state link-primary2"
                                                                            title="Imprimir documento">
                                                                            <iconify-icon
                                                                                icon="solar:printer-bold-duotone"
                                                                                class="fs-7"></iconify-icon>Imprimir
                                                                            documento
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <hr class="dropdown-divider">
                                                                    </li>
                                                                    <li class="dropdown-item">
                                                                        <a href="#" class="link-state link-info2"
                                                                            @click.stop="abrirLogsDocumento(anotacao)">
                                                                            <iconify-icon
                                                                                icon="solar:info-circle-bold-duotone"
                                                                                class="fs-7"></iconify-icon> Detalhes
                                                                        </a>
                                                                    </li>
                                                                    <li v-if="!anotacao.assinatura_prof_hash && !anotacao.assinatura_hash">
                                                                        <hr class="dropdown-divider">
                                                                    </li>
                                                                    <li class="dropdown-item" v-if="!anotacao.assinatura_prof_hash && !anotacao.assinatura_hash">
                                                                        <a :href="`#anotacao-${anotacao.id}`"
                                                                            class="link-state link-danger2"
                                                                            title="Excluir documento"
                                                                            @click.stop="excluirDocumento(item, anotacao, aaaIndex, 'anotacoes')">
                                                                            <iconify-icon
                                                                                icon="solar:trash-bin-minimalistic-bold-duotone"
                                                                                class="fs-7"></iconify-icon> Excluir
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tbody v-else>
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">Nenhum registro
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="tab-pane fade" :id="'documento-' + item.id" role="tabpanel"
                                            style="max-height: 50vh; min-height:200px; overflow-y: auto;">
                                            <!--- Documento --->
                                            <table class="table table-hover  mb-0 align-middle">
                                                <thead class="text-dark fs-4">
                                                    <tr>
                                                        <th style="width: 30%;">
                                                            <h6 class="fs-4 fw-semibold mb-0">Descrição</h6>
                                                        </th>
                                                        <th style="width: 15%; text-align:center;">
                                                            <h6 class="fs-4 fw-semibold mb-0">Profissional</h6>
                                                        </th>
                                                        <th style="width: 15%; text-align: center;">
                                                            <h6 class="fs-4 fw-semibold mb-0">Feito em</h6>
                                                        </th>
                                                        <th style="width: 25%; text-align: center;">
                                                            <h6 class="fs-4 fw-semibold mb-0">Assinaturas</h6>
                                                        </th>
                                                        <th style="width: 15%; text-align: center;">
                                                            <h6 class="fs-4 fw-semibold mb-0">Opções</h6>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody v-if="item.docs && item.docs.length">
                                                    <tr v-for="(doc, documentoIndex) in item.docs" :key="doc.id">
                                                        <td style="white-space: normal;"
                                                            :title="resumoReceita(doc.texto, 500)">{{
                                                            resumoReceita(doc.texto, 120) }}</td>
                                                        <td style="text-align: center;">{{ doc.profissional?.nome || '-'
                                                            }}</td>
                                                        <td style="text-align: center;">{{ formatarData(doc.created) }}
                                                        </td>

                                                        <!-- Coluna Única: Status Inteligente -->
                                                        <td style="text-align: center;">
                                                            <!-- Caso 1: Já assinado pelo Profissional -->
                                                            <template v-if="doc.assinatura_prof_hash">
                                                                <iconify-icon icon="solar:lock-keyhole-bold-duotone"
                                                                    class="fs-7 text-success cursor-pointer"
                                                                    @click="abrirLogsDocumento(doc)"
                                                                    title="Concluído - Clique para ver detalhes"></iconify-icon>
                                                            </template>

                                                            <!-- Caso 2: Aguardando Paciente -->
                                                            <template
                                                                v-else-if="doc.exigir_assinatura_paciente && !doc.assinatura_hash">
                                                                <span
                                                                    class="badge d-inline-flex align-items-center gap-1 px-2 py-1 cursor-pointer bg-warning-subtle text-warning"
                                                                    @click="abrirLogsDocumento(doc)"
                                                                    style="border:1px solid rgba(251,191,36,0.3);border-radius:8px;font-size:11px;white-space:nowrap;"
                                                                    title="Clique para ver detalhes e logs">
                                                                    <iconify-icon icon="solar:handshake-bold-duotone"
                                                                        class="fs-6"></iconify-icon>
                                                                    Aguardando Paciente
                                                                </span>
                                                            </template>

                                                            <!-- Caso 3: Aguardando Profissional -->
                                                            <template
                                                                v-else-if="doc.exigir_assinatura_profissional && !doc.assinatura_prof_hash">
                                                                <span
                                                                    class="badge d-inline-flex align-items-center gap-1 px-2 py-1 cursor-pointer bg-info-subtle text-info"
                                                                    @click="abrirLogsDocumento(doc)"
                                                                    style="border:1px solid rgba(59,130,246,0.3);border-radius:8px;font-size:11px;white-space:nowrap;"
                                                                    title="Clique para ver detalhes e logs">
                                                                    Aguardando Profissional
                                                                </span>
                                                            </template>

                                                            <!-- Caso 4: Concluído (Paciente ou Profissional) -->
                                                            <template
                                                                v-else-if="doc.assinatura_prof_hash || doc.assinatura_hash">
                                                                <iconify-icon icon="solar:lock-keyhole-bold-duotone"
                                                                    class="fs-7 text-success cursor-pointer"
                                                                    @click="abrirLogsDocumento(doc)"
                                                                    title="Concluído - Clique para ver detalhes"></iconify-icon>
                                                            </template>

                                                            <!-- Caso 5: Documento Simples -->
                                                            <template v-else>
                                                                <span
                                                                    class="badge d-inline-flex align-items-center gap-1 px-2 py-1 cursor-pointer bg-light text-muted"
                                                                    @click="abrirLogsDocumento(doc)"
                                                                    style="border:1px solid rgba(148,163,184,0.3);border-radius:8px;font-size:11px;white-space:nowrap;"
                                                                    title="Clique para ver detalhes e logs">
                                                                    Não Exigida
                                                                </span>
                                                            </template>
                                                        </td>

                                                        <!-- Coluna: Opções -->
                                                        <td>
                                                            <div class="dropdown">
                                                                <button
                                                                    class="btn  bg-transparent text-primary  d-inline-flex align-items-center justify-content-center"
                                                                    type="button" :id="'dropdownMenu-' + doc.id"
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <iconify-icon icon="solar:menu-dots-bold"
                                                                        class="fs-7"></iconify-icon>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end"
                                                                    :aria-labelledby="'dropdownMenu-' + doc.id">
                                                                    <!-- Assinar Paciente -->
                                                                    <li class="dropdown-item" v-if="!doc.assinatura_hash">
                                                                        <a :href="`<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'assinarPaciente'], ['fullBase' => true]) ?>/${doc.id}`"
                                                                            class="link-state link-success2 d-flex align-items-center gap-2"
                                                                            title="Gerar link de assinatura do paciente">
                                                                            <iconify-icon icon="solar:user-hand-up-bold-duotone" class="fs-7"></iconify-icon> Assinar Paciente
                                                                        </a>
                                                                    </li>
                                                                    <!-- Ação: Assinar Profissional com Precedência -->
                                                                    <li class="dropdown-item"
                                                                        v-if="!doc.assinatura_prof_hash && doc.exigir_assinatura_profissional"
                                                                        :class="{ 'disabled opacity-50': doc.exigir_assinatura_paciente && !doc.assinatura_hash }">
                                                                        <a :href="doc.exigir_assinatura_paciente && !doc.assinatura_hash ? 'javascript:void(0)' : `<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'assinarProfissional'], ['fullBase' => true]) ?>/${doc.id}`"
                                                                            class="link-state link-primary2 d-flex align-items-center gap-2"
                                                                            :title="doc.exigir_assinatura_paciente && !doc.assinatura_hash ? 'A assinatura do paciente é obrigatória e deve ser realizada antes da assinatura profissional para evitar o fechamento do documento.' : 'Assinar digitalmente com certificado ICP-Brasil'">
                                                                            <iconify-icon
                                                                                icon="solar:pen-new-square-bold-duotone"
                                                                                class="fs-7"></iconify-icon> Assinar
                                                                            Profissional
                                                                        </a>
                                                                    </li>
                                                                    <li
                                                                        v-if="!doc.assinatura_prof_hash && doc.exigir_assinatura_profissional">
                                                                        <hr class="dropdown-divider">
                                                                    </li>

                                                                    <li class="dropdown-item">
                                                                        <a :href="`<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'imprimir'], ['fullBase' => true]) ?>/${doc.id}`"
                                                                            target="_blank"
                                                                            class="link-state link-primary2"
                                                                            title="Imprimir documento">
                                                                            <iconify-icon
                                                                                icon="solar:printer-bold-duotone"
                                                                                class="fs-7"></iconify-icon>Imprimir
                                                                            documento
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <hr class="dropdown-divider">
                                                                    </li>
                                                                    <li class="dropdown-item">
                                                                        <a href="#" class="link-state link-info2"
                                                                            title="Visualizar detalhes e registros de log"
                                                                            @click.stop="abrirLogsDocumento(doc)">
                                                                            <iconify-icon
                                                                                icon="solar:info-circle-bold-duotone"
                                                                                class="fs-7"></iconify-icon> Detalhes do
                                                                            Documento
                                                                        </a>
                                                                    </li>
                                                                    <li v-if="!doc.assinatura_prof_hash && !doc.assinatura_hash">
                                                                        <hr class="dropdown-divider">
                                                                    </li>
                                                                    <li class="dropdown-item" v-if="!doc.assinatura_prof_hash && !doc.assinatura_hash">
                                                                        <a :href="`#documento-${doc.id}`"
                                                                            class="link-state link-danger2"
                                                                            title="Excluir documento"
                                                                            @click.stop="excluirDocumento(item, doc, documentoIndex, 'docs')">
                                                                            <iconify-icon
                                                                                icon="solar:trash-bin-minimalistic-bold-duotone"
                                                                                class="fs-7"></iconify-icon> Excluir
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tbody v-else>
                                                    <tr>
                                                        <td colspan="5" class="text-center text-muted">Nenhum registro
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>


                                        <div class="tab-pane fade" :id="'laudo-' + item.id" role="tabpanel"
                                            style="max-height: 50vh; min-height:200px; overflow-y: auto;">
                                            <!--- laudo --->
                                            <table class="table table-hover  mb-0 align-middle">
                                                <thead class="text-dark fs-4">
                                                    <tr>
                                                        <th style="width: 30%;">
                                                            <h6 class="fs-4 fw-semibold mb-0">Descrição</h6>
                                                        </th>
                                                        <th style="width: 15%; text-align: center;">
                                                            <h6 class="fs-4 fw-semibold mb-0">Feito em</h6>
                                                        </th>
                                                        <th style="width: 25%; text-align: center;">
                                                            <h6 class="fs-4 fw-semibold mb-0">Assinaturas</h6>
                                                        </th>
                                                        <th style="width: 15%; text-align:center;">Opções</th>
                                                    </tr>
                                                </thead>
                                                <tbody v-if="item.laudos && item.laudos.length">
                                                    <tr v-for="(laudo, laudoIndex) in item.laudos" :key="laudo.id">
                                                        <td style="white-space: normal;"
                                                            :title="resumoReceita(laudo.texto, 500)">{{
                                                            resumoReceita(laudo.texto, 120) }}
                                                        </td>

                                                        <td style="text-align: center;">{{ formatarData(laudo.created)
                                                            }}</td>

                                                        <!-- Status Laudo -->
                                                        <td style="text-align: center;">
                                                            <template v-if="laudo.assinatura_prof_hash">
                                                                <iconify-icon icon="solar:lock-keyhole-bold-duotone"
                                                                    class="fs-7 text-success cursor-pointer"
                                                                    @click="abrirLogsDocumento(laudo)"
                                                                    title="Concluído - Clique para ver detalhes"></iconify-icon>
                                                            </template>
                                                            <template
                                                                v-else-if="laudo.exigir_assinatura_paciente && !laudo.assinatura_hash">
                                                                <span
                                                                    class="badge d-inline-flex align-items-center gap-1 px-2 py-1 cursor-pointer bg-warning-subtle text-warning"
                                                                    @click="abrirLogsDocumento(laudo)"
                                                                    style="border:1px solid rgba(251,191,36,0.3);border-radius:8px;font-size:11px;"
                                                                    title="Clique para ver detalhes">
                                                                    <iconify-icon icon="solar:handshake-bold-duotone"
                                                                        class="fs-6"></iconify-icon> Aguardando Paciente
                                                                </span>
                                                            </template>
                                                            <template
                                                                v-else-if="laudo.exigir_assinatura_profissional && !laudo.assinatura_prof_hash">
                                                                <span
                                                                    class="badge d-inline-flex align-items-center gap-1 px-2 py-1 cursor-pointer bg-info-subtle text-info"
                                                                    @click="abrirLogsDocumento(laudo)"
                                                                    style="border:1px solid rgba(59,130,246,0.3);border-radius:8px;font-size:11px;"
                                                                    title="Clique para ver detalhes">
                                                                    Aguardando Profissional
                                                                </span>
                                                            </template>
                                                            <template v-else-if="laudo.assinatura_hash">
                                                                <iconify-icon icon="solar:lock-keyhole-bold-duotone"
                                                                    class="fs-7 text-success cursor-pointer"
                                                                    @click="abrirLogsDocumento(laudo)"
                                                                    title="Concluído - Clique para ver detalhes"></iconify-icon>
                                                            </template>
                                                            <template v-else>
                                                                <span
                                                                    class="badge d-inline-flex align-items-center gap-1 px-2 py-1 cursor-pointer bg-light text-muted"
                                                                    @click="abrirLogsDocumento(laudo)"
                                                                    style="border:1px solid rgba(148,163,184,0.3);border-radius:8px;font-size:11px;"
                                                                    title="Clique para ver detalhes">
                                                                    Não Exigida
                                                                </span>
                                                            </template>
                                                        </td>

                                                        <td>
                                                            <div class="dropdown">
                                                                <button
                                                                    class="btn  bg-transparent text-primary  d-inline-flex align-items-center justify-content-center"
                                                                    type="button" :id="'dropdownMenu-' + laudo.id"
                                                                    data-bs-toggle="dropdown" aria-expanded="false"><i
                                                                        class="fas fa-bars"></i></button>
                                                                <ul class="dropdown-menu dropdown-menu-end"
                                                                    :aria-labelledby="'dropdownMenu-' + laudo.id">
                                                                    <!-- Assinar Paciente -->
                                                                    <li class="dropdown-item" v-if="!laudo.assinatura_hash">
                                                                        <a :href="`<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'assinarPaciente'], ['fullBase' => true]) ?>/${laudo.id}`"
                                                                            class="link-state link-success2 d-flex align-items-center gap-2"
                                                                            title="Gerar link de assinatura do paciente">
                                                                            <iconify-icon icon="solar:user-hand-up-bold-duotone" class="fs-7"></iconify-icon> Assinar Paciente
                                                                        </a>
                                                                    </li>
                                                                    <!-- Assinar Profissional -->
                                                                    <li class="dropdown-item"
                                                                        v-if="!laudo.assinatura_prof_hash && laudo.exigir_assinatura_profissional"
                                                                        :class="{ 'disabled opacity-50': laudo.exigir_assinatura_paciente && !laudo.assinatura_hash }">
                                                                        <a :href="laudo.exigir_assinatura_paciente && !laudo.assinatura_hash ? 'javascript:void(0)' : `<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'assinarProfissional'], ['fullBase' => true]) ?>/${laudo.id}`"
                                                                            class="link-state link-primary2 d-flex align-items-center gap-2"
                                                                            :title="laudo.exigir_assinatura_paciente && !laudo.assinatura_hash ? 'A assinatura do paciente é obrigatória.' : 'Assinar digitalmente'">
                                                                            <iconify-icon
                                                                                icon="solar:pen-new-square-bold-duotone"
                                                                                class="fs-7"></iconify-icon> Assinar
                                                                            Profissional
                                                                        </a>
                                                                    </li>
                                                                    <li
                                                                        v-if="!laudo.assinatura_prof_hash && laudo.exigir_assinatura_profissional">
                                                                        <hr class="dropdown-divider">
                                                                    </li>

                                                                    <li class="dropdown-item">
                                                                        <a :href="`<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'imprimir'], ['fullBase' => true]) ?>/${laudo.id}`"
                                                                            target="_blank"
                                                                            class="link-state link-primary2"
                                                                            title="Imprimir documento">
                                                                            <iconify-icon
                                                                                icon="solar:printer-bold-duotone"
                                                                                class="fs-7"></iconify-icon>Imprimir
                                                                            documento
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <hr class="dropdown-divider">
                                                                    </li>
                                                                    <li class="dropdown-item">
                                                                        <a href="#" class="link-state link-info2"
                                                                            @click.stop="abrirLogsDocumento(laudo)">
                                                                            <iconify-icon
                                                                                icon="solar:info-circle-bold-duotone"
                                                                                class="fs-7"></iconify-icon> Detalhes
                                                                        </a>
                                                                    </li>
                                                                    <li v-if="!laudo.assinatura_prof_hash && !laudo.assinatura_hash">
                                                                        <hr class="dropdown-divider">
                                                                    </li>
                                                                    <li class="dropdown-item" v-if="!laudo.assinatura_prof_hash && !laudo.assinatura_hash">
                                                                        <a :href="`#laudo-${laudo.id}`"
                                                                            class="link-state link-danger2"
                                                                            title="Excluir documento"
                                                                            @click.stop="excluirDocumento(item, laudo, laudoIndex, 'laudos')">
                                                                            <iconify-icon
                                                                                icon="solar:trash-bin-minimalistic-bold-duotone"
                                                                                class="fs-7"></iconify-icon> Excluir
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tbody v-else>
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">Nenhum registro
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="tab-pane fade" :id="'receita-' + item.id" role="tabpanel"
                                            style="max-height: 50vh; min-height:200px; overflow-y: auto;">
                                            <!--- Receita --->
                                            <table class="table text-nowrap mb-0 align-middle">

                                                <thead class="text-dark fs-4">
                                                    <tr>
                                                        <th style="width: 30%;">
                                                            <h6 class="fs-4 fw-semibold mb-0">Descrição</h6>
                                                        </th>
                                                        <th style="width: 15%;">
                                                            <h6 class="fs-4 fw-semibold mb-0">Profissional</h6>
                                                        </th>
                                                        <th style="width: 15%; text-align:center;">
                                                            <h6 class="fs-4 fw-semibold mb-0">Emitido em</h6>
                                                        </th>
                                                        <th style="width: 25%; text-align:center;">
                                                            <h6 class="fs-4 fw-semibold mb-0">Assinaturas</h6>
                                                        </th>
                                                        <th style="width: 15%; text-align:center;">Opções</th>
                                                    </tr>
                                                </thead>

                                                <tbody v-if="item.receitas && item.receitas.length">

                                                    <tr v-for="(receita, receitasIndex) in item.receitas"
                                                        :key="receita.id">

                                                        <td style="white-space: normal;"
                                                            :title="resumoReceita(receita.texto, 500)">
                                                            {{ resumoReceita(receita.texto, 120) }}
                                                        </td>

                                                        <td>{{ receita.profissional ? receita.profissional.nome : '-' }}
                                                        </td>

                                                        <td style="text-align:center;">
                                                            {{ formatarData(receita.created) }}
                                                        </td>

                                                        <!-- Status Receita -->
                                                        <td style="text-align: center;">
                                                            <template v-if="receita.assinatura_prof_hash">
                                                                <iconify-icon icon="solar:lock-keyhole-bold-duotone"
                                                                    class="fs-7 text-success cursor-pointer"
                                                                    @click="abrirLogsDocumento(receita)"
                                                                    title="Concluído - Clique para ver detalhes"></iconify-icon>
                                                            </template>
                                                            <template
                                                                v-else-if="receita.exigir_assinatura_paciente && !receita.assinatura_hash">
                                                                <span
                                                                    class="badge d-inline-flex align-items-center gap-1 px-2 py-1 cursor-pointer bg-warning-subtle text-warning"
                                                                    @click="abrirLogsDocumento(receita)"
                                                                    style="border:1px solid rgba(251,191,36,0.3);border-radius:8px;font-size:11px;"
                                                                    title="Clique para ver detalhes">
                                                                    <iconify-icon icon="solar:handshake-bold-duotone"
                                                                        class="fs-6"></iconify-icon> Aguardando Paciente
                                                                </span>
                                                            </template>
                                                            <template
                                                                v-else-if="receita.exigir_assinatura_profissional && !receita.assinatura_prof_hash">
                                                                <span
                                                                    class="badge d-inline-flex align-items-center gap-1 px-2 py-1 cursor-pointer bg-info-subtle text-info"
                                                                    @click="abrirLogsDocumento(receita)"
                                                                    style="border:1px solid rgba(59,130,246,0.3);border-radius:8px;font-size:11px;"
                                                                    title="Clique para ver detalhes">
                                                                    Aguardando Profissional
                                                                </span>
                                                            </template>
                                                            <template v-else-if="receita.assinatura_hash">
                                                                <iconify-icon icon="solar:lock-keyhole-bold-duotone"
                                                                    class="fs-7 text-success cursor-pointer"
                                                                    @click="abrirLogsDocumento(receita)"
                                                                    title="Concluído - Clique para ver detalhes"></iconify-icon>
                                                            </template>
                                                            <template v-else>
                                                                <span
                                                                    class="badge d-inline-flex align-items-center gap-1 px-2 py-1 cursor-pointer bg-light text-muted"
                                                                    @click="abrirLogsDocumento(receita)"
                                                                    style="border:1px solid rgba(148,163,184,0.3);border-radius:8px;font-size:11px;"
                                                                    title="Clique para ver detalhes">
                                                                    Não Exigida
                                                                </span>
                                                            </template>
                                                        </td>

                                                        <td>
                                                            <div class="dropdown">
                                                                <button
                                                                    class="btn  bg-transparent text-primary  d-inline-flex align-items-center justify-content-center"
                                                                    type="button" :id="'dropdownMenu-' + receita.id"
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="fas fa-bars"></i>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end"
                                                                    :aria-labelledby="'dropdownMenu-' + receita.id">
                                                                    <!-- Assinar Paciente -->
                                                                    <li class="dropdown-item" v-if="!receita.assinatura_hash">
                                                                        <a :href="`<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'assinarPaciente'], ['fullBase' => true]) ?>/${receita.id}`"
                                                                            class="link-state link-success2 d-flex align-items-center gap-2"
                                                                            title="Gerar link de assinatura do paciente">
                                                                            <iconify-icon icon="solar:user-hand-up-bold-duotone" class="fs-7"></iconify-icon> Assinar Paciente
                                                                        </a>
                                                                    </li>
                                                                    <!-- Assinar Profissional -->
                                                                    <li class="dropdown-item"
                                                                        v-if="!receita.assinatura_prof_hash && receita.exigir_assinatura_profissional"
                                                                        :class="{ 'disabled opacity-50': receita.exigir_assinatura_paciente && !receita.assinatura_hash }">
                                                                        <a :href="receita.exigir_assinatura_paciente && !receita.assinatura_hash ? 'javascript:void(0)' : `<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'assinarProfissional'], ['fullBase' => true]) ?>/${receita.id}`"
                                                                            class="link-state link-primary2 d-flex align-items-center gap-2"
                                                                            :title="receita.exigir_assinatura_paciente && !receita.assinatura_hash ? 'A assinatura do paciente é obrigatória.' : 'Assinar digitalmente'">
                                                                            <iconify-icon
                                                                                icon="solar:pen-new-square-bold-duotone"
                                                                                class="fs-7"></iconify-icon> Assinar
                                                                            Profissional
                                                                        </a>
                                                                    </li>
                                                                    <li
                                                                        v-if="!receita.assinatura_prof_hash && receita.exigir_assinatura_profissional">
                                                                        <hr class="dropdown-divider">
                                                                    </li>

                                                                    <li class="dropdown-item">
                                                                        <a :href="`<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'imprimir'], ['fullBase' => true]) ?>/${receita.id}`"
                                                                            target="_blank"
                                                                            class="link-state link-primary2"
                                                                            title="Imprimir receita">
                                                                            <iconify-icon
                                                                                icon="solar:printer-bold-duotone"
                                                                                class="fs-7"></iconify-icon>Imprimir
                                                                            receita
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <hr class="dropdown-divider">
                                                                    </li>
                                                                    <li class="dropdown-item">
                                                                        <a href="#" class="link-state link-info2"
                                                                            @click.stop="abrirLogsDocumento(receita)">
                                                                            <iconify-icon
                                                                                icon="solar:info-circle-bold-duotone"
                                                                                class="fs-7"></iconify-icon> Detalhes
                                                                        </a>
                                                                    </li>
                                                                    <li v-if="!receita.assinatura_prof_hash && !receita.assinatura_hash">
                                                                        <hr class="dropdown-divider">
                                                                    </li>
                                                                    <li class="dropdown-item" v-if="!receita.assinatura_prof_hash && !receita.assinatura_hash">
                                                                        <a :href="`#receita-${receita.id}`"
                                                                            class="link-state link-danger2"
                                                                            title="Excluir receita"
                                                                            @click.stop="excluirDocumento(item, receita, receitasIndex, 'receitas')">
                                                                            <iconify-icon
                                                                                icon="solar:trash-bin-minimalistic-bold-duotone"
                                                                                class="fs-7"></iconify-icon> Excluir
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                </tbody>

                                                <tbody v-else>
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted">
                                                            Nenhum registro
                                                        </td>
                                                    </tr>
                                                </tbody>

                                            </table>
                                        </div>
                                        <div class="tab-pane fade" :id="'pagamento-' + item.id" role="tabpanel"
                                            style="max-height: 50vh; min-height:200px; overflow-y: auto;">
                                            <!--- Pagamento --->
                                            <table class="table text-nowrap mb-0 align-middle">
                                                <thead class="text-dark fs-4">
                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">Descrição</h6>
                                                    </th>
                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">Vencimento</h6>
                                                    </th>
                                                    <th>
                                                        <h6 class="fs-4 fw-semibold mb-0">Valor pago</h6>
                                                    </th>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted">Nenhum registro
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="tab-pane fade" :id="'anexo-' + item.id" role="tabpanel"
                                            style="max-height: 50vh; min-height:200px; overflow-y: auto;">
                                            <!--- Anexo --->
                                            <table class="table table-hover text-nowrap mb-0 align-middle">
                                                <thead class="text-dark fs-4">
                                                    <tr>
                                                        <th>
                                                            <h6 class="fs-4 fw-semibold mb-0">Arquivo</h6>
                                                        </th>
                                                        <th>
                                                            <h6 class="fs-4 fw-semibold mb-0">Tamanho</h6>
                                                        </th>
                                                        <th>
                                                            <h6 class="fs-4 fw-semibold mb-0">Enviado em</h6>
                                                        </th>
                                                        <th style="width:5%;">
                                                            <h6 class="fs-4 fw-semibold mb-0">Baixar</h6>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody v-if="item.anexos && item.anexos.length">
                                                    <tr v-for="(anexo, aIndex) in item.anexos" :key="anexo.id">
                                                        <td>{{ anexo.nome_arquivo }}</td>
                                                        <td>{{ formatarTamanho(anexo.tamanho) }}</td>
                                                        <td>{{ formatarData(anexo.created) }}</td>
                                                        <td class="d-flex gap-2">
                                                            <div class="dropdown">
                                                                <button
                                                                    class="btn  bg-transparent text-primary  d-inline-flex align-items-center justify-content-center"
                                                                    type="button" :id="'dropdownMenu-' + anexo.id"
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="fas fa-bars"></i>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end"
                                                                    :aria-labelledby="'dropdownMenu-' + anexo.id">
                                                                    <li class="dropdown-item">
                                                                        <a :href="`<?= $this->Url->build(['controller' => 'Anexos', 'action' => 'download'], ['fullBase' => true]) ?>/${anexo.id}`"
                                                                            target="_blank"
                                                                            class="link-state link-primary2"
                                                                            title="Baixar documento">
                                                                            <iconify-icon
                                                                                icon="solar:cloud-download-bold-duotone"
                                                                                class="fs-7"></iconify-icon>Baixar
                                                                            documento
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <hr class="dropdown-divider">
                                                                    </li>
                                                                    <li class="dropdown-item">
                                                                        <a :href="`#procedimento-${item.id}`"
                                                                            class="link-state link-danger2"
                                                                            title="Excluir documento"
                                                                            @click="removerAnexo(item, anexo, aIndex)">
                                                                            <iconify-icon
                                                                                icon="solar:trash-bin-minimalistic-bold-duotone"
                                                                                class="fs-7"></iconify-icon> Excluir
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tbody v-else>
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">
                                                            Nenhum anexo enviado
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>

                                </div>

                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-1 col-sm-6" style="height: 70px;">
                                    <div class="form-check form-switch">
                                        <label class="form-check-label" for="item-aprovar">Aprovar</label>
                                        <input class="form-check-input success" type="checkbox" id="item-aprovar"
                                            v-model="item.aprovar" @change="aprovarProcedimento(item, index)">
                                    </div>
                                </div>
                                <div class="col-lg-1 col-sm-6" style="height: 70px;">
                                    <div class="form-check form-switch">
                                        <label class="form-check-label" for="item-parcelar">Parcelar</label>
                                        <input class="form-check-input success" type="checkbox" id="item-parcelar"
                                            v-model="item.parcelar" @change="atualizarParcelas(item)"
                                            :disabled="item.aprovar">
                                    </div>
                                </div>
                                <div class="col-lg-1 col-sm-6" style="height: 70px;">
                                    <div class="form-check form-switch">
                                        <label class="form-check-label" for="item-Imprimir">Imprimir</label>
                                        <input class="form-check-input success" type="checkbox" id="item-imprimir"
                                            v-model="filaImpressao" v-bind:value="item.id">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <?= $this->Form->control('desconto', ['label' => 'Desconto', 'class' => 'form-control', 'v-model' => 'item.desconto', 'v-money' => 'money', '@input' => 'atualizarParcelas(item)', ':disabled' => 'item.aprovar']); ?>
                                </div>

                                <div class="col-lg-1" style="height: 70px;">
                                    <div class="input mb-3 text" v-show="item.parcelar">
                                        <label for="quantidade">Parcelas</label>
                                        <input type="text" name="parcelas" id="parcelas" class="form-control"
                                            v-model="item.parcelas" @input="atualizarParcelas(item)"
                                            :disabled="item.aprovar">
                                    </div>
                                </div>

                                <div class="col-lg-2" style="height: 70px;">
                                    <div class="input mb-3 text" v-show="item.parcelar">
                                        <label for="quantidade">Valor das parcelas</label>
                                        <div class="form-control">{{item.valor_parcela}}</div>
                                    </div>
                                </div>
                                <div class="col-lg-2" style="height: 70px;">
                                    <div class="input mb-3 text">
                                        <label for="quantidade">Subtotal</label>
                                        <div class="form-control">R$ {{ formatarMoeda(calcularSubtotal(item)) }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-2" style="height: 70px;">
                                    <button type="button"
                                        class="justify-content-center w-100 btn btn-success d-flex align-items-center mt-3"
                                        @click="salvarConsulta(item, index)"
                                        :disabled="(item.aprovar === true && item.id) || item.salvando"><span
                                            v-if="item.salvando">Salvando...</span><span v-else>Salvar</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="file" ref="uploadInput" style="display:none" multiple @change="arquivoSelecionado" />

            <?= $this->Form->end() ?>
            <div class="row">
                <div class="col-md-2 offset-md-7 mb-3">
                    <button type="button"
                        class="justify-content-center w-100 btn mb-1 btn-rounded btn-primary d-flex align-items-center"
                        @click="abrirImpressao">
                        Imprimir
                    </button>
                </div>
                <div class="col-md-3 mb-3">
                    <button type="button" class="justify-content-center w-100 btn btn-primary" @click="adicionarItem">
                        Adicionar procedimento
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!---- modal anotacoes ------>
    <div class="modal fade" id="bs-modal-anotacoes" tabindex="-1" aria-labelledby="bs-modal-anotacoes"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center pb-0">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        Anotações
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-0">
                            <p class="card-subtitle mb-0">
                                Utilize o campo abaixo para descrever a evolução clínica do paciente, incluindo
                                respostas ao tratamento, alterações no quadro, condutas adotadas e observações
                                relevantes ao longo do acompanhamento.</p>
                            <input type="hidden" name="procedimento_id" id="procedimento-id"
                                v-model="anotacao.procedimento_id">
                            <input type="hidden" name="usuario_id" id="usuario-id" v-model="anotacao.usuario_id">
                            <?= $this->Form->control('texto', ['label' => '', 'class' => 'form-control', 'type' => 'textarea', 'rows' => 5, 'id' => 'texto-anotacao']); ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer pt-0">
                    <button type="button" class="btn bg-danger-subtle text-danger  waves-effect text-start"
                        data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-success waves-effect text-start" @click="salvarAnotacao"
                        :disabled="anotacao.salvando"><span v-if="anotacao.salvando">Salvando...</span><span
                            v-else>Salvar</span></button>
                </div>
            </div>
        </div>
    </div>
    <!---- modal receitas ------>
    <div class="modal fade" id="bs-modal-receitas" tabindex="-1" aria-labelledby="bs-modal-receitas" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center pb-0">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        Receita
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-8 mb-0">
                            <p class="card-subtitle mb-0">
                                Utilize o campo abaixo para descrever a evolução clínica do paciente, incluindo
                                respostas ao tratamento, alterações no quadro, condutas adotadas e observações
                                relevantes ao longo do acompanhamento.</p>
                            <input type="hidden" name="procedimento_id" id="procedimento-id"
                                v-model="receita.procedimento_id">
                            <input type="hidden" name="usuario_id" id="usuario-id" v-model="receita.usuario_id">
                            <?= $this->Form->control('texto', ['label' => '', 'class' => 'form-control', 'type' => 'textarea', 'rows' => 5, 'id' => 'texto-receita']); ?>
                        </div>
                        <div class="col-md-4 mb-0">
                            <div class="receitas-container">
                                <div class="receitas-busca">
                                    <input type="text" class="form-control" id="buscarReceita"
                                        placeholder="Buscar medicamento...">
                                </div>

                                <div class="receitas-scroll">
                                    <div class="accordion" id="accordionReceitas">

                                        <?php
                                        $i = 0;

                                        $labels = [
                                            'analgesico' => 'Analgésico',
                                            'antibiotico' => 'Antibiótico',
                                            'antiinflamatorio' => 'Anti-inflamatório',
                                            'antialergico' => 'Antialérgico',
                                            'corticoide' => 'Corticoide',
                                            'antifungico' => 'Antifúngico',
                                            'antiviral' => 'Antiviral',
                                            'gastrointestinal' => 'Gastrointestinal',
                                            'respiratorio' => 'Respiratório',
                                            'topico' => 'Uso tópico',
                                            'personalizado' => 'Personalizado'
                                        ];
                                        ?>

                                        <?php foreach ($agrupados as $categoria => $itens):
                                            $i++;
                                            $label = $labels[$categoria] ?? ucfirst($categoria);
                                            ?>

                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="heading<?= $i ?>">
                                                    <button class="accordion-button <?= $i != 1 ? 'collapsed' : '' ?>"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapse<?= $i ?>"
                                                        aria-expanded="<?= $i == 1 ? 'true' : 'false' ?>"
                                                        aria-controls="collapse<?= $i ?>">

                                                        <?= h($label) ?> (<?= count($itens) ?>)

                                                    </button>
                                                </h2>

                                                <div id="collapse<?= $i ?>"
                                                    class="accordion-collapse collapse <?= $i == 1 ? 'show' : '' ?>"
                                                    data-bs-parent="#accordionReceitas">
                                                    <div class="accordion-body">
                                                        <?php if (empty($itens)) { ?>
                                                            <div class="text-muted small">
                                                                Nenhum medicamento cadastrado.
                                                            </div>
                                                        <?php } else { ?>
                                                            <?php foreach ($itens as $item): ?>
                                                                <div class="mb-2">
                                                                    <a href="#"
                                                                        class="adicionarReceitas d-block text-decoration-none"
                                                                        data-descricao="<?= htmlspecialchars($item->descricao, ENT_QUOTES, 'UTF-8') ?>"><?= h($item->titulo) ?></a>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>

                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer pt-0">
                    <button type="button" class="btn bg-danger-subtle text-danger  waves-effect text-start"
                        data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn bg-info-subtle text-info"
                        onclick="limparReceitasSelecionadas()">Limpar</button>
                    <button type="button" class="btn btn-success waves-effect text-start" @click="salvarReceita"
                        :disabled="receita.salvando"><span v-if="receita.salvando">Salvando...</span><span
                            v-else>Salvar</span></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Modelo -->
    <div class="modal fade" id="bs-modal-modelo" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Salvar como modelo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <p class="text-muted">
                        Este conteúdo será salvo como um modelo reutilizável.
                    </p>

                    <div class="mb-3">
                        <label class="form-label">Título do modelo (Para impressão)</label>
                        <input type="text" class="form-control" v-model="modelo.titulo"
                            placeholder="Ex: Laudo, Atestado">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nome do modelo</label>
                        <input type="text" class="form-control" v-model="modelo.nome"
                            placeholder="Ex: Anamnese padrão adulto">
                    </div>

                    <div class="mb-3">
                        <div class="py-2">
                            <input class="form-check-input" type="checkbox" value="1" id="checkCabecalhoChecked"
                                v-model="modelo.cabecalho">
                            <label class="form-check-label" for="checkCabecalhoChecked">Incluir dados do paciente no
                                cabeçalho</label>
                        </div>
                        <div class="py-2">
                            <input class="form-check-input" type="checkbox" value="1"
                                id="checkExigirAssinaturaPacienteChecked" v-model="modelo.exigir_assinatura_paciente">
                            <label class="form-check-label" for="checkExigirAssinaturaPacienteChecked">Este documento
                                exige a assinatura digital do cliente</label>
                        </div>
                        <div class="py-2">
                            <input class="form-check-input" type="checkbox" value="1"
                                id="checkExigirAssinaturaProfissionalChecked"
                                v-model="modelo.exigir_assinatura_profissional">
                            <label class="form-check-label" for="checkExigirAssinaturaProfissionalChecked">Este
                                documento exige a assinatura do profissional</label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-success" @click="salvarModelo" :disabled="modelo.salvando">
                        <span v-if="modelo.salvando">Salvando...</span>
                        <span v-else>Salvar modelo</span>
                    </button>
                </div>

            </div>
        </div>
    </div>


    <!---- modal anamneses ------>
    <div class="modal fade" id="bs-modal-anamnese" tabindex="-1" aria-labelledby="bs-modal-anamnese" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center pb-0">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        Anamnese
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-0">
                            <p class="card-subtitle mb-0">Utilize o campo abaixo para registrar informações do
                                atendimento, observações importantes, histórico do paciente e outros detalhes
                                relevantes. Esses dados auxiliam no acompanhamento e garantem um atendimento mais
                                completo e organizado.</p>
                            <input type="hidden" name="procedimento_id" id="procedimento-id"
                                v-model="anamnese.procedimento_id">
                            <input type="hidden" name="usuario_id" id="usuario-id" v-model="anamnese.usuario_id">
                            <?= $this->Form->control('texto', ['label' => '', 'class' => 'form-control', 'type' => 'textarea', 'rows' => 5, 'id' => 'texto-anamnese']); ?>
                        </div>
                        <div class="col-md-4 mb-0">
                            <h4>Modelos de Anamnese</h4>
                            <div class="list-group">
                                <a href="#" v-for="modelo in modelosAnamnese" :key="modelo.id"
                                    class="list-group-item d-flex justify-content-between align-items-center adicionarModelo p-3"
                                    :data-id="modelo.id" :data-tipo="'anamnese'" :data-cabecalho="modelo.cabecalho"
                                    :data-titulo="modelo.titulo" :data-descricao="encodeURIComponent(modelo.descricao)"
                                    @click.prevent>

                                    <span>{{ modelo.nome }}</span>

                                    <button class="btn btn-sm btn-excluir-modelo p-0" v-show="modelo.estabelecimento_id"
                                        @click.stop="excluirModelo(modelo, 'anamnese')">
                                        <iconify-icon icon="solar:trash-bin-minimalistic-bold-duotone"
                                            class="fs-7 aside-icon"></iconify-icon>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer pt-0">
                    <button type="button" class="btn btn-info waves-effect text-start"
                        @click="abrirModalModelo('anamnese') ">Transformar em modelo</button>
                    <button type="button" class="btn bg-danger-subtle text-danger  waves-effect text-start"
                        data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-success waves-effect text-start" @click="salvarAnamnese"
                        :disabled="anamnese.salvando"><span v-if="anamnese.salvando">Salvando...</span><span
                            v-else>Salvar</span></button>
                </div>
            </div>
        </div>
    </div>

    <!---- modal laudo ------>
    <div class="modal fade" id="bs-modal-laudo" tabindex="-1" aria-labelledby="bs-modal-laudo" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center pb-0">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        Laudo
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-0">
                            <p class="card-subtitle mb-0">Utilize o campo abaixo para registrar informações do
                                atendimento, observações importantes, histórico do paciente e outros detalhes
                                relevantes. Esses dados auxiliam no acompanhamento e garantem um atendimento mais
                                completo e organizado.</p>
                            <input type="hidden" name="procedimento_id" id="procedimento-id"
                                v-model="laudo.procedimento_id">
                            <input type="hidden" name="usuario_id" id="usuario-id" v-model="laudo.usuario_id">
                            <?= $this->Form->control('texto', ['label' => '', 'class' => 'form-control', 'type' => 'textarea', 'rows' => 5, 'id' => 'texto-laudo']); ?>
                        </div>
                        <div class="col-md-4 mb-0">
                            <h4>Modelos de Laudos</h4>
                            <div class="list-group">
                                <a href="#" v-for="modelo in modelosLaudo" :key="modelo.id"
                                    class="list-group-item d-flex justify-content-between align-items-center adicionarModelo p-3"
                                    :data-id="modelo.id" :data-tipo="'laudo'" :data-cabecalho="modelo.cabecalho"
                                    :data-cabecalho="modelo.cabecalho" :data-titulo="modelo.titulo"
                                    :data-descricao="encodeURIComponent(modelo.descricao)" @click.prevent>

                                    <span>{{ modelo.nome }}</span>

                                    <button class="btn btn-sm btn-excluir-modelo p-0" v-show="modelo.estabelecimento_id"
                                        @click.stop="excluirModelo(modelo, 'laudo')">
                                        <iconify-icon icon="solar:trash-bin-minimalistic-bold-duotone"
                                            class="fs-7 aside-icon"></iconify-icon>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer pt-0">
                    <button type="button" class="btn btn-info waves-effect text-start"
                        @click="abrirModalModelo('laudo') ">Transformar em modelo</button>
                    <button type="button" class="btn bg-danger-subtle text-danger  waves-effect text-start"
                        data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-success waves-effect text-start" @click="salvarLaudo"
                        :disabled="laudo.salvando"><span v-if="laudo.salvando">Salvando...</span><span
                            v-else>Salvar</span></button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="bs-modal-documento" tabindex="-1" aria-labelledby="bs-modal-documento"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center pb-0">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        Documento clínico
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-0">
                            <p class="card-subtitle mb-0">Utilize o campo abaixo para registrar informações do
                                atendimento, observações importantes, histórico do paciente e outros detalhes
                                relevantes. Esses dados auxiliam no acompanhamento e garantem um atendimento mais
                                completo e organizado.</p>
                            <input type="hidden" name="procedimento_id" id="procedimento-id"
                                v-model="documento.procedimento_id">
                            <input type="hidden" name="usuario_id" id="usuario-id" v-model="documento.usuario_id">
                            <?= $this->Form->control('texto', ['label' => '', 'class' => 'form-control', 'type' => 'textarea', 'rows' => 5, 'id' => 'texto-documento']); ?>
                        </div>
                        <div class="col-md-4 mb-0">
                            <h4>Modelos de Documentos</h4>
                            <div class="list-group">
                                <a href="#" v-for="modelo in modelosDocumento" :key="modelo.id"
                                    class="list-group-item d-flex justify-content-between align-items-center adicionarModelo p-3"
                                    :data-id="modelo.id" :data-tipo="'documento'" :data-cabecalho="modelo.cabecalho"
                                    :data-titulo="modelo.titulo" :data-descricao="encodeURIComponent(modelo.descricao)"
                                    @click.prevent>
                                    <span>{{ modelo.nome }}</span>
                                    <button class="btn btn-sm btn-excluir-modelo p-0" v-show="modelo.estabelecimento_id"
                                        @click.stop="excluirModelo(modelo, 'documento')">
                                        <iconify-icon icon="solar:trash-bin-minimalistic-bold-duotone"
                                            class="fs-7 aside-icon"></iconify-icon>
                                    </button>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer pt-0">
                    <button type="button" class="btn btn-info waves-effect text-start"
                        @click="abrirModalModelo('documento'); ">Transformar em modelo</button>
                    <button type="button" class="btn bg-danger-subtle text-danger  waves-effect text-start"
                        data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-success waves-effect text-start" @click="salvarDocumento"
                        :disabled="documento.salvando"><span v-if="documento.salvando">Salvando...</span><span
                            v-else>Salvar</span></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Logs do Documento -->
    <div class="modal fade" id="bs-modal-logs-documento" tabindex="-1" aria-labelledby="bs-modal-logs-documento"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header border-bottom-0 pb-0">
                    <div class="d-flex align-items-center gap-2">
                        <div
                            class="p-2 bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center">
                            <iconify-icon icon="solar:history-bold-duotone" class="fs-7 text-primary"></iconify-icon>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0">Registros do Documento</h5>
                            <p class="text-muted mb-0" style="font-size: 12px;" v-if="logsModal.doc">
                                Histórico de ações e trilha de auditoria
                            </p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <div v-if="logsModal.loading" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Carregando...</span>
                        </div>
                        <p class="mt-2 text-muted">Buscando registros...</p>
                    </div>
                    <div v-else>
                        <!-- Metadados Técnicos -->
                        <div v-if="logsModal.metadata" class="mb-4 p-3 bg-light rounded-3 border">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <h6 class="text-primary fw-bold mb-2" style="font-size: 13px;">Informações Gerais
                                    </h6>
                                    <div class="mb-1 d-flex justify-content-between">
                                        <span class="text-muted small">ID:</span>
                                        <span class="fw-medium small">{{ logsModal.metadata.id }}</span>
                                    </div>
                                    <div class="mb-1 d-flex justify-content-between">
                                        <span class="text-muted small">Criado em:</span>
                                        <span class="fw-medium small">{{ logsModal.metadata.created }}</span>
                                    </div>
                                    <div class="mb-1 d-flex justify-content-between">
                                        <span class="text-muted small">Cód. Validação:</span>
                                        <span class="fw-medium small font-monospace">{{
                                            logsModal.metadata.codigo_validacao }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 border-start ps-4">
                                    <h6 class="text-primary fw-bold mb-2" style="font-size: 13px;">Exigências de
                                        Assinatura</h6>
                                    <div class="mb-1 d-flex justify-content-between align-items-center">
                                        <span class="text-muted small">Assinatura Paciente:</span>
                                        <span class="badge"
                                            :class="logsModal.metadata.exigir_paciente ? 'bg-primary-subtle text-primary' : 'bg-light text-muted'"
                                            style="font-size: 10px;">
                                            {{ logsModal.metadata.exigir_paciente ? 'OBRIGATÓRIA' : 'NÃO EXIGIDA' }}
                                        </span>
                                    </div>
                                    <div class="mb-1 d-flex justify-content-between align-items-center">
                                        <span class="text-muted small">Assinatura Profissional:</span>
                                        <span class="badge"
                                            :class="logsModal.metadata.exigir_profissional ? 'bg-primary-subtle text-primary' : 'bg-light text-muted'"
                                            style="font-size: 10px;">
                                            {{ logsModal.metadata.exigir_profissional ? 'OBRIGATÓRIA' : 'NÃO EXIGIDA' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-12 border-top pt-3 mt-3">
                                    <h6 class="text-primary fw-bold mb-2" style="font-size: 13px;">Trilha de Integridade
                                        (Hashes)</h6>

                                    <!-- Detalhes Paciente -->
                                    <div v-if="logsModal.metadata.paciente_hash"
                                        class="mb-3 p-2 bg-white rounded border">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <iconify-icon icon="solar:user-bold" class="text-warning"></iconify-icon>
                                            <span class="fw-bold small">Assinatura do Paciente</span>
                                        </div>
                                        <div class="small text-truncate" :title="logsModal.metadata.paciente_hash">
                                            <span class="text-muted">SHA-256:</span> {{ logsModal.metadata.paciente_hash
                                            }}
                                        </div>
                                        <div class="small mt-1 d-flex gap-3">
                                            <span><span class="text-muted">Data:</span> {{
                                                logsModal.metadata.paciente_data }}</span>
                                            <span><span class="text-muted">IP Origem:</span> {{
                                                logsModal.metadata.paciente_ip }}</span>
                                        </div>
                                    </div>

                                    <!-- Detalhes Profissional -->
                                    <div v-if="logsModal.metadata.profissional_hash"
                                        class="p-2 bg-white rounded border">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <iconify-icon icon="solar:lock-keyhole-bold"
                                                class="text-success"></iconify-icon>
                                            <span class="fw-bold small">Assinatura Digital Profissional (PAdES)</span>
                                        </div>
                                        <div class="small text-truncate" :title="logsModal.metadata.profissional_hash">
                                            <span class="text-muted">Hash Final:</span> {{
                                            logsModal.metadata.profissional_hash }}
                                        </div>
                                        <div class="small mt-1 d-flex flex-wrap gap-x-3">
                                            <span v-if="logsModal.metadata.profissional_signer"><span
                                                    class="text-muted">Signatário:</span> {{
                                                logsModal.metadata.profissional_signer }}</span>
                                            <span><span class="text-muted">Data:</span> {{
                                                logsModal.metadata.profissional_data }}</span>
                                            <span><span class="text-muted">IP Origem:</span> {{
                                                logsModal.metadata.profissional_ip }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:list-bold" class="text-muted"></iconify-icon>
                            Registros de Atividades (Auditoria)
                        </h6>
                        <div v-if="logsModal.logs && logsModal.logs.length" class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 rounded-start" style="font-size: 12px; font-weight: 600;">
                                            Ação</th>
                                        <th class="border-0" style="font-size: 12px; font-weight: 600;">Usuário</th>
                                        <th class="border-0"
                                            style="font-size: 12px; font-weight: 600; text-align: center;">IP</th>
                                        <th class="border-0 rounded-end"
                                            style="font-size: 12px; font-weight: 600; text-align: center;">Data/Hora
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="log in logsModal.logs" :key="log.id">
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-semibold text-dark">{{ log.acao }}</span>
                                                <small v-if="log.detalhes" class="text-muted"
                                                    style="font-size: 10px;">{{ log.detalhes }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted" style="font-size: 13px;">{{ log.usuario }}</span>
                                        </td>
                                        <td style="text-align: center;">
                                            <span class="badge bg-light text-dark fw-normal" style="font-size: 11px;">{{
                                                log.ip }}</span>
                                        </td>
                                        <td style="text-align: center;">
                                            <span class="text-muted" style="font-size: 12px;">{{ log.created }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else class="text-center py-5">
                            <iconify-icon icon="solar:document-medicine-bold-duotone"
                                class="fs-10 text-muted opacity-50 mb-3"></iconify-icon>
                            <p class="text-muted">Nenhum registro encontrado para este documento.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <a v-if="logsModal.doc"
                        :href="`<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'downloadLogs'], ['fullBase' => true]) ?>/${logsModal.doc.id}`"
                        class="btn btn-outline-primary d-inline-flex align-items-center gap-2" target="_blank">
                        <iconify-icon icon="solar:download-bold-duotone" class="fs-5"></iconify-icon>
                        Download CSV
                    </a>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://unpkg.com/vue@2.7.16/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.tiny.cloud/1/u5fmbus2y1uaztd68og30b8f49lcwdm3j7n444vah8x1pg42/tinymce/8/tinymce.min.js"
    referrerpolicy="origin" crossorigin="anonymous"></script>
<script>
    tinymce.init({
        selector: '#texto-anotacao',
        menubar: false,
        plugins: 'anchor autolink charmap searchreplace visualblocks wordcount lists',
        toolbar: 'undo redo | bold italic underline strikethrough | align lineheight | numlist bullist indent outdent',

        setup: function (editor) {
            editor.on('keyup change paste undo redo', function () {
                appConsulta.anotacao.texto = editor.getContent();
            });
        }
    });

    tinymce.init({
        selector: '#texto-anamnese',
        menubar: false,
        plugins: 'anchor autolink charmap searchreplace visualblocks wordcount lists',
        toolbar: 'undo redo | bold italic underline strikethrough | align lineheight | numlist bullist indent outdent',
        setup: function (editor) {
            editor.on('keyup change paste undo redo', function () {
                appConsulta.anamnese.texto = editor.getContent();
            });
        }
    });

    tinymce.init({
        selector: '#texto-receita',
        menubar: false,
        plugins: 'anchor autolink charmap searchreplace visualblocks wordcount lists',
        toolbar: 'undo redo | bold italic underline strikethrough | align lineheight | numlist bullist indent outdent',
        setup: function (editor) {
            editor.on('keyup change paste undo redo', function () {
                appConsulta.receita.texto = editor.getContent();
            });
        }
    });

    tinymce.init({
        selector: '#texto-laudo',
        menubar: false,
        plugins: 'anchor autolink charmap searchreplace visualblocks wordcount lists',
        toolbar: 'undo redo | bold italic underline strikethrough | align lineheight | numlist bullist indent outdent',

        setup: function (editor) {
            editor.on('keyup change paste undo redo', function () {
                appConsulta.laudo.texto = editor.getContent();
            });
        }
    });

    tinymce.init({
        selector: '#texto-documento',
        menubar: false,
        plugins: 'anchor autolink charmap searchreplace visualblocks wordcount lists',
        toolbar: 'undo redo | bold italic underline strikethrough | align lineheight | numlist bullist indent outdent',

        setup: function (editor) {
            editor.on('keyup change paste undo redo', function () {
                appConsulta.documento.texto = editor.getContent();
            });
        }
    });

</script>

<?= $this->Html->script(['vue/v-money.2.x.js', 'plugins/bootstrap-select.js', 'vue/select2-vue.js']); ?>
<?= $this->Html->css(['plugins/bootstrap-select.css']); ?>
<style>
    .accordion-body {
        padding: var(--bs-accordion-body-padding-y) 1rem;
    }

    .accordion-button {
        padding: var(--bs-accordion-btn-padding-y) 1rem;
    }
</style>
<script type="text/x-template" id="select2-template">
    <select>
        <slot></slot>
    </select>
</script>

<script>
    Vue.config.ignoredElements = [
        'iconify-icon'
    ];
    Vue.use(VMoney, { precision: 2 });
    const appConsulta = new Vue({
        el: '#appConsulta',

        data() {
            return {
                modelosAnamnese: [],
                modelosLaudo: [],
                modelosDocumento: [],
                selecionados: {
                    anamnese: [],
                    laudo: [],
                    documento: []
                },
                itemCounter: 0,
                itemReceita: null,
                itemUpload: null, // item que vai receber o anexo
                itemAnotacao: null,
                itemAnamnese: null,
                itemLaudo: null,
                itemDocumento: null,
                filaImpressao: [],
                itens: <?= json_encode($consulta->procedimentos); ?>,
                servicosRaw: <?= json_encode($servicos); ?>,
                modelo: {
                    tipo: null,
                    titulo: '',
                    nome: '',
                    descricao: '',
                    cabecalho: false,
                    exigir_assinatura_paciente: false,
                    exigir_assinatura_profissional: false,
                    salvando: false
                },
                anotacao: {
                    procedimento_id: null,
                    usuario_id: <?= (int) $this->request->getAttribute('identity')->id ?>,
                    profissional_id: <?= (int) $this->request->getAttribute('identity')->profissional_id ?>,
                    tipo: 'anotacao',
                    cabecalho: false,
                    texto: '',
                    salvando: false
                },
                anamnese: {
                    procedimento_id: null,
                    usuario_id: <?= (int) $this->request->getAttribute('identity')->id ?>,
                    profissional_id: <?= (int) $this->request->getAttribute('identity')->profissional_id ?>,
                    texto: '',
                    cabecalho: false,
                    titulo: null,
                    tipo: 'anamnese',
                    salvando: false
                },
                receita: {
                    procedimento_id: null,
                    usuario_id: <?= (int) $this->request->getAttribute('identity')->id ?>,
                    profissional_id: <?= (int) $this->request->getAttribute('identity')->profissional_id ?>,
                    texto: '',
                    cabecalho: false,
                    titulo: 'Receita',
                    tipo: 'receita',
                    salvando: false
                },
                laudo: {
                    procedimento_id: null,
                    usuario_id: <?= (int) $this->request->getAttribute('identity')->id ?>,
                    profissional_id: <?= (int) $this->request->getAttribute('identity')->profissional_id ?>,
                    texto: '',
                    cabecalho: false,
                    titulo: null,
                    tipo: 'laudo',
                    salvando: false
                },
                documento: {
                    procedimento_id: null,
                    usuario_id: <?= (int) $this->request->getAttribute('identity')->id ?>,
                    profissional_id: <?= (int) $this->request->getAttribute('identity')->profissional_id ?>,
                    texto: '',
                    cabecalho: false,
                    titulo: null,
                    tipo: 'documento',
                    salvando: false
                },
                logsModal: {
                    show: false,
                    loading: false,
                    doc: null,
                    logs: [],
                    metadata: null
                },

                money: {
                    decimal: ',',
                    thousands: '.',
                    prefix: '',
                    suffix: '',
                    precision: 2,
                    masked: false
                },
            };
        },
        mounted() {
            this.itens = this.itens.map(item => ({
                ...item,
                aberto: false,
                anexos: item.anexos || []
            }));
        },

        computed: {

            servicos() {
                return Object.entries(this.servicosRaw).map(([id, text]) => ({
                    id,
                    text
                }));
            },

            servicoLabel() {
                return id =>
                    this.servicosRaw[id] || 'Novo procedimento';
            }
        },

        methods: {
            excluirModelo(modelo, tipo) {
                Swal.fire({
                    title: 'Tem certeza?',
                    text: 'Esse modelo será excluído permanentemente.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, excluir',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {

                    if (!result.isConfirmed) return;

                    axios.post("<?= $this->Url->build(['controller' => 'Modelos', 'action' => 'delete', '_ext' => 'json']) ?>", {
                        id: modelo.id
                    },
                        {
                            headers: {
                                'X-CSRF-Token': "<?= $this->request->getAttribute('csrfToken') ?>"
                            }
                        }).then(response => {

                            if (response.data.success) {

                                if (tipo == 'anamnese')
                                    this.carregarModelosAnamnese();

                                if (tipo == 'laudo')
                                    this.carregarModelosLaudo();


                                if (tipo == 'documento')
                                    this.carregarModelosDocumento();

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Excluído!',
                                    text: 'Modelo removido com sucesso',
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                            } else {
                                Swal.fire('Erro', 'Não foi possível excluir', 'error');
                            }

                        })
                        .catch(() => {
                            Swal.fire('Erro', 'Falha na requisição', 'error');
                        });

                });
            },
            carregarModelosDocumento() {
                axios.get("<?= $this->Url->build(['controller' => 'Modelos', 'action' => 'listar', '_ext' => 'json']) ?>?tipo=documento")
                    .then(response => {
                        this.modelosDocumento = response.data.modelos || [];
                    });
            },
            carregarModelosLaudo() {
                axios.get("<?= $this->Url->build(['controller' => 'Modelos', 'action' => 'listar', '_ext' => 'json']) ?>?tipo=laudo")
                    .then(response => {
                        this.modelosLaudo = response.data.modelos || [];
                    });
            },
            carregarModelosAnamnese() {
                axios.get("<?= $this->Url->build(['controller' => 'Modelos', 'action' => 'listar', '_ext' => 'json']) ?>?tipo=anamnese")
                    .then(response => {
                        this.modelosAnamnese = response.data.modelos || [];
                    });
            },
            abrirModalModelo(tipo) {

                const mapa = {
                    anamnese: this.anamnese,
                    receita: this.receita,
                    anotacao: this.anotacao,
                    laudo: this.laudo,
                    documento: this.documento,
                };

                const item = mapa[tipo];

                if (!item || !item.texto) {
                    Swal.fire({
                        title: 'Atenção',
                        text: 'Não há conteúdo para transformar em modelo.',
                        icon: 'warning'
                    });
                    return;
                }

                // preenche dados
                this.modelo.tipo = tipo;
                this.modelo.descricao = item.texto;
                this.modelo.titulo = '';
                this.modelo.cabecalho = false;
                this.modelo.nome = '';

                // abre modal
                new bootstrap.Modal(document.getElementById('bs-modal-modelo')).show();
            },
            resumoReceita(texto, limite = 120) {

                if (!texto) return '';

                // remove HTML
                let textoLimpo = texto.replace(/<[^>]*>/g, ' ')
                    .replace(/&nbsp;/g, ' ')
                    .trim();

                // decodifica entidades HTML
                const txt = document.createElement("textarea");
                txt.innerHTML = textoLimpo;
                textoLimpo = txt.value;

                // limita caracteres
                if (textoLimpo.length > limite) {
                    textoLimpo = textoLimpo.substring(0, limite) + '...';
                }

                return textoLimpo;
            },
            salvarModelo() {

                if (!this.modelo.titulo) {
                    Swal.fire({
                        title: 'Atenção',
                        text: 'Informe o título do modelo.',
                        icon: 'warning'
                    });
                    return;
                }

                if (!this.modelo.nome) {
                    Swal.fire({
                        title: 'Atenção',
                        text: 'Informe o nome do modelo.',
                        icon: 'warning'
                    });
                    return;
                }

                if (this.modelo.salvando) return;

                this.modelo.salvando = true;

                axios.post(
                    "<?= $this->Url->build(['controller' => 'Modelos', 'action' => 'salvar', '_ext' => 'json']) ?>",
                    this.modelo,
                    {
                        headers: {
                            'X-CSRF-Token': "<?= $this->request->getAttribute('csrfToken') ?>"
                        }
                    }
                ).then(response => {

                    if (response.data.success) {

                        if (this.modelo.tipo == 'anamnese')
                            this.carregarModelosAnamnese();

                        if (this.modelo.tipo == 'laudo')
                            this.carregarModelosLaudo();

                        if (this.modelo.tipo == 'documento')
                            this.carregarModelosDocumento()

                        Swal.fire({
                            title: 'Sucesso',
                            text: 'Modelo salvo com sucesso!',
                            icon: 'success'
                        });

                        // fecha modal
                        bootstrap.Modal
                            .getInstance(document.getElementById('bs-modal-modelo'))
                            .hide();

                        this.modelo.nome = '';
                        this.modelo.titulo = '';
                        this.modelo.descricao = '';
                        this.modelo.cabecalho = false;
                        this.modelo.exigir_assinatura_paciente = false;
                        this.modelo.exigir_assinatura_profissional = false;

                    } else {
                        Swal.fire({
                            title: 'Erro',
                            text: 'Ocorreu um erro ao salvar o modelo',
                            icon: 'error'
                        });
                    }

                }).catch(() => {

                    Swal.fire({
                        title: 'Erro',
                        text: 'Erro ao salvar modelo.',
                        icon: 'error'
                    });

                }).finally(() => {
                    this.modelo.salvando = false;
                });
            },
            salvarDocumento() {

                if (this.documento.salvando) return;

                if (!this.documento.texto) {
                    Swal.fire({
                        title: 'Atenção',
                        text: 'Informe o texto do documento',
                        icon: 'warning'
                    });
                    return;
                }

                this.documento.salvando = true;

                axios.post(
                    "<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'salvar', '_ext' => 'json']) ?>",
                    this.documento,
                    {
                        headers: {
                            'X-CSRF-Token': "<?= $this->request->getAttribute('csrfToken') ?>"
                        }
                    }
                ).then(response => {

                    if (response.data.success) {

                        const novoDocumento = response.data.documento;

                        if (!this.itemDocumento.docs) {
                            this.$set(this.itemDocumento, 'docs', []);
                        }

                        this.itemDocumento.docs.unshift(novoDocumento);

                        Swal.fire({
                            title: 'Sucesso',
                            text: 'Documento salvo com sucesso',
                            icon: 'success'
                        });

                        bootstrap.Modal
                            .getInstance(document.getElementById('bs-modal-documento'))
                            .hide();

                        //this.itemDocumento = null;
                        this.documento.texto = '';
                        this.documento.cabecalho = false;
                        this.documento.titulo = null,
                            this.documento.procedimento_id = null;

                    } else {
                        Swal.fire({
                            title: 'Erro',
                            text: 'Ocorreu um erro ao salvar o documento',
                            icon: 'error'
                        });
                    }

                }).catch(() => {

                    Swal.fire({
                        title: 'Erro',
                        text: 'Erro ao salvar o documento',
                        icon: 'error'
                    });

                }).finally(() => {

                    this.documento.salvando = false;

                });

            },
            salvarLaudo() {

                if (this.laudo.salvando) return;

                if (!this.laudo.texto) {
                    Swal.fire({
                        title: 'Atenção',
                        text: 'Informe o texto da laudo',
                        icon: 'warning'
                    });
                    return;
                }

                this.laudo.salvando = true;

                axios.post(
                    "<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'salvar', '_ext' => 'json']) ?>",
                    this.laudo,
                    {
                        headers: {
                            'X-CSRF-Token': "<?= $this->request->getAttribute('csrfToken') ?>"
                        }
                    }
                ).then(response => {

                    if (response.data.success) {

                        const novoDocumento = response.data.documento;

                        if (!this.itemLaudo.laudos) {
                            this.$set(this.itemLaudo, 'laudos', []);
                        }

                        this.itemLaudo.laudos.unshift(novoDocumento);

                        Swal.fire({
                            title: 'Sucesso',
                            text: 'Laudo salvo com sucesso',
                            icon: 'success'
                        });

                        bootstrap.Modal
                            .getInstance(document.getElementById('bs-modal-laudo'))
                            .hide();

                        this.itemLaudo = null;
                        this.laudo.texto = '';
                        this.laudo.titulo = null;
                        this.laudo.procedimento_id = null;

                    } else {
                        Swal.fire({
                            title: 'Erro',
                            text: 'Ocorreu um erro ao salvar o laudo',
                            icon: 'error'
                        });
                    }

                }).catch(() => {

                    Swal.fire({
                        title: 'Erro',
                        text: 'Erro ao salvar o laudo.',
                        icon: 'error'
                    });

                }).finally(() => {

                    this.laudo.salvando = false;

                });

            },
            salvarAnamnese() {

                if (this.anamnese.salvando) return;

                if (!this.anamnese.texto) {
                    Swal.fire({
                        title: 'Atenção',
                        text: 'Informe o texto da anamnese',
                        icon: 'warning'
                    });
                    return;
                }

                this.anamnese.salvando = true;

                axios.post(
                    "<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'salvar', '_ext' => 'json']) ?>",
                    this.anamnese,
                    {
                        headers: {
                            'X-CSRF-Token': "<?= $this->request->getAttribute('csrfToken') ?>"
                        }
                    }
                ).then(response => {

                    if (response.data.success) {

                        const novoDocumento = response.data.documento;

                        if (!this.itemAnamnese.anamneses) {
                            this.$set(this.itemAnamnese, 'anamneses', []);
                        }

                        this.itemAnamnese.anamneses.unshift(novoDocumento);

                        Swal.fire({
                            title: 'Sucesso',
                            text: 'Anamnese salva com sucesso',
                            icon: 'success'
                        });

                        bootstrap.Modal
                            .getInstance(document.getElementById('bs-modal-anamnese'))
                            .hide();

                        this.itemAnamnese = null;
                        this.anamnese.texto = '';
                        this.anamnese.laudo = null;
                        this.anamnese.procedimento_id = null;

                    } else {
                        Swal.fire({
                            title: 'Erro',
                            text: 'Ocorreu um erro ao salvar anamnese',
                            icon: 'error'
                        });
                    }

                }).catch(() => {

                    Swal.fire({
                        title: 'Erro',
                        text: 'Erro ao salvar anamnese.',
                        icon: 'error'
                    });

                }).finally(() => {

                    this.anamnese.salvando = false;

                });

            },
            salvarReceita() {

                // pega conteúdo atual do TinyMCE
                const editor = tinymce.get('texto-receita');
                if (editor) {
                    this.receita.texto = editor.getContent();
                }

                // evita clique duplo
                if (this.receita.salvando)
                    return;

                // valida texto (remove HTML vazio)
                const textoLimpo = (this.receita.texto || '')
                    .replace(/<[^>]*>/g, '')
                    .replace(/&nbsp;/g, '')
                    .trim();

                if (!textoLimpo) {
                    Swal.fire({
                        title: 'Atenção',
                        text: 'Informe o texto da receita',
                        icon: 'warning'
                    });
                    return;
                }

                this.receita.salvando = true;

                axios.post(
                    "<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'salvar', '_ext' => 'json']) ?>",
                    this.receita,
                    {
                        headers: {
                            'X-CSRF-Token': "<?= $this->request->getAttribute('csrfToken') ?>"
                        }
                    }
                )
                    .then(response => {

                        if (response.data.success) {

                            const novaReceita = response.data.documento;

                            // garante que existe array reativo
                            if (!Array.isArray(this.itemReceita.receitas)) {
                                this.$set(this.itemReceita, 'receitas', []);
                            }

                            // adiciona receita no início da lista
                            this.itemReceita.receitas.unshift(novaReceita);

                            Swal.fire({
                                title: 'Sucesso',
                                text: 'Receita foi salva com sucesso',
                                icon: 'success'
                            });

                            // fecha modal
                            const modal = bootstrap.Modal.getInstance(
                                document.getElementById('bs-modal-receitas')
                            );

                            if (modal) {
                                modal.hide();
                            }

                            // limpa seleções de medicamentos
                            limparReceitasSelecionadas();

                            // limpa TinyMCE
                            if (editor) {
                                editor.setContent('');
                            }

                            // limpa dados
                            this.receita.texto = '';
                            this.receita.titulo = '';
                            this.receita.procedimento_id = null;
                            this.itemReceita = null;

                        } else {

                            Swal.fire({
                                title: 'Erro',
                                text: 'Ocorreu um erro na tentativa de salvar a receita',
                                icon: 'error'
                            });

                        }

                    })
                    .catch(() => {

                        Swal.fire({
                            title: 'Erro',
                            text: 'Erro ao salvar receita',
                            icon: 'error'
                        });

                    })
                    .finally(() => {

                        if (this.receita && typeof this.receita === 'object') {
                            this.receita.salvando = false;
                        }

                    });

            },
            salvarAnotacao() {


                // Impede duplo clique imediatamente
                if (this.anotacao.salvando)
                    return;
                this.anotacao.salvando = true;
                if (!this.anotacao.texto) {
                    Swal.fire({
                        title: 'Atenção',
                        text: 'Informe o texto da evolução clínica',
                        icon: 'warning'
                    });
                    return;
                }

                this.anotacao.salvando = true;

                axios.post(
                    "<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'salvar', '_ext' => 'json']) ?>",
                    this.anotacao,
                    {
                        headers: {
                            'X-CSRF-Token': "<?= $this->request->getAttribute('csrfToken') ?>"
                        }
                    }
                ).then(response => {

                    if (response.data.success) {

                        const novaAnotacao = response.data.documento;

                        // garante que o array existe (reatividade)
                        if (!this.itemAnotacao.anotacoes) {
                            this.$set(this.itemAnotacao, 'anotacoes', []);
                        }

                        // adiciona a nova anotação na lista
                        this.itemAnotacao.anotacoes.unshift(novaAnotacao);

                        Swal.fire({
                            title: 'Sucesso',
                            text: 'Evolução clínica salva com sucesso',
                            icon: 'success'
                        });

                        // fecha o modal
                        bootstrap.Modal
                            .getInstance(document.getElementById('bs-modal-anotacoes'))
                            .hide();

                        // limpa o formulário
                        this.itemAnotacao = null;
                        this.anotacao.texto = null;
                        this.anotacao.procedimento_id = null;

                    } else {
                        Swal.fire({
                            title: 'Erro',
                            text: 'Ocorreu um erro na tentativa de salvar a evolução clínica',
                            icon: 'error'
                        });
                    }

                }).catch(() => {
                    Swal.fire({
                        title: 'Erro',
                        text: 'Erro ao salvar a evolução clínica',
                        icon: 'error'
                    });
                }).finally(() => {
                    // Libera o botão em QUALQUER cenário

                    this.anotacao.salvando = false;
                });
            },
            abrirModalDocumento(item) {
                this.carregarModelosDocumento();
                this.itemDocumento = item;

                this.documento = {
                    procedimento_id: item.id,
                    usuario_id: <?= (int) $this->request->getAttribute('identity')->id ?>,
                    profissional_id: <?= (int) $this->request->getAttribute('identity')->profissional_id ?>,
                    cabecalho: false,
                    texto: '',
                    titulo: null,
                    tipo: 'documento',
                    salvando: false
                };

                // abre o modal via Bootstrap
                const modalDocumento = new bootstrap.Modal(
                    document.getElementById('bs-modal-documento')
                );

                this.$nextTick(() => {
                    const editor = tinymce.get('texto-documento');
                    if (editor)
                        editor.setContent('');
                });

                modalDocumento.show();
            },
            abrirModalLaudo(item) {
                this.carregarModelosLaudo();
                this.itemLaudo = item;

                this.laudo = {
                    procedimento_id: item.id,
                    usuario_id: <?= (int) $this->request->getAttribute('identity')->id ?>,
                    profissional_id: <?= (int) $this->request->getAttribute('identity')->profissional_id ?>,
                    texto: '',
                    cabecalho: false,
                    titulo: null,
                    tipo: 'laudo',
                    salvando: false
                };

                // abre o modal via Bootstrap
                const modalLaudo = new bootstrap.Modal(
                    document.getElementById('bs-modal-laudo')
                );

                this.$nextTick(() => {
                    const editor = tinymce.get('texto-laudo');
                    if (editor)
                        editor.setContent('');
                });

                modalLaudo.show();
            },
            abrirModal(item) {

                this.itemDocumento = item;

                this.documento = {
                    procedimento_id: item.id,
                    usuario_id: <?= (int) $this->request->getAttribute('identity')->id ?>,
                    profissional_id: <?= (int) $this->request->getAttribute('identity')->profissional_id ?>,
                    texto: '',
                    cabecalho: false,
                    tipo: 'documento',
                    salvando: false
                };

                // abre o modal via Bootstrap
                const modalDocumento = new bootstrap.Modal(
                    document.getElementById('bs-modal-documento')
                );

                this.$nextTick(() => {
                    const editor = tinymce.get('texto-documento');
                    if (editor)
                        editor.setContent('');
                });

                modalDocumento.show();
            },

            abrirLogsDocumento(doc) {
                this.logsModal.doc = doc;
                this.logsModal.logs = [];
                this.logsModal.loading = true;
                this.logsModal.show = true;

                const modal = new bootstrap.Modal(document.getElementById('bs-modal-logs-documento'));
                modal.show();

                axios.get(`<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'getLogs'], ['fullBase' => true]) ?>/${doc.id}`, {
                    headers: { 'X-CSRF-Token': '<?= $this->request->getAttribute('csrfToken') ?>' }
                }).then(response => {
                    if (response.data.ok) {
                        this.logsModal.logs = response.data.logs;
                        this.logsModal.metadata = response.data.metadata;
                    }
                }).catch(() => {
                    this.logsModal.logs = [];
                    this.logsModal.metadata = null;
                }).finally(() => {
                    this.logsModal.loading = false;
                });
            },

            abrirModalAnamnese(item) {
                this.carregarModelosAnamnese();
                this.itemAnamnese = item;

                this.anamnese = {
                    procedimento_id: item.id,
                    usuario_id: <?= (int) $this->request->getAttribute('identity')->id ?>,
                    profissional_id: <?= (int) $this->request->getAttribute('identity')->profissional_id ?>,
                    texto: '',
                    titulo: null,
                    cabecalho: false,
                    tipo: 'anamnese',
                    salvando: false
                };

                // abre o modal via Bootstrap
                const modalAnamnese = new bootstrap.Modal(
                    document.getElementById('bs-modal-anamnese')
                );

                this.$nextTick(() => {
                    const editor = tinymce.get('texto-anamnese');
                    if (editor)
                        editor.setContent('');
                });

                modalAnamnese.show();
            },
            abrirModalReceita(item) {

                this.itemReceita = item;

                this.receita = {
                    procedimento_id: item.id,
                    usuario_id: <?= (int) $this->request->getAttribute('identity')->id ?>,
                    profissional_id: <?= (int) $this->request->getAttribute('identity')->id ?>,
                    tipo: 'receita',
                    titulo: 'Receita',
                    cabecalho: false,
                    texto: '',
                    salvando: false
                };

                limparReceitasSelecionadas();

                const modalEl = document.getElementById('bs-modal-receitas');
                const modalReceitas = bootstrap.Modal.getOrCreateInstance(modalEl);

                this.$nextTick(() => {
                    const editor = tinymce.get('texto-receita');
                    if (editor)
                        editor.setContent('');
                });

                modalReceitas.show();
            },
            abrirModalAnotacao(item) {

                this.itemAnotacao = item;

                this.anotacao = {
                    procedimento_id: item.id,
                    usuario_id: <?= (int) $this->request->getAttribute('identity')->id ?>,
                    profissional_id: <?= (int) $this->request->getAttribute('identity')->id ?>,
                    tipo: 'anotacao',
                    cabecalho: false,
                    texto: null,
                    salvando: false
                };

                // abre o modal via Bootstrap
                const modalAnotacoes = new bootstrap.Modal(
                    document.getElementById('bs-modal-anotacoes')
                );

                this.$nextTick(() => {
                    const editor = tinymce.get('texto-anotacao');
                    if (editor)
                        editor.setContent('');
                });

                modalAnotacoes.show();
            },
            formatarTamanho(bytes) {
                if (!bytes) return '-';

                const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                const i = Math.floor(Math.log(bytes) / Math.log(1024));

                return (bytes / Math.pow(1024, i)).toFixed(2) + ' ' + sizes[i];
            },

            formatarData(data) {
                if (!data) return '-';

                const d = new Date(data);
                return d.toLocaleDateString('pt-BR') + ' ' +
                    d.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
            },
            removerAnexo(item, anexo, index) {

                Swal.fire({
                    title: "Excluir anexo?",
                    text: "Este arquivo será removido permanentemente.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sim, excluir",
                    cancelButtonText: "Cancelar",
                }).then(result => {

                    if (!result.isConfirmed)
                        return;

                    axios.delete(
                        "<?= $this->Url->build(['controller' => 'Anexos', 'action' => 'delete'], ['fullBase' => true]) ?>/" + anexo.id + ".json", {
                        headers: {
                            'X-CSRF-Token': "<?= $this->request->getAttribute('csrfToken') ?>"
                        }
                    }
                    ).then(response => {

                        if (response.data.success) {

                            // remove do array SEM reload
                            item.anexos.splice(index, 1);

                            Swal.fire({
                                title: "Removido!",
                                text: response.data.message,
                                icon: "success"
                            });

                        } else {
                            Swal.fire({
                                title: "Erro",
                                text: response.data.message,
                                icon: "warning"
                            });
                        }

                    }).
                        catch(() => {
                            Swal.fire({
                                title: "Erro",
                                text: "Erro ao remover o anexo.",
                                icon: "error"
                            });
                        });

                });
            },
            carregarAnexos(item) {
                axios.get(
                    "<?= $this->Url->build(['controller' => 'Anexos', 'action' => 'index', '_ext' => 'json']) ?>", {
                    params: {
                        entidade: 'Procedimentos',
                        entidade_id: item.id
                    }
                }
                ).then(response => {
                    item.anexos = response.data.anexos;
                });
            },
            abrirUpload(item) {
                this.itemUpload = item;
                this.$refs.uploadInput.click();
            },
            abrirImpressao: function () {

                if (this.filaImpressao.length === 0) {
                    Swal.fire({
                        title: "Erro",
                        text: "Selecione pelo menos um procedimento",
                        icon: "error"
                    });

                    return;
                }

                const url = `items=${this.filaImpressao.join(',')}`;
                const finalUrl = `<?= $this->Url->build(["controller" => "Consultas", "action" => "imprimir", $consulta->id]); ?>?${url}`;

                window.open(finalUrl, '_blank');

            },
            arquivoSelecionado(event) {
                const files = Array.from(event.target.files);

                if (!files.length || !this.itemUpload) {
                    event.target.value = '';
                    return;
                }

                const formData = new FormData();

                formData.append('entidade', 'Procedimentos');
                formData.append('entidade_id', this.itemUpload.id);

                files.forEach(file => {
                    formData.append('arquivos[]', file);
                });

                Swal.fire({
                    title: 'Enviando arquivos...',
                    html: `
            <div style="width:100%; background:#eee; border-radius:4px; overflow:hidden;">
                <div id="upload-progress-bar"
                     style="width:0%; height:14px; background:#3085d6; transition: width .2s;"></div>
            </div>
            <p style="margin-top:10px;">
                <strong id="upload-progress-text">0%</strong>
            </p>`,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                axios.post(
                    "<?= $this->Url->build(['controller' => 'Anexos', 'action' => 'upload', '_ext' => 'json'], ['fullBase' => true]); ?>",
                    formData, {
                    headers: {
                        'X-CSRF-Token': "<?= $this->request->getAttribute('csrfToken') ?>",
                        'Content-Type': 'multipart/form-data'
                    },
                    onUploadProgress: (progressEvent) => {
                        if (progressEvent.total) {
                            const percent = Math.round(
                                (progressEvent.loaded * 100) / progressEvent.total
                            );

                            const bar = document.getElementById('upload-progress-bar');
                            const text = document.getElementById('upload-progress-text');

                            if (bar) bar.style.width = percent + '%';
                            if (text) text.innerText = percent + '%';
                        }
                    }
                }
                )
                    .
                    then((response) => {
                        Swal.close();

                        if (response.data.success) {

                            //a AQUI É A CORREÇÃO
                            const index = this.itens.findIndex(i => i.id === this.itemUpload.id);

                            if (index !== -1) {
                                // garante reatividade
                                this.$set(this.itens[index], 'anexos', response.data.anexos);
                            }

                            Swal.fire({
                                title: 'Sucesso',
                                text: response.data.message,
                                icon: 'success'
                            });

                        } else {
                            Swal.fire({
                                title: 'Erro',
                                text: response.data.message,
                                icon: 'error'
                            });
                        }
                    })
                    .
                    catch((error) => {
                        Swal.close();

                        console.error('Erro ao enviar arquivos:', error);

                        Swal.fire({
                            title: 'Erro',
                            text: 'Falha ao tentar enviar os arquivos. Tente novamente.',
                            icon: 'error'
                        });
                    });

                event.target.value = '';
            },
            aprovarProcedimento(item, index) {

                //  Se desaprovou, fecha imediatamente
                if (item.aprovar == false || item.aprovar == 0) {
                    item.aberto = false;
                }

                // Se o item ainda não existe no banco, nenhuma confirmação é necessária
                if (!item.id) {
                    return;
                }

                // Se estiver APROVANDO (true), salva direto
                if (item.aprovar === true) {
                    this.salvarConsulta(item, index);
                    return;
                }

                // Se estiver DESAPROVANDO (false), pedir confirmação
                Swal.fire({
                    title: "Confirma a ação?",
                    text: "Este procedimento já estava aprovado. Tem certeza que deseja desaprovar?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sim, confirmo!",
                    cancelButtonText: "Cancelar",
                }).then((result) => {

                    if (result.isConfirmed) {
                        // Confirmado → salva como desaprovado
                        this.salvarConsulta(item, index);
                    } else {
                        // Cancelou → volta ao estado original (aprovado)
                        item.aprovar = true;
                    }

                });
            },

            atualizarParcelas(item) {
                const subtotal = this.calcularSubtotal(item);

                const parcelas = parseInt(item.parcelas || 1);

                let valor = 0;

                if (parcelas > 0) {
                    valor = subtotal / parcelas;
                }

                // Atualiza já formatado
                item.valor_parcela = valor.toLocaleString('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            },
            totalGeral() {
                let total = 0;

                this.itens.forEach(item => {
                    // converter valor_unitario
                    const valor = parseFloat((item.valor_unitario || "0").toString().replace('.', '').replace(',', '.')) || 0;
                    const desconto = parseFloat((item.desconto || "0").toString().replace('.', '').replace(',', '.')) || 0;
                    const qtd = parseInt(item.quantidade || 1);

                    if (!item.parcelar) {
                        total += (valor - desconto) * qtd;
                    }
                });

                return total.toLocaleString('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });;
            },

            calcularSubtotal(item) {
                // converter para número mesmo usando v-money
                const valor = parseFloat((item.valor_unitario || "0").toString().replace('.', '').replace(',', '.')) || 0;
                const desconto = parseFloat((item.desconto || "0").toString().replace('.', '').replace(',', '.')) || 0;
                const qtd = parseInt(item.quantidade || 1);

                const subtotal = (valor * qtd) - desconto;

                return subtotal > 0 ? subtotal : 0;
            },
            formatarMoeda(valor) {
                return valor.toLocaleString('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            },
            salvarConsulta(item, index) {

                // Impede duplo clique imediatamente
                if (item.salvando)
                    return;


                if (!item.servico_id) {
                    Swal.fire({
                        title: 'Atenção',
                        text: "Selecione um procedimento antes de salvar.",
                        icon: "warning"
                    });
                    return;
                }

                if (!item.profissional_id) {
                    Swal.fire({
                        title: 'Atenção',
                        text: "Selecione um profissional antes de salvar.",
                        icon: "warning"
                    });
                    return;
                }

                // Marca como salvando
                this.$set(this.itens[index], 'salvando', true);

                axios.post(
                    "<?= $this->Url->build(['controller' => 'Procedimentos', 'action' => 'salvar', '_ext' => 'json'], ['fullBase' => true]); ?>",
                    item, {
                    headers: {
                        'X-CSRF-Token': "<?= $this->request->getAttribute('csrfToken') ?>"
                    }
                }
                )
                    .then((response) => {

                        if (response.data.success == true) {

                            //this.itens[index].id = response.data.id;
                            this.$set(this.itens[index], 'id', response.data.id);

                            Swal.fire({
                                title: 'Sucesso',
                                text: response.data.message,
                                icon: "success"
                            });

                        } else {
                            Swal.fire({
                                title: 'Atenção',
                                text: response.data.message,
                                icon: "warning"
                            });
                        }

                    })
                    .
                    catch((error) => {
                        console.error(error);

                        Swal.fire({
                            title: 'Atenção',
                            text: "Erro ao salvar o procedimento.",
                            icon: "warning"
                        });
                    }).finally(() => {
                        // Libera o botão em QUALQUER cenário
                        this.$set(this.itens[index], 'salvando', false);
                    });

            },
            adicionarItem() {
                this.itens.push({
                    consulta_id: <?= $consulta->id; ?>,
                    servico_id: null,
                    profissional_id: null,
                    quantidade: 1,
                    valor_unitario: '',
                    desconto: '0.00',
                    aberto: true,
                    aprovar: false,
                    parcelar: false,
                    parcelas: 1,
                    valor_parcela: '0,00',
                });
            },

            selecionarProcedimento(item, index) {

                if (!item.servico_id)
                    return;

                axios.get(
                    "<?= $this->Url->build(['controller' => 'Servicos', 'action' => 'view'], ['fullBase' => true]); ?>/" + item.servico_id + '.json', {
                    headers: {
                        "X-CSRF-Token": "<?= $this->request->getAttribute('csrfToken') ?>"
                    }
                }
                ).then((response) => {
                    // mantém o this correto
                    this.itens[index].valor_unitario = response.data.servico.valor;
                })
                    .catch((error) => {
                        console.error(error);
                    });
            },
            removerReceita(item, receita, index) {

                Swal.fire({
                    title: "Excluir receita?",
                    text: "Esta receita será removida permanentemente.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sim, excluir",
                    cancelButtonText: "Cancelar"
                }).then(result => {

                    if (!result.isConfirmed)
                        return;

                    Swal.fire({
                        title: 'Removendo...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => Swal.showLoading()
                    });

                    axios.delete(
                        "<?= $this->Url->build(['controller' => 'Receitas', 'action' => 'delete'], ['fullBase' => true]); ?>/" + receita.id + ".json",
                        {
                            headers: {
                                'X-CSRF-Token': "<?= $this->request->getAttribute('csrfToken') ?>"
                            }
                        }
                    )
                        .then(response => {

                            Swal.close();

                            if (response.data && response.data.success) {

                                item.receitas.splice(index, 1);

                                Swal.fire({
                                    title: "Removido!",
                                    text: response.data.message,
                                    icon: "success"
                                });

                            } else {

                                Swal.fire({
                                    title: "Erro",
                                    text: response.data.message || "Erro ao remover receita.",
                                    icon: "warning"
                                });

                            }

                        })
                        .catch((error) => {

                            Swal.close();

                            console.error(error);

                            Swal.fire({
                                title: "Erro",
                                text: error.response?.data?.message || "Erro ao remover a receita.",
                                icon: "error"
                            });

                        });

                });
            },
            excluirDocumento(item, documento, index, tipo) {
                if (documento.assinatura_prof_hash || documento.assinatura_hash) {
                    Swal.fire({
                        title: "Não permitido",
                        text: "Este documento já foi assinado e não pode ser excluído.",
                        icon: "error"
                    });
                    return;
                }

                Swal.fire({
                    title: "Excluir documento?",
                    text: "Este documento será removido permanentemente.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sim, excluir",
                    cancelButtonText: "Cancelar"
                }).then(result => {

                    if (!result.isConfirmed)
                        return;

                    Swal.fire({
                        title: 'Removendo...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => Swal.showLoading()
                    });

                    axios.delete(
                        "<?= $this->Url->build(['controller' => 'Documentos', 'action' => 'delete'], ['fullBase' => true]); ?>/" + documento.id + ".json",
                        {
                            headers: {
                                'X-CSRF-Token': "<?= $this->request->getAttribute('csrfToken') ?>"
                            }
                        }
                    )
                        .then(response => {

                            Swal.close();

                            if (response.data && response.data.success) {

                                // remove da lista (AJUSTE AQUI conforme seu padrão)
                                if (item[tipo]) {
                                    item[tipo].splice(index, 1);
                                }

                                Swal.fire({
                                    title: "Removido!",
                                    text: response.data.message,
                                    icon: "success"
                                });

                            } else {

                                Swal.fire({
                                    title: "Erro",
                                    text: response.data.message || "Erro ao remover documento.",
                                    icon: "warning"
                                });

                            }

                        })
                        .catch((error) => {

                            Swal.close();

                            console.error(error);

                            Swal.fire({
                                title: "Erro",
                                text: error.response?.data?.message || "Erro ao remover o documento.",
                                icon: "error"
                            });

                        });

                });
            },
            removerItem(item, index) {


                if (item.aprovar == 1) {

                    Swal.fire({
                        title: "Excluir?",
                        text: "Este procedimento já esta aprovado!",
                        icon: "warning",
                        confirmButtonText: "OK",
                    });
                    return;
                }

                // Se ainda não foi salvo no banco
                if (!item.id) {
                    this.itens.splice(index, 1);
                    return;
                }

                Swal.fire({
                    title: "Excluir?",
                    text: "Deseja remover este procedimento?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sim",
                    cancelButtonText: "Cancelar"
                }).then(result => {
                    if (!result.isConfirmed)
                        return;

                    // Abre o alerta de carregamento
                    Swal.fire({
                        title: 'Removendo...',
                        text: 'Aguarde enquanto o procedimento é removido',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    axios.delete(
                        "<?= $this->Url->build(['controller' => 'Procedimentos', 'action' => 'delete'], ['fullBase' => true]); ?>/" + item.id + '.json', {
                        headers: {
                            'X-CSRF-Token': "<?= $this->request->getAttribute('csrfToken') ?>"
                        },
                        data: {
                            consulta_id: <?= $consulta->id; ?>
                        }
                    }
                    )
                        .then((response) => {
                            Swal.close(); // fecha o loading
                            if (response.data.success) {
                                this.itens.splice(index, 1);

                                Swal.fire({
                                    title: 'Removido!',
                                    text: response.data.message,
                                    icon: 'success'
                                });
                            } else {
                                Swal.fire({
                                    title: 'Atenção',
                                    text: response.data.message,
                                    icon: 'warning'
                                });
                            }

                        })
                        .catch(() => {

                            Swal.close();
                            Swal.fire({
                                title: 'Erro',
                                text: 'Erro ao remover o procedimento.',
                                icon: 'error'
                            });
                        });

                });
            }
        },

    });

    $(document).on('keyup', '#buscarReceita', function () {

        var termo = $(this).val().toLowerCase();

        $('.adicionarReceitas').each(function () {

            var texto = $(this).text().toLowerCase();
            var item = $(this).closest('.mb-2');

            if (texto.includes(termo)) {
                item.show();
                $(this).closest('.accordion-collapse').addClass('show');
            } else {
                item.hide();
            }

        });

    });

    $(document).on('click', '.adicionarReceitas', function (e) {
        e.preventDefault();

        var $link = $(this);
        var texto = $link.data('descricao');

        if (!texto) return;

        var editor = tinymce.get('texto-receita');
        var conteudoAtual = editor.getContent();

        var bloco = '<p data-receita="' + btoa(texto) + '">' + texto + '</p>';

        if ($link.hasClass('receita-selecionada')) {

            // REMOVE do editor
            var parser = new DOMParser();
            var doc = parser.parseFromString(conteudoAtual, 'text/html');

            $(doc).find('p[data-receita="' + btoa(texto) + '"]').remove();

            editor.setContent(doc.body.innerHTML);

            $link.removeClass('receita-selecionada');

        } else {

            // ADICIONA se ainda não existir
            if (!conteudoAtual.includes('data-receita="' + btoa(texto) + '"')) {
                editor.insertContent(bloco + '<br>');
            }

            $link.addClass('receita-selecionada');
        }
    });

    function limparReceitasSelecionadas() {

        $('.adicionarReceitas').removeClass('receita-selecionada');

        var editor = tinymce.get('texto-receita');
        if (editor) {
            editor.setContent('');
        }

        if (typeof appConsulta !== 'undefined') {
            appConsulta.receita.texto = '';
        }
    }


    function atualizarEditor(tipo, vm) {

        const conteudo = vm.selecionados[tipo]
            .map(m => m.descricao)
            .join('<hr>');

        const editor = tinymce.get('texto-' + tipo);

        if (editor) {
            editor.setContent(conteudo);
        } else {
            $('#texto-' + tipo).val(conteudo);
        }
    }
    $(document).on('click', '.adicionarModelo', function () {

        const id = $(this).data('id');
        const tipo = $(this).data('tipo');
        const descricao = decodeURIComponent($(this).data('descricao'));
        const titulo = $(this).data('titulo'); // NOVO
        const cabecalho = $(this).data('cabecalho'); // NOVO

        const vm = appConsulta;

        const lista = vm.selecionados[tipo];

        const index = lista.findIndex(m => m.id == id);

        if (index >= 0) {
            lista.splice(index, 1);
            $(this).removeClass('active');
        } else {
            lista.push({ id, descricao, titulo, cabecalho }); // NOVO
            $(this).addClass('active');
        }

        atualizarEditor(tipo, vm);

        // DEFINE O TÍTULO AUTOMATICAMENTE
        if (lista.length > 0) {
            vm[tipo].titulo = lista[lista.length - 1].titulo;
            vm[tipo].cabecalho = lista[lista.length - 1].cabecalho;
        } else {
            vm[tipo].titulo = null;
            vm[tipo].cabecalho = null;
        }
    });

    document.addEventListener("show.bs.modal", function (event) {

        const modals = document.querySelectorAll('.modal.show');
        const zIndex = 1050 + (10 * modals.length);

        const modal = event.target;
        modal.style.zIndex = zIndex;

        setTimeout(() => {
            const backdrops = document.querySelectorAll('.modal-backdrop:not(.modal-stack)');
            const backdrop = backdrops[backdrops.length - 1];

            if (backdrop) {
                backdrop.style.zIndex = zIndex - 1;
                backdrop.classList.add('modal-stack');
            }
        }, 0);
    });

    document.addEventListener("hidden.bs.modal", function () {

        if (document.querySelectorAll('.modal.show').length > 0) {
            document.body.classList.add('modal-open');
        }

    });
</script>


<style type="text/css">
    .form-check {
        padding-left: 0;
    }

    .select2-container--default.select2-container--disabled .select2-selection--single {
        background-color: var(--bs-secondary-bg);
    }

    .form-check.form-switch .form-check-label {
        font-weight: 700;
    }

    .form-check.form-switch {
        display: flex;
        flex-direction: column;
        /* label em cima, switch embaixo */
        align-items: center;
        /* centraliza ambos no eixo horizontal */
        justify-content: center;
        /* centraliza no eixo vertical (se tiver altura) */
        gap: 0.25rem;
        padding-left: 0;
    }

    .form-switch .form-check-input {
        width: 4em;
        height: 2rem;
        margin-left: 0;
        /* importante para não empurrar o switch */
        float: none;
        /* bootstrap usa float aqui */
    }

    .switch-label-top {
        display: flex;
        flex-direction: column;
        align-items: center;
        /* label e switch alinhados entre si */
        gap: 0.25rem;
    }

    .form-check.form-switch {
        display: flex;
        justify-content: center;
        /* centraliza o bloco inteiro */
    }

    .form-check.form-switch {
        display: flex;
        justify-content: center;
        /* centraliza horizontal */
    }

    .boxed-layout:hover {
        box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
    }

    .collapsed-area {
        display: none !important;
    }

    .select2-container--default .select2-selection--single {
        padding-top: 0.575rem;
        padding-right: 1.375rem;
        padding-bottom: 0.575rem;
        padding-left: 0.975rem;
        font-size: medium;
        font-weight: 500;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #1ab394;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 20px !important;
        height: 30px !important;
    }

    body .select2-container--default .select2-selection--single .select2-selection__arrow {
        top: -10px;
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-left: 0px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        top: 80%
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        border: 1px solid #aaa;
        border-radius: .5rem;
        box-sizing: border-box;
        display: inline-block;
        margin-left: 5px;
        margin-top: 5px;
        padding: 0;
        padding-left: 0px;
        padding-left: 20px;
        position: relative;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: bottom;
        white-space: nowrap;

    }

    .select2-container {
        display: inherit !important;
    }

    .nav2 {
        display: flex;
        flex-wrap: wrap;
        list-style: none;
        padding-left: 0;
        margin-bottom: 1rem;
        border-bottom: 1px solid #dee2e6;
    }

    .nav2-item {
        margin-bottom: -1px;
    }

    .nav2-link {
        display: block;
        padding: 0.5rem 1rem;
        color: #0d6efd;
        text-decoration: none;
        background: none;
        border: 1px solid transparent;
        border-top-left-radius: 0.375rem;
        border-top-right-radius: 0.375rem;
    }

    .nav2-link:hover {
        border-color: #e9ecef #e9ecef #dee2e6;
        color: #0a58ca;
    }

    .nav2-link.active {
        color: #495057;
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
    }

    .nav2-link.disabled {
        color: #6c757d;
        pointer-events: none;
        background-color: transparent;
        border-color: transparent;
    }

    /* Dropdown alinhado ao Bootstrap */
    .nav2 .dropdown-menu {
        margin-top: 0;
    }

    .modal-body {
        max-height: 80vh;
    }

    .receitas-container {
        display: flex;
        flex-direction: column;
        height: 70vh;
        /* controla altura geral */
    }

    .receitas-busca {
        position: sticky;
        top: 0;
        background: #fff;
        z-index: 5;
        padding-bottom: 8px;
    }

    .receitas-scroll {
        flex: 1;
        overflow-y: auto;
        padding-right: 6px;
    }

    /* Scroll discreto */
    .receitas-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .receitas-scroll::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 4px;
    }

    .receita-selecionada {
        background-color: rgba(13, 110, 253, 0.1);
        border-left: 3px solid #0d6efd;
        padding-left: 6px;
        border-radius: 4px;
    }
</style>
<?php

$this->Breadcrumbs->setTemplates([
    'wrapper' => '<nav aria-label="breadcrumb"><ol class="breadcrumb mb-0 mt-1">{{content}}</ol></nav>',
    'item' => '<li class="breadcrumb-item"><a href="{{url}}"{{innerAttrs}}>{{title}}</a></li>{{separator}}',
    'itemWithoutLink' => '<li class="breadcrumb-item active" aria-current="page"><span{{innerAttrs}}>{{title}}</span></li>{{separator}}',
    'separator' => ''
]);


$this->BreadcrumbsPlus->setFirstCrumb(
    ['title' => '<i class="ti ti-home fs-4 mt-1"></i>', 'url' => ['controller' => 'Usuarios', 'action' => 'index']]
);

?>
<div class="mb-3 overflow-hidden position-relative">
    <div class="px-3">
        <h4 class="fs-6 mb-0"><?= $title; ?></h4>
        <?= $this->BreadcrumbsPlus->render(['class' => 'breadcrumb mb-0']) ?>
    </div>
</div>

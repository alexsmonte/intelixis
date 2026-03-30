<?php

$cakeDescription = 'Intelixis: Soluções inteligentes';
use Cake\Core\Configure;

?>

<!DOCTYPE html>
<html lang="pt_br" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="horizontal">


<head>
<style>

/* Cor de fundo do tooltip */
.tooltip .tooltip-inner {
    background-color: #E7F2FB; /* cor de fundo personalizada */
    color: #2271BA;           /* cor do texto */
    font-size: 0.875rem;       /* tamanho da fonte */
    padding: 6px 10px;         /* espaçamento interno */
    border-radius: 6px;        /* borda arredondada */
    max-width: 250px;           /* largura máxima */
    text-align: center;
}

/* Cor da seta */
.tooltip .tooltip-arrow::before {
    border-color: #E7F2FB !important;
}

/* Opcional: animação ao aparecer */
.tooltip.show {
    opacity: 1 !important;
    transition: opacity 0.3s ease-in-out;
}

</style>   



    <!-- Required meta tags -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/png" href="/img/logos/favicon.png" />

        <title>
            <?= $cakeDescription ?> -
            <?= $this->fetch('title') ?>
        </title>

    <!-- Core Css -->
    <?= $this->Html->css(['styles.css']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <!-- CSS do Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- jQuery (necessário para Select2) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- JS do Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <?= $this->Html->script('forms/select2.init.js') ?>

    <!-- Import Js Files -->
    <?= $this->Html->script(['/libs/bootstrap/dist/js/bootstrap.bundle.min.js', '/libs/simplebar/dist/simplebar.min.js', '/js/theme/rmhorizontal.init.js', '/js/theme/theme.js','/js/theme/app.min.js','/js/theme/sidebarmenu.js','/js/theme/feather.min.js', '/js/highlights/highlight.min.js']) ?>

   <!-- Sweet Alert-->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- vue  -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>



</head>

<body class="link-sidebar">

<!-- Preloader -->
<div class="preloader">
    <img src="img/logos/favicon.png" alt="carregando" class="lds-ripple img-fluid" />
</div>
<div id="main-wrapper">
    <!-- Sidebar Start -->
    <aside class="left-sidebar with-vertical">
        <!-- ---------------------------------- -->
        <!-- Start Vertical Layout Sidebar -->
        <!-- ---------------------------------- -->
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="../horizontal/index.html" class="text-nowrap logo-img">
                <img src="/img/logos/logo-light.svg" class="dark-logo" alt="Logo-Dark" />
                <img src="/img/logos/logo-dark.svg" class="light-logo" alt="Logo-light" />
            </a>
            <a href="javascript:void(0)" class="sidebartoggler ms-auto text-decoration-none fs-5 d-block d-xl-none">
                <i class="ti ti-x"></i>
            </a>
        </div>

        <div class="scroll-sidebar" data-simplebar>
            <!-- Sidebar navigation-->
            <?= $this->element('menu'); ?>
            <!-- End Sidebar navigation -->
        </div>

        <div class=" fixed-profile mx-3 mt-3">
            <div class="card bg-primary-subtle mb-0 shadow-none">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <img src="/img/profile/user-1.jpg" width="45" height="45" class="img-fluid rounded-circle" alt="spike-img" />
                            <div>
                                <?php
                                $nomeCompleto = $this->request->getSession()->read('Usuario.nome_completo');
                                $primeiroNome = explode(' ', trim($nomeCompleto))[0] ?? '';
                                ?>
                                <h5 class="mb-1"><?= $primeiroNome; ?></h5>
                                <p class="mb-0">Admin</p>
                            </div>
                        </div>
                        <a href="../horizontal/authentication-login.html" class="position-relative" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Logout">
                            <iconify-icon icon="solar:logout-line-duotone" class="fs-8"></iconify-icon>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ---------------------------------- -->
        <!-- Start Vertical Layout Sidebar -->
        <!-- ---------------------------------- -->
    </aside>
    <!--  Sidebar End -->
    <div class="page-wrapper">
        <?= $this->element('sidebar'); ?>
        <div class="body-wrapper">
            <div class="container-fluid">
                <?= $this->element('header'); ?>
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </div>
        </div>

<!---
        <script>
            function handleColorTheme(e) {
                document.documentElement.setAttribute("data-color-theme", e);
            }
        </script>
        <button class="btn btn-primary p-3 rounded-circle d-flex align-items-center justify-content-center customizer-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
            <i class="icon ti ti-settings fs-7"></i>
        </button>
--->

    </div>
    <div class="dark-transparent sidebartoggler"></div>
</div>


  



<!-- solar icons -->
<script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>



<!-- highlight.js (code view) -->
<script>
    hljs.initHighlightingOnLoad();
    document.querySelectorAll("pre.code-view > code").forEach((codeBlock) => {
        codeBlock.textContent = codeBlock.innerHTML;
    });
</script>
<!-- imask.js -->
<script src="https://unpkg.com/imask"></script>
<!--?= $this->Html->script('mascaras.js') ?-->
<!-- select2 -->
<!-- Fecha Alert-->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
      setTimeout(() => {
        alert.classList.remove('show');
        alert.classList.add('fade');
        setTimeout(() => alert.remove(), 500);
      }, 5000); // tempo em ms
    });
  });
</script>

</body>
</html>

 


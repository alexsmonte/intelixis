<?php
$cakeDescription = 'Intelixs: Soluções inteligentes';

use Cake\Core\Configure;

?>
<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="horizontal">
<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/png" href="/img/logos/favicon.png" />

    <!-- Core Css -->
    <title>
        <?= $cakeDescription ?> -
        <?= $this->fetch('title') ?>
    </title>

    <!-- Core Css -->
    <?= $this->Html->css(['styles.css']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>

<body>
<!-- Preloader -->
<div class="preloader">
    <img src="img/logos/favicon.png" alt="loader" class="lds-ripple img-fluid" />
</div>

<div id="main-wrapper" class="p-0 bg-white auth-customizer-none">
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
        <?= $this->fetch('content') ?>
    </div>
</div>
<!-- Import Js Files -->

<?= $this->Html->script(['/libs/bootstrap/dist/js/bootstrap.bundle.min.js', '/libs/simplebar/dist/simplebar.min.js', '/js/theme/app.horizontal.init.js', '/js/theme/theme.js','/js/theme/app.min.js','/js/theme/feather.min.js']) ?>

<!-- solar icons -->
<script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>


</html>
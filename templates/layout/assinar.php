<?php
$cakeDescription = 'Intelixis';
use Cake\Core\Configure;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="theme-color" content="#2563eb" />
    <link rel="shortcut icon" type="image/png" href="/img/logos/favicon.png" />
    <title><?= $this->fetch('title') ?> — <?= $cakeDescription ?></title>
    <?= $this->Html->css(['styles.css']) ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html, body {
            margin: 0; padding: 0;
            background: #f1f5f9;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100dvh;
        }
    </style>
</head>
<body>
    <?= $this->fetch('content') ?>
    <?= $this->fetch('script') ?>
</body>
</html>

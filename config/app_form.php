<?php

// config/app_form.php (ou onde você definir os templates)
return [
    'inputContainer' =>
        '<div class="input mb-3 {{type}}{{required}}">{{content}}</div>',
    'inputContainerError' =>
        '<div class="input mb-3 {{type}}{{required}} has-error">{{content}}{{error}}</div>',
    'error' =>
        '<div class="invalid-feedback d-block">{{content}}</div>',
    'errorClass' => ' is-invalid'
];

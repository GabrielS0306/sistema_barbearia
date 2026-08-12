<?php

    // tests/bootstrap.php
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/../core/Database.php';
    require_once __DIR__ . '/../core/Router.php';
    require_once __DIR__ . '/../core/Upload.php';
    require_once __DIR__ . '/../core/Mailer.php';
    require_once __DIR__ . '/../core/Csrf.php';
    require_once __DIR__ . '/../core/Paginacao.php';
    require_once __DIR__ . '/../core/Logger.php';
    require_once __DIR__ . '/../core/Pix.php';

    // Carrega todos os models
    foreach (glob(__DIR__ . '/../app/models/*.php') as $model) {
        require_once $model;
    }

?>
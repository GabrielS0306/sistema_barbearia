<?php 

    // public/index.php

    // carrega classes de infraestrutura
    require_once __DIR__ . '/../core/Database.php';
    require_once __DIR__ . '/../core/Router.php';

    // Autoload simples: carrega Models e Controllers automaticamente
    spl_autoload_register(function (string $class) {
        $caminhos = [
            __DIR__ . "/../app/models/{$class}.php",
            __DIR__ . "/../app/controllers/{$class}.php",
        ];

        foreach ($caminhos as $caminho) {
            if (file_exists($caminho)) {
                require_once $caminho;
                return;
            }
        }
    });

    session_start();

    $router = new Router();

    // Rotas -> Irá sendo adicionado as rotas conforme o projeto for crescendo
    $router->get('/login', 'AuthController::login');
    $router->post('/login', 'AuthController::login');

    // Pega a URL vinda do .htaccess e remove a barra inicial/final
    $uri = $_GET['url'] ?? '';
    $uri = '/' . trim($uri, '/');

    $router->dispatch($_SERVER['REQUEST_METHOD'], $uri);

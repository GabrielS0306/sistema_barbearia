<?php

    // public/router.php
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // Remove prefixo /barbearia se existir (ambiente local)
    $uri = preg_replace('#^/barbearia#', '', $uri);

    // Redireciona raiz pro login
    if ($uri === '/' || $uri === '') {
        header('Location: /login');
        exit;
    }

    // Se for um arquivo estático que existe, serve diretamente
    if (file_exists(__DIR__ . $uri)) {
        return false;
    }

    // Passa a URI pro index.php via $_GET['url']
    $_GET['url'] = ltrim($uri, '/');

    require __DIR__ . '/index.php';

?>
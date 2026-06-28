<?php 

    // public/router.php
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = str_replace('/barbearia', '', $uri);

    if (file_exists(__DIR__ . $uri) && $uri !== '/') {
        return false; // Serve o arquivo estático diretamente 
    }

    $_GET['url'] = ltrim($uri, '/');

    require __DIR__ . '/index.php';

?>
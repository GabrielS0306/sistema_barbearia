<?php 

    // core/Router.php
    class Router {
        private array $routes = [];

        // O método get é usado para registrar rotas do tipo GET, associando um caminho específico a um manipulador (handler) que será executado quando essa rota for acessada.
        public function get(string $path, string $handler): void {
            $this->routes['GET'][$path] = $handler;
        }

        // O método post é usado para registrar rotas do tipo POST, associando um caminho específico a um manipulador (handler) que será executado quando essa rota for acessada.
        public function post(string $path, string $handler): void {
            $this->routes['POST'][$path] = $handler;
        }

        public function dispatch(string $method, string $url): void {
            $handler = $this->routes[$method][$url] ?? null;

            if (!$handler) {
                // http_response_code() -> Define o código de status HTTP para a resposta. Neste caso, 404 indica que a página solicitada não foi encontrada.
                http_response_code(404);
                echo "404 - Página não encontrada";
                return;
            }

            [$class, $method] = explode('::', $handler);
            (new $class())->$method();
        }
    }

?>
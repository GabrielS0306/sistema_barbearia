<?php
    // core/Router.php
    class Router {
        private array $routes = [];
        private array $protectedRoutes = [];

        public function get(string $path, string $handler, array $roles = []): void {
            $this->routes['GET'][$path] = $handler;
            if (!empty($roles)) {
                $this->protectedRoutes[$path] = $roles;
            }
        }

        public function post(string $path, string $handler, array $roles = []): void {
            $this->routes['POST'][$path] = $handler;
            if (!empty($roles)) {
                $this->protectedRoutes[$path] = $roles;
            }
        }

        private function protect(string $path): void {
            if (!isset($this->protectedRoutes[$path])) {
                return; // rota pública, deixa passar
            }

            if (!isset($_SESSION['user_id'])) {
                header('Location: /barbearia/login');
                exit;
            }

            if (!in_array($_SESSION['user_role'], $this->protectedRoutes[$path])) {
                http_response_code(403);
                echo 'Acesso negado.';
                exit;
            }
        }

        public function dispatch(string $method, string $uri): void {
            $handler = $this->routes[$method][$uri] ?? null;

            if (!$handler) {
                http_response_code(404);
                echo '404 - Página não encontrada';
                return;
            }

            $this->protect($uri);

            [$class, $action] = explode('::', $handler);
            (new $class())->$action();
        }
    }
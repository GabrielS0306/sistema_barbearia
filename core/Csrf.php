<?php 

    // core/Csrf.php
    class Csrf {
        public static function gerar(): string {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (empty($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }

            return (string) ($_SESSION['csrf_token']);
        }

        public static function validar(string $token): bool {
            if (empty($_SESSION['csrf_token'])) {
                return false;
            }

            return hash_equals($_SESSION['csrf_token'], trim($token));
        }

        public static function campo(): string {
            return "<input type='hidden' name='csrf_token' value='" . self::gerar() . "'>";
        }

        public static function verificar(): void {
            $token = $_POST['csrf_token'] ?? '';

            if (!self::validar($token)) {
                http_response_code(403);
                die('Requisição inválida - token CSRF inválido');
            }
        }
    }

?>
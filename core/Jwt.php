<?php

    // core/Jwt.php
    use Firebase\JWT\JWT as FirebaseJWT;
    use Firebase\JWT\Key;

    class Jwt {
        private static string $segredo = 'BARBEARIA_JWT_SECRET_2026_TROCAR_EM_PRODUCAO';
        private static string $algoritmo = 'HS256';
        private static int $expiracao = 3600; // 1 hora

        public static function gerar(array $payload): string {
            $agora = time();

            $dados = array_merge($payload, [
                'iat' => $agora,
                'exp' => $agora + self::$expiracao,
            ]);

            return FirebaseJWT::encode($dados, self::$segredo, self::$algoritmo);
        }

        public static function validar(string $token): array|false {
            try {
                $decoded = FirebaseJWT::decode($token, new Key(self::$segredo, self::$algoritmo));
                return (array) $decoded;
            } catch (\Exception $e) {
                Logger::aviso('JWT inválido: ' . $e->getMessage());
                return false;
            }
        }

        public static function tokenDaRequisicao(): string|false {
            $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

            if (str_starts_with($header, 'Bearer ')) {
                return substr($header, 7);
            }

            return false;
        }
    }

?>
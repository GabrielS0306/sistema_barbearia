<?php 

    //core/RateLimiter.php
    class RateLimiter {
        private static string $pasta = '';

        private static function getPasta(): string {
            if (empty(self::$pasta)) {
                self::$pasta = __DIR__ . '/../storage/rate_limit/';
            }

            return self::$pasta;
        }

        public static function verificar(string $chave, int $limite = 60, int $janela = 60): bool {
            $pasta = self::getPasta();

            if (!is_dir($pasta)) {
                mkdir($pasta, 0755, true);
            }

            $arquivo = $pasta . md5($chave) . '.json';
            $agora   = time();

            $dados = ['requisicoes' => [], 'total' => 0];

            if (file_exists($arquivo)) {
                $dados = json_decode(file_get_contents($arquivo), true);
            }

            // Remove requisições fora da janela de tempo
            $dados['requisicoes'] = array_filter(
                $dados['requisicoes'],
                fn($timestamp) => ($agora - $timestamp) < $janela
            );

            // Verifica se atingiu o limite
            if (count($dados['requisicoes']) >= $limite) {
                return false;
            }

            // Registra a requisição atual
            $dados['requisicoes'][] = $agora;
            $dados['total']         = count($dados['requisicoes']);

            file_put_contents($arquivo, json_encode($dados), LOCK_EX);

            return true;
        }

        public static function cabecalhos(string $chave, int $limite = 60, int $janela = 60): array {
            $pasta   = self::getPasta();
            $arquivo = $pasta . md5($chave) . '.json';
            $agora   = time();

            $total = 0;
            if (file_exists($arquivo)) {
                $dados = json_decode(file_get_contents($arquivo), true);
                
                $recentes = array_filter(
                    $dados['requisicoes'] ?? [],
                    fn($t) => ($agora - $t) < $janela
                );
                $total = count($recentes);
            }

            return [
                'X-RateLimit-Limit'     => $limite,
                'X-RateLimit-Remaining' => max(0, $limite - $total),
                'X-RateLimit-Window'    => $janela,
            ];
        }
    }

?>
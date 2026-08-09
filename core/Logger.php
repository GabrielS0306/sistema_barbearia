<?php 

    // core/Logger.php
    class Logger {
        private static string $arquivo = '';

        public static function getArquivo(): string {
            if (empty(self::$arquivo)) {
                self::$arquivo = __DIR__ . '/../logs/app-' . date('Y-m') . '.log';
            }

            return self::$arquivo;
        }

        public static function erro(string $mensagem, array $contexto = []): void {
            self::registrar('ERRO', $mensagem, $contexto);
        }

        public static function aviso(string $mensagem, array $contexto = []): void {
            self::registrar('AVISO', $mensagem, $contexto);
        }

        public static function info(string $mensagem, array $contexto = []): void {
            self::registrar('INFO', $mensagem, $contexto);
        }

        private static function registrar(string $nivel, string $mensagem, array $contexto = []): void {
            $pasta = dirname(self::getArquivo());

            if (!is_dir($pasta)) {
                mkdir($pasta, 0755, true);
            }

            $data = date('Y-m-d H:i:s');
            $contextoStr = !empty($contexto) ? ' | ' . json_encode($contexto, JSON_UNESCAPED_UNICODE) : '';
            $linha     = "[{$data}] [{$nivel}] {$mensagem}{$contextoStr}" . PHP_EOL;
    
            file_put_contents(self::getArquivo(), $linha, FILE_APPEND | LOCK_EX);
        }

        public static function capturarExcecoes(): void {
            set_exception_handler(function (Throwable $e) {
                self::erro($e->getMessage(), [
                    'arquivo' => $e->getFile(),
                    'linha'   => $e->getLine(),
                    'trace'   => $e->getTraceAsString(),
                ]);

                http_response_code(500);
                echo '500 - Erro interno do servidor.';
                exit;
            });

            set_error_handler(function (int $errno, string $errstr, string $errfile, int $errline) {
                if (!(error_reporting() & $errno)) return false;

                self::aviso($errstr, [
                    'arquivo' => $errfile,
                    'linha'   => $errline,
                    'codigo'  => $errno,
                ]);

                return true;
            });
        }
    }

?>
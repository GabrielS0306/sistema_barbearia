<?php

    // tests/Unit/Core/LoggerTest.php
    use PHPUnit\Framework\TestCase;

    class LoggerTest extends TestCase {
        private string $arquivoLog;

        protected function setUp(): void {
            $this->arquivoLog = __DIR__ . '/../../../logs/app-' . date('Y-m') . '.log';
        }

        public function test_registra_erro(): void {
            Logger::erro('Teste de erro');
            $this->assertFileExists($this->arquivoLog);
            $conteudo = file_get_contents($this->arquivoLog);
            $this->assertStringContainsString('[ERRO] Teste de erro', $conteudo);
        }

        public function test_registra_aviso(): void {
            Logger::aviso('Teste de aviso');
            $conteudo = file_get_contents($this->arquivoLog);
            $this->assertStringContainsString('[AVISO] Teste de aviso', $conteudo);
        }

        public function test_registra_info(): void {
            Logger::info('Teste de info');
            $conteudo = file_get_contents($this->arquivoLog);
            $this->assertStringContainsString('[INFO] Teste de info', $conteudo);
        }

        public function test_registra_contexto(): void {
            Logger::erro('Erro com contexto', ['chave' => 'valor']);
            $conteudo = file_get_contents($this->arquivoLog);
            $this->assertStringContainsString('"chave":"valor"', $conteudo);
        }

        public function test_log_contem_data(): void {
            Logger::info('Teste de data');
            $conteudo = file_get_contents($this->arquivoLog);
            $this->assertStringContainsString(date('Y-m-d'), $conteudo);
        }
    }

?>
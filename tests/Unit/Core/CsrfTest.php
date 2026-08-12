<?php

    // tests/Unit/Core/CsrfTest.php
    use PHPUnit\Framework\TestCase;

    class CsrfTest extends TestCase {
        protected function setUp(): void {
            // Inicia sessão pra os testes
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            // Limpa o token antes de cada teste
            unset($_SESSION['csrf_token']);
        }

        public function test_gerar_retorna_string(): void {
            $token = Csrf::gerar();
            $this->assertIsString($token);
        }

        public function test_token_tem_64_caracteres(): void {
            $token = Csrf::gerar();
            $this->assertEquals(64, strlen($token));
        }

        public function test_token_consistente_na_mesma_sessao(): void {
            $token1 = Csrf::gerar();
            $token2 = Csrf::gerar();
            $this->assertEquals($token1, $token2);
        }

        public function test_validar_token_correto(): void {
            $token = Csrf::gerar();
            $this->assertTrue(Csrf::validar($token));
        }

        public function test_validar_token_incorreto(): void {
            Csrf::gerar();
            $this->assertFalse(Csrf::validar('token_invalido'));
        }

        public function test_validar_sem_sessao_retorna_false(): void {
            unset($_SESSION['csrf_token']);
            $this->assertFalse(Csrf::validar('qualquer_token'));
        }

        public function test_campo_retorna_input_html(): void {
            $campo = Csrf::campo();
            $this->assertStringContainsString('<input', $campo);
            $this->assertStringContainsString('name=\'csrf_token\'', $campo);
            $this->assertStringContainsString('type=\'hidden\'', $campo);
        }
    }

?>
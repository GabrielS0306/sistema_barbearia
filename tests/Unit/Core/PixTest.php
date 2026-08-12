<?php

    // tests/Unit/Core/PixTest.php
    use PHPUnit\Framework\TestCase;

    class PixTest extends TestCase {
        private Pix $pix;

        protected function setUp(): void {
            $this->pix = new Pix();
        }

        public function test_gerar_copia_cola_retorna_string(): void {
            $codigo = $this->pix->gerarCopiaCola(25.00);
            $this->assertIsString($codigo);
        }

        public function test_copia_cola_nao_vazio(): void {
            $codigo = $this->pix->gerarCopiaCola(25.00);
            $this->assertNotEmpty($codigo);
        }

        public function test_copia_cola_contem_crc(): void {
            $codigo = $this->pix->gerarCopiaCola(25.00);
            // CRC16 sempre tem 4 caracteres hexadecimais no final
            $this->assertMatchesRegularExpression('/[0-9A-F]{4}$/', $codigo);
        }

        public function test_copia_cola_contem_valor(): void {
            $codigo = $this->pix->gerarCopiaCola(50.00);
            $this->assertStringContainsString('50.00', $codigo);
        }

        public function test_gerar_qr_code_retorna_string(): void {
            $qrCode = $this->pix->gerarQrCode(25.00);
            $this->assertIsString($qrCode);
        }

        public function test_qr_code_e_base64(): void {
            $qrCode = $this->pix->gerarQrCode(25.00);

            $this->assertStringStartsWith('data:image/svg+xml;base64,', $qrCode);
        }
    }

?>
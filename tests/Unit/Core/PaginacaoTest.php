<?php

    // tests/Unit/Core/PaginacaoTest.php
    use PHPUnit\Framework\TestCase;

    class PaginacaoTest extends TestCase {
        public function test_total_paginas_calculado_corretamente(): void {
            $paginacao = new Paginacao(100, 10, 1);
            $this->assertEquals(10, $paginacao->totalPaginas());
        }

        public function test_total_paginas_com_resto(): void {
            $paginacao = new Paginacao(101, 10, 1);
            $this->assertEquals(11, $paginacao->totalPaginas());
        }

        public function test_offset_pagina_1(): void {
            $paginacao = new Paginacao(100, 10, 1);
            $this->assertEquals(0, $paginacao->offset());
        }

        public function test_offset_pagina_2(): void {
            $paginacao = new Paginacao(100, 10, 2);
            $this->assertEquals(10, $paginacao->offset());
        }

        public function test_offset_pagina_3(): void {
            $paginacao = new Paginacao(100, 10, 3);
            $this->assertEquals(20, $paginacao->offset());
        }

        public function test_tem_proxima_quando_nao_e_ultima_pagina(): void {
            $paginacao = new Paginacao(100, 10, 1);
            $this->assertTrue($paginacao->temProxima());
        }

        public function test_nao_tem_proxima_na_ultima_pagina(): void {
            $paginacao = new Paginacao(100, 10, 10);
            $this->assertFalse($paginacao->temProxima());
        }

        public function test_tem_anterior_quando_nao_e_primeira_pagina(): void {
            $paginacao = new Paginacao(100, 10, 2);
            $this->assertTrue($paginacao->temAnterior());
        }

        public function test_nao_tem_anterior_na_primeira_pagina(): void {
            $paginacao = new Paginacao(100, 10, 1);
            $this->assertFalse($paginacao->temAnterior());
        }

        public function test_pagina_atual_nunca_menor_que_1(): void {
            $paginacao = new Paginacao(100, 10, -5);
            $this->assertEquals(1, $paginacao->paginaAtual());
        }

        public function test_limite_retorna_por_pagina(): void {
            $paginacao = new Paginacao(100, 15, 1);
            $this->assertEquals(15, $paginacao->limite());
        }

        public function test_paginas_retorna_range_correto(): void {
            $paginacao = new Paginacao(100, 10, 5);
            $this->assertEquals([3, 4, 5, 6, 7], $paginacao->paginas());
        }
    }

?>
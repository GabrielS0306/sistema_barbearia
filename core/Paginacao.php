<?php 

    // core/Paginacao.php
    class Paginacao {
        private int $total;
        private int $porPagina;
        private int $paginaAtual;
        
        public function __construct(int $total, int $porPagina = 10, int $paginaAtual = 1) {
            $this->total      = $total;
            $this->porPagina  = $porPagina;
            $this->paginaAtual = max(1, $paginaAtual);
        }

        public function totalPaginas(): int {
            return (int) ceil($this->total / $this->porPagina);
        }

        public function offSet(): int {
            return ($this->paginaAtual - 1) * $this->porPagina;
        }

        public function limite(): int {
            return $this->porPagina;
        }

        public function paginaAtual(): int {
            return $this->paginaAtual;
        }

        public function temAnterior(): bool {
            return $this->paginaAtual > 1;
        }

        public function temProxima(): bool {
            return $this->paginaAtual < $this->totalPaginas();
        }

        public function paginaAnterior(): int {
            return $this->paginaAtual - 1;
        }

        public function proximaPagina(): int {
            return $this->paginaAtual + 1;
        }

        public function paginas(): array {
            $paginas = [];
            $inicio  = max(1, $this->paginaAtual - 2);
            $fim     = min($this->totalPaginas(), $this->paginaAtual + 2);

            for ($i = $inicio; $i <= $fim; $i++) {
                $paginas[] = $i;
            };

            return $paginas;
        }
    }

?>
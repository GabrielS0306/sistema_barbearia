<?php 

    class AgendamentoController {
        public function novo(): void {
            require __DIR__ . "/../views/agendamento/novo.php";
        }

        public function meus(): void {
            require __DIR__ . "/../views/agendamento/lista.php";
        }
    }

?>
<?php 

    // app/controllers/AdminController.php
    class AdminController {
        public function dashboard(): void {
            require __DIR__ . "/../views/admin/dashboard.php";
        }

        public function barbeiros(): void {
            require __DIR__ . "/../views/admin/barbeiros.php";
        }

        public function servicos(): void {
            require __DIR__ . "/../views/admin/servicos.php";
        }

        public function agendamentos(): void {
            $modelAgendamento = new Agendamento();
            $modelBarbeiro = new Barbeiro();

            $filtros = [
                'data'        => $_GET['data'] ?? '',
                'barbeiro_id' => $_GET['barbeiro_id'] ?? '',
                'status'      => $_GET['status'] ?? '',
            ];


            $agendamentos = $modelAgendamento->buscarTodos($filtros);
            $contagem = $modelAgendamento->contarPorStatus();
            $barbeiros = $modelBarbeiro->listarTodos();

            require __DIR__ . "/../views/admin/agendamentos.php";
        }
    }

?>
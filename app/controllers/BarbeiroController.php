<?php 

    class BarbeiroController {
        private Agendamento $model;

        public function __construct() {
            $this->model = new Agendamento();
        }

        public function agenda(): void {
            $barbeiroId = $_SESSION['user_barbeiro_id'];
            $data = $_GET['data'] ?? date('Y-m-d');

            $agendamentos = $this->model->buscarPorBarbeiro($barbeiroId, $data);

            require __DIR__ . "/../views/barbeiro/agenda.php";
        }

        public function atualizarStatus(): void {
            $id = (int) ($_POST['id'] ?? 0);
            $status = $_POST['status'] ?? '';

            $statusPermitidos = ['onfirmado', 'concluido', 'cancelado'];

            if ($id && in_array($status, $statusPermitidos)) {
                $db = Database::getInstance();
                $stmt = $db->prepare("UPDATE agendamentos SET status = :status WHERE id = :id");
                $stmt->execute([':status' => $status, ':id' => $id]);
            }

            header("Location: /barbearia/barbeiro/agenda");
            exit;
        }
    }

?>
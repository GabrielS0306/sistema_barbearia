<?php 

    // app/controllers/Agendamentocontroller.php
    class AgendamentoController {
        private Agendamento $model;

        public function __construct() {
            $this->model = new Agendamento();
        }

        public function novo(): void {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $barbeiroId = (int) $_POST['barbeiro_id'];
                $data       = $_POST['data'];
                $hora       = $_POST['hora'];

                if (!$this->model->horarioDisponivel($barbeiroId, $data, $hora)) {
                    $_SESSION['erro'] = 'Horário indisponivel. Escolha outro, por favor!';
                    header("Location: /barbearia/agendamento/novo");
                    exit;
                }

                $this->model->criar([
                    'cliente_id'  => $_SESSION['user_cliente_id'],
                    'barbeiro_id' => $barbeiroId,
                    'servico_id'  => (int) $_POST['servico_id'],
                    'data'        => $data,
                    'hora'        => $hora,
                ]);

                $_SESSION['sucesso'] = "Agendamento realizado com sucesso!";
                header("Location: /barbearia/agendamento/meus");
                exit;
            }

            // GET: Carrega barbeiros e serviços pra preencher o formulário
            $db = Database::getInstance();
            $barbeiros = $db->query('SELECT id, nome FROM barbeiros ORDER BY nome')->fetchAll();
            $servicos  = $db->query('SELECT id, nome, preco, duracao_min FROM servicos ORDER BY nome')->fetchAll();

            require __DIR__ . "/../views/agendamento/novo.php";
        }

        public function meus(): void {
            $clienteId = $_SESSION['user_cliente_id'];
            $agendamentos = $this->model->buscarPorCliente($clienteId);

            require __DIR__ . "/../views/agendamento/lista.php";
        }
    }

?>
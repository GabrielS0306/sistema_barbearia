<?php 

    // app/controllers/ClienteController.php
    class ClienteController {
        private Cliente $model;

        public function __construct() {
            $this->model = new Cliente();
        }

        public function perfil(): void {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                Csrf::verificar();

                $nome     = trim($_POST['nome'] ?? '');
                $telefone = trim($_POST['telefone'] ?? '');

                if (empty($nome)) {
                    $_SESSION['erro'] = "O nome não pode ser vazio.";
                    header('Location: /barbearia/cliente/perfil');
                    exit;
                }

                $clienteId = $_SESSION['user_cliente_id'];
                $this->model->atualizar($clienteId, [
                    'nome'     => $nome,
                    'telefone' => $telefone,
                ]);

                $_SESSION['sucesso'] = "Perfil atualizado com sucesso.";
                header('Location: /barbearia/cliente/perfil');
                exit;
            }

            $cliente = $this->model->buscarPorUsuarioId($_SESSION['user_id']);
            require __DIR__ . "/../views/cliente/perfil.php";
        }
    }

?>
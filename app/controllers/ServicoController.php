<?php 

    // app/ontrollers/Servicocontroller.php
    class ServicoController {
        private Servico $model;

        public function __construct() {
            $this->model = new Servico;
        }

        // lista todos os serviços 
        public function index(): void {
            $servicos = $this->model->listarTodos();

            require __DIR__ . "/../views/admin/servicos.php";
        }

        // Exibe formulário (novo ou edição) e processa o envio
        public function form(): void {
            if ($_SERVER['REQUEST_METHOD'] === "POST") {
                $dados = [
                    'nome'        => trim($_POST['nome']),
                    'descricao'   => trim($_POST['descricao']),
                    'preco'       => trim($_POST['preco']),
                    'duracao_min' => trim($_POST['duracao_min']),   
                ];

                $id = $_POST['id'] ?? null;

                if ($id) {
                    $this->model->atualizar((int) $id, $dados);
                } else {
                    $this->model->criar($dados);
                }

                header('Location: /barbearia/admin/servicos');
                exit;
            }

            // GET: se vier um id na URL, carrega pra edição; senão, formulário vazio
            $id = $_GET['id'] ?? null;
            $servico = $id ? $this->model->buscarPorID((int) $id) : null;

            require __DIR__ . "/../views/admin/servico-form.php";
        }

        public function deletar() {
            $id = (int) ($_POST['id'] ?? 0);

            if ($id) {
                $this->model->deletar($id);
            }

            header('Location: /barbearia/admin/servicos');
            exit;
        }
    }

?>
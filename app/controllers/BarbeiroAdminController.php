<?php 

    // app/controllers/BarbeiroAdminController.php
    class BarbeiroAdminController {
        private Barbeiro $model;

        public function __construct(){
            $this->model = new Barbeiro;
        }

        // Lista todos os perfis de barbeiros já criados 
        public function index(): void {
            $barbeiros = $this->model->listarTodos();

            require __DIR__ . "/../views/admin/barbeiros.php";
        }

        // Formuláriopara cirar o USUÁRIO com role barbeiro 
        public function novoUsuario(): void {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = trim($_POST['email']);
                $senha = $_POST['senha'];

                $db = Database::getInstance();

                $stmt = $db->prepare("SELECT id FROM usuarios WHERE email = :email");
                $stmt->execute([':email' => $email]);

                if ($stmt->fetch()) {
                    $_SESSION['erro'] = "Email já cadastrado";
                    header("Location: /barbearia/admin/barbeiros/novo-usuario");
                    exit;
                }

                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                $stmt = $db->prepare(
                    "INSERT INTO usuarios (email, senha, role) 
                    VALUES (:email, :senha, :role)"
                );

                $stmt->execute([
                    ':email' => $email,
                    ':senha' => $senhaHash,
                    ':role'   => 'barbeiro'
                ]);

                header('Location: /barbearia/admin/barbeiros/novo-perfil');
                exit;
            }

            require __DIR__ . "/../views/admin/barbeiro-novo-usuario.php";
        }

        // Formulário para vincular um usuário (role barbeiro) a um perfil de barbeiro
        public function novoPerfil(): void {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nomeArquivo = '';

                if (!empty($_FILES['foto']['name'])) {
                    $pastaDestino = __DIR__ . "/../../public/uploads/barbeiros";
                    $resultado = Upload::salvarImagem($_FILES['foto'], $pastaDestino);

                    if ($resultado === false) {
                        $_SESSION['erro'] = 'Erro ao enviar a foto. Verifique o formato (jpg, png, webp) e tamanho (máx 2MB).';
                        header("Location: /barbearia/admin/barbeiros/novo-perfil");
                        exit;
                    }

                    $nomeArquivo = $resultado;
                }

                $dados = [
                    'usuario_id'    => (int) $_POST['usuario_id'],
                    'nome'          => trim($_POST['nome']),
                    'especialidade' => trim($_POST['especialidade']),
                    'foto'          => $nomeArquivo,
                ];

                $this->model->criar($dados);

                header('Location: /barbearia/admin/barbeiros');
                exit;
            }

            $usuariosDisponiveis = $this->model->usuariosSemPerfil();
            require __DIR__ . "/../views/admin/barbeiro-novo-perfil.php";
        }

        public function editar(): void {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = (int) $_POST['id'];

                $barbeiroAtual = $this->model->buscarPorId($id);
                $nomeArquivo = $barbeiroAtual['foto'] ?? '';

                if (!empty($_FILES['foto']['name'])) {
                    $pastaDestino = __DIR__ . "/../../public/uploads/barbeiros";
                    $resultado = Upload::salvarImagem($_FILES['foto'], $pastaDestino);

                    if ($resultado !== false) {
                        $nomeArquivo = $resultado;
                    }
                }

                $dados = [
                    'nome'          => trim($_POST['nome']),
                    'especialidade' => trim($_POST['especialidade']),
                    'foto'          => $nomeArquivo,
                ];

                $this->model->atualizar($id ,$dados);

                header('Location: /barbearia/admin/barbeiros');
                exit;
            }

            $id = (int) ($GET['id'] ?? 0);
            $barbeiro = $this->model->buscarPorId($id);

            require __DIR__ . "/../views/admin/barbiero-editar.php";
        }

        public function deletar(): void {
            $id = (int) ($_POST['id'] ?? 0);

            if ($id) {
                $this->model->deletar($id);
            }

            header('Location: /barbearia/admin/barbeiros');
            exit;
        }
    }

?>
<?php 

    class AuthController {
        public function login(): void {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = trim($_POST['email']) ?? '';
                $password = $_POST['senha'] ?? '';

                $db = Database::getInstance();
                $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = :email");
                $stmt->execute(['email' => $email]);
                $usuario = $stmt->fetch();
                
                if ($usuario && password_verify($password, $usuario['senha'])) {
                    $_SESSION['user_id'] = $usuario['id'];
                    $_SESSION['user_role'] = $usuario['role'];

                    // Redireciona conforme o papel do usuário
                    if ($usuario && password_verify($password, $usuario['senha'])) {
                        $_SESSION['user_id']   = $usuario['id'];
                        $_SESSION['user_role'] = $usuario['role'];

                        if ($usuario['role'] === 'admin') {
                            header('Location: /barbearia/admin/dashboard');
                        } elseif ($usuario['role'] === 'barbeiro') {
                            header('Location: /barbearia/barbeiro/agenda');
                        } else {
                            header('Location: /barbearia/agendamento/novo');
                        }
                        exit;
                    }

                    exit;
                }

                $_SESSION['erro'] = "Email ou senha incorretos.";
                header('Location: /barbearia/login');
                exit;
            }

            require __DIR__ . '/../views/auth/login.php';
        }

        public function logout(): void {
            session_destroy();
            header('Location: /barbearia/login');
            exit;
        }

        public function register(): void {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nome = trim($_POST['nome']) ?? '';
                $email = trim($_POST['email']) ?? '';
                $senha = $_POST['senha'] ?? '';
                $confirmarSenha = $_POST['confirmar_senha'] ?? '';

                if ($senha !== $confirmarSenha) {
                    $_SESSION['erro'] = "As senhas não coincidem.";
                    header('Location: /barbearia/register');
                    exit;
                }

                $db = Database::getInstance();
                $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = :email");
                $stmt->execute(['email' => $email]);
                if ($stmt->fetch()) {
                    $_SESSION['erro'] = "Email já cadastrado.";
                    header('Location: /barbearia/register');
                    exit;
                }

                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                $stmt = $db->prepare("INSERT INTO usuarios (nome, email, senha, role) VALUES (:nome, :email, :senha, 'cliente')");
                $stmt->execute([
                    'nome' => $nome,
                    'email' => $email,
                    'senha' => $senhaHash
                ]);

                $_SESSION['sucesso'] = "Registro bem-sucedido! Faça login para continuar.";
                header('Location: /barbearia/login');
                exit;
            }

            require __DIR__ . '/../views/auth/register.php';
        }
    }

?>
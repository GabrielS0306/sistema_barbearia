<?php 

    class AuthController {
        public function login(): void {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = trim($_POST['email']) ?? '';
                $password = $_POST['senha'] ?? '';

                // Validação PHP
                if (empty($email) || empty($password)) {
                    $_SESSION['erro'] = "Preencha todos os campos.";
                    header("Location: /barbearia/login");
                    exit;
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $_SESSION['erro'] = "Email invalido.";
                    header("Location: /barbearia/login");
                    exit;
                }

                $db = Database::getInstance();
                $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = :email");
                $stmt->execute([':email' => $email]);
                $usuario = $stmt->fetch();
                
                if ($usuario && password_verify($password, $usuario['senha'])) {
                    $_SESSION['user_id']   = $usuario['id'];
                    $_SESSION['user_role'] = $usuario['role'];

                    if ($usuario['role'] === 'cliente') {
                        $stmt = $db->prepare("SELECT id FROM clientes WHERE usuario_id = :uid");
                        $stmt->execute([':uid' => $usuario['id']]);
                        $cliente = $stmt->fetch();
                        $_SESSION['user_cliente_id'] = $cliente['id'] ?? null;
                    }

                    if ($usuario['role'] === 'barbeiro') {
                        $stmt = $db->prepare('SELECT id FROM barbeiros WHERE usuario_id = :uid');
                        $stmt->execute([':uid' => $usuario['id']]);
                        $barbeiro = $stmt->fetch();
                        $_SESSION['user_barbeiro_id'] = $barbeiro['id'] ?? null;
                    }

                    if ($usuario['role'] === 'admin') {
                        header('Location: /barbearia/admin/dashboard');
                    } elseif ($usuario['role'] === 'barbeiro') {
                        header('Location: /barbearia/barbeiro/agenda');
                    } else {
                        header('Location: /barbearia/agendamento/novo');
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
                $nome           = trim($_POST['nome']) ?? '';
                $email          = trim($_POST['email']) ?? '';
                $senha          = $_POST['senha'] ?? '';
                $confirmarSenha = $_POST['confirmar_senha'] ?? '';

                if (empty($nome) || empty($email) || empty($senha) || empty($confirmarSenha)) {
                    $_SESSION['erro'] = 'Preencha todos os campos.';
                    header('Location: /barbearia/register');
                    exit;
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $_SESSION['erro'] = 'Email inválido.';
                    header('Location: /barbearia/register');
                    exit;
                }

                if (strlen($senha) < 6) {
                    $_SESSION['erro'] = 'A senha deve ter no mínimo 6 caracteres.';
                    header('Location: /barbearia/register');
                    exit;
                }

                if ($senha !== $confirmarSenha) {
                    $_SESSION['erro'] = "As senhas não coincidem.";
                    header('Location: /barbearia/register');
                    exit;
                }

                $db   = Database::getInstance();
                $stmt = $db->prepare("SELECT id FROM usuarios WHERE email = :email");
                $stmt->execute([':email' => $email]);
                
                if ($stmt->fetch()) {
                    $_SESSION['erro'] = "Email já cadastrado.";
                    header('Location: /barbearia/register');
                    exit;
                }

                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                $stmt = $db->prepare("INSERT INTO usuarios (email, senha, role) VALUES (:email, :senha, 'cliente')");
                $stmt->execute([
                    ':email' => $email,
                    ':senha' => $senhaHash,
                ]);

                $usuarioId = (int) $db->lastInsertId();

                if (!$usuarioId) {
                    $_SESSION['erro'] = 'Erro ao criar conta. Tente novamente.';
                    header('Location: /barbearia/register');
                    exit;
                }

                $stmt = $db->prepare("INSERT INTO clientes (usuario_id, nome, telefone) VALUES (:uid, :nome, '')");
                $stmt->execute([
                    ':uid'  => $usuarioId,
                    ':nome' => $nome,
                ]);

                $_SESSION['sucesso'] = "Registro bem-sucedido! Faça login para continuar.";
                header('Location: /barbearia/login');
                exit;
            }

            require __DIR__ . '/../views/auth/register.php';
        }
    }

?>
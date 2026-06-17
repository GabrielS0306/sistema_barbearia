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
                    match ($usuario['role']) {
                        'admin' => header('Location: barbearia/admin/dashboard'),
                        'barber' => header('Location: barbearia/barber/agenda'),
                        'cliente' => header('Location: barbearia/cliente/agendamento/novo'),
                        default => header('Location: barbearia/register') // Redireciona para o registro se o papel for desconhecido,
                    };

                    exit;
                }

                $_SESSION['erro'] = "Email ou senha incorretos.";
                header('Location: /barbearia/login');
                exit;
            }

            require __DIR__ . '/../views/auth/login.php';
        }
    }

?>
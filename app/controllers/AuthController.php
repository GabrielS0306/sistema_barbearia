<?php 

    class AuthController {
        public function login(): void {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $email = trim($_POST['email']) ?? '';
                $password = $_POST['password'] ?? '';

                $db = Database::getInstance();
                $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
                $stmt->execute(['email' => $email]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_role'] = $user['role'];

                    // Redireciona conforme o papel do usuário
                    match ($user['role']) {
                        'admin' => header('Location: barbearia/admin/dashboard'),
                        'barber' => header('Location: barbearia/barber/agenda'),
                        default => header('Location: barbearia/agendamento/novo'),
                    };

                    exit;
                }

                $_SESSION['error'] = "Email ou senha incorretos.";
                header('Location: /barbearia/login');
                exit;
            }

            require __DIR__ . '/../views/auth/login.php';
        }
    }

?>
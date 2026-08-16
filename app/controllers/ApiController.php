<?php 

    // app/controllers/ApiController.php
    class ApiController {
        private function autenticarJwt(): array|false {
            $token = Jwt::tokenDaRequisicao();

            if (!$token) {
                $this->json(['erro' => 'Token não fornecido'], 401);

                return false;
            }

            $payload = Jwt::validar($token);

            if (!$payload) {
                $this->json(['erro' => 'Token inválido ou expirado'], 401);

                return false;
            }

            return $payload;
        }

        public function login(): void {
            $body  = json_decode(file_get_contents('php://input'), true);
            $email = trim($body['email'] ?? '');
            $senha = $body['senha'] ?? '';

            if (empty($email) || empty($senha)) {
                $this->erro('Informe email e senha');

                return;
            }

            $db   = Database::getInstance();
            $stmt = $db->prepare('SELECT * FROM usuarios WHERE email = :email');
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch();

            if (!$usuario || !password_verify($senha, $usuario['senha'])) {
                $this->json(['erro' => 'Credenciais inválidas.'], 401);
                return;
            }

            $token = Jwt::gerar([
                'user_id'   => $usuario['id'],
                'user_role' => $usuario['role'],
                'email'     => $usuario['email'],
            ]);

            $this->json([
                'token'   => $token,
                'tipo'    => 'Bearer',
                'expira'  => 3600,
                'usuario' => [
                    'id'    => $usuario['id'],
                    'email' => $usuario['email'],
                    'role'  => $usuario['role'],
                ],
            ]);
        }

        private function json(mixed $dados, int $status = 200): void {
            http_response_code($status);
            
            header('Content-type: application/json; charset=utf-8');
            header('Acess-Control-Allow-Origin: *');
            
            echo json_encode($dados, JSON_UNESCAPED_UNICODE);

            exit;
        }

        private function erro(string $menssagem, int $status = 400): void {
            $this->json(['erro' => $menssagem], $status);
        }

        public function barbeiros(): void {
            $this->verificarRateLimit(30); // 30 requisições por minuto

            $model     = new Barbeiro();
            $barbeiros = $model->listarTodos();

            $dados = array_map(fn($b) => [
                'id'            => $b['id'],
                'nome'          => $b['nome'],
                'especialidade' => $b['especialidade'] ?? '',
                'foto'          => $b['foto'] ? '/barbearia/public/uploads/barbeiros/' . $b['foto'] : null,
            ], $barbeiros);

            $this->json($dados);
        }

        public function servicos(): void {
            $this->verificarRateLimit(30);

            $model     = new Servico();
            $servicos = $model->listarTodos();

            $dados = array_map(fn($s) => [
                'id'            => $s['id'],
                'nome'          => $s['nome'],
                'descricao'     => $s['descricao'] ?? '',
                'preco'         => (float) $s['preco'] ?? '',
                'duracao_min'   => (int) $s['duracao_min'],
            ], $servicos);

            $this->json($dados);
        }

        public function horarios():void {
            $this->verificarRateLimit(60); // mais permissivo pois é chamado pelo AJAX

            $barbeiroId = (int) ($_GET['barbeiro_id'] ?? 0);
            $data       = $_GET['data'] ?? '';

            if (!$barbeiroId || empty($data)) {
                $this->erro('Informe barbeiro_id e data');
                return;
            }

            if ($data < date('Y-m-d')) {
                $this->erro('Data não pode ser no passado');
                return;
            }

            $modelAgendamento   = new Agendamento();
            $modelFuncionamento = new HorarioFuncionamento();

            // busca horários dentro do funcionamento
            $horariosDisponiveis = $modelFuncionamento->horariosDisponiveis($data);

            // Se não tiver horários disponiveis nesse dia
            if (empty($horariosDisponiveis)) {
                $this->json([]);
                return;
            }

            $dados = array_map(fn($h) => [
                'hora'       => $h,
                'disponivel' => $modelAgendamento->horarioDisponivel($barbeiroId, $data, $h),
            ], $horariosDisponiveis);

            $this->json(array_values($dados));
        }

        public function agendamentos():void {
            $payload = $this->autenticarJwt();
            if (!$payload) return;

            if ($payload['user_role'] !== 'cliente') {
                $this->json(['erro' => 'Acesso negado.'], 403);

                return;
            }

            // Busca o cliente_id pelo usuario_id do token
            $db   = Database::getInstance();
            $stmt = $db->prepare('SELECT id FROM clientes WHERE usuario_id = :uid');
            $stmt->execute([':uid' => $payload['user_id']]);
            $cliente = $stmt->fetch();

            if (!$cliente) {
                $this->json(['erro' => 'Cliente não encontrado.'], 404);

                return;
            }

            $model        = new Agendamento();
            $agendamentos = $model->buscarPorCliente($cliente['id']);

            $dados = array_map(fn($a) => [
                'id'                 => $a['id'],
                'barbeiro'           => $a['barbeiro'],
                'servico'            => $a['servico'],
                'data'               => $a['data'],
                'hora'               => substr($a['hora'], 0, 5),
                'status'             => $a['status'],
                'forma_pagamento'    => $a['forma_pagamento'],
                'status_pagamento'   => $a['status_pagamento'],
                'preco'              => $a['preco'],
            ], $agendamentos);

            $this->json($dados);
        }

        public function criarAgendamentos():void {
            $payload = $this->autenticarJwt();
            if (!$payload) return;

            if ($payload['user_role'] !== 'cliente') {
                $this->json(['erro' => 'Acesso negado.'], 403);
                return;
            }

            $db   = Database::getInstance();
            $stmt = $db->prepare('SELECT id FROM clientes WHERE usuario_id = :uid');
            $stmt->execute([':uid' => $payload['user_id']]);
            $cliente = $stmt->fetch();

            if (!$cliente) {
                $this->json(['erro' => 'Cliente não encontrado.'], 404);
                return;
            }

            $body = json_decode(file_get_contents('php://input', true));

            $barbeiroId = (int) ($body['barbeiro_id'] ?? 0);
            $servicoId  = (int) ($body['servico_id'] ?? 0);
            $barbeiroId = $body['data'] ?? '';
            $barbeiroId = $body['hora'] ?? '';

            if (!$barbeiroId || !$servicoId || empty($data) || empty($hora)) {
                $this->erro('Preencha todos os campos');
                return;
            }

            if ($data < date('Y-m-d')) {
                $this->erro('Data não pode ser no passado.');
                return;
            }

            $model = new Agendamento();

            if (!$model->horarioDisponivel($barbeiroId, $data, $hora)) {
                $this->erro('Horário indisponível');
                return;
            }

            $model->criar([
                'cliente_id'       => $_SESSION['user_cliente_id'],
                'barbeiro_id'      => $barbeiroId,
                'servico_id'       => $servicoId,
                'data'             => $data,
                'hora'             => $hora,
                'forma_pagamento'  => 'dinheiro',
                'status_pagamento' => 'pendente',
            ]);

            $this->json(['mensagem' => 'Agendamento criado com sucesso'], 201);
        }

        public function enviarLembretes(): void {
            $tokenEsperado = 'barbearia2026_lembrete_xK9mP3qR7vN2wL5j'; // String aleatória pro endpoint
            $tokenRecebido = $_GET['token'] ?? '';

            if ($tokenRecebido !== $tokenEsperado) {
                $this->json(['erro' => 'Não autorizado.'], 401);
                return;
            }

            date_default_timezone_set('America/Sao_Paulo');

            $amanha = date('Y-m-d', strtotime('+1 day'));

            $db   = Database::getInstance();
            $stmt = $db->prepare("
                SELECT a.data, a.hora,
                    c.nome AS cliente,
                    b.nome AS barbeiro,
                    s.nome AS servico,
                    s.preco,
                    u.email
                FROM agendamentos a
                JOIN clientes c  ON a.cliente_id  = c.id
                JOIN barbeiros b ON a.barbeiro_id = b.id
                JOIN servicos s  ON a.servico_id  = s.id
                JOIN usuarios u  ON c.usuario_id  = u.id
                WHERE a.data = :amanha
                AND a.status NOT IN ('cancelado', 'concluido')
            ");
            $stmt->execute([':amanha' => $amanha]);
            $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $enviados = 0;
            $erros    = 0;

            foreach ($agendamentos as $ag) {
                if (Mailer::enviarLembrete($ag)) {
                    $enviados++;
                } else {
                    $erros++;
                }
            }

            $this->json([
                'mensagem' => 'Lembretes processados.',
                'enviados' => $enviados,
                'erros'    => $erros,
                'data'     => $amanha,
            ]);
        }

        public function servicosPorBarbeiro(): void {
            $barbeiroId = (int) ($_GET['barbeiro_id'] ?? 0);

            if (!$barbeiroId) {
                $this->erro('Informe barbeiro_id');
                return;
            }

            $model = new Barbeiro();
            $servicos = $model->buscarServicos($barbeiroId);

            $dados = array_map(fn($s) => [
                'id'            => $s['id'],
                'nome'          => $s['nome'],
                'preco'         => (float) $s['preco'],
                'duracao_min'   => (int) $s['duracao_min'],
            ], $servicos);

            $this->json($dados);
        }

        private function verificarRateLimit(int $limite = 60): void {
            $ip   = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $rota = $_SERVER['REQUEST_URI'] ?? '';
            $chave = "api:{$ip}:{$rota}";

            $cabecalhos = RateLimiter::cabecalhos($chave, $limite);
            foreach ($cabecalhos as $nome => $valor) {
                header("{$nome}: {$valor}");
            }

            if (!RateLimiter::verificar($chave, $limite)) {
                $this->json([
                    'erro'    => 'Muitas requisições. Tente novamente em alguns instantes.',
                    'codigo'  => 429,
                ], 429);
            }
        }
    }

?>
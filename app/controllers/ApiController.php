<?php 

    // app/controllers/ApiController.php
    class ApiController {
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

            $model = new Agendamento();
            $horarios = ['08:00', '08:30', '09:00', '09:30', '10:00', '10:30',
                        '11:00', '11:30', '13:00', '13:30', '14:00', '14:30',
                        '15:00', '15:30', '16:00', '16:30', '17:00', '17:30'];

            $dados = array_map(fn($h) => [
                'hora'       => $h,
                'disponivel' => $model->horarioDisponivel($barbeiroId, $data, $h),
            ], $horarios);

            $this->json($dados);
        }

        public function agendamentos():void {
            if (!isset($_SESSION['user_cliente_id'])) {
                $this->erro('Não autorizado.', 401);
                return;
            }

            $model = new Agendamento();
            $agendamentos = $model->buscarPorCliente($_SESSION['user_cliente_id']);

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
            if (!isset($_SESSION['user_cliente_id'])) {
                $this->erro('Não autorizado.', 401);
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
    }

?>
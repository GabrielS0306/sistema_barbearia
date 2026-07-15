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
                $servicoID  = (int) $_POST['servico_id'];
                $data       = $_POST['data'];
                $hora       = $_POST['hora'];

                // Validação  PHP
                if (!$barbeiroId || !$servicoID || empty($data) || empty($hora)) {
                    $_SESSION['erro'] = 'Preencha todos os campos do agendamento.';
                    header('Location: /barbearia/agendamento/novo');
                    exit;
                }

                if ($data < date('Y-m-d')) {
                    $_SESSION['erro'] = 'Não é possível agendar para uma data no passado.';
                    header('Location: /barbearia/agendamento/novo');
                    exit;
                }

                if (!$this->model->horarioDisponivel($barbeiroId, $data, $hora)) {
                    $_SESSION['erro'] = 'Horário indisponivel. Escolha outro, por favor!';
                    header("Location: /barbearia/agendamento/novo");
                    exit;
                }

                $_SESSION['agendamento_pendente'] = [
                    'cliente_id'  => $_SESSION['user_cliente_id'],
                    'barbeiro_id' => $barbeiroId,
                    'servico_id'  => $servicoID,
                    'data'        => $data,
                    'hora'        => $hora,
                ];

                header('Location: /barbearia/agendamento/pagamento');
            }

            // GET: Carrega barbeiros e serviços pra preencher o formulário
            $db        = Database::getInstance();
            $barbeiros = $db->query('SELECT id, nome FROM barbeiros ORDER BY nome')->fetchAll();
            $servicos  = $db->query('SELECT id, nome, preco, duracao_min FROM servicos ORDER BY nome')->fetchAll();

            require __DIR__ . "/../views/agendamento/novo.php";
        }

        public function meus(): void {
            $clienteId = $_SESSION['user_cliente_id'];
            $agendamentos = $this->model->buscarPorCliente($clienteId);

            require __DIR__ . "/../views/agendamento/lista.php";
        }

        public function comprovante(): void {
            $id = (int) ($_GET['id'] ?? 0);

            if (!$id) {
                header('Location: /barbearia/agendamento/meus');
                exit;
            }

            $db   = Database::getInstance();
            $stmt = $db->prepare('
                SELECT a.*, 
                    c.nome AS cliente, 
                    c.telefone,
                    b.nome AS barbeiro, 
                    s.nome AS servico, 
                    s.preco,
                    s.duracao_min
                FROM agendamentos a
                JOIN clientes c ON a.cliente_id = c.id
                JOIN barbeiros b ON a.barbeiro_id = b.id
                JOIN servicos s ON a.servico_id = s.id
                WHERE a.id = :id AND a.cliente_id = :cliente_id
            ');

            $stmt->execute([
                ':id'         => $id,
                ':cliente_id' => $_SESSION['user_cliente_id'],
            ]);

            $ag = $stmt->fetch();

            if (!$ag) {
                header('Location: /barbearia/agendamento/meus');
                exit;
            }

            // Gera o PDF com DOMPDF
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($this->gerarHtmlComprovante($ag));
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream('comprovante-agendamento.pdf', ['Attachment' => true]);
            exit;
        }

        private function gerarHtmlComprovante(array $ag): string {
            date_default_timezone_set('America/Sao_Paulo');

            $data   = date('d/m/Y', strtotime($ag['data']));
            $hora   = $ag['hora'];
            $preco  = 'R$ ' . number_format($ag['preco'], 2, ',', '.');
            $status = ucfirst($ag['status']);

            return "
                <!DOCTYPE html>
                <html lang='pt-br'>
                <head>
                    <meta charset='UTF-8'>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            color: #1a1a1a;
                            padding: 40px;
                        }
                        .header {
                            text-align: center;
                            border-bottom: 3px solid #f59e0b;
                            padding-bottom: 20px;
                            margin-bottom: 30px;
                        }
                        .header h1 {
                            color: #f59e0b;
                            font-size: 28px;
                            margin: 0;
                        }
                        .header p {
                            color: #666;
                            margin: 5px 0 0;
                        }
                        .titulo {
                            font-size: 18px;
                            font-weight: bold;
                            color: #333;
                            margin-bottom: 20px;
                        }
                        .tabela {
                            width: 100%;
                            border-collapse: collapse;
                        }
                        .tabela tr {
                            border-bottom: 1px solid #eee;
                        }
                        .tabela td {
                            padding: 12px 8px;
                            font-size: 14px;
                        }
                        .tabela td:first-child {
                            color: #666;
                            width: 40%;
                        }
                        .tabela td:last-child {
                            font-weight: bold;
                        }
                        .status {
                            display: inline-block;
                            padding: 4px 12px;
                            border-radius: 20px;
                            font-size: 12px;
                            background: #fef3c7;
                            color: #92400e;
                        }
                        .footer {
                            margin-top: 40px;
                            text-align: center;
                            color: #999;
                            font-size: 12px;
                            border-top: 1px solid #eee;
                            padding-top: 20px;
                        }
                    </style>
                </head>
                <body>
                    <div class='header'>
                        <h1>Barbearia</h1>
                        <p>Comprovante de Agendamento</p>
                    </div>

                    <p class='titulo'>Detalhes do Agendamento</p>

                    <table class='tabela'>
                        <tr>
                            <td>Cliente</td>
                            <td>{$ag['cliente']}</td>
                        </tr>
                        <tr>
                            <td>Barbeiro</td>
                            <td>{$ag['barbeiro']}</td>
                        </tr>
                        <tr>
                            <td>Serviço</td>
                            <td>{$ag['servico']}</td>
                        </tr>
                        <tr>
                            <td>Data</td>
                            <td>{$data}</td>
                        </tr>
                        <tr>
                            <td>Horário</td>
                            <td>{$hora}</td>
                        </tr>
                        <tr>
                            <td>Duração</td>
                            <td>{$ag['duracao_min']} minutos</td>
                        </tr>
                        <tr>
                            <td>Preço</td>
                            <td>{$preco}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td><span class='status'>{$status}</span></td>
                        </tr>
                    </table>

                    <div class='footer'>
                        <p>Documento gerado em " . date('d/m/Y H:i') . "</p>
                        <p>barb-system.rf.gd</p>
                    </div>
                </body>
                </html>
            ";
        }

        public function pagamento(): void {
            if (empty($_SESSION['agendamento_pendente'])) {
                header('Location: /barbearia/agendamento/novo');
                exit;
            }

            $dados = $_SESSION['agendamento_pendente'];

            // Buscar os detalhes do serviço e barbeiro e serviço para exibir na tela 
            $db = Database::getInstance();

            $stmtServico = $db->prepare('SELECT nome, preco FROM servicos WHERE id = :id');
            $stmtServico->execute([':id' => $dados['servico_id']]);
            $servico = $stmtServico->fetch();

            $stmtBarbeiro = $db->prepare('SELECT nome FROM barbeiros WHERE id = :id');
            $stmtBarbeiro->execute([':id' => $dados['barbeiro_id']]);
            $barbeiro = $stmtBarbeiro->fetch();

            require __DIR__ . "/../views/agendamento/pagamento.php";
        }

        public function confirmarPagamento(): void {
            if (empty($_SESSION['agendamento_pendente'])) {
                header('Location: /barbearia/agendamento/novo');
                exit;
            }

            $formaPagamento = $_POST['forma_pagamento'] ?? 'dinheiro';
            $formasValidas = ['dinheiro', 'pix', 'cartao'];

            if (!in_array($formaPagamento, $formasValidas)) {
                $formaPagamento = 'dinheiro';
            }

            $dados = $_SESSION['agendamento_pendente'];
            $dados['forma_pagamento'] = $formaPagamento;
            $dados['status_pagamento'] = 'pendente';

            $this->model->criar($dados);

            unset($_SESSION['agendamento_pendente']);

            $_SESSION['sucesso'] = "Agendamento realizado com sucesso!";
            header("Location: /barbearia/agendamento/meus");
            exit;
        }

        public function cancelar(): void {
            $id        = (int) ($_POST['id'] ?? 0);
            $clienteId = $_SESSION['user_cliente_id'];

            if (!$id) {
                header('Location: /barbearia/agendamento/meus');
                exit;
            }

            // Busca o agendamento pra validar que pertence ao cliente e pode ser cancelado 
            $db = Database::getInstance();
            $stmt = $db->prepare('SELECT * FROM agendamentos WHERE id = :id  AND cliente_id = :cid');
            $stmt->execute([':id' => $id, ':cid' => $clienteId]);
            $ag = $stmt->fetch();

            if (!$ag) {
                $_SESSION['erro'] = 'Agendamento não encontrado.';
                header('Location: /barbearia/agendamento/meus');
                exit;
            }

            // Só pode cancelar se não tiver concluído ou cancelado 
            if (in_array($ag['status'], ['concluido', 'cancelado'])) {
                $_SESSION['erro'] = 'Este agendamento não pode ser cancelado.';
                header('Location: /barbearia/agendamento/meus');
                exit;
            }

            // Só pode cancelar se a data não tiver passsado 
            if ($ag['data'] < date('Y-m-d')) {
                $_SESSION['erro'] = 'Não é possivel cancelar um agendamento passado.';
                header('Location: /barbearia/agendamento/meus');
                exit;
            }

            // verifica se precisa de reembolso 
            $reembolsoSolicitado = $ag['status_pagamento'] === 'pago' ? 1 : 0;

            $stmt = $db->prepare('UPDATE agendamentos
                        SET status = :status,
                            status_pagamento = :sp,
                            reembolso_solicitado = :reembolso
                        WHERE id = :id
            ');

            $stmt->execute([
                ':status' => 'cancelado',
                ':sp'     => 'cancelado',
                ':reembolso' => $reembolsoSolicitado,
                ':id'     => $id,
            ]);

            if ($reembolsoSolicitado) {
                $_SESSION['sucesso'] = 'Agendamento cancelado. Reembolso solicitado — entraremos em contato em breve.';
            } else {
                $_SESSION['sucesso'] = 'Agendamento cancelado com sucesso.';
            }

            header('Location: /barbearia/agendamento/meus');
            exit;
        }
    }

?>
<?php 

    // app/controllers/AdminController.php
    class AdminController {
        public function dashboard(): void {
            $model = new Dashboard();

            $totalClientes     = $model->totalClientes();
            $totalBarbeiros    = $model->totalBarbeiros();
            $agendamentosHoje  = $model->agendamentosHoje();
            $receiteMes        = $model->receitaMes();
            $porStatus         = $model->agendamentoPorStatus();
            $proximos          = $model->proximosAgendamentos();
            $metricasBarbeiros = $model->metricasPorBarbeiro();

            require __DIR__ . "/../views/admin/dashboard.php";
        }

        public function barbeiros(): void {
            require __DIR__ . "/../views/admin/barbeiros.php";
        }

        public function servicos(): void {
            require __DIR__ . "/../views/admin/servicos.php";
        }

        public function agendamentos(): void {
            $modelAgendamento = new Agendamento();
            $modelBarbeiro = new Barbeiro();

            $filtros = [
                'data'        => $_GET['data'] ?? '',
                'barbeiro_id' => $_GET['barbeiro_id'] ?? '',
                'status'      => $_GET['status'] ?? '',
            ];

            $paginaAtual = (int) ($_GET['pagina'] ?? '');
            $porPagina    = 10;

            $total     = $modelAgendamento->contarTodos($filtros);
            $paginacao = new Paginacao($total, $porPagina, $paginaAtual);

            $agendamentos = $modelAgendamento->buscarTodos(
                $filtros,
                $paginacao->limite(),
                $paginacao->offSet(),
            );

            $contagem = $modelAgendamento->contarPorStatus();
            $barbeiros = $modelBarbeiro->listarTodos();

            $_SESSION['sucesso'] = 'Tela de agendamentos carregada com sucesso.';

            require __DIR__ . "/../views/admin/agendamentos.php";
        }

        public function relatorio(): void {
            date_default_timezone_set('America/Sao_Paulo');

            $dataInicio = $_GET['data_inicio'] ?? date('Y-m-d', strtotime('first day of this month'));
            $dataFim = $_GET['data_fim'] ?? date('Y-m-d');

            $model = new Agendamento();
            $agendamentos = $model->buscarPorPeriodo($dataInicio, $dataFim);
            $totalReceita = array_sum(array_column($agendamentos, 'preco'));
            $totalCount = count($agendamentos);

            $dompdf = new Dompdf\Dompdf();
            $dompdf->loadHtml($this->gerarHtmlRelatorio($agendamentos, $dataInicio, $dataFim, $totalReceita, $totalCount));
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream("relatorio_agendamentos.pdf", ["Attachment" => false]);
            
            exit;
        }

        private function gerarHtmlRelatorio(array $agendamentos, string $dataInicio, string $dataFim, float $totalReceita, int $totalCount): string {
            $dataInicioFormatada = date('d/m/Y', strtotime($dataInicio));
            $dataFimFormatada    = date('d/m/Y', strtotime($dataFim));
            $totalReceitaFormatado = 'R$ ' . number_format($totalReceita, 2, ',', '.');

            $linhas = '';
            foreach ($agendamentos as $ag) {
                $data  = date('d/m/Y', strtotime($ag['data']));
                $hora  = substr($ag['hora'], 0, 5);
                $preco = 'R$ ' . number_format($ag['preco'], 2, ',', '.');
                $linhas .= "
                    <tr>
                        <td>{$data}</td>
                        <td>{$hora}</td>
                        <td>{$ag['cliente']}</td>
                        <td>{$ag['barbeiro']}</td>
                        <td>{$ag['servico']}</td>
                        <td>{$preco}</td>
                        <td>{$ag['status']}</td>
                    </tr>";
            }

            return "
                <!DOCTYPE html>
                <html lang='pt-br'>
                <head>
                    <meta charset='UTF-8'>
                    <style>
                        body { font-family: Arial, sans-serif; color: #1a1a1a; padding: 40px; font-size: 13px; }
                        .header { text-align: center; border-bottom: 3px solid #f59e0b; padding-bottom: 20px; margin-bottom: 30px; }
                        .header h1 { color: #f59e0b; font-size: 26px; margin: 0; }
                        .header p { color: #666; margin: 5px 0 0; }
                        .periodo { background: #fef3c7; border: 1px solid #f59e0b; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; }
                        .periodo p { margin: 0; font-size: 13px; color: #92400e; }
                        .resumo { display: flex; gap: 20px; margin-bottom: 25px; }
                        .card { flex: 1; border: 1px solid #eee; border-radius: 8px; padding: 12px; text-align: center; }
                        .card .valor { font-size: 22px; font-weight: bold; color: #f59e0b; }
                        .card .label { font-size: 11px; color: #666; margin-top: 4px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                        thead tr { background: #f59e0b; color: #1a1a1a; }
                        thead td { padding: 8px 6px; font-weight: bold; font-size: 11px; }
                        tbody tr:nth-child(even) { background: #f9f9f9; }
                        tbody td { padding: 7px 6px; border-bottom: 1px solid #eee; font-size: 12px; }
                        .footer { margin-top: 30px; text-align: center; color: #999; font-size: 11px; border-top: 1px solid #eee; padding-top: 15px; }
                    </style>
                </head>
                <body>
                    <div class='header'>
                        <h1>Barbearia</h1>
                        <p>Relatório de Agendamentos</p>
                    </div>

                    <div class='periodo'>
                        <p>
                            Período: <strong>{$dataInicioFormatada}</strong> até <strong>{$dataFimFormatada}</strong></p>
                    </div>

                    <div class='resumo'>
                        <div class='card'>
                            <div class='valor'>{$totalCount}</div>
                            <div class='label'>Total de Agendamentos</div>
                        </div>
                        <div class='card'>
                            <div class='valor'>{$totalReceitaFormatado}</div>
                            <div class='label'>Receita Total</div>
                        </div>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <td>Data</td>
                                <td>Hora</td>
                                <td>Cliente</td>
                                <td>Barbeiro</td>
                                <td>Serviço</td>
                                <td>Preço</td>
                                <td>Status</td>
                            </tr>
                        </thead>
                        <tbody>
                            {$linhas}
                        </tbody>
                    </table>

                    <div class='footer'>
                        <p>Relatório gerado em " . date('d/m/Y H:i') . "</p>
                        <p>barb-system.rf.gd</p>
                    </div>
                </body>
                </html>
            ";
        }

        public function confirmarReembolso(): void {
            Csrf::verificar();
            
            $id = (int) ($_POST['id'] ?? 0);
            if ($id) {
                $db   = Database::getInstance();
                $stmt = $db->prepare('
                    UPDATE agendamentos 
                    SET reembolso_solicitado = 0, 
                        status_pagamento = :sp 
                    WHERE id = :id
                ');

                $historico = new AgendamentoHistorico();
                $historico->registrar(
                    $id,
                    $_SESSION['user_id'],
                    'reembolso_confirmado',
                    'Reembolso confirmado pelo admin.'
                );

                $stmt->execute([
                    ':sp' => 'cancelado',
                    ':id' => $id,
                ]);
                $_SESSION['sucesso'] = 'Reembolso confirmado com sucesso.';
            }
            header('Location: /barbearia/admin/agendamentos');
            exit;
        }

        public function historico(): void {
            $id = (int) ($_GET['id'] ?? 0);

            if (!$id) {
                header('Location: /barbearia/admin/agendamentos');
                exit;
            }

            $db = Database::getInstance();

            $stmt = $db->prepare('
                SELECT a.*, c.nome AS cliente, b.nome AS barbeiro, s.nome AS servico
                FROM agendamentos a
                JOIN clientes c  ON a.cliente_id  = c.id
                JOIN barbeiros b ON a.barbeiro_id = b.id  
                JOIN servicos s  ON a.servico_id  = s.id  
                WHERE a.id = :id
            ');

            $stmt->execute([':id' => $id]);
            $agendamento = $stmt->fetch();

            if (!$agendamento) {
                header('Location: /barbearia/admin/agendamentos');
                exit;
            }

            $modelHistorico = new AgendamentoHistorico();
            $historico      = $modelHistorico->buscarPorAgendamento($id);

            require __DIR__ . '/../views/admin/agendamento-historico.php';
        }

        public function confirmarPix(): void {
            $id = (int) ($_POST['id'] ?? 0);

            if ($id) {
                $db   = Database::getInstance();

                $stmt = $db->prepare('
                    UPDATE agendamentos 
                    SET status_pagamento = :sp,
                        status = :status
                    WHERE id = :id
                    AND forma_pagamento = :fp
                    AND status_pagamento = :sp_atual
                ');

                $stmt->execute([
                    ':sp'      => 'pago',
                    ':status'  => 'confirmado',
                    ':id'      => $id,
                    ':fp'      => 'pix',
                    ':sp_atual' => 'pendente',
                ]);

                $historico = new AgendamentoHistorico();
                $historico->registrar(
                    $id,
                    $_SESSION['user_id'],
                    'confirmado',
                    'Pagamento via PIX confirmado pelo admin.'
                );

                $_SESSION['sucesso'] = 'Pagamento PIX confirmado com sucesso!';
            }

            header('Location: /barbearia/admin/agendamentos');
            exit;
        }

        public function relatorioBarbeiro(): void {
            date_default_timezone_set('America/Sao_Paulo');

            $barbeiroId = (int) ($_GET['id'] ?? 0);

            if (!$barbeiroId) {
                header('Location: /barbearia/admin/dashboard');
                exit;
            }

            $db   = Database::getInstance();
            $stmt = $db->prepare('SELECT nome FROM barbeiros WHERE id = :id');
            $stmt->execute([':id' => $barbeiroId]);
            $barbeiro = $stmt->fetch();

            if (!$barbeiro) {
                header('Location: /barbearia/admin/dashboard');
                exit;
            }

            $dataInicio = $_GET['data_inicio'] ?? date('Y-m-01');
            $dataFim    = $_GET['data_fim'] ?? date('Y-m-d');

            $model = new Agendamento();
            $agendamentos = $model->buscarPorPeriodo($dataInicio, $dataFim, $barbeiroId);
            $totalReceita = array_sum(array_column($agendamentos, 'preco'));
            $totalCount = count($agendamentos);

            $dompdf = new Dompdf\Dompdf();
            $dompdf->loadHtml($this->gerarHtmlRelatorioBarbeiro($agendamentos, $barbeiro['nome'], $dataInicio, $dataFim, $totalReceita, $totalCount));
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream('relatorio-' . strtolower(str_replace(' ', '-', $barbeiro['nome'])) . '.pdf', ['Attachment' => false]);
            exit;
        }

        private function gerarHtmlRelatorioBarbeiro(array $agendamentos, string $nomeBarbeiro, string $dataInicio, string $dataFim, float $totalReceita, int $totalCount): string {
            $dataInicioFormatada   = date('d/m/Y', strtotime($dataInicio));
            $dataFimFormatada      = date('d/m/Y', strtotime($dataFim));
            $totalReceitaFormatado = 'R$ ' . number_format($totalReceita, 2, ',', '.');

            $linhas = '';
            foreach ($agendamentos as $ag) {
                $data  = date('d/m/Y', strtotime($ag['data']));
                $hora  = substr($ag['hora'], 0, 5);
                $preco = 'R$ ' . number_format($ag['preco'], 2, ',', '.');
                $linhas .= "
                <tr>
                    <td>{$data}</td>
                    <td>{$hora}</td>
                    <td>{$ag['cliente']}</td>
                    <td>{$ag['servico']}</td>
                    <td>{$preco}</td>
                    <td>{$ag['status']}</td>
                </tr>";
            }

            return "
                <!DOCTYPE html>
                <html lang='pt-br'>
                <head>
                    <meta charset='UTF-8'>
                    <style>
                        body { font-family: Arial, sans-serif; color: #1a1a1a; padding: 40px; font-size: 13px; }
                        .header { text-align: center; border-bottom: 3px solid #f59e0b; padding-bottom: 20px; margin-bottom: 30px; }
                        .header h1 { color: #f59e0b; font-size: 26px; margin: 0; }
                        .header p { color: #666; margin: 5px 0 0; }
                        .barbeiro { background: #fef3c7; border: 1px solid #f59e0b; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; }
                        .barbeiro p { margin: 0; font-size: 14px; color: #92400e; }
                        .resumo { display: flex; gap: 20px; margin-bottom: 25px; }
                        .card { flex: 1; border: 1px solid #eee; border-radius: 8px; padding: 12px; text-align: center; }
                        .card .valor { font-size: 22px; font-weight: bold; color: #f59e0b; }
                        .card .label { font-size: 11px; color: #666; margin-top: 4px; }
                        table { width: 100%; border-collapse: collapse; }
                        thead tr { background: #f59e0b; color: #1a1a1a; }
                        thead td { padding: 8px 6px; font-weight: bold; font-size: 11px; }
                        tbody tr:nth-child(even) { background: #f9f9f9; }
                        tbody td { padding: 7px 6px; border-bottom: 1px solid #eee; font-size: 12px; }
                        .footer { margin-top: 30px; text-align: center; color: #999; font-size: 11px; border-top: 1px solid #eee; padding-top: 15px; }
                    </style>
                </head>
                <body>
                    <div class='header'>
                        <h1>Barbearia</h1>
                        <p>Relatório por Barbeiro</p>
                    </div>

                    <div class='barbeiro'>
                        <p>Barbeiro: <strong>{$nomeBarbeiro}</strong></p>
                        <p>Período: <strong>{$dataInicioFormatada}</strong> até <strong>{$dataFimFormatada}</strong></p>
                    </div>

                    <div class='resumo'>
                        <div class='card'>
                            <div class='valor'>{$totalCount}</div>
                            <div class='label'>Total de Agendamentos</div>
                        </div>
                        <div class='card'>
                            <div class='valor'>{$totalReceitaFormatado}</div>
                            <div class='label'>Receita Total</div>
                        </div>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <td>Data</td>
                                <td>Hora</td>
                                <td>Cliente</td>
                                <td>Serviço</td>
                                <td>Preço</td>
                                <td>Status</td>
                            </tr>
                        </thead>
                        <tbody>
                            {$linhas}
                        </tbody>
                    </table>

                    <div class='footer'>
                        <p>Relatório gerado em " . date('d/m/Y H:i') . "</p>
                        <p>barb-system.rf.gd</p>
                    </div>
                </body>
                </html>
            ";
        }

        public function horarios(): void {
            $model = new HorarioFuncionamento();

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                Csrf::verificar();

                $ids     = $_POST['id'] ?? [];
                $inicios = $_POST['hora_inicio'] ?? [];
                $fins    = $_POST['hora_fim'] ?? [];
                $ativos  = $_POST['ativo'] ?? [];

                foreach ($ids as $i => $id) {
                    $model->atualizar((int) $id, [
                        'hora_inicio' => $inicios[$i],
                        'hora_fim'    => $fins[$i],
                        'ativo'       => isset($ativos[$i]) ? 1 : 0,
                    ]);
                }

                $_SESSION['sucesso'] = 'Horários de funcionamento atualizados!';
                header('Location: /barbearia/admin/horarios');
                exit;
            }

            $horarios = $model->listarTodos();
            require __DIR__ . '/../views/admin/horarios.php';
        }

        public function logs(): void {
            $arquivo = __DIR__ . '/../../logs/app-' . date('Y-m-d') . '.log';
            $linhas  = [];

            if (file_exists($arquivo)) {
                $conteudo = file_get_contents($arquivo);
                $linhas = array_filter(explode(PHP_EOL, $conteudo));
                $linhas = array_reverse(array_values($linhas)); // Mais recentes primeiro
            }

            require __DIR__ . '/../views/admin/logs.php';
        }
    }

?>
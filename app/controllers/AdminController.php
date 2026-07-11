<?php 

    // app/controllers/AdminController.php
    class AdminController {
        public function dashboard(): void {
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


            $agendamentos = $modelAgendamento->buscarTodos($filtros);
            $contagem = $modelAgendamento->contarPorStatus();
            $barbeiros = $modelBarbeiro->listarTodos();

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
    }

?>
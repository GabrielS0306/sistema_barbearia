<?php 

    class BarbeiroController {
        private Agendamento $model;

        public function __construct() {
            $this->model = new Agendamento();
        }

        public function agenda(): void {
            $barbeiroId = $_SESSION['user_barbeiro_id'];
            $data = $_GET['data'] ?? date('Y-m-d');

            $agendamentos = $this->model->buscarPorBarbeiro($barbeiroId, $data);

            require __DIR__ . "/../views/barbeiro/agenda.php";
        }

        public function atualizarStatus(): void {
            $id = (int) ($_POST['id'] ?? 0);
            $status = $_POST['status'] ?? '';

            $statusPermitidos = ['onfirmado', 'concluido', 'cancelado'];

            if ($id && in_array($status, $statusPermitidos)) {
                $db = Database::getInstance();
                $stmt = $db->prepare("UPDATE agendamentos SET status = :status WHERE id = :id");
                $stmt->execute([':status' => $status, ':id' => $id]);
            }

            header("Location: /barbearia/barbeiro/agenda");
            exit;
        }

        public function agendaPdf(): void {
            date_default_timezone_set('America/Sao_Paulo');

            $barbeiroId = $_SESSION['user_barbeiro_id'];
            $data       = $_GET['data'] ?? date('Y-m-d');

            $agendamento = $this->model->buscarPorBarbeiro($barbeiroId, $data);

            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($this->gerarHtmlAgenda($agendamento, $data));
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream('agenda-' . $data . '.pdf', ['Attachment' => false]);

            exit;
        }

        private function gerarHtmlAgenda(array $agendamentos, string $data): string {
            date_default_timezone_set('America/Sao_Paulo');
            $dataFormatada = date('d/m/Y', strtotime($data));
            $diaSemana = strftime('%A', strtotime($data));

            $linhas = '';

            if (empty($agendamentos)) {
                $linhas = "<tr><td colspan='5' style='text-align:center; color:#666; padding:20px;'>Nenhum agendamento para este dia.</td></tr>";
            } else {
                foreach ($agendamentos as $ag) {
                    $hora = substr($ag['hora'], 0, 5);
                    $preco = '$RS ' . number_format($ag['preco'], 2, ',', '.');

                    $linhas .= "
                        <tr>
                            <td>{$hora}</td>
                            <td>{$ag['cliente']}</td>
                            <td>{$ag['servico']}</td>
                            <td>{$ag['duracao_min']}min</td>
                            <td>{$ag['status']}</td>
                        </tr>
                    ";
                }
            }

            $total = count($agendamentos);

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
                        .data { background: #fef3c7; border: 1px solid #f59e0b; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; }
                        .data p { margin: 0; font-size: 14px; color: #92400e; font-weight: bold; }
                        .resumo { margin-bottom: 20px; font-size: 13px; color: #666; }
                        table { width: 100%; border-collapse: collapse; }
                        thead tr { background: #f59e0b; color: #1a1a1a; }
                        thead td { padding: 8px 6px; font-weight: bold; font-size: 11px; }
                        tbody tr:nth-child(even) { background: #f9f9f9; }
                        tbody td { padding: 8px 6px; border-bottom: 1px solid #eee; font-size: 12px; }
                        .footer { margin-top: 30px; text-align: center; color: #999; font-size: 11px; border-top: 1px solid #eee; padding-top: 15px; }
                    </style>
                </head>
                <body>
                    <div class='header'>
                        <h1>Barbearia</h1>
                        <p>Agenda do Dia</p>
                    </div>

                    <div class='data'>
                        <p>{$dataFormatada}</p>
                    </div>

                    <div class='resumo'>
                        <p>Total de agendamentos: <strong>{$total}</strong></p>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <td>Horário</td>
                                <td>Cliente</td>
                                <td>Serviço</td>
                                <td>Duração</td>
                                <td>Status</td>
                            </tr>
                        </thead>
                        <tbody>
                            {$linhas}
                        </tbody>
                    </table>

                    <div class='footer'>
                        <p>Gerado em " . date('d/m/Y H:i') . "</p>
                        <p>barb-system.rf.gd</p>
                    </div>
                </body>
                </html>
            ";
        }
    }

?>
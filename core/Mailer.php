<?php

    // core/Mailer.php
    require_once __DIR__ . '/../vendor/autoload.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    class Mailer {
        private static function criar(): PHPMailer {
            $cfg = require __DIR__ . '/../config/mail.php';

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = $cfg['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $cfg['username'];
            $mail->Password   = $cfg['password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $cfg['port'];
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom($cfg['from'], $cfg['from_name']);

            return $mail;
        }

        public static function enviarConfirmacaoAgendamento(array $dados): bool {
            try {
                $mail = self::criar();
                $mail->addAddress($dados['email'], $dados['cliente']);
                $mail->isHTML(true);
                $mail->Subject = 'Agendamento confirmado - Barbearia';
                $mail->Body    = self::templateConfirmacao($dados);
                $mail->send();

                return true;
            } catch (Exception $erro) {
                error_log('Erro ao enviar e-mail' .  $erro->getMessage());
                return false;
            }
        }

        public static function enviarCancelamentoAgendamento(array $dados): bool {
                try {
                $mail = self::criar();
                $mail->addAddress($dados['email'], $dados['cliente']);
                $mail->isHTML(true);
                $mail->Subject = 'Agendamento cancelado - Barbearia';
                $mail->Body    = self::templateCancelamento($dados);
                $mail->send();

                return true;
            } catch (Exception $erro) {
                error_log('Erro ao enviar e-mail' .  $erro->getMessage());

                return false;
            }
        }

        private static function templateConfirmacao(array $dados): string {
            $data = date('d/m/Y', strtotime($dados['data']));
            $hora = substr($dados['hora'], 0, 5);
            $preco = 'R$' . number_format($dados['preco'], 2, ',', '.');

            return "
                <!DOCTYPE html>
                <html lang='pt-br'>
                    <head>
                        <meta charset='UTF-8'>
                    </head>
                    <body style='font-family: Arial, sans-serif; color: #1a1a1a; padding: 40px; max-width: 600px; margin: 0 auto;'>
                        <div style='text-align: center; border-bottom: 3px solid #f59e0b; padding-bottom: 20px; margin-bottom: 30px;'>
                            <h1 style='color: #f59e0b; margin: 0;'>Barbearia</h1>

                            <p style='color: #666; margin: 5px 0 0;'>
                                Confirmação de Agendamento
                            </p>
                        </div>

                        <p>Olá, <strong>{$dados['cliente']}</strong>!</p>

                        <p>Seu agendamento foi confirmado com sucesso. Veja os detalhes abaixo:</p>

                        <table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>
                            <tr style='border-bottom: 1px solid #eee;'>
                                <td style='padding: 10px; color: #666;'>Barbeiro</td>

                                <td style='padding: 10px; font-weight: bold;'>{$dados['barbeiro']}</td>
                            </tr>
                            <tr style='border-bottom: 1px solid #eee;'>
                                <td style='padding: 10px; color: #666;'>Serviço</td>

                                <td style='padding: 10px; font-weight: bold;'>{$dados['servico']}</td>
                            </tr>
                            <tr style='border-bottom: 1px solid #eee;'>
                                <td style='padding: 10px; color: #666;'>Data</td>

                                <td style='padding: 10px; font-weight: bold;'>{$data}</td>
                            </tr>
                            <tr style='border-bottom: 1px solid #eee;'>
                                <td style='padding: 10px; color: #666;'>Horário</td>

                                <td style='padding: 10px; font-weight: bold;'>{$hora}</td>
                            </tr>
                            <tr>
                                <td style='padding: 10px; color: #666;'>Preço</td>

                                <td style='padding: 10px; font-weight: bold; color: #f59e0b;'>{$preco}</td>
                            </tr>
                        </table>

                        <p style='color: #666; font-size: 13px; margin-top: 30px;'>
                            Caso precise cancelar ou adiar, acesse o sistema com antecedência.
                        </p>

                        <div style='text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; color: #999; font-size: 12px;'>
                            <p>barb-system.rf.gd</p>
                        </div>
                    </body>
                </html>
            ";
        }

        private static function templateCancelamento(array $dados): string {
            $data = date('d/m/Y', strtotime($dados['data']));
            $hora = substr($dados['hora'], 0, 5);

            return "
                <!DOCTYPE html>
                <html lang='pt-br'>
                    <head>
                        <meta charset='UTF-8'>
                    </head>
                    <body style='font-family: Arial, sans-serif; color: #1a1a1a; padding: 40px; max-width: 600px; margin: 0 auto;'>
                        <div style='text-align: center; border-bottom: 3px solid #f59e0b; padding-bottom: 20px; margin-bottom: 30px;'>
                            <h1 style='color: #f59e0b; margin: 0;'>Barbearia</h1>

                            <p style='color: #666; margin: 5px 0 0;'>Cancelamento de Agendamento</p>
                        </div>

                        <p>Olá, <strong>{$dados['cliente']}</strong>!</p>

                        <p>Seu agendamento foi cancelado. Veja os detalhes abaixo:</p>

                        <table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>
                            <tr style='border-bottom: 1px solid #eee;'>
                                <td style='padding: 10px; color: #666;'>Barbeiro</td>

                                <td style='padding: 10px; font-weight: bold;'>{$dados['barbeiro']}</td>
                            </tr>
                            <tr style='border-bottom: 1px solid #eee;'>
                                <td style='padding: 10px; color: #666;'>Serviço</td>

                                <td style='padding: 10px; font-weight: bold;'>{$dados['servico']}</td>
                            </tr>
                            <tr style='border-bottom: 1px solid #eee;'>
                                <td style='padding: 10px; color: #666;'>Data</td>

                                <td style='padding: 10px; font-weight: bold;'>{$data}</td>
                            </tr>
                            <tr>
                                <td style='padding: 10px; color: #666;'>Horário</td>
                                
                                <td style='padding: 10px; font-weight: bold;'>{$hora}</td>
                            </tr>
                        </table>

                        <p style='color: #666; font-size: 13px; margin-top: 30px;'>
                            Para fazer um novo agendamento, acesse o sistema.
                        </p>

                        <div style='text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; color: #999; font-size: 12px;'>
                            <p>barb-system.rf.gd</p>
                        </div>
                    </body>
                </html>
            ";
        }
    }

?>
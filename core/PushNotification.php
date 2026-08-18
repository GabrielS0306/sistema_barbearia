<?php

    // core/PushNotification.php
    use Minishlink\WebPush\WebPush;
    use Minishlink\WebPush\Subscription;

    class PushNotification {
        private static string $vapidPublic  = 'BOkBjVFiNwBBnPDmXeEFvGQzf7kQn_5VKcNmxSBjKrMlZuA8YdP2tC3xW6R1mH9nEoQ4sL7vIjXyZbUwTpDc';
        private static string $vapidPrivate = 'SUA_CHAVE_PRIVADA_VAPID_AQUI';
        private static string $vapidEmail   = 'mailto:seu@email.com';

        public static function enviar(array $inscricao, string $titulo, string $corpo, string $url = '/'): bool {
            try {
                $auth = [
                    'VAPID' => [
                        'subject'    => self::$vapidEmail,
                        'publicKey'  => self::$vapidPublic,
                        'privateKey' => self::$vapidPrivate,
                    ],
                ];

                $webPush = new WebPush($auth);

                $subscription = Subscription::create([
                    'endpoint' => $inscricao['endpoint'],
                    'keys'     => [
                        'p256dh' => $inscricao['p256dh'],
                        'auth'   => $inscricao['auth'],
                    ],
                ]);

                $payload = json_encode([
                    'titulo' => $titulo,
                    'corpo'  => $corpo,
                    'url'    => $url,
                ]);

                $webPush->queueNotification($subscription, $payload);

                foreach ($webPush->flush() as $report) {
                    if (!$report->isSuccess()) {
                        Logger::aviso('Push falhou: ' . $report->getReason());
                        return false;
                    }
                }

                return true;
            } catch (\Exception $e) {
                Logger::erro('Erro ao enviar push: ' . $e->getMessage());
                return false;
            }
        }

        public static function enviarParaUsuario(int $usuarioId, string $titulo, string $corpo, string $url = '/'): void {
            $db   = Database::getInstance();
            $stmt = $db->prepare('SELECT * FROM push_inscricoes WHERE usuario_id = :uid');
            $stmt->execute([':uid' => $usuarioId]);
            $inscricoes = $stmt->fetchAll();

            foreach ($inscricoes as $inscricao) {
                self::enviar($inscricao, $titulo, $corpo, $url);
            }
        }
    }

?>
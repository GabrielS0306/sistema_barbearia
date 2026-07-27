<?php

    // scripts/lembrete.php
    // Executado diariamente via cron job — envia lembretes de agendamentos do dia seguinte
    // Bloqueia acesso via navegador — só permite execução via CLI
    if (php_sapi_name() !== 'cli') {
        http_response_code(403);
        die('Acesso negado.');
    }

    define('ROOT', dirname(__DIR__));

    require_once ROOT . '/vendor/autoload.php';
    require_once ROOT . '/core/Database.php';
    require_once ROOT . '/core/Mailer.php';

    date_default_timezone_set('America/Sao_Paulo');

    $amanha = date('Y-m-d', strtotime('+1 day'));

    try {
        $db   = Database::getInstance();
        $stmt = $db->prepare("
            SELECT a.data, a.hora, 
                c.nome AS cliente,
                b.nome AS barbeiro,
                s.nome AS servico,
                s.preco,
                u.email
            FROM agendamentos a
            JOIN clientes c   ON a.cliente_id  = c.id
            JOIN barbeiros b  ON a.barbeiro_id = b.id
            JOIN servicos s   ON a.servico_id  = s.id
            JOIN usuarios u   ON c.usuario_id  = u.id
            WHERE a.data = :amanha
            AND a.status NOT IN ('cancelado', 'concluido')
        ");
        $stmt->execute([':amanha' => $amanha]);
        $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $enviados = 0;
        $erros    = 0;

        foreach ($agendamentos as $ag) {
            $resultado = Mailer::enviarLembrete($ag);
            if ($resultado) {
                $enviados++;
                echo "[OK] Lembrete enviado para {$ag['email']} — {$ag['data']} {$ag['hora']}\n";
            } else {
                $erros++;
                echo "[ERRO] Falha ao enviar para {$ag['email']}\n";
            }
        }

        echo "\nTotal: {$enviados} enviados, {$erros} erros.\n";

    } catch (Exception $e) {
        echo "Erro crítico: " . $e->getMessage() . "\n";
        exit(1);
    }

?>
<?php 

    // app/models/AgendamentoHistorico.php
    class AgendamentoHistorico {
        private PDO $db;

        public function __construct() {
            $this->db = Database::getInstance();
        }

        public function registrar(int $agendamentoId, int $usuarioId, string $acao, string $detalhes = ''): bool {
            $stmt = $this->db->prepare('
                INSERT INTO agendamento_historico (agendamento_id, usuario_id, acao, detalhes)
                VALUES (:agendamento_id, :usuario_id, :acao, :detalhes)
            ');

            return $stmt->execute([
                ':agendamento_id' => $agendamentoId, 
                ':usuario_id'     => $usuarioId, 
                ':acao'           => $acao, 
                ':detalhes'       => $detalhes,
            ]);
        }

        public function buscarPorAgendamento(int $agendamentoId): array {
            $stmt = $this->db->prepare('
                SELECT h.*, u.email,
                    CASE u.role
                        WHEN "admin"    THEN "admin"
                        WHEN "barbeiro" THEN "Barbeiro"
                        WHEN "cliente"  THEN "Cliente"
                    END AS role_label
                FROM agendamento_historico  h 
                JOIN usuarios u ON h.usuario_id = u.id
                WHERE h.agendamento_id = :id 
                ORDER BY h.created_at ASC
            ');

            $stmt->execute([':id' => $agendamentoId]);

            return $stmt->fetchAll();
        }
    }

?>
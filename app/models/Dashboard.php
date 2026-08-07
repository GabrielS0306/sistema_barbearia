<?php 

    class Dashboard {
        private PDO $db;

        public function __construct() {
            $this->db = Database::getInstance();
        }

        public function totalClientes(): int {
            $stmt = $this->db->query('SELECT COUNT(*) FROM clientes');

            return (int) $stmt->fetchColumn();
        }

        public function totalBarbeiros(): int {
            $stmt = $this->db->query('SELECT COUNT(*) FROM barbeiros WHERE ativo = 1');

            return (int) $stmt->fetchColumn();
        }

        public function agendamentosHoje(): int {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM agendamentos WHERE data = :data AND status != 'cancelado'"
            );

            $stmt->execute([':data' => date('Y-m-d')]);

            return (int) $stmt->fetchColumn();
        }

        public function receitaMes(): float {
            $stmt = $this->db->prepare(
                "SELECT COALESCE(SUM(s.preco), 0)
                FROM agendamentos a
                JOIN servicos s ON a.servico_id = s.id
                WHERE MONTH(a.data) = MONTH(CURDATE())
                AND YEAR(a.data) = YEAR(CURDATE())
                AND a.status = 'concluido'"
            );

            $stmt->execute();

            return (float) $stmt->fetchColumn();
        }

        public function agendamentoPorStatus(): array {
            $stmt = $this->db->prepare(
                "SELECT status, COUNT(*) AS total
                FROM agendamentos
                WHERE MONTH(data) = MONTH(CURDATE())
                AND YEAR(data) = YEAR(CURDATE())
                GROUP BY status"
            );

            $stmt->execute();
            $resultado = $stmt->fetchAll();

            $contagem = [
                'pendente'   => 0,
                'confirmado' => 0,
                'concluido'  => 0,
                'cancelado'  => 0,
            ];

            foreach ($resultado as $row) {
                $contagem[$row['status']] = (int) $row['total'];
            }

            return $contagem;
        }

        public function proximosAgendamentos(): array {
            $stmt = $this->db->prepare(
                "SELECT a.data, a.hora, c.nome AS cliente, b.nome AS barbeiro, s.nome AS servico
                FROM agendamentos a
                JOIN clientes c ON a.cliente_id = c.id
                JOIN barbeiros b ON a.barbeiro_id = b.id
                JOIN servicos s ON a.servico_id = s.id
                WHERE a.data >= :hoje AND a.status != 'cancelado'
                ORDER BY a.data ASC, a.hora ASC
                LIMIT 5"
            );

            $stmt->execute([':hoje' => date('Y-m-d')]);
            
            return $stmt->fetchAll();
        }

        public function metricasPorBarbeiro(): array {
            $stmt = $this->db->prepare("
                SELECT 
                    b.id,
                    b.nome,
                    COUNT(a.id) AS total_agendamentos,
                    SUM(CASE WHEN a.status = 'concluido' THEN 1 ELSE 0 END) AS concluidos,
                    SUM(CASE WHEN a.status = 'cancelado' THEN 1 ELSE 0 END) AS cancelados,
                    SUM(CASE WHEN a.status = 'concluido' THEN s.preco ELSE 0 END) AS receita
                FROM barbeiros b
                LEFT JOIN agendamentos a ON a.barbeiro_id = b.id
                    AND MONTH(a.data) = MONTH(CURDATE())
                    AND YEAR(a.data) = YEAR(CURDATE())
                LEFT JOIN servicos s ON a.servico_id = s.id
                WHERE b.ativo = 1
                GROUP BY b.id, b.nome
                ORDER BY receita DESC
            ");

            $stmt->execute();
            return $stmt->fetchAll();
        }
    }

?>
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
    }

?>
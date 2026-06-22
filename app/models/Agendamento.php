<?php 

    // app/models/Agendamento.php
    class Agendamento {
        private PDO $db;

        public function __construct(){
            $this->db = Database::getInstance();
        }

        public function horarioDisponivel(int $bareiroId, string $data, string $hora): bool {
            $sql = "SELECT id FROM agendamentos
                    WHERE barbeiro_id = :bid
                    AND data = :data
                    AND hora = :hora
                    AND status NOT IN ('cancelado')";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':bid'=> $bareiroId, ':data' => $data, ':hora' => $hora]);

            return $stmt->rowCount() === 0;
        }

        public function criar(array $dados): bool {
            $sql = "INSERT INTO agendamentos (cliente_id, barbeiro_id, servico_id, data, hora)
                    VALUES (:cid, :bid, :sid, :data, :hora)";
            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':cid' => $dados['cliente_id'],
                ':bid' => $dados['barbeiro_id'],
                ':sid' => $dados['servico_id'],
                ':data' => $dados['data'],
                ':hora' => $dados['hora'],
            ]);
        }

        public function buscarPorBarbeiro(int $barbeiroId, string $data): array {
            $sql = "SELECT a.*, c.nome AS cliente, s.nome AS servico, s.duracao_min
            FROM agendamentos a
            JOIN clientes c ON a.cliente_id = c.id
            JOIN servicos s ON a.servico_id = s.id
            WHERE a.barbeiro_id = :bid AND a.data = :data
            ORDER BY a.hora";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':bid' => $barbeiroId, ':data' => $data]);

            return $stmt->fetchAll();
        }

        public function buscarPorCliente(int $clienteId): array {
            $sql = 'SELECT a.*, b.nome AS barbeiro, s.nome AS servico, s.preco
                FROM agendamentos a
                JOIN barbeiros b ON a.barbeiro_id = b.id
                JOIN servicos s ON a.servico_id = s.id
                WHERE a.cliente_id = :cid
                ORDER BY a.data DESC, a.hora DESC';

            $stmt = $this->db->prepare($sql);
            $stmt->execute([':cid' => $clienteId]);

            return $stmt->fetchAll();
        }
    }

?>
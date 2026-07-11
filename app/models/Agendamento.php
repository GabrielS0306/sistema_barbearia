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
            $sql = "SELECT a.*, c.nome AS cliente, s.nome AS servico, s.duracao_min, s.preco 
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

        public function buscarTodos(array $filtros = []): array {
            $sql = 'SELECT a.*, 
                        c.nome AS cliente,
                        b.nome AS barbeiro,
                        s.nome AS servico,
                        s.preco
                    FROM agendamentos a 
                    JOIN clientes c ON a.cliente_id = c.id
                    JOIN barbeiros b ON a.barbeiro_id = b.id
                    JOIN servicos s ON a.servico_id = s.id
                    WHERE 1=1';

            $params = [];

            if (!empty($filtros['data'])) {
                $sql .= ' AND a.data = :data';
                $params[':data'] = $filtros['data'];
            }

            if (!empty($filtros['barbeiro_id'])) {
                $sql .= ' AND a.barbeiro_id = :barbeiro_id';
                $params[':barbeiro_id'] = $filtros['barbeiro_id'];
            }

            if (!empty($filtros['status'])) {
                $sql .= ' AND a.status = :status';
                $params[':status'] = $filtros['status'];
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll();
        }

        public function contarPorStatus(): array {
            $sql = 'SELECT status, COUNT(*) AS total FROM agendamentos GROUP BY status';

            $stmt = $this->db->query($sql);
            $resultado = $stmt->fetchAll();

            $contagem = [
                'pendente' => 0,
                'confirmado' => 0,
                'concluido' => 0,
                'cancelado' => 0
            ];

            foreach ($resultado as $row) {
                $contagem[$row['status']] = $row['total'];
            }

            return $contagem;
        }

        public function buscarPorPeriodo(string $dataInicio, string $dataFim): array {
            $sql = 'SELECT a.*,
                        c.nome AS cliente,
                        b.nome AS barbeiro,
                        s.nome AS servico,
                        s.preco
                    FROM agendamentos a 
                    JOIN clientes c ON a.cliente_id = c.id
                    JOIN barbeiros b ON a.barbeiro_id = b.id
                    JOIN servicos s ON a.servico_id = s.id
                    WHERE a.data BETWEEN :data_inicio AND :data_fim
                    ORDER BY a.data ASC, a.hora ASC';

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':data_inicio' => $dataInicio,
                ':data_fim' => $dataFim
            ]);

            return $stmt->fetchAll();
        }
    }

?>
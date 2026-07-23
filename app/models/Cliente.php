<?php 

    // app/models/Cliente.php
    class Cliente {
        private PDO $db;

        public function __construct() {
            $this->db = Database::getInstance();
        }

        public function buscarPorUsuarioId(int $usuarioId): array | false {
            $stmt = $this->db->prepare('
                SELECT c.*, u.email 
                FROM clientes c
                JOIN usuarios u ON c.usuario_id = u.id
                WHERE c.usuario_id = :uid
            ');

            $stmt->execute([':uid' => $usuarioId]);

            return $stmt->fetch();
        }

        public function atualizar(int $id, array $dados): bool {
            $stmt = $this->db->prepare('
                UPDATE clientes 
                SET nome = :nome, telefone = :telefone 
                WHERE id = :id
            ');
            
            return $stmt->execute([
                ':id'       => $id,
                ':nome'     => $dados['nome'],
                ':telefone' => $dados['telefone'],
            ]);
        }
    }

?>
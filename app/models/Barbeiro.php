<?php 

    // app/models/Servico.php
    class Barbeiro {
        private PDO $db;

        public function __construct() {
            $this->db = Database::getInstance();
        }

        public function listarTodos(): array {
            $sql = "SELECT b.*, u.email FROM barbeiros b 
                    JOIN usuarios u ON b.usuario_id = u.id";
            
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        }

        public function buscarPorId(int $id): array|false {
            $stmt = $this->db->prepare('SELECT * FROM barbeiros WHERE id = :id');
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } 

        public function criar(array $dados): bool {
            $sql = 'INSERT INTO barbeiros (usuario_id, nome, especialidade, foto) 
                    VALUES (:usuario_id, :nome, :especialidade, :foto)';
            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':usuario_id'    => $dados['usuario_id'],
                ':nome'          => $dados['nome'],
                ':especialidade' => $dados['especialidade'],
                ':foto'          => $dados['foto'],
            ]);
        }

        public function atualizar(int $id, array $dados): bool {
            $sql = 'UPDATE barbeiros
                    SET nome = :nome, especialidade = :especialidade, foto = :foto
                    WHERE id = :id';
            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':id'              => $id,
                ':nome'            => $dados['nome'],
                ':especialidade'   => $dados['especialidade'],
                ':foto'            => $dados['foto'],
            ]);
        }

        public function deletar(int $id): bool {
            $stmt = $this->db->prepare('DELETE FROM barbeiros WHERE id = :id');

            return $stmt->execute([':id' => $id]);
        }

        public function usuariosSemPerfil(): array {
            $sql = "SELECT u.id, u.email
                    FROM usuarios u
                    LEFT JOIN barbeiros b ON b.usuario_id = u.id
                    WHERE u.role = 'barbeiro' AND b.id IS NULL";

            $stmt = $this->db->query($sql);

            return $stmt->fetchAll();
        }
    }

?>
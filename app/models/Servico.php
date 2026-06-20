<?php 

    // app/models/Servico.php
    class Servico {
        private PDO $db;

        public function __construct() {
            $this->db = Database::getInstance();
        }

        public function listarTodos(): array {
            $stmt = $this->db->query('SELECT * FROM servicos ORDER BY nome');
            return $stmt->fetchAll();
        }

        public function buscarPorId(int $id): array|false {
            $stmt = $this->db->prepare('SELECT * FROM servicos WHERE id = :id');
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } 

        public function criar(array $dados): bool {
            $sql = 'INSERT INTO servicos (nome, descricao, preco, duracao_min) 
                    VALUES (:nome, :descricao, :preco, :duracao_min)';
            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':nome'        => $dados['nome'],
                ':descricao'   => $dados['descricao'],
                ':preco'       => $dados['preco'],
                ':duracao_min' => $dados['duracao_min'],
            ]);
        }

        public function atualizar(int $id, array $dados): bool {
            $sql = 'UPDATE servicos
                    SET nome = :nome, descricao = :descricao, preco = :preco, duracao_min = :duracao_min
                    WHERE id = :id';
            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':id'          => $id,
                ':nome'        => $dados['nome'],
                ':descricao'   => $dados['descricao'],
                ':preco'       => $dados['preco'],
                ':duracao_min' => $dados['duracao_min'],
            ]);
        }

        public function deletar(int $id): bool {
            $stmt = $this->db->prepare('DELETE FROM servicos WHERE id = :id');
            return $stmt->execute([':id' => $id]);
        }
    }

?>
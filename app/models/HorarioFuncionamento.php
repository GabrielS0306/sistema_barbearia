<?php 

    // app/Models/HorarioFuncionamento.php
    class HorarioFuncionamento {
        private PDO $db;

        public function __construct() {
            $this->db = Database::getInstance();
        }

        public function listarTodos(): array {
            $stmt = $this->db->query('SELECT * FROM horarios_funcionamento ORDER BY dia_semana');

            return $stmt->fetchAll();
        }

        public function buscarPorDia(int $diaSemana): array | false {
            $stmt = $this->db->prepare('
                SELECT * FROM horarios_funcionamento 
                WHERE dia_semana = :dia_semana
            ');

            $stmt->execute([':dia_semana' => $diaSemana]);

            return $stmt->fetch();
        }

        public function atualizar(int $id, array $dados): bool {
            $stmt = $this->db->prepare('
                UPDATE horarios_funcionamento 
                SET hora_inicio = :inicio, hora_fim = :fim, ativo = :ativo
                WHERE id = :id
            ');

            return $stmt->execute([
                ':id'     => $id,
                ':inicio' => $dados['hora_inicio'],
                ':fim'    => $dados['hora_fim'],
                ':ativo'  => $dados['ativo'],
            ]);
        }

        public function horariosDisponiveis(string $data): array {
            $diaSemana = (int) date('w', strtotime($data)); 
            $horario   = $this->buscarPorDia($diaSemana);

            if (!$horario) return [];

            $todos = ['08:00', '08:30', '09:00', '09:30', '10:00', '10:30',
                    '11:00', '11:30', '13:00', '13:30', '14:00', '14:30',
                    '15:00', '15:30', '16:00', '16:30', '17:00', '17:30'
            ];

            $inicio = substr($horario['hora_inicio'], 0, 5);
            $fim    = substr($horario['hora_fim'], 0, 5);

            return array_filter($todos, fn($h) => $h >= $inicio && $h <= $fim);
        }
    }

?>
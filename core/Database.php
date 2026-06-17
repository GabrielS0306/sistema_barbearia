<?php 

    // Class Database é responsável por gerenciar a conexão com o banco de dados usando o padrão Singleton para garantir que apenas uma instância da conexão seja criada durante a execução do aplicativo.

    // core/Database.php
    class Database {
        private static ?PDO $instance = null;

        private function __construct() {}

        public static function getInstance():PDO {
            if (self::$instance === null) {
                $cfg = require __DIR__ . '/../config/database.php';
                $dns = "mysql:host={$cfg['host']};dbname={$cfg['dbname']};charset=utf8mb4";

                self::$instance = new PDO($dns, $cfg['username'], $cfg['password'], [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            }

            return self::$instance;
        }
    }

?>
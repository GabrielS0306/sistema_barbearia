<?php 

    // Class Database é responsável por gerenciar a conexão com o banco de dados usando o padrão Singleton para garantir que apenas uma instância da conexão seja criada durante a execução do aplicativo.

    // core/Database.php
    class Database {
        private static ?PDO $instance = null;

        private function __construct() {}

        public static function getInstance():PDO {
            if (self::$instance === null) {
                // Produção: variáveis de ambiente do inift
                // Desenvolvimento: arquivo/Database.php
                
                if (getenv('MYSQLHOST')) {
                    $host = getenv('MYSQLHOST');
                    $dbname = getenv('MYSQLDATABASE');
                    $user = getenv('MYSQLUSER');
                    $pass = getenv('MYSQLPASSWORD');
                    $port = getenv('MYSQLPORT') ?? '3306';
                    $dns = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
                } else {
                    $cfg = require __DIR__ . '/../config/database.php';
                    $dns = "mysql:host={$cfg['host']};dbname={$cfg['dbname']};charset=utf8mb4";
                    $user = $cfg['user'];
                    $pass = $cfg['pass'];
                }

                self::$instance = new PDO($dns, $user, $pass, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            }

            return self::$instance;
        }
    }

?>
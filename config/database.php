<?php
    class Database{
        private $hostname;
        private $database;
        private $username;
        private $password;
        private $charset;

        function __construct(){
            $this->loadEnv();
        }

        private function loadEnv(){
            $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
            $dotenv->load();

            $this->hostname = $_ENV['DB_HOST'];
            $this->database = $_ENV['DB_DATABASE'];
            $this->username = $_ENV['DB_USERNAME'];
            $this->password = $_ENV['DB_PASSWORD'];
            $this->charset = $_ENV['DB_CHARSET'];
        }

        function conectar(){
            try{
                $conexion = "mysql:host=" . $this->hostname . ";dbname=" . $this->database . ";charset=" . $this->charset;

                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false
                ];

                $pdo = new PDO($conexion, $this->username, $this->password, $options);

                return $pdo;
            }catch(PDOException $e){
                echo 'Error conexión:' . $e->getMessage();
                exit;
            }
        }
    }
?>

<?php
    class Database {
        private $host = "127.0.0.1";
        private $database_name = "teste_produto";
        private $username = "postgres";
        private $password = "123";

        public $conn;

        public function getConnection(){
            $this->conn = null;
            try{
                $this->conn = new PDO("pgsql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);
                $this->conn->exec("SET NAMES utf8");
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }catch(PDOException $exception){
                echo "Não foi possível estabelecer conexão com o banco de dados. Motivo: " . $exception->getMessage();
            }
            return $this->conn;
        }        
    }
?>
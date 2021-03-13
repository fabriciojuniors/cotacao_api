<?php

    class Database {
        /*private $host = "127.0.0.1";
        private $database_name = "teste_produto";
        private $username = "postgres";
        private $password = "123";*/
        
        private $host = "ec2-54-162-119-125.compute-1.amazonaws.com";
        private $database_name = "d6m7a1kferqtag";
        private $username = "knjeowajnpoevj";
        private $password = "31b78b78108976cfa4f2720c2aa8c59373220d2676d027f2d31a371731d1b7e0";
        public $conn;

        public function getConnection(){
            $this->conn = null;
            try{
                $this->conn = new PDO("pgsql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);
                //$this->conn->exec("SET NAMES utf8");
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }catch(PDOException $exception){
                echo "Não foi possível estabelecer conexão com o banco de dados. Motivo: " . $exception->getMessage();
            }
            return $this->conn;
        }        
    }

    $conexao = new Database();
    $conexao = $conexao->getConnection();
    echo $conexao
?>
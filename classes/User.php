<?php
    class Users{
        private $id;
        private $nome;
        private $login;
        private $senha;
        private $email;
        private $data_cadastro;
        private $conexao;

        public function __construct($id, $nome, $login, $senha,$email, $data_cadastro, $conexao){
            $this->conexao = $conexao;
            $this->setNome($nome);
            $this->setLogin($login);
            $this->setSenha($senha);
            $this->setDataCadastro($data_cadastro);
            $this->setId($id);
            $this->setEmail($email);
        }

        public function setEmail($email){
            $this->email= $email;
        }

        public function setId($id){
            $this->id = $id;
        }

        public function setNome($nome){
            $this->nome = strtoupper($nome);
        }

        public function setLogin($login){
            $this->login = strtoupper($login);
        }

        public function setSenha($senha){
            $this->senha = md5($senha);
        }

        public function setDataCadastro($data){
            $this->data_cadastro = $data;
        }
        
        public function getId(){
                return $this->id;
        }
        
        public function getNome(){
                return $this->nome;
        }

        public function getLogin(){
                return $this->login;
        }

        public function getSenha(){
                return $this->senha;
        }

        public function getData_cadastro(){
                return $this->data_cadastro;
        }

        public function insert(){
            $sql = "INSERT INTO usuario(nome, login, senha, data_cadastro, email) 
                    VALUES (:nome, :login, :senha, now(), :email)";

            $query = $this->conexao->prepare($sql);

            $query->bindParam(":nome", $this->nome);
            $query->bindParam(":login", $this->login);
            $query->bindParam(":senha", $this->senha);
            $query->bindParam(":email", $this->email);

            $query->execute();

            return $query;
        }

        public function authUser(){
            $sql = "SELECT *, now()tempo FROM usuario
                    WHERE login = :login and senha = :senha";
            
            $query = $this->conexao->prepare($sql);
            $query->bindParam(":login", $this->login);
            $query->bindParam(":senha", $this->senha);

            $query->execute();

            if($query->rowCount() > 0){
                $usuario = $query->fetch(PDO::FETCH_ASSOC, PDO::FETCH_NAMED);
                $hash = md5($usuario['id'].$usuario['nome'].$usuario['login'].$usuario['senha'].$usuario['tempo']);

                $sessao = array(
                    "id" => $usuario['id'],
                    "nome" => $usuario['nome'],
                    "login" => $usuario['login'],
                    "aniversario"=> $usuario['data_cadastro'],
                    "email" => $usuario['email'],
                    "instante" => $usuario['tempo'],
                    "hash" => $hash
                );

                echo json_encode($sessao);
            }else{
                echo json_encode(array("mensagem" => "Usuário ou senha inválidos."));
            }
        }

        public function getPagitination($inicio, $fim){
            $sql = "select * from (select *, row_number() over() as linha from usuario)tabela where linha between ".$inicio . " and ". $fim;

            $query = $this->conexao->prepare($sql);
            $query->execute();
            $result =[];

            if($query->rowCount() > 0){
                for($i = 0; $i < $query->rowCount(); $i++){
                    $result[$i] = $query->fetch(PDO::FETCH_NAMED, PDO::FETCH_ASSOC);
                }

            }
            echo json_encode($result);
        }

        
    }

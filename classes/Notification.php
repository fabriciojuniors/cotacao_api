<?php
    class Notification {
        private $id;
        private $idUsuario;
        private $moeda;
        private $cotacao;
        private $dataAtivacao;
        private $conexao;


        public function __construct($id, $idUsuario, $moeda, $cotacao,$dataAtivacao, $conexao)
        {
            $this->setID($id);
            $this->setIdUsuario($idUsuario);
            $this->setMoeda($moeda);
            $this->setCotacao($cotacao);
            $this->setDataAtivacao($dataAtivacao);
            $this->setConexao($conexao);
        }

        public function setConexao($conexao){
                $this->conexao = $conexao;
        }

        public function setID($id){
            $this->id = $id;
        }

        public function setIdUsuario($idUsuario){
            $this->idUsuario = $idUsuario;
        }

        public function setMoeda($moeda){
            $this->moeda = strtoupper($moeda);
        }

        public function setCotacao($cotacao){
            $this->cotacao = $cotacao;
        }

        public function setDataAtivacao($dataAtivacao){
            $this->dataAtivacao = $dataAtivacao;
        }

        public function getConexao(){
            return $this->conexao;
        }
        public function getId(){
            return $this->id;
        }
        public function getIdUsuario(){
            return $this->idUsuario;
        }
        public function getMoeda(){
            return $this->moeda;
        }
        public function getCotacao(){
            return $this->cotacao;
        }
        public function getDataAtivacao(){
            return $this->dataAtivacao;
        }

        public function insert(){
            $sql = "INSERT INTO notifica_usuario(id_usuario, moeda, valor, data_ativacao) values(:usuario, :moeda, :valor, now())";

            $query = $this->getConexao()->prepare($sql);

            $query->bindParam(":usuario", $this->idUsuario);
            $query->bindParam(":moeda", $this->moeda);
            $query->bindParam(":valor", $this->cotacao);

            $query->execute();

            return $query;
        }

        public function delete(){
            $sql = "DELETE FROM notifica_usuario WHERE id_usuario = :usuario";

            $query = $this->getConexao()->prepare($sql);

            $query->bindParam(":usuario", $this->idUsuario);

            $query->execute();

            return $query;
        }

    }
?>
<?php
    class Product{
        private $id;
        private $nome;
        private $valor;
        private $descricao;
        private $dono;
        private $data;
        private $lote;

        private $conexao;

        public function __construct($conexao){
            $this->conexao = $conexao;
        }

        //Setters
        public function setId($id){
            $this->id = $id;
        }

        public function setNome($nome){
            $this->nome = strtoupper($nome);
        }

        public function setValor($valor){
            $this->valor = $valor;
        }

        public function setDescricao($descricao){
            $this->descricao = strtoupper($descricao);
        }

        public function setDono($dono){
            $this->dono = strtoupper($dono);
        }

        public function setData($data){
            $this->data = $data;
        }

        public function setLote($lote){
            $this->lote = strtoupper($lote);
        }

        //Getters
        public function getId(){
            return $this->id;
        }

        public function getNome(){
            return $this->nome;
        }

        public function getValor(){
            return $this->valor;
        }

        //Métodos
        public function insert($nome, $valor, $descricao, $dono, $data, $lote){
            $this->setNome($nome);
            $this->setValor($valor);
            $this->setDescricao($descricao);
            $this->setDono($dono);
            $this->setData($data);
            $this->setLote($lote);
            $sql = "INSERT INTO produto( nome, valor, descricao, dono, data, lote)
                    VALUES( :nome, :valor, :descricao, :dono, :data, :lote)";

            $query = $this->conexao->prepare($sql);

            $query->bindParam(":nome", $this->nome);
            $query->bindParam(":valor", $this->valor);
            $query->bindParam(":descricao", $this->descricao);
            $query->bindParam(":dono", $this->dono);
            $query->bindParam(":data", $this->data);
            $query->bindParam(":lote", $this->lote);

            $query->execute();

            return $query;
        }

        public function update($id, $nome, $valor, $descricao, $lote, $data, $dono){
            $this->setId($id);
            $this->setNome($nome);
            $this->setValor($valor);
            $this->setDescricao($descricao);
            $this->setLote($lote);
            $this->setData($data);
            $this->setDono($dono);

                $sql = "UPDATE produto SET
                            nome = :nome,
                            valor = :valor,
                            descricao = :descricao,
                            lote = :lote,
                            data = :data,
                            dono = :dono
                        WHERE id = :id";
                
                $query = $this->conexao->prepare($sql);
                $query->bindParam(":nome", $this->nome);
                $query->bindParam(":valor", $this->valor);
                $query->bindParam(":descricao", $this->descricao);
                $query->bindParam(":lote", $this->lote);
                $query->bindParam(":data", $this->data);
                $query->bindParam(":dono", $this->dono);
                $query->bindParam(":id", $this->id);
                $query->execute();

                return $query;
                
        }

        public function delete($id){
            try{
                $sql = "DELETE FROM produto 
                WHERE id = :id";

                $query = $this->conexao->prepare($sql);
                $query->bindParam(":id", $id);
                $query->execute();
                return $query->rowCount();
            }catch(PDOException $ex){
                return $ex->getMessage();
            }
   
        }

        public function getProdutos(){
            $sql = "SELECT *, to_char(data, 'DD/MM/YYYY')DATAFORMATADA  FROM produto";

            $query = $this->conexao->prepare($sql);
            $query->execute();
            return $query;
        }

        public function getById($id){
            $sql = "SELECT * FROM produto
                    WHERE id = :id";

            $query = $this->conexao->prepare($sql);

            $query->bindParam(":id", $id);

            $query->execute();
            return $query;
        }

        public function getFiltro($filtro){
            $filtro = strtoupper($filtro);
            $sql = "SELECT * FROM produto WHERE (nome like '%$filtro%' or descricao like '%$filtro%')";

            $query = $this->conexao->prepare($sql);
            $query->execute();
            return $query;
        }
    }
?>
<?php
    //Incluir cabeçalho padrão para post
    include_once'../../configs/headerPost.php';

    ///Inclui os arquivos de conexão com o BD e Classe Produtos
    include_once'../../configs/database.php';
    include_once'../../classes/Product.php';    

    $conexao = new Database();
    $conexao = $conexao->getConnection();

    //Recupera o corpo da requisicação HTTP. Vem no formato JSON
    $dados = json_decode(file_get_contents("php://input"));

    $id = $dados->id;
    $nome = $dados->nome;
    $valor = $dados->valor;
    $descricao = $dados->descricao;
    $dono = $dados->dono;
    $lote = $dados->lote;
    $data = $dados->data;

    $produto = new Product($conexao);
    
    try{
        $query = $produto->update($id, $nome, $valor, $descricao, $lote, $data, $dono);
        echo $query->rowCount();
    } catch(PDOException $e){
        echo $e->getMessage();
    }
?>
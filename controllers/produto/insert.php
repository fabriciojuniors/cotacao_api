<?php
    //Incluir cabeçalho padrão para post
    include_once '../../configs/headerPost.php';

    ///Inclui os arquivos de conexão com o BD e Classe Produtos
    include_once '../../configs/database.php';
    include_once '../../classes/Product.php';    

    $conexao = new Database();
    $conexao = $conexao->getConnection();

    //Recupera o corpo da requisicação HTTP. Vem no formato JSON
    $dados = json_decode(file_get_contents("php://input"));

    $nome = $dados->nome;
    $valor = $dados->valor;
    $descricao = $dados->descricao;
    $dono = $dados->dono;
    $data = $dados->data;
    $lote = $dados->lote;
    $produto = new Product($conexao);
    
    try{
        $query = $produto->insert( $nome, $valor, $descricao, $dono, $data, $lote);
        if($query->rowCount() > 0){
            echo json_encode(array("mensagem"=> "Produto adicionado."));
            http_response_code(201);
        }
    }catch(PDOException $ex){
        echo json_encode(array("mensagem"=>$ex));
        http_response_code(500);
    }

?>
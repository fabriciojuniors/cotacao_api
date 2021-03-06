<?php
    //Incluir cabeçalho padrão para post
    include_once '../../configs/headerGet.php';

    ///Inclui os arquivos de conexão com o BD e Classe Produtos
    include_once '../../configs/database.php';
    include_once '../../classes/Product.php';    

    $conexao = new Database();
    $conexao = $conexao->getConnection();

    $produto = new Product($conexao);

    $query = $produto->getProdutos();

    if($query->rowCount() > 0){
        $produtos = $query->fetchAll(PDO::FETCH_NAMED);
        echo json_encode($produtos);
        http_response_code(200);
    }else{
        $err = array(
            "mensagem" => "Não foi possível localizar produtos"
       );
       http_response_code(400);
       echo json_encode($err);
    }
?>    
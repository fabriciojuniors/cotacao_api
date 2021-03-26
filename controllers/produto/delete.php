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

    $id = $dados->id;

    $produto = new Product($conexao);

    $query = $produto->delete($id);
    
    if($query >= 0){
        echo json_encode(array("mensagem"=>"Registros afetados: $query"));
        http_response_code(200);
    }else{
        echo json_encode(array("mensagem"=>$query));
        http_response_code(400);
    }
?>

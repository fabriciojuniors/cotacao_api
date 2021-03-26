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

    $filtro = $dados->filtro;
    $produto = new Product($conexao);

    $query = $produto->getFiltro($filtro);

    if($query->rowCount() > 0){
        $produto = $query->fetchAll(PDO::FETCH_NAMED);
        echo json_encode($produto);
        http_response_code(200); 
    }else{
        $err = array(
                        "mensagem" => "Não foi possível localizar produto"
                   );
        http_response_code(400);
        echo json_encode($err);
    }

?>
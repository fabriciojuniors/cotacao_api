<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/configs/headerPost.php';

    include_once $_SERVER['DOCUMENT_ROOT'] . '/configs/database.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/classes/User.php';    

    $conexao = new Database();
    $conexao = $conexao->getConnection();

    $dados = json_decode(file_get_contents("php://input"));

    $usuario = new Users(null, $dados->nome, $dados->login, $dados->senha,$dados->email, null, $conexao);

    try{
        $query = $usuario->insert();
        echo json_encode(array("mensagem"=>"Registros afetados: ".$query->rowCount()));
    }catch(PDOException $e){
        echo json_encode($e->errorInfo[2]);
    }
    
?>
<?php
    include_once '../../configs/headerPost.php';

    include_once '../../configs/database.php';
    include_once '../../classes/User.php';    

    $conexao = new Database();
    $conexao = $conexao->getConnection();

    $dados = json_decode(file_get_contents("php://input"));

    $usuario = new Users(null, null, $dados->login, $dados->senha,null, null, $conexao);

    $query = $usuario->authUser();

    echo $query;
?>
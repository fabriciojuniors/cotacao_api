<?php

    include_once '../../configs/headerGet.php';

    include_once '../../configs/database.php';
    include_once '../../classes/User.php';    

    $conexao = new Database();
    $conexao = $conexao->getConnection();

    $user = new Users(null,null, null, null, null, null, $conexao);

    $inicio = $_GET['inicio'];
    $fim = $_GET['fim'];

    $query = $user->getPagitination($inicio, $fim);

    echo $query;
?>
<?php
    include $_SERVER['DOCUMENT_ROOR'] . '/cotacao_api/configs/database.php';

    $conexao = new Database();
    $conexao = $conexao->getConnection();

    echo $conexao;
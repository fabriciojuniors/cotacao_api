<?php
    include $_SERVER['DOCUMENT_ROOT'] . '/cotacao_api/configs/database.php';

    $conexao = new Database();
    $conexao = $conexao->getConnection();

    echo "Oii";
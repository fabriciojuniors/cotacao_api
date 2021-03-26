<?php
    include_once'../../configs/headerPost.php';

    include_once'../../configs/database.php';
    include_once'../../classes/Notification.php';    

    $conexao = new Database();
    $conexao = $conexao->getConnection();

    $dados = json_decode(file_get_contents("php://input"));
    $linha = 0;
    $erro = '';
    foreach ($dados as $d) {
        $notificacao = new Notification(null, $d->usuario, $d->moeda, $d->cotacao, null, $conexao);
        try{
            //$notificacao->delete();
            $query = $notificacao->insert();
            $linha += $query->rowCount();
        }catch(PDOException $e){
            $erro .= $e->getMessage();
        } 
    }

    if($linha > 0){
        echo $linha;
    }
    else {
        echo $erro;
    }
    
?>
    


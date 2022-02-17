<?php
    include 'email.php';
    include_once '../../configs/database.php';

    $conexao = new Database();
    $conexao = $conexao->getConnection();

    $url = "https://economia.awesomeapi.com.br/json/all";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, True);

    $return = curl_exec($curl);
    curl_close($curl);
    $arrResp = json_decode($return, true);
    
    $sql = "SELECT ID 
              FROM USUARIO";
    $query = $conexao->prepare($sql);
    $query->execute();
    
    $contemNotificacao = false;
    $tabelaMoedas = '<table><thead><th>Moeda</th><th>Cotação Buscada</th><th>Cotação atual</th></thead><tbody>';
    $tabelaMoedasCorpo = '';


    while($row = $query->fetch(PDO::FETCH_ASSOC, PDO::FETCH_NAMED)){
        $sqlLog = "INSERT INTO log_email(tipo, destinatario, mensagem, data) values('Percorrer usuários', 'x', 'Sucesso', now())";
        $queryLog = $conexao->prepare($sqlLog);
        $queryLog->execute();
        $tabelaMoedasCorpo = '';
        $tabelaMoedas = '<table border=1 style="text-align: center"><thead><th>Moeda</th><th>Cotação Buscada</th><th>Cotação atual</th></thead><tbody>';

        $sql = "SELECT u.nome, u.email, nu.moeda, nu.valor, nu.data_ativacao 
                  FROM notifica_usuario nu
            INNER JOIN usuario u on (u.id = nu.id_usuario)
                 WHERE u.id = ".$row['id'];
        $queryNotificacao = $conexao->prepare($sql);
        $queryNotificacao->execute();
        $nome = '';
        $moedas = '';
        $emailDest = '';
        while($rowNotificacao = $queryNotificacao->fetch(PDO::FETCH_ASSOC, PDO::FETCH_NAMED)){
            $nome = $rowNotificacao['nome'];
            $emailDest = $rowNotificacao['email'];
            foreach($arrResp as $arr =>$val){
                $moeda = $val['code'];
                $cotacao = $val['high'];
                if($rowNotificacao['moeda'] == $moeda && $cotacao <= $rowNotificacao['valor']){
                    $moedas .= ($moedas == '') ? $moeda : ', ' . $moeda;
                    $tabelaMoedasCorpo .= '<tr>';
                    $tabelaMoedasCorpo .= '<td>'.$moeda.'</td>';
                    $tabelaMoedasCorpo .= '<td>'.number_format($rowNotificacao['valor'],2,",",".").'</td>';
                    $tabelaMoedasCorpo .= '<td>'.number_format($cotacao,2, ",", ".") .'</td>';
                    $tabelaMoedasCorpo .= '</tr>';
                }
            }
        }
        
        $tabelaMoedas .= $tabelaMoedasCorpo . '</tbody></table>';

        if($tabelaMoedasCorpo <> ''){
            $email = '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">';
            $email .= '<div class="card" style="width: 30rem;">';
            $email .= '<div class="card-body">';
            $email .= '<h5 class="card-title">Olá, '. $nome. '</h5>';
            $email .= '<p class="card-text">Lembra a notificação que você programou no <strong>Cotação APP</strong>? Ela chegou!</p> ';
            $email .= '<p class="card-text">Isso significa que a cotação baixou (moedas: '.$moedas.') e você já pode tirar aquele plano de férias no exterior da gaveta. </p>';
            $email .= $tabelaMoedas;
            $email .= '</div></div>';
            
            //$email = '<div > <h3>Olá, '.$nome.'</h3> <p>Lembra a notificação que você programou no <strong>Cotação APP</strong>? Ela chegou!</p>  <p>Isso significa que a cotação baixou (moedas: '.$moedas.') e você já pode tirar aquele plano de férias no exterior da gaveta. </p>'.$tabelaMoedas.' <br> <p>Equipe Cotação APP.</p</div>';

            enviarEmail($emailDest, $nome, 'A cotação baixo, venha conferir!', $email, "Notificação automática - cotação");
        }
    } 
    


    


?>
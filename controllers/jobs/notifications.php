<?php
    include 'email.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'configs/database.php';

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
            $email = '<div > <h3>Olá, '.$nome.'</h3> <p>Lembra a notificação que você programou no <strong>Cotação APP</strong>? Ela chegou!</p>  <p>Isso significa que a cotação baixou (moedas: '.$moedas.') e você já pode tirar aquele plano de férias no esterior da gaveta. </p>'.$tabelaMoedas.' <br> <p>Equipe Cotação APP.</p</div>';

            enviarEmail($emailDest, $nome, 'A cotação baixo, venha conferir!', $email, "Notificação automática - cotação");
        }
    } 
    


    


?>
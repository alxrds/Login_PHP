<?php

    include 'conexao.php';

    session_start();

    $msg = '';
    $email = '';
    $nome = '';
    $sobrenome = '';
    $site = 'Sistema de Login';
    $url = 'ar.dev.br';
    $emailsite = 'email@'.$url;

    if($_POST){

        $sql = 'SELECT * FROM tbl_usuario WHERE email = :email;';
        $stmt = $PDO->prepare($sql);
        $stmt->bindValue(':email', $_POST['email']);
        $stmt->execute();
        $row = $stmt->fetch();
        $num_rows = $stmt->rowCount();
    
        if($num_rows == 0){
    
            $msg = 'O e-mail '.$_POST['email'].' não existe!';
    
        }else{

            $token = substr(md5(time()), 0, 6);

            $cab = "From: ".$site." <".$emailsite.">\n";

            $email = $_POST['email'];
            $mensagem = "Olá  ".$row['nome'].", \n";
            $mensagem .= "houve uma solicitação de mudança de senha no site ".$site." \n";
            $mensagem .= "acesse: http://".$url."/app-login/mudaSenha.php?token=".$token ." para finalizar a solicitação. \n \n \n \n";
            $mensagem .= "Caso não tenha solicitado, apenas ignore esta mensagem. \n \n \n \n \n \n \n \n";
            $mensagem .= "Equipe ".$site." ";

            if(mail($email, utf8_decode("Mudança de Senha"), $mensagem, $cab)){

                $sql = 'UPDATE tbl_usuario SET token = :token WHERE email = :email;';
                $stmt = $PDO->prepare($sql);
                $stmt->bindValue(':email', $_POST['email']);
                $stmt->bindValue(':token', $token);
                $stmt->execute();

            }


            $msg = 'Siga as instruções enviada para '.$_POST['email'].'.';
            
        }

    }


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sistema de Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Tamma+2&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h3 class="title">Esqueceu a senha</h3>
        </header>
        <main>
            <div class="box-msg">
                <?php
                    echo $msg;
                ?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box-login shadow">
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" class="form-control" name="email" placeholder="Seu e-mail" required>
                            </div>
                            <button type="submit" class="btn btn-primary" id="btn">Enviar</button>
                        </form>
                        <div class="b2">
                            <a href="index.php"><p>Voltar</p></a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            <p> &copy; <?php echo date('Y') ?> Todos os diteitos reservados </p>
        </footer>
    </div>
</body>
</html>
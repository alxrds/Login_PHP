<?php

    include 'conexao.php';

    $msg = '';
    $email = '';
    $nome = '';
    $sobrenome ='';
    $site = 'Sistema de Login';
    $url = 'ar.dev.br';
    $emailsite = 'email@'.$url;

    if($_POST){      

        $email = $_POST['email'];
        $nome = $_POST['nome'];
        $sobrenome = $_POST['sobrenome'];

        $sql = 'SELECT * FROM tbl_usuario WHERE email = :email;';
        $stmt = $PDO->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $data = $stmt->fetch();
        $num_rows = $stmt->rowCount();

        if($num_rows > 0){

            $msg = 'O e-mail '.$email.' Já existe!';

        }elseif($_POST['senha'] != $_POST['confirmasenha']){

            $msg = 'As senhas digitadas são diferentes';

        }else{

            $token = substr(md5(time()), 0, 6);

            $cab = "From: ".$site." <".$emailsite.">\n";

            $email = $_POST['email'];
            $mensagem = "Olá  ".$_POST['nome'].", \n";
            $mensagem .= "houve uma solicitação de cadastro no site ".$site." \n";
            $mensagem .= "acesse: http://".$url."/app-login/ativaUsuario.php?token=".$token ." para finalizar a solicitação. \n \n \n \n";
            $mensagem .= "Caso não tenha solicitado, apenas ignore esta mensagem. \n \n \n \n \n \n \n \n";
            $mensagem .= "Equipe ".$site." ";

            if(mail($email, utf8_decode("Criação de Usário"), $mensagem, $cab)){

            $sql = 'INSERT INTO tbl_usuario (nome, sobrenome, email, senha, nivel_de_acesso, token, status) VALUES (:nome, :sobrenome, :email, :senha, 0, :token, 0);';
            $stmt = $PDO->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':sobrenome', $sobrenome);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':senha', md5(md5($_POST['senha'])));
            $stmt->bindValue(':token', $token);
            $stmt->execute();

            $msg = 'Siga as Instrunções enviadas para seu e-mail';

            }else{

                $msg = 'Erro ao enviar a-mail para: '.$email.'.';

            }

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
            <h3 class="title">Sistema de Login</h3>
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
                                <label for="nome">Nome</label>
                                <input type="text" value="<?php echo $nome ?>" class="form-control" name="nome" placeholder="Seu nome" required>
                            </div>
                            <div class="form-group">
                                <label for="sobrenome">Sobrenome</label>
                                <input type="text" value="<?php echo $sobrenome ?>" class="form-control" name="sobrenome" placeholder="Seu sobrenome" required>
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" value="<?php echo $email ?>" class="form-control" name="email" placeholder="Seu e-mail" required>
                            </div>
                            <div class="form-group">
                                <label for="senha">Senha</label>
                                <input type="password" class="form-control" name="senha" placeholder="Sua senha" required>
                            </div>
                            <div class="form-group">
                                <label for="senha">Confirmar Senha</label>
                                <input type="password" class="form-control" name="confirmasenha" placeholder="Sua senha" required>
                            </div>
                            <button type="submit" class="btn btn-primary" id="btn">Cadastrar</button>
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
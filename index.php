<?php

    include 'conexao.php';

    $msg = '';
    $email = '';

    if(isset($_POST['email']) && strlen($_POST['email']) > 0){
       
        if(!isset($SESSION)){

            session_start();

            $_SESSION['email'] = $_POST['email'];
            $_SESSION['senha'] = md5(md5($_POST['senha']));

            $sql = 'SELECT * FROM tbl_usuario WHERE email = :email;';
            $stmt = $PDO->prepare($sql);
            $stmt->bindValue(':email', $_SESSION['email']);
            $stmt->execute();
            $row = $stmt->fetch();
            $num_rows = $stmt->rowCount();

            if($num_rows == 0){

                $msg = 'O e-mail '.$_SESSION['email'].' não existe!';

            }elseif($row['senha'] != $_SESSION['senha']){

                $msg = 'Senha incorreta!';
                $email = $_SESSION['email'];

            }elseif($row['status'] == 0){

                $msg = 'E-mail pendente de validação!';
                $email = $_SESSION['email'];

            }elseif($row['senha'] == $_SESSION['senha'] && $row['email'] == $_SESSION['email']){

                $_SESSION['email'] = $row['id'];
                header('Location:dashboard.php');

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
                                <label for="email">E-mail</label>
                                <input type="email" value="<?php echo $email ?>" class="form-control" name="email" placeholder="Seu e-mail" required>
                            </div>
                            <div class="form-group">
                                <label for="senha">Senha</label>
                                <input type="password" class="form-control" name="senha" placeholder="Sua senha" required>
                            </div>
                            <button type="submit" class="btn btn-primary" id="btn">Entrar</button>
                        </form>
                        <div class="b2">
                            <a href="esqueceuSenha.php"><p>Esqueci minha senha</p></a>
                            <a href="cadastraUsuario.php"><p>Fazer cadastro</p></a>
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
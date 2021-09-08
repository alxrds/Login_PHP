<?php

include 'conexao.php';

$token = $_GET['token'];

if($token){

    $sql = 'SELECT * FROM tbl_usuario WHERE token = :token;';
    $stmt = $PDO->prepare($sql);
    $stmt->bindValue(':token', $token);
    $stmt->execute();
    $data = $stmt->fetch();
    $num_rows = $stmt->rowCount();

    if($num_rows > 0){

        $sql = 'UPDATE tbl_usuario SET status = :status WHERE token = :token;';
        $stmt = $PDO->prepare($sql);
        $stmt->bindValue(':token', $token);
        $stmt->bindValue(':status', 1);
        $stmt->execute();

        $msg =  'Usuário ativado!';

    }else{

        $msg =  'Token de ativação inválido!';

    }

}else{

    $msg = 'Token de ativação inválido!';

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
            <div class="b2">
                <a href="index.php"><p>Fazer login</p></a>
            </div>
        </main>
        <footer>
            <p> &copy; <?php echo date('Y') ?> Todos os diteitos reservados </p>
        </footer>
    </div>
</body>
</html>
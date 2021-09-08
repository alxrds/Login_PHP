<?php

    include 'conexao.php';

    session_start();

    if(!$_SESSION || !is_numeric($_SESSION['email'])){

        header('Location:index.php');

    }

    $sql = 'SELECT * FROM tbl_usuario WHERE id = :id;';
    $stmt = $PDO->prepare($sql);
    $stmt->bindValue(':id', $_SESSION['email']);
    $stmt->execute();
    $row = $stmt->fetch();
    $num_rows = $stmt->rowCount();

    if($num_rows > 0){

        $sql = 'UPDATE tbl_usuario SET token = :token WHERE id = :id;';
        $stmt = $PDO->prepare($sql);
        $stmt->bindValue(':token', '');
        $stmt->bindValue(':id', $_SESSION['email']);
        $stmt->execute();

    }

    if($row['nivel_de_acesso'] == 1){

        $menu_admin = true;

    }else{

        $menu_admin = false;

    }

?>

<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Sistema de Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Tamma+2&display=swap" rel="stylesheet">
    <link href="css/dashboard.css" rel="stylesheet">
  </head>

  <body>
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Sistema de Login</a>
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="logout.php">Sair</a>
        </li>
      </ul>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link active" href="#">
                  <span data-feather="home"></span>
                  Dashboard <span class="sr-only">(current)</span>
                </a>
              </li>
              <?php if($menu_admin){?>
              <li class="nav-item">
                <a class="nav-link" href="usuarios.php">
                  <span data-feather="file"></span>
                  Usuários
                </a>
              </li>
              <?php } ?>
            </ul>
          </div>
        </nav>
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Dashboard</h1>
          </div>
        </main>
      </div>
    </div>
  </body>
</html>

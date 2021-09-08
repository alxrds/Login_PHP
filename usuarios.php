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
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Tamma+2&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css">
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
                <a class="nav-link" href="dashboard.php">
                  <span data-feather="home"></span>
                  Dashboard 
                </a>
              </li>
              <?php if($menu_admin){?>
              <li class="nav-item">
                <a class="nav-link active" href="#">
                  <span data-feather="usuarios"></span>
                  Usuários <span class="sr-only">(current)</span>
                </a>
              </li>
              <?php } ?>
            </ul>
          </div>
        </nav>
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Usuários</h1>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="container box">
                <div align="right">
                  <button type="button" id="modal_button" class="btn btn-info">Criar novo usuário</button>
                </div>
                <div id="result" class="table-responsive">
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>

    <div id="customerModal" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Criar novo usuário</h4>
        </div>
        <div class="modal-body">
          <label>Nome</label>
          <input type="text" name="nome" id="nome" class="form-control">
          <br />
          <label>Sobrenome</label>
          <input type="text" name="sobrenome" id="sobrenome" class="form-control">
          <br />
          <label>E-mail</label>
          <input type="email" name="email" id="email" class="form-control">
          <br />
          <label>Senha</label>
          <input type="password" name="senha" id="senha" class="form-control">
          <br />
        </div>
        <div class="modal-footer">
          <input type="hidden" name="id" id="id">
          <input type="submit" name="action" id="action" class="btn btn-success">
          <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        </div>
        </div>
      </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>


    <script>

      $(document).ready(function(){
      fetchUser(); 
      function fetchUser(){
        var action = "Load";
        $.ajax({
        url : "action.php", 
        method:"POST", 
        data:{action:action}, 
        success:function(data){
          $('#result').html(data); 
        }
        });
      }


      $('#modal_button').click(function(){
        $('#customerModal').modal('show');
        $('#nome').val(''); 
        $('#sobrenome').val('');
        $('#email').val(''); 
        $('#senha').val(''); 
        $('.modal-title').text("Criar Novo Usuário"); 
        $('#action').val('Criar'); 
      });

      $('#action').click(function(){
        nome = $('#nome').val(); 
        sobrenome = $('#sobrenome').val();
        email = $('#email').val(); 
        senha = $('#senha').val();  
        id = $('#id').val();  
        action = $('#action').val();  
        if(nome != '' && sobrenome != '' && email != '' && senha != ''){
        $.ajax({
          url : "action.php",    
          method:"POST",     
          data:{nome:nome, sobrenome:sobrenome, email:email, senha:senha, id:id, action:action}, 
          success:function(data){
          alert(data);    
          $('#customerModal').modal('hide'); 
          fetchUser();    
          }
        });
        }
        else
        {
        alert("Preencha todos os dados"); 
        }
      });

      $(document).on('click', '.update', function(){
        var id = $(this).attr("id"); 
        var action = "Select";   
        $.ajax({
        url:"action.php",   
        method:"POST",    
        data:{id:id, action:action},
        dataType:"json",   
        success:function(data){
          $('#customerModal').modal('show');  
          $('.modal-title').text("Editar Usuário"); 
          $('#action').val("Editar");     
          $('#id').val(id);     
          $('#nome').val(data.nome);  
          $('#sobrenome').val(data.sobrenome); 
          $('#email').val(data.email);  
          $('#senha').val(data.senha);   
        }
        });
      });

      $(document).on('click', '.delete', function(){
        var id = $(this).attr("id");
        if(confirm("Deseja realmente remover este usuário?")){
        var action = "Delete"; 
        $.ajax({
          url:"action.php",   
          method:"POST",   
          data:{id:id, action:action}, 
          success:function(data)
          {
          fetchUser();    
          alert(data);    
          }
        })
        }else{
        return false; 
        }
      });
      });

</script>


  </body>
</html>


<?php

include 'conexao.php'; 

if(isset($_POST["action"])){

 if($_POST["action"] == "Load"){

    $statement = $PDO->prepare("SELECT * FROM tbl_usuario WHERE nivel_de_acesso != 1 ORDER BY id DESC");
    $statement->execute();
    $result = $statement->fetchAll();
    $output = '';
    $output .= '
   <table class="table table-bordered">
    <tr>
     <th width="40%">Nome</th>
     <th width="40%">Sobrenome</th>
     <th width="40%">email</th>
     <th width="40%">Nível</th>
     <th width="10%">Editar</th>
     <th width="10%">Excluir</th>
    </tr>
  ';

  if($statement->rowCount() > 0){
   foreach($result as $row){

       if($row['nivel_de_acesso'] == 1){
        $row['nivel_de_acesso'] = 'Administrador';
       }else{
        $row['nivel_de_acesso'] = 'Usuário';
       }

    $output .= '
    <tr>
     <td>'.$row["nome"].'</td>
     <td>'.$row["sobrenome"].'</td>
     <td>'.$row["email"].'</td>
     <td>'.$row["nivel_de_acesso"].'</td>
     <td><button type="button" id="'.$row["id"].'" class="btn btn-primary btn-xs update"><i class="far fa-edit center"></i></button></td>
     <td><button type="button" id="'.$row["id"].'" class="btn btn-danger btn-xs delete"><i class="fas fa-trash center"></i></button></td>
    </tr>
    ';
   }
  }  else  {
   $output .= '
    <tr>
     <td colspan="6" align="center">Dados não encontrados</td>
    </tr>
   ';
  }
  $output .= '</table>';
  echo $output;
 }

 if($_POST["action"] == "Criar"){

    $sql = 'SELECT * FROM tbl_usuario WHERE email = :email;';
    $stmt = $PDO->prepare($sql);
    $stmt->bindValue(':email', $_POST['email']);
    $stmt->execute();
    $row = $stmt->fetch();
    $num_rows = $stmt->rowCount();

    if($num_rows >= 1){

        echo 'Usuário Já existe!';
      
    }else{

        $statement = $PDO->prepare("
        INSERT INTO tbl_usuario (nome, sobrenome, email, senha, nivel_de_acesso, status) 
        VALUES (:nome, :sobrenome, :email, :senha, 0, 1)");
        $result = $statement->execute(
        array(
            ':nome' => $_POST["nome"],
            ':sobrenome' => $_POST["sobrenome"],
            ':email' => $_POST["email"],
            ':senha' => md5(md5($_POST["senha"])))
        );
   }

  if(!empty($result)){
   echo 'Usuário Cadastrado!';

  }

 }


 if($_POST["action"] == "Select"){
  $output = array();
  $statement = $PDO->prepare(
   "SELECT * FROM tbl_usuario 
   WHERE id = '".$_POST["id"]."' 
   LIMIT 1");
  $statement->execute();
  $result = $statement->fetchAll();
  foreach($result as $row){
   $output["nome"] = $row["nome"];
   $output["sobrenome"] = $row["sobrenome"];
   $output["email"] = $row["email"];
   $output["senha"] = $row["senha"];
  }
  echo json_encode($output);
 }

 if($_POST["action"] == "Editar"){

    $statement = $PDO->prepare(
    "UPDATE tbl_usuario 
    SET nome = :nome, sobrenome = :sobrenome, email = :email, senha = :senha 
    WHERE id = :id
    "
    );
    $result = $statement->execute(
    array(
        ':nome' => $_POST["nome"],
        ':sobrenome' => $_POST["sobrenome"],
        ':email' => $_POST["email"],
        ':senha' => md5(md5($_POST["senha"])),
        ':id'   => $_POST["id"])
    );

  if(!empty($result)){
   echo 'Usuário Editado!';
  }
 }

 if($_POST["action"] == "Delete"){
  $statement = $PDO->prepare(
   "DELETE FROM tbl_usuario WHERE id = :id"
  );
  $result = $statement->execute(
   array(
    ':id' => $_POST["id"]
   )
  );
  if(!empty($result)){
   echo 'Usuário Removido!';
  }
 }

}

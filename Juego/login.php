<?php
  session_start();
  $user=($_POST["username"])??"";
  $password=($_POST["password"])??"";
  $error=false;
// aquí NO usamos MVC ya que es muy básica la acción
  if (isset ($_POST["username"],$_POST["password"])){
    //podríamos usar el controlador pero como sólo es hacer una consulta
      require_once "models/userModel.php";
      $userModel= new userModel();
      $usuario=$userModel->login($user, $password);
      if ($usuario!=null){// Usuario correcto
        $_SESSION["username"]=$usuario;
        header("location:index.php");
        exit ();
      }
      //SI NO EXISTE EL USUARIO SE HA PRODUCIDO UN ERROR
      $error=true;
  }
  $msg="";$visibilidad="";$style="";
  if ($error){
    $msg="Error, Usuario o Password Incorrectos";
    $visibilidad="visible";
    $style="alert-danger";
  }
  if(isset($_GET["session"])&&($_GET["session"]=="logout")){
    $msg="Fin de Sesion";
    $visibilidad="visible";
    $style="alert-success";
  }
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <!-- Custom styles for this template -->
    <link href="assets/css/login/sign-in.css" rel="stylesheet">
    <link href="assets/css/sign-in.css" rel="stylesheet">

  </head>
  <body class="d-flex align-items-center py-4 bg-body-tertiary">
<main class="form-signin w-100 m-auto">
  <div class="background">
    <form action="<?= $_SERVER['PHP_SELF']?>" method="POST">
      <!-- Mensaje de alerta-->
      <div class="alert <?=$style.' '.$visibilidad?>" ><?=$msg?></div>
      <img class="logo" src="./assets/img/altlogo.png" alt="">
      <h1 class="h3 mb-3 fw-normal">Inicio de Sesión</h1>
      <div class="form-floating">
        <!-- <input type="usuario" class="form-control" id="usuario" name="usuario"  placeholder="name@example.com"> -->
        <input type="username" class="form-control" id="username" name="username" value="<?= $user?>" placeholder="name@example.com">
        <label for="username">Usuario</label>
      </div>
      <div class="form-floating">
        <input type="password" class="form-control" id="password" name="password"   placeholder="Password">
        <label for="password">Password</label>
      </div>
      <div class="form-check text-start my-3">
        <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
        <label class="form-check-label" for="flexCheckDefault">
          Recuerdame
        </label>
      </div>
      <button class="btn btn-primary w-100 py-2" type="submit">Sign in</button>
    </form>
  </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>
</html>
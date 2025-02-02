<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <title>APP MVC Y PDO </title>
  <!-- Bootstrap core CSS -->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <!-- Custom styles for this template -->
  <link href="assets/css/dashboard.css" rel="stylesheet">
  <link href="assets/css/404.css" rel="stylesheet">
  <!-- ACA METEMOS NUESTROS CSS -->
  <link href="assets/css/alt/custom.css" rel="stylesheet">
  <script defer src="views/layout/js/head.js"></script>
</head>
<body>
<!-- <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="index.php"><img src="../Juego/assets/img/logo.png" alt=""></a> -->
  <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
    <div class="imgContainer"><a class="px-3" href="index.php"><img src="../Juego/assets/img/logo.png" alt=""></a></div>

    <!-- WEBSOCKET -->
    <p>Cliente WebSocket</p>
    <input type="text" id="mensaje" placeholder="Escribe un mensaje">
    <button id="sendMessage" onclick="enviarMensaje()">Enviar</button>
    <ul id="mensajes"></ul>
    <!-- ---------------------- -->

    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-nav">
      <div class="nav-item text-nowrap">
        <a class="nav-link px-3 disabled" href=""> Usuario Conectado: <?= $_SESSION["username"]->username?></a>
      </div>
    </div>
    <div class="navbar-nav">
      <div class="nav-item text-nowrap">
        <a class="nav-link px-3" id=logout href="logout.php">Sign out</a>
      </div>
    </div>
    <div class="navbar-nav">
      <div class="nav-item text-nowrap">
        <p style="color:white; margin: 0;"><img src="../Juego/assets/img/evolutions.png" alt="" width="30px"> x<?=$_SESSION["username"]->digievolutions?></p>
      </div>
    </div>
    <div class="navbar-nav">
      <div class="nav-item text-nowrap">
        <a class="nav-link px-3" id=profilePicture href="index.php?tabla=user&accion=ver&id=<?=$_SESSION['username']->id?>"><img src="../Administrador/assets/img/users/<?= $_SESSION["username"]->username?>/profile.png" alt=""></a>
      </div>
    </div>
  </header>
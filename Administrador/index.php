<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digimon Admin</title>
</head>
<body>
</body>
</html>

<?php
ob_start();
require_once "config/sessionControl.php";
require_once("router/router.php");
require_once("views/layout/head.php");

$vista = router();
?>
<div class="container-fluid">
    <div class="row">
        <?php
        require_once "views/layout/navbar.php";
        if (!file_exists($vista)) echo "Error, REVISA TUS RUTAS";
        else require_once($vista);
        ?>
    </div>
</div>
<?php
require_once("views/layout/footer.php");
ob_end_flush();
?>

IMPORTANTE:

1) Tener npm instalado (hacer npm install)
2) En linux ejecutar todo con sudo (sudo node ./server.js)
3) Cambiar en ./Juego/layout/js/head.js los valores URL y PORT dependiendo de la IP y el puerto en el que trabaja el **SERVIDOR DE NODE**.
4) Cambiar en ./ServidorWSS/server.js lo mismo que en el punto 3).
5) Para acceder, se tiene que usar **http**
    - Ejemplo: http://172.30.7.251:81/ProyectoDigimon-main/Juego/index.php


$Datos = $_POST["vaina"];
$foto = $_FILES["iamgen"];

var_dump($foto);
$temporal = $_FILES["foto1"]["tmp_name"];
$destino = "imagenes/subidas/victoria.jpg";
if (move_uploaded_file($temporal, $destino)) {
  echo "Se subio bien";
else {
  echo "No se subio";
}

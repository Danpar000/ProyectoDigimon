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

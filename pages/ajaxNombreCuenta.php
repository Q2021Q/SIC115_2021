<?php
//Codigo que muestra solo los errores exceptuando los notice.
error_reporting(E_ALL & ~E_NOTICE);
?>
<?php
$codigo=$_REQUEST["codigo"];
if ($codigo=="") {
  echo '<input type="text" class="form-text" id="nombreCuenta" name="nombreCuenta" value="Vacio." >
  <span class="bar"></span>
  <label>Cuenta</label>';
}else {
  include '../config/conexion.php';
  $result = $conexion->query("select * from catalogo where codigocuenta=".$codigo);
  $nombre=$codigo;
  if ($result) {
    while ($fila = $result->fetch_object()) {
      $nombre=$fila->nombrecuenta;
      echo "<input type='text' class='form-text' id='nombreCuenta' name='nombreCuenta' value='".$nombre."' >
      <span class='bar'></span>
      <label>Cuenta</label>";
      echo "<input type='hidden' class='form-text' id='bandera2' name='bandera2' value='".$fila->idcatalogo."' >
      <span class='bar'></span>
      <label>Cuenta</label>";

    }
  }else {
    echo "<input type='text' class='form-text' id='nombreCuenta' name='nombreCuenta' value='error consulta' >
    <span class='bar'></span>
    <label>Cuenta</label>";
  }
  // if ($result->num_rows<1) {
  //   echo '<input type="text" class="form-text" id="nombreCuenta" name="nombreCuenta" value="'.$nombre.'" >
  //   <span class="bar"></span>
  //   <label>Cuenta</label>';
  // }

}



 ?>

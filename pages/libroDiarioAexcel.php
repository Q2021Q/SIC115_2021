<?php 
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename= LibroDiario.xls");

 ?>

<?php
session_start();
if($_SESSION["logueado"] == TRUE) {
  include '../config/conexion.php';
  $result = $conexion->query("select * from anio where estado=1");
  if($result)
  {
    while ($fila=$result->fetch_object()) {
      $anioActivo=$fila->idanio;
    }
  }
 ?>
  

       

                    <center>
                      <h3>Libro Diario</h3>
                    </center>
                    <table width="80%">
                    <thead>
                      <tr>
                      <th>Fecha</th>
                        <th>Codigo</th>
                        <th>Concepto</th>
                        <th>Debe</th>
                        <th>Haber</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php

$result = $conexion->query("select * from partida where idanio='".$anioActivo."' order by idpartida ASC");
if ($result) {
  while ($fila = $result->fetch_object()) {

      echo "<th>" . $fila->fecha . "</th>";
      echo "<td>Partida #" . $fila->idpartida . "</td>";
  
      echo "</tr>";
      
      $idpartida=$fila->idpartida;
      $result2 = $conexion->query("select * from ldiario where idpartida='".$idpartida."'order by debe DESC");

      if ($result2) {

        while ($fila2 = $result2->fetch_object()) {
          $cuenta=$fila2->idcatalogo;
          $debe=$fila2->debe;
          $haber=$fila2->haber;

          //para mostrar la Cuenta
          $result3 = $conexion->query("select * from catalogo where idcatalogo=".$cuenta);
          if ($result3) {
            while ($fila3 = $result3->fetch_object()) {
              $codigocuenta=$fila3->codigocuenta;
              $nombrecuenta=$fila3->nombrecuenta;
              echo "<tr>";
                echo "<th> </th>";
                echo "<th> " . $codigocuenta . "</th>";
                if ($debe>=$haber) {
                  echo "<th>" . $nombrecuenta . "</th>";
                }else {

                  echo "<th>" . $nombrecuenta . "</th>";
                }
                if ($debe==0) {
                    echo "<th></th>";
                }else {
                  echo "<th>$ " . $debe . "</th>";
                }
                if ($haber==0) {
                    echo "<th></th>";
                }else {
                  echo "<th>$ " . $haber . "</th>";
                }

              echo "</tr>";
            }
          }
        }
      }
      echo "<tr>";
      echo "<th> </th>";
      echo "<th> </th>";
      echo "<th>V/ ".$fila->concepto."</th>";
      echo "<th> </th>";
      echo "<th> </th>";
      echo "</tr>";
  }
}
?>
                
<?php
}else {
header("Location: ../index.php");
}
?>

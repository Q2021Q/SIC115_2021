
<?php 
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename= archivo.xls");

 ?>

<?php
session_start();

if($_SESSION["logueado"] == TRUE) {
include "../config/conexion.php";
$result = $conexion->query("select * from anio where estado=1");
if($result)
{
  $fila = $result->fetch_object();
  $anioActivo = $fila->idanio;
  /*while ($fila=$result->fetch_object()) {
    $anioActivo=$fila->idanio;
  }*/
}

 ?>

                  <div class="panel-heading" align="left-bg">
                    <center>
                       <h1>Libro Mayor</h1>
                    </center>
                     
                      <!--<h4>Mayorizacion de Nivel <?php // echo $nivelMayorizacion; ?></h4>
                      <input type="hidden" name="anioActivo" id="anioActivo" value="<?php echo $anioActivo; ?>">
                      <input type="hidden" name="nivel" id="nivel" value="<?php //echo $nivelMayorizacion; ?>">-->
                  </div>



                    <table class="default" width="80%" >
                    <thead>
                      <tr>
                         <th></th>
                        <th>Fecha</th>                      
                        <th >Concepto</th>
                        <th >P/F</th>
                        <th >Debe</th>
                        <th >Haber</th>
                        <th >Saldo</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
              
                    $saldo=0;
                    $result = $conexion->query("select idcatalogo as id,saldo, nombrecuenta as nombre, codigocuenta as codigo from catalogo where nivel=3 order by codigocuenta asc");
                    if ($result) {
                        while ($fila = $result->fetch_object()) {
                          $nombre=$fila->nombre;
                          $id=$fila->id;
                          $codigo=$fila->codigo;
                          //obtener total de caracteres del codigo segun el nivelcuenta
                          //$loncadena=strlen($codigo);
                          //inicio de la consulta para encontrar las cuentas que son subcuentas de la cuenta anterior
                          $resultSubcuenta= $conexion->query("Select c.nombrecuenta as nombre, c.codigocuenta as codigo, p.idpartida as npartida, p.concepto, p.fecha as fecha, l.debe as debe, l.haber as haber FROM catalogo c INNER JOIN ldiario l ON c.idcatalogo = l.idcatalogo INNER JOIN partida p ON l.idpartida = p.idpartida WHERE  SUBSTRING(c.codigocuenta, 1,4) = '".$codigo."' AND p.idanio='".$anioActivo."' ORDER BY fecha ASC");
                          if ($resultSubcuenta) {
                              if (($resultSubcuenta->num_rows)<1) {
                            }else {
                              echo "<tr>
                              <td class='bg-success' colspan='3' align='CENTER'>.</td></tr>";
                               echo "<tr>
                              <td class='bg-success' colspan='3' align='CENTER'>.</td></tr>";
                               echo "<tr>
                              <td class='bg-success' colspan='3' align='CENTER'>.</td></tr>";


                              echo "<tr>
                              <td class='bg-success' colspan='2' align='RIGHT'>       CUENTA ".$codigo."</td>      
                              <td class='bg-success' colspan='4' align='CENTER'>".$nombre."</td></tr>";
                              while ($fila2 = $resultSubcuenta->fetch_object()) {
                                echo "<tr>";


                                echo "<th>".''."</th>";
                                echo "<th>".$fila2->fecha."</th>";
                                echo "<th>".$fila2->concepto."</th>";
                                echo "<th class='bg-info'>".$fila2->npartida."</th>";
                                echo "<th class='bg-info'>".$fila2->debe."</th>";
                                echo "<th class='bg-danger'>".$fila2->haber."</th>";
                                if ($fila->saldo=="DEUDOR") {
                                  $saldo=$saldo+($fila2->debe)-($fila2->haber);
                                }else {
                                  $saldo=$saldo-($fila2->debe)+($fila2->haber);
                                }

                                echo "<th class='bg-warning'>".$saldo."</th>";
                                echo "</tr>";
                              }
                              $saldo=0;
                            }
                          }else {
                            msg("Error");
                          }
                        }//cierre while consulta 1
                      }//cierre result consulta 1
                    //fin consulta por nivel
                     ?>
                    </tbody>
                      </table>

<?php

}
?>

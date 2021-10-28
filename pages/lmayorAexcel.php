
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
              
        
                  <div class="panel-heading">
                    <center>
                      <h1>Libro Mayor</h3>
                   
                    </center>
                  </div>
                    <table  width="100%">
                    <thead>
                      <tr>
                      <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th ></th>
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
                          $resultSubcuenta= $conexion->query("Select c.nombrecuenta as nombre, c.codigocuenta as codigo, C.saldo as saldo, p.idpartida as npartida, p.concepto, p.fecha as fecha, l.debe as debe, l.haber as haber FROM catalogo c INNER JOIN ldiario l ON c.idcatalogo = l.idcatalogo INNER JOIN partida p ON l.idpartida = p.idpartida WHERE  SUBSTRING(c.codigocuenta, 1,4) = '".$codigo."' AND p.idanio='".$anioActivo."' ORDER BY fecha ASC");
                          if ($resultSubcuenta) {
                              if (($resultSubcuenta->num_rows)>0) {

                                echo "<tr>
                              <td class='bg-success' colspan='3' align='CENTER'>.</td></tr>";
                              echo "<tr>
                              <td class='bg-success' colspan='3' align='CENTER'>.</td></tr>";
                              echo "<tr>
                              <td class='bg-success' colspan='3' align='CENTER'>.</td></tr>";

                                echo <<< END
                                <td class='bg-success' colspan='3' align='CENTER'>Cuenta $codigo</td>      
                                <td class='bg-success' colspan='3' align='LEFT'>$nombre</td></tr>
                                END;

                                echo "<tr>
                              <th class='bg-success' colspan='1' align='CENTER'>CONCEPTO</th>
                              <th class='bg-success' colspan='1' align='CENTER'>FECHA</th>
                              <th class='bg-success' colspan='1' align='CENTER'>DEBE</th>
                              <th class='bg-success' colspan='1' align='CENTER'>HABER</th>
                              <th class='bg-success' colspan='1' align='CENTER'>SALDO</th>
                              </tr>";

                                $fecha = '0000-00-00';
                                $debe_sub = 0;
                                $haber_sub = 0;
                                $saldofinal = 0;

                                $subcuenta = $resultSubcuenta->fetch_object();
                                
                                //Almacenando primer objeto de la consulta
                                $fecha = $subcuenta->fecha;
                                $debe_sub = $subcuenta->debe;
                                $haber_sub = $subcuenta->haber;
                                if($subcuenta->saldo == "DEUDOR"){
                                  $saldofinal = $saldofinal + ($subcuenta->debe)-($subcuenta->haber);
                                }else{
                                  $saldofinal = $saldofinal - ($subcuenta->debe)+($subcuenta->haber);
                                }
                                
                                while ($subcuenta = $resultSubcuenta->fetch_object()){
                                  if ($fecha == $subcuenta->fecha){
                                    $debe_sub = $debe_sub + $subcuenta->debe;
                                    $haber_sub = $haber_sub + $subcuenta->haber;
                                    if($subcuenta->saldo == "DEUDOR"){
                                      $saldofinal = $saldofinal + ($subcuenta->debe)-($subcuenta->haber);
                                    }else{
                                      $saldofinal = $saldofinal - ($subcuenta->debe)+($subcuenta->haber);
                                    }
                                  }else{
                                    echo <<< END
                                    <tr>
                                    <th>Movimiento dia</th>
                                    <th>$fecha</th>
                                    <th class='bg-info'>$debe_sub</th>
                                    <th class='bg-danger'>$haber_sub</th>
                                    <th class='bg-warning'>$saldofinal</th>
                                    </tr>
                                    END;

                                    $debe_sub = $subcuenta->debe;
                                    $haber_sub = $subcuenta->haber;
                                    if($subcuenta->saldo == "DEUDOR"){
                                      $saldofinal = $saldofinal + ($subcuenta->debe)-($subcuenta->haber);
                                    }else{
                                      $saldofinal = $saldofinal - ($subcuenta->debe)+($subcuenta->haber);
                                    }
                                    $fecha = $subcuenta->fecha;
                                  }
                                }

                                echo <<< END
                                    <tr>
                                    <th>Movimiento dia</th>
                                    <th>$fecha</th>
                                    <th class='bg-info'>$debe_sub</th>
                                    <th class='bg-danger'>$haber_sub</th>
                                    <th class='bg-warning'>$saldofinal</th>
                                    </tr>
                                    END;

                            }else {
                            
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
      

<!

<?php

}else {
header("Location: ../index.php");
}
?>

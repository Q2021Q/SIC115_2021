<?php 
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename= balance.xls");

 ?>

<?php
session_start();

if($_SESSION["logueado"] == TRUE) {
  //$inventariofinal=$_REQUEST["if"];
  $inv_get=$_REQUEST["if"] ?? "";
  $saveanio = $_REQUEST['saveanio'] ?? "";
  include '../config/conexion.php';
  $result = $conexion->query("select * from anio where estado=1");
  if($result)
  {
    while ($fila=$result->fetch_object()) {
      $anioActivo=$fila->idanio;
      $inv = $fila->inventariof;
    }
  }
  if($inv_get == "" && $inv>-1){
    $inventariofinal = $inv;
  }else{
    $inventariofinal = $inv_get;
  }
  if($inventariofinal>-1){
    //Calculo de fecha minima
    $result = $conexion->query("select MIN(fecha) as fecha from partida where idanio=".$anioActivo);
    if ($result) {
      while ($fila = $result->fetch_object()) {
        $fechaMinima=$fila->fecha;
        $fechaMinima=date("d-m-Y",strtotime($fechaMinima));
      }
    }
    //Calculo de fecha maxima
    $result2 = $conexion->query("select MAX(fecha) as fecha from partida where idanio=".$anioActivo);
    if ($result2) {
      while ($fila = $result2->fetch_object()) {
        $fechaMaxima=$fila->fecha;
        $fechaMaximaDia =date("d",strtotime($fechaMaxima));
        $fechaMaximaMes = date("m",strtotime($fechaMaxima));
        //Identificar mes de ultima partida registrada
        switch($fechaMaximaMes){
            case 1: $fechaMaximaMes = "Enero"; break;
            case 2: $fechaMaximaMes = "Febrero"; break;
            case 3: $fechaMaximaMes = "Marzo"; break;
            case 4: $fechaMaximaMes = "Abril"; break;
            case 5: $fechaMaximaMes = "Mayo"; break;
            case 6: $fechaMaximaMes = "Junio"; break;
            case 7: $fechaMaximaMes = "Julio"; break;
            case 8: $fechaMaximaMes = "Agosto"; break;
            case 9: $fechaMaximaMes = "Septiembre"; break;
            case 10: $fechaMaximaMes = "Octubre"; break;
            case 11: $fechaMaximaMes = "Noviembre"; break;
            case 12: $fechaMaximaMes = "Diciembre"; break;
        }
      }
    }
    /*------------------ ACTIVO CORRIENTE ------------------*/
    //Calculo de efectivo y equivalentes
    $efectivoYER = $conexion->query("select c.nombrecuenta, c.codigocuenta, p.idpartida, p.concepto, p.fecha, l.debe, l.haber from catalogo c INNER JOIN ldiario l ON c.idcatalogo = l.idcatalogo inner join partida p on l.idpartida = p.idpartida WHERE substring(c.codigocuenta,1,4)='1101' and p.idanio='".$anioActivo."'");
    if($efectivoYER){
      $saldoEYE=0;
      while($fila=$efectivoYER->fetch_object()){
        $saldoEYE=$saldoEYE+($fila->debe)-($fila->haber);
      }
    }
    //Calculo de cuentas por cobrar comerciales
    $cuentaPCC = $conexion->query("select c.nombrecuenta, c.codigocuenta, p.idpartida, p.concepto, p.fecha, l.debe, l.haber from catalogo c INNER JOIN ldiario l ON c.idcatalogo = l.idcatalogo inner join partida p on l.idpartida = p.idpartida WHERE substring(c.codigocuenta,1,4)='1103' and p.idanio='".$anioActivo."'");
    if($cuentaPCC){
      $saldoPCC = 0;
      while($fila=$cuentaPCC->fetch_object()){
        $saldoPCC=$saldoPCC+($fila->debe)-($fila->haber);
      }
    }
    //Calculo de IVA Credito Fiscal
    $IVACF = $conexion->query("select c.nombrecuenta, c.codigocuenta, p.idpartida, p.concepto, p.fecha, l.debe, l.haber from catalogo c INNER JOIN ldiario l ON c.idcatalogo = l.idcatalogo inner join partida p on l.idpartida = p.idpartida WHERE substring(c.codigocuenta,1,4)='1109' and p.idanio='".$anioActivo."'");
    if($IVACF){
      $saldoIVACF = 0;
      while($fila=$IVACF->fetch_object()){
        $saldoIVACF=$saldoIVACF+($fila->debe)-($fila->haber);
      }
    }
    /*ACTIVO NO CORRIENTE */

    /*PASIVO CORRIENTE*/
    //Calculo de Cuentas por pagar comerciales
    $cuentasPP = $conexion->query("select c.nombrecuenta, c.codigocuenta, p.idpartida, p.concepto, p.fecha, l.debe, l.haber from catalogo c INNER JOIN ldiario l ON c.idcatalogo = l.idcatalogo inner join partida p on l.idpartida = p.idpartida WHERE substring(c.codigocuenta,1,4)='2101' and p.idanio='".$anioActivo."'");
    if($cuentasPP){
      $saldoCPP = 0;
      while($fila=$cuentasPP->fetch_object()){
        $saldoCPP=$saldoCPP+($fila->haber)-($fila->debe);
      }
    }
    //Calculo de IVA debito fiscal
    $IVADF = $conexion->query("select c.nombrecuenta, c.codigocuenta, p.idpartida, p.concepto, p.fecha, l.debe, l.haber from catalogo c INNER JOIN ldiario l ON c.idcatalogo = l.idcatalogo inner join partida p on l.idpartida = p.idpartida WHERE substring(c.codigocuenta,1,4)='2108' and p.idanio='".$anioActivo."'");
    if($IVADF){
      $saldoIVADF = 0;
      while($fila=$IVADF->fetch_object()){
        $saldoIVADF=$saldoIVADF+($fila->haber)-($fila->debe);
      }
    }

    
    
 ?>

<body id="mimin" class="dashboard">


    <!-- start: Content -->
     
        <input type="hidden" name="bandera" id="bandera">
        <input type="hidden" name="baccion" id="baccion">
                <center>
                  <h3>Empresa Crisales</h3>
                  <h3>Balance General del periodo</h3>
                  <h3>Al <?php echo $fechaMaximaDia." de ".$fechaMaximaMes." del ".$anioActivo?></h3>
                </center>
                    <table width="70%">
                      <thead>
                        <tr>
                          <th>CONCEPTO</th>
                          <th>DEBE</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <th>ACTIVO</th>
                        </tr>
                        <tr class="bg-light">
                          <th>ACTIVO CORRIENTE</th>
                        </tr>
                        <tr class="bg-light">
                          <td align="left">EFECTIVO Y EQUIVALENTES</td>
                          <th ><?php echo "$".$saldoEYE ?></th>
                        </tr>
                        <tr class="bg-light">
                          <td align="left">CUENTAS POR COBRAR COMERCIALES</td>
                          <th<?php echo "$".$saldoPCC ?></th>
                        </tr>
                        <tr class="bg-light">
                          <td align="left">IVA CREDITO FISCAL</td>
                          <th><?php echo "$".$saldoIVACF ?></th>
                        </tr>
                        <tr class="bg-light">
                          <td align="left">INVENTARIO FINAL</td>
                          <th><?php echo "$".$inventariofinal ?></th>
                        </tr>
                        <tr>
                          <th>TOTAL ACTIVO CORRIENTE</th>
                          <th><?php echo "$".$saldoActivoCorriente=$saldoEYE+$saldoPCC+$saldoIVACF+$inventariofinal?></th>
                        </tr>
                        <tr class="bg-light">
                          <th>ACTIVO NO CORRIENTE</th>
                        </tr>
                        <tr>
                          <th>TOTAL ACTIVO NO CORRIENTE</th>
                          <th><?php echo "$".$saldoActivoNC=0?></th>
                        </tr>
                        <tr>
                          <th>TOTAL ACTIVO</th>
                          <th><?php echo "$".$saldoTotalActivos=$saldoActivoCorriente+$saldoActivoNC?></th>
                        </tr>
                        <tr><th>.</th></tr>
                        <tr><th>.</th></tr>
                        <tr><th>.</th></tr>
                        <tr class='bg-info'>
                          <th>PASIVO</th>
                        </tr>
                        <tr class='bg-light'>
                          <th>PASIVO CORRIENTE</th>
                        </tr>
                        <tr class="bg-light">
                          <td align="left">CUENTAS POR PAGAR COMERCIALES</td>
                          <th><?php echo "$".$saldoCPP?></th>
                        </tr>
                        <tr class="bg-light">
                          <td align="left">IVA DEBITO FISCAL</td>
                          <th><?php echo "$".$saldoIVADF?></th>
                        </tr>
                        <tr class='bg-light'>
                          <th>TOTAL PASIVO CORRIENTE</th>
                          <th><?php echo "$".$saldoPasivoC=$saldoCPP+$saldoIVADF?></th>
                        </tr>
                        <tr class="bg-light">
                          <th>PASIVO NO CORRIENTE</th>
                        </tr>
                        <tr class='bg-light'>
                          <th>TOTAL PASIVO NO CORRIENTE</th>
                          <th><?php echo "$".$saldoPasivoNC=0?></th>
                        </tr>
                        <tr class="bg-info">
                          <th>TOTAL PASIVO</th>
                          <th><?php echo "$".$saldoTotalPasivos=$saldoPasivoC+$saldoPasivoNC?></th>
                        </tr>
                         <tr><th>.</th></tr>
                        <tr><th>.</th></tr>
                        <tr><th>.</th></tr>
                        <tr class="bg-info">
                          <th>PATRIMONIO</th>
                        </tr>
                        <tr class="bg-light">
                          <td align="left">RESERVA LEGAL</td>
                          <td align="center"></td>
                        </tr>
                        <tr class="bg-light">
                          <td align="left">UTILIDAD DEL EJERCICIO</td>
                          <td align="center"></td>
                        </tr>
                        <tr class="bg-info">
                          <th align="center">TOTAL PATRIMONIO</td>
                          <td align="center"></td>
                        </tr>
                        <tr class="bg-info">
                          <th>TOTAL PASIVO + PATRIMONIO</th>
                          <td align="center"></td>
                        </tr>
                      <?php
                        $result = $conexion->query("select c.nombrecuenta as nombre,c.idcatalogo as id, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','2') as codigocorto FROM catalogo as c where SUBSTRING(c.codigocuenta,1,'2')='13' and c.nivel='3' ORDER BY c.codigocuenta ASC");
                        
                        
                        // PATRIMONO
                        $result = $conexion->query("select c.nombrecuenta as nombre,c.idcatalogo as id, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','2') as codigocorto FROM catalogo as c where SUBSTRING(c.codigocuenta,1,'2')='31' and c.nivel='3' ORDER BY c.codigocuenta ASC");
                        
                        ?>
                      </tbody>
                    </table>
  
</body>




<?php
    }

}else {
header("Location: ../index.php");
}
?>

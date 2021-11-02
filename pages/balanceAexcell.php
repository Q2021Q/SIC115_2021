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

    //Calculo de saldos en cuentas de activos, pasivos y patrimonio

    $arregloSeccionPrincipal[] = array(1,"ACTIVOS");
    $arregloSeccionPrincipal[] = array(2,"PASIVOS");
    $arregloSeccionPrincipal[] = array(3,"PATRIMONIO");

    for($i=1;$i<4;$i++){

      //obtención de cuentas de nivel 2, grupos financieros
      $consulta ="SELECT codigocuenta, nombrecuenta FROM catalogo WHERE codigocuenta LIKE '".$i."_' order by codigocuenta asc";
      $gruposfinan = $conexion->query($consulta);
      if($gruposfinan){
        while($grupo = $gruposfinan->fetch_object()){
          $arregloGrupo[] = array($grupo->codigocuenta,$grupo->nombrecuenta);
        }
      }

      $consulta = "SELECT codigocuenta, nombrecuenta FROM catalogo WHERE codigocuenta LIKE '".$i."___' order by codigocuenta asc";
      $cuentas = $conexion->query($consulta);
      if($cuentas){
        while($cuenta = $cuentas->fetch_object()){
          $saldoCuenta = 0;
          $codigoCuenta = $cuenta->codigocuenta;
          $nombreCuenta = $cuenta->nombrecuenta;

          //obteniendo subcuentas de la cuenta
          $consulta = "Select l.debe, l.haber, c.saldo as saldo FROM catalogo c INNER JOIN ldiario l ON c.idcatalogo = l.idcatalogo INNER JOIN partida p ON l.idpartida = p.idpartida WHERE  SUBSTRING(c.codigocuenta, 1,4) = '".$codigoCuenta."' AND p.idanio='".$anioActivo."'";
          $subcuentas = $conexion->query($consulta);
          if($subcuentas){
            while($sub = $subcuentas->fetch_object()){
              if($sub->saldo == 'DEUDOR'){
                $saldoCuenta = $saldoCuenta + ($sub->debe) - ($sub->haber);
              }else{
                $saldoCuenta = $saldoCuenta - ($sub->debe) + ($sub->haber);
              }
            }
          }
          $tablaCuentas[] = array($codigoCuenta,$nombreCuenta,$saldoCuenta);
        }//fin de ciclo de cuentas

      }//fin de saldos de cuentas
    }//fin de de calculo de saldos por seccion principal
 ?>






    <!-- start: Content -->
       
                <center>
                  <h3>Empresa Crisales</h3>
                  <h3>Balance General del periodo</h3>
                  <h3>Al <?php echo "{$fechaMaximaDia} de {$fechaMaximaMes} del {$anioActivo}" ?></h3>
                </center>
                    <table width="80%">
                      <thead>
                        <tr>
                          <td>CONCEPTO</td>
                          <th>Debe</th>
                        </tr>
                      </thead>
                      <tbody>
                        
                        <?php
                          //impresion de la seccion
                          foreach($arregloSeccionPrincipal as $seccion){
                            $saldoSeccion = 0;
                            echo <<<END
                            <tr class="bg-info">
                            <td colspan='2' align='center'>$seccion[1]</td>
                            </tr>
                            END;

                            //impresion del grupo
                            foreach($arregloGrupo as $grupo){
                              $saldoGrupo = 0;
                              $sec = substr($grupo[0],0,1);
                              if($sec==$seccion[0]){
                                echo <<<END
                                <tr class="bg-light">
                                <td colspan='2' align='center'>$grupo[1]</td>
                                </tr>
                                END;

                                //impresion de la cuenta de control
                                foreach($tablaCuentas as $control){
                                  $gru = substr($control[0],0,2);
                                  if($gru == $grupo[0]){
                                    if($control[2]!=0){
                                      echo <<<END
                                      <tr class="bg-light">
                                      <td align="left">$control[1]</td>
                                      <td align="center">$control[2]</td>
                                      </tr>
                                      END;
                                      $saldoGrupo = $saldoGrupo + $control[2];
                                    }
                                  }else{
                                    continue;
                                  }
                                }

                                echo <<<END
                                <tr class='bg-light'>
                                <td align='center'>TOTAL $grupo[1]</td>
                                <td align='center'>$saldoGrupo</td>
                                </tr>
                                END;
                                $saldoSeccion = $saldoSeccion + $saldoGrupo;
                              }else{
                                continue;
                              }
                            }

                            echo <<<END
                            <tr class="bg-success">
                            <td align="center">TOTAL $seccion[1]</td>
                            <td align="center">$saldoSeccion</td>
                            </tr>
                            END;
                          }
                        ?>
                      </tbody>
                    </table>
  



<?php
    }

}else {
header("Location: ../index.php");
}
?>

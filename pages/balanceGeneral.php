<?php
//Codigo que muestra solo los errores exceptuando los notice.
error_reporting(E_ALL & ~E_NOTICE);
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
<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta name="description" content="Miminium Admin Template v.1">
  <meta name="author" content="Isna Nur Azis">
  <meta name="keyword" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema Contable</title>

  <!-- start: Css -->
  <link rel="stylesheet" type="text/css" href="../asset/css/bootstrap.min.css">

  <!-- plugins -->
  <link rel="stylesheet" type="text/css" href="../asset/css/plugins/font-awesome.min.css"/>
  <link rel="stylesheet" type="text/css" href="../asset/css/plugins/datatables.bootstrap.min.css"/>
  <link rel="stylesheet" type="text/css" href="../asset/css/plugins/animate.min.css"/>
  <link href="../asset/css/style.css" rel="stylesheet">
  <!-- end: Css -->

  <link rel="shortcut icon" href="../asset/img/logomi.jpg">
  <script type="text/javascript">
    function modify(id){
      //alert("entra");
      document.getElementById('bandera').value='enviar';
      document.getElementById('baccion').value=id;
      document.turismo.submit();
    }
    function confirmar(id){
      if (confirm("!!Advertencia!! Desea Eliminar Este Registro?")) {
        document.getElementById('bandera').value='desaparecer';
        document.getElementById('baccion').value=id;
        alert(id);
        document.turismo.submit();
      }else{
        alert("No entra");
      }
    }
    //funcion para exportar la tabla del catalogo a excell
    function balanceExcell(){
      var inv=document.getElementById("if").value;
      var anio=document.getElementById("anioActivo").value;
      const ventana = window.open("reportes/BalanceExecell.php?if="+inv+"&anio="+anio+"","_blank");
    }
    function balancePDF(){
      var inv=document.getElementById("if").value;
      var anio=document.getElementById("anioActivo").value;
      const ventana = window.open("reportes/BalancePDF.php?if="+inv+"&anio="+anio+"","_blank");
    }
    function diarioPDF(){
      var anio=document.getElementById("anioActivo").value;
      const ventana = window.open("reportes/librodiarioPDF.php?anio="+anio+"","_blank");
    }
    function cerrarVentana(ventana){
      ventana.close();
    }
  </script>
</head>

<body id="mimin" class="dashboard">
  <?php include "header.php"?>

  <div class="container-fluid mimin-wrapper">

    <?php include "menu.php";?>


    <!-- start: Content -->
    <div id="content">
      <div class="panel box-shadow-none content-header">
        <div class="panel-body">
          <div class="col-md-12">
            <h3 class="animated fadeInLeft">Balance General</h3>
            <p class="animated fadeInDown">Tabla <span class="fa-angle-right fa"></span> Datos de la tabla.</p>
          </div>
        </div>
      </div>
      <form id="turismo" name="turismo" action="" method="post">
        <input type="hidden" name="bandera" id="bandera">
        <input type="hidden" name="baccion" id="baccion">
        <div class="col-md-12 top-20 padding-0">
          <div class="col-md-12">
            <div class="panel">
              <div class="panel-heading">
                <center>
                  <h3>Empresa Crisales</h3>
                  <h3>Balance General del periodo</h3>
                  <h3>Al <?php echo "{$fechaMaximaDia} de {$fechaMaximaMes} del {$anioActivo}" ?></h3>
                </center>
              </div>
              <div class="panel-body">
                  <div class="responsive-table">
                    <table id="datatables-example" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Concepto</th>
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
                  </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
          <!-- end: content -->
          <!-- end: right menu -->
  </div>
</body>

<!-- start: Javascript -->
<script src="../asset/js/jquery.min.js"></script>
<script src="../asset/js/jquery.ui.min.js"></script>
<script src="../asset/js/bootstrap.min.js"></script>

<!-- plugins -->
<script src="../asset/js/plugins/moment.min.js"></script>
<script src="../asset/js/plugins/jquery.datatables.min.js"></script>
<script src="../asset/js/plugins/datatables.bootstrap.min.js"></script>
<script src="../asset/js/plugins/jquery.nicescroll.js"></script>

<!-- custom -->
<script src="../asset/js/main.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#datatables-example').DataTable();
  });
</script>
<!-- end: Javascript -->

</html>
<?php
include "../config/conexion.php";

if($saveanio != ""){
  $consulta = "UPDATE anio SET inventariof='".$inventariofinal."' WHERE idanio='".$saveanio."'";
  $save = $conexion->query($consulta);
  if($save){
    echo "<script type='text/javascript'>";
    echo "alert('Exito');";
    echo "document.location.href='estado.php';";
    echo "</script>";
  }else{
    msg("No Exito");
  }
}
}else{
 ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema Contable</title>

    <!-- start: Css -->
    <link rel="stylesheet" type="text/css" href="../asset/css/bootstrap.min.css">

    <!-- plugins -->
    <link rel="stylesheet" type="text/css" href="../asset/css/plugins/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="../asset/css/plugins/datatables.bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="../asset/css/plugins/animate.min.css" />
    <link href="../asset/css/style.css" rel="stylesheet">
    <!-- end: Css -->

    <link rel="shortcut icon" href="../asset/img/logomi.jpg">
    <script type="text/javascript">
    window.onload = function() {
        $('#myModalB').modal('show');
    };

    function noir2() {
        alert("Es necesario un conteo fisico del inventario final para realizar el estado de resultados.");
        $('#myModalB').modal('hide');
    }

    function ir2() {
        document.location.href = "balanceGeneral.php?if=" + document.getElementById("inventarioFinal").value;
    }
    </script>
</head>

<body id="mimin" class="dashboard">
    <?php include "header.php"?>

    <div class="container-fluid mimin-wrapper">

        <?php include "menu.php";?>
        <!-- start: Content -->
        <div id="content">
            <div class="panel box-shadow-none content-header">
                <div class="panel-body">
                    <div class="col-md-12">
                        <h3 class="animated fadeInLeft">Informe Financiero</h3>
                        <p class="animated fadeInDown">
                            Balance General
                        </p>

                    </div>
                </div>
            </div>
            <div class="col-md-12 top-20 padding-0">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <center>
                                <h3>Balance General</h3>
                            </center>
                            <div id="myModalB" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Inventario Final</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group form-animate-text"
                                                style="margin-top:30px !important;">
                                                <input type="number" class="form-text" id="inventarioFinal"
                                                    name="inventarioFinal" value="1" min="1">
                                                <span class="bar"></span>
                                                <label>Inventario final.</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" onclick="ir2();" class="btn btn-default"
                                                data-dismiss="modal">Ir</button>
                                            <button type="button" onclick="noir2();" class="btn btn-default"
                                                data-dismiss="modal">Cerrar</button>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="panel-body">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end: content -->




    </div>
</body>

<!-- start: Javascript -->
<script src="../asset/js/jquery.min.js"></script>
<script src="../asset/js/jquery.ui.min.js"></script>
<script src="../asset/js/bootstrap.min.js"></script>
<!-- plugins -->
<script src="../asset/js/plugins/moment.min.js"></script>
<script src="../asset/js/plugins/jquery.datatables.min.js"></script>
<script src="../asset/js/plugins/datatables.bootstrap.min.js"></script>
<script src="../asset/js/plugins/jquery.nicescroll.js"></script>
<!-- custom -->
<script src="../asset/js/main.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#datatables-example').DataTable();
});
</script>
<!-- end: Javascript -->

</html>


<?php
    }

}else {
header("Location: ../index.php");
}
?>

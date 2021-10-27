<?php
//Codigo que muestra solo los errores exceptuando los notice.
error_reporting(E_ALL & ~E_NOTICE);
?>
<?php
session_start();
if($_SESSION["logueado"] == TRUE) {
$inventariofinal=$_REQUEST["if"];
function mensaje($texto)
{
    echo "<script type='text/javascript'>";
    echo "alert('$texto');";
  //  echo "document.location.href='listacliente.php';";
    echo "</script>";
}

include "../config/conexion.php";
$result = $conexion->query("select * from anio where estado=1");
if($result)
{
  while ($fila=$result->fetch_object()) {
    $anioActivo=$fila->idanio;
  }
}
//saldo de ventas
$resultventa= $conexion->query("Select l.debe, l.haber, c.saldo FROM catalogo c INNER JOIN ldiario l ON c.idcatalogo = l.idcatalogo INNER JOIN partida p ON l.idpartida = p.idpartida WHERE  SUBSTRING(c.codigocuenta, 1,4) = '5101' AND p.idanio='".$anioActivo."'");
if ($resultventa) {
    $saldoV = 0;
    while ($fila = $resultventa->fetch_object()) {
      if ($fila->saldo =="DEUDOR") {
        $saldoV=$saldoV+($fila->debe)-($fila->haber);
      }else {
        $saldoV=$saldoV-($fila->debe)+($fila->haber);
      }
      }
  }
  // saldo de reb y dev sobre ventas
$resultRebDevVet= $conexion->query("Select l.debe, l.haber FROM catalogo c INNER JOIN ldiario l ON c.idcatalogo = l.idcatalogo INNER JOIN partida p ON l.idpartida = p.idpartida WHERE  SUBSTRING(c.codigocuenta, 1,4) = '4103' AND p.idanio='".$anioActivo."'");
if ($resultRebDevVet) {
      $saldoRDV = 0;
    while ($fila = $resultRebDevVet->fetch_object()) {
      $saldoRDV=$saldoRDV+($fila->debe)-($fila->haber);
      }
}
//Saldo de Compras
$resultCompras= $conexion->query("Select l.debe, l.haber FROM catalogo c INNER JOIN ldiario l ON c.idcatalogo = l.idcatalogo INNER JOIN partida p ON l.idpartida = p.idpartida WHERE  SUBSTRING(c.codigocuenta, 1,4) = '4101' AND p.idanio='".$anioActivo."'");
if ($resultCompras) {
      $saldoComp = 0;
    while ($fila = $resultCompras->fetch_object()) {
      $saldoComp=$saldoComp+($fila->debe)-($fila->haber);
      }
}
//Saldo de Gastos sobre Compras
$resultGastoComp= $conexion->query("Select l.debe, l.haber FROM catalogo c INNER JOIN ldiario l ON c.idcatalogo = l.idcatalogo INNER JOIN partida p ON l.idpartida = p.idpartida WHERE  SUBSTRING(c.codigocuenta, 1,4) = '4102' AND p.idanio='".$anioActivo."'");
if ($resultGastoComp) {
      $saldoGasComp = 0;
    while ($fila = $resultGastoComp->fetch_object()) {
      $saldoGasComp=$saldoGasComp+($fila->debe)-($fila->haber);
      }
}
//Saldo de Reb y dev sobre Compras
$resultRebDevComp= $conexion->query("Select l.debe, l.haber FROM catalogo c INNER JOIN ldiario l ON c.idcatalogo = l.idcatalogo INNER JOIN partida p ON l.idpartida = p.idpartida WHERE  SUBSTRING(c.codigocuenta, 1,4) = '5102' AND p.idanio='".$anioActivo."'");
if ($resultRebDevComp) {
      $saldoRDC = 0;
    while ($fila = $resultRebDevComp->fetch_object()) {
      $saldoRDC=$saldoRDC-($fila->debe)+($fila->haber);
      }
}
//Saldo Gastos de admon
$resuktGasAdmon= $conexion->query("Select l.debe, l.haber FROM catalogo c INNER JOIN ldiario l ON c.idcatalogo = l.idcatalogo INNER JOIN partida p ON l.idpartida = p.idpartida WHERE  SUBSTRING(c.codigocuenta, 1,4) = '4201' AND p.idanio='".$anioActivo."'");
if ($resuktGasAdmon) {
      $saldoGA = 0;
    while ($fila = $resuktGasAdmon->fetch_object()) {
      $saldoGA=$saldoGA+($fila->debe)-($fila->haber);
      }
}
//Saldo Gastos de ventas
$resuktGasVen= $conexion->query("Select l.debe, l.haber FROM catalogo c INNER JOIN ldiario l ON c.idcatalogo = l.idcatalogo INNER JOIN partida p ON l.idpartida = p.idpartida WHERE  SUBSTRING(c.codigocuenta, 1,4) = '4202' AND p.idanio='".$anioActivo."'");
if ($resuktGasVen) {
      $saldoGV = 0;
    while ($fila = $resuktGasVen->fetch_object()) {
      $saldoGV=$saldoGV+($fila->debe)-($fila->haber);
      }
}
//Saldo Gastos de Finan
$resuktGasFina= $conexion->query("Select l.debe, l.haber FROM catalogo c INNER JOIN ldiario l ON c.idcatalogo = l.idcatalogo INNER JOIN partida p ON l.idpartida = p.idpartida WHERE  SUBSTRING(c.codigocuenta, 1,4) = '4301' AND p.idanio='".$anioActivo."'");
if ($resuktGasFina) {
    $saldoGF = 0;
    while ($fila = $resuktGasFina->fetch_object()) {
      $saldoGF=$saldoGF+($fila->debe)-($fila->haber);
      }
}
//Saldo Otros Gastos
$resulOG= $conexion->query("Select l.debe, l.haber FROM catalogo c INNER JOIN ldiario l ON c.idcatalogo = l.idcatalogo INNER JOIN partida p ON l.idpartida = p.idpartida WHERE  SUBSTRING(c.codigocuenta, 1,4) = '4302' AND p.idanio='".$anioActivo."'");
if ($resulOG) {
    $saldoOG = 0;
    while ($fila = $resulOG->fetch_object()) {
      $saldoOG=$saldoOG+($fila->debe)-($fila->haber);
      }
    $saldoOG = $saldoOG + $saldoGF;
}
//Saldo Otros ingresos
$resulOI= $conexion->query("Select l.debe, l.haber FROM catalogo c INNER JOIN ldiario l ON c.idcatalogo = l.idcatalogo INNER JOIN partida p ON l.idpartida = p.idpartida WHERE  SUBSTRING(c.codigocuenta, 1,4) = '5203' AND p.idanio='".$anioActivo."'");
if ($resulOI) {
    $saldoOI = 0;
    while ($fila = $resulOI->fetch_object()) {
      $saldoOI=$saldoOI-($fila->debe)+($fila->haber);
      }
}
$resulII= $conexion->query("select inventarioi from anio where idanio='".$anioActivo."'");
if ($resulII) {
    $saldoII = 0;
    while ($fila = $resulII->fetch_object()) {
      $saldoII= $fila->inventarioi;
      }
}

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
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
    <script type="text/javascript">
    //funcion para exportar la tabla del catalogo a excell
    function estadoExcell() {
        var inv = document.getElementById("if").value;
        var anio = document.getElementById("anioActivo").value;
        const ventana = window.open("reportes/estadoResultadosExcell.php?if=" + inv + "&anio=" + anio + "", "_blank");
        //window.setTimeout(cerrarVentana(ventana), 80000);

    }

    function estadoPDF() {
        var inv = document.getElementById("if").value;
        var anio = document.getElementById("anioActivo").value;
        const ventana = window.open("reportes/estadoResultadosPDF.php?if=" + inv + "&anio=" + anio + "", "_blank");
        //window.setTimeout(cerrarVentana(ventana), 80000);

    }
    /*
    function cerrarVentana(ventana) {
        ventana.close();
    }*/
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
                            Estado De Resultados
                        </p>

                    </div>
                </div>
            </div>
            <div class="col-md-12 top-20 padding-0">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <center>
                                <h3>Estado de Resultados</h3>

                                <input type="hidden" name="anioActivo" id="anioActivo"
                                    value="<?php echo $anioActivo; ?>">
                                <input type="hidden" name="if" id="if" value="<?php echo $inventariofinal; ?>">
                            </center>
                        </div>
                        <div class="panel-body">
                            <div class="responsive-table">
                                <table id="datatables-example" class="table table-striped table-bordered table-hover "
                                    width="100%" cellspacing="0">

                                    <tbody>
                                        <tr>
                                            <td>Ventas</td>
                                            <td></td>
                                            <td>$<?php echo $saldoV;?></td>
                                        </tr>
                                        <tr>
                                            <td>(-) Rebajas y devoluciones sobre ventas</td>
                                            <td></td>
                                            <td>$<?php echo $saldoRDV;?></td>
                                        </tr>
                                        <tr>
                                            <td>(=) Ventas netas</td>
                                            <td></td>
                                            <td>$<?php echo $saldoV-$saldoRDV;?></td>
                                        </tr>
                                        <tr>
                                            <td>(-) Costo de ventas</td>
                                            <td></td>
                                            <td>$<?php echo ((($saldoComp+$saldoGasComp)-$saldoRDC)+$saldoII)-$inventariofinal;?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> Compras</td>
                                            <td>$<?php echo $saldoComp;?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>(+) Gastos Sobre Compras</td>
                                            <td>$<?php echo $saldoGasComp;?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>(=) Compras Totales</td>
                                            <td>$<?php echo $saldoComp+$saldoGasComp;?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>(-) Rebajas y devoluciones Sobre Compras</td>
                                            <td>$<?php echo $saldoRDC;?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>(=) Compras Netas</td>
                                            <td>$<?php echo ($saldoComp+$saldoGasComp)-$saldoRDC;?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>(+) Inventario Inicial</td>
                                            <td>$<?php echo $saldoII;?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>(=) Mercaderia Disponible</td>
                                            <td>$
                                                <?php 
                                                    echo (($saldoComp+$saldoGasComp)-$saldoRDC)+$saldoII;
                                                  ?>
                                            </td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>(-) Inventario Final</td>
                                            <td>$<?php echo $inventariofinal?></td>

                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>(=) Utilidad Bruta</td>
                                            <td></td>
                                            <td>$
                                                <?php 
                                                    echo ($saldoV-$saldoRDV)-(((($saldoComp+$saldoGasComp)-$saldoRDC)+$saldoII)-$inventariofinal)
                                                  ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>(-) Gastos de Operacion</td>
                                            <td></td>
                                            <td>$<?php echo $saldoGA+$saldoGV+$saldoGF?></td>
                                        </tr>
                                        <tr>
                                            <td> Gastos de Administracion</td>
                                            <td>$<?php echo $saldoGA?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td> (+) Gastos de Venta</td>
                                            <td>$<?php echo $saldoGV?></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td> (=) Utilidad de Operacion</td>
                                            <td></td>
                                            <td>$
                                                <?php 
                                                    echo (($saldoV-$saldoRDV)-(((($saldoComp+$saldoGasComp)-$saldoRDC)+$saldoII)-$inventariofinal))-($saldoGA+$saldoGV+$saldoGF)
                                                  ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> (-) Otros gastos</td>
                                            <td></td>
                                            <td>$<?php echo $saldoOG?></td>
                                        </tr>
                                        <tr>
                                            <td> (+) Otros Ingresos</td>
                                            <td></td>
                                            <td>$<?php echo $saldoOI?></td>
                                        </tr>
                                        <tr>
                                            <td> (=) Utilidad Antes de Impuesto Y Reserva</td>
                                            <td></td>
                                            <td>$
                                                <?php 
                                                    echo (((($saldoV-$saldoRDV)-(((($saldoComp+$saldoGasComp)-$saldoRDC)+$saldoII)-$inventariofinal))-($saldoGA+$saldoGV+$saldoGF))-$saldoOG)+$saldoOI
                                                  ?>
                                            </td>
                                        </tr>
                                        <?php 
                                          $UAIR=(((($saldoV-$saldoRDV)-(((($saldoComp+$saldoGasComp)-$saldoRDC)+$saldoII)-$inventariofinal))-($saldoGA+$saldoGV+$saldoGF))-$saldoOG)+$saldoOI;
                                          $RL=$UAIR*0.07;
                                          ?>
                                        <tr>
                                            <td> (-) Reserva Legal (7%)</td>
                                            <td></td>
                                            <td>$<?php echo $RL?></td>
                                        </tr>
                                        <tr>
                                            <td> (=) Utilidad antes de Impuesto sobre la Renta</td>
                                            <td></td>
                                            <td>$<?php echo $UAIR-$RL?></td>
                                        </tr>
                                        <?php $ISR=($UAIR-$RL)*0.30 ?>
                                        <tr>
                                            <td> (-) Impuesto sobre la renta (30%)</td>
                                            <td></td>
                                            <td>$<?php echo $ISR?></td>
                                        </tr>
                                        <tr>
                                            <td> (=) Utilidad del Ejercicio</td>
                                            <td></td>
                                            <td>$<?php echo ($UAIR-$RL)-$ISR?></td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
$(document).ready(function() {
    $('#datatables-example').DataTable();
});
</script>
<!-- end: Javascript -->

</html>
<?php
}else {
header("Location: ../index.php");
}
?>
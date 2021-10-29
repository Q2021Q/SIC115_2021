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

    /*PASIVO NO CORRIENTE*/

    /*PATRIMONIO*/

    //saldo de ventas
    /*
    $resultventa= $conexion->query("select c.nombrecuenta as nombre, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','2') as codigocorto, p.idpartida as npartida, p.concepto as concepto, p.fecha as fecha, l.debe as debe, l.haber as haber FROM catalogo as c,partida as p, ldiario as l where SUBSTRING(c.codigocuenta,1,'2')='51' and p.idpartida=l.idpartida and l.idcatalogo=c.idcatalogo and p.idanio='".$anioActivo."' ORDER BY p.idpartida ASC");
    if ($resultventa) {
        while ($fila = $resultventa->fetch_object()) {
          if ($fila->saldo=="DEUDOR") {
            $saldoV=$saldoV+($fila->debe)-($fila->haber);
          }else {
            $saldoV=$saldoV-($fila->debe)+($fila->haber);
          }
          }
      }
    // saldo de reb y dev sobre ventas
    $resultRebDevVet= $conexion->query("select c.nombrecuenta as nombre, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','3') as codigocorto, p.idpartida as npartida, p.concepto as concepto, p.fecha as fecha, l.debe as debe, l.haber as haber FROM catalogo as c,partida as p, ldiario as l where SUBSTRING(c.codigocuenta,1,'3')='411' and p.idpartida=l.idpartida and l.idcatalogo=c.idcatalogo and p.idanio='".$anioActivo."' ORDER BY p.idpartida ASC");
    if ($resultRebDevVet) {
        while ($fila = $resultRebDevVet->fetch_object()) {
          $saldoRDV=$saldoRDV+($fila->debe)-($fila->haber);
          }
    }
    //Saldo de Compras
    $resultCompras= $conexion->query("select c.nombrecuenta as nombre, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','2') as codigocorto, p.idpartida as npartida, p.concepto as concepto, p.fecha as fecha, l.debe as debe, l.haber as haber FROM catalogo as c,partida as p, ldiario as l where SUBSTRING(c.codigocuenta,1,'2')='43' and p.idpartida=l.idpartida and l.idcatalogo=c.idcatalogo and p.idanio='".$anioActivo."' ORDER BY p.idpartida ASC");
    if ($resultCompras) {
        while ($fila = $resultCompras->fetch_object()) {
          $saldoComp=$saldoComp+($fila->debe)-($fila->haber);
          }
    }
    //Saldo de Gastos sobre Compras
    $resultGastoComp= $conexion->query("select c.nombrecuenta as nombre, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','2') as codigocorto, p.idpartida as npartida, p.concepto as concepto, p.fecha as fecha, l.debe as debe, l.haber as haber FROM catalogo as c,partida as p, ldiario as l where SUBSTRING(c.codigocuenta,1,'2')='44' and p.idpartida=l.idpartida and l.idcatalogo=c.idcatalogo and p.idanio='".$anioActivo."' ORDER BY p.idpartida ASC");
    if ($resultGastoComp) {
        while ($fila = $resultGastoComp->fetch_object()) {
          $saldoGasComp=$saldoGasComp+($fila->debe)-($fila->haber);
          }
    }
    //Saldo de Reb y dev sobre Compras
    $resultRebDevComp= $conexion->query("select c.nombrecuenta as nombre, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','2') as codigocorto, p.idpartida as npartida, p.concepto as concepto, p.fecha as fecha, l.debe as debe, l.haber as haber FROM catalogo as c,partida as p, ldiario as l where SUBSTRING(c.codigocuenta,1,'2')='53' and p.idpartida=l.idpartida and l.idcatalogo=c.idcatalogo and p.idanio='".$anioActivo."' ORDER BY p.idpartida ASC");
    if ($resultRebDevComp) {
        while ($fila = $resultRebDevComp->fetch_object()) {
          $saldoRDC=$saldoRDC-($fila->debe)+($fila->haber);
          }
    }
    //Saldo Gastos de admon
    $resuktGasAdmon= $conexion->query("select c.nombrecuenta as nombre, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','3') as codigocorto, p.idpartida as npartida, p.concepto as concepto, p.fecha as fecha, l.debe as debe, l.haber as haber FROM catalogo as c,partida as p, ldiario as l where SUBSTRING(c.codigocuenta,1,'3')='415' and p.idpartida=l.idpartida and l.idcatalogo=c.idcatalogo and p.idanio='".$anioActivo."' ORDER BY p.idpartida ASC");
    if ($resuktGasAdmon) {
        while ($fila = $resuktGasAdmon->fetch_object()) {
          $saldoGA=$saldoGA+($fila->debe)-($fila->haber);
          }
    }
    //Saldo Gastos de ventas
    $resuktGasVen= $conexion->query("select c.nombrecuenta as nombre, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','3') as codigocorto, p.idpartida as npartida, p.concepto as concepto, p.fecha as fecha, l.debe as debe, l.haber as haber FROM catalogo as c,partida as p, ldiario as l where SUBSTRING(c.codigocuenta,1,'3')='416' and p.idpartida=l.idpartida and l.idcatalogo=c.idcatalogo and p.idanio='".$anioActivo."' ORDER BY p.idpartida ASC");
    if ($resuktGasVen) {
        while ($fila = $resuktGasVen->fetch_object()) {
          $saldoGV=$saldoGV+($fila->debe)-($fila->haber);
          }
    }
    //Saldo Gastos de Finan
    $resuktGasFina= $conexion->query("select c.nombrecuenta as nombre, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','3') as codigocorto, p.idpartida as npartida, p.concepto as concepto, p.fecha as fecha, l.debe as debe, l.haber as haber FROM catalogo as c,partida as p, ldiario as l where SUBSTRING(c.codigocuenta,1,'3')='417' and p.idpartida=l.idpartida and l.idcatalogo=c.idcatalogo and p.idanio='".$anioActivo."' ORDER BY p.idpartida ASC");
    if ($resuktGasFina) {
        while ($fila = $resuktGasFina->fetch_object()) {
          $saldoGF=$saldoGF+($fila->debe)-($fila->haber);
          }
    }
    //Saldo Otros Gastos
    $resulOG= $conexion->query("select c.nombrecuenta as nombre, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','3') as codigocorto, p.idpartida as npartida, p.concepto as concepto, p.fecha as fecha, l.debe as debe, l.haber as haber FROM catalogo as c,partida as p, ldiario as l where SUBSTRING(c.codigocuenta,1,'3')='423' and p.idpartida=l.idpartida and l.idcatalogo=c.idcatalogo and p.idanio='".$anioActivo."' ORDER BY p.idpartida ASC");
    if ($resulOG) {
        while ($fila = $resulOG->fetch_object()) {
          $saldoOG=$saldoOG+($fila->debe)-($fila->haber);
          }
    }
    //Saldo Otros ingresos
    $resulOI= $conexion->query("select c.nombrecuenta as nombre, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','3') as codigocorto, p.idpartida as npartida, p.concepto as concepto, p.fecha as fecha, l.debe as debe, l.haber as haber FROM catalogo as c,partida as p, ldiario as l where SUBSTRING(c.codigocuenta,1,'3')='521' and p.idpartida=l.idpartida and l.idcatalogo=c.idcatalogo and p.idanio='".$anioActivo."' ORDER BY p.idpartida ASC");
    if ($resulOI) {
        while ($fila = $resulOI->fetch_object()) {
          $saldoOI=$saldoOI-($fila->debe)+($fila->haber);
          }
    }
    $resulII= $conexion->query("select c.nombrecuenta as nombre, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','3') as codigocorto, p.idpartida as npartida, p.concepto as concepto, p.fecha as fecha, l.debe as debe, l.haber as haber FROM catalogo as c,partida as p, ldiario as l where SUBSTRING(c.codigocuenta,1,'3')='118' and p.idpartida=l.idpartida and l.idcatalogo=c.idcatalogo and p.idanio='".$anioActivo."' ORDER BY p.idpartida ASC");
    if ($resulII) {
        while ($fila = $resulII->fetch_object()) {
          $saldoII=$saldoII+($fila->debe)-($fila->haber);
          }
    }
    */


    
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
                  <h3>Al <?php echo $fechaMaximaDia." de ".$fechaMaximaMes." del ".$anioActivo?></h3>
                </center>
              </div>
              <div class="panel-body">
                <a class="btn btn-primary" href="./balanceAexcell.php">Descargar Balance General</a>
                  <div class="responsive-table">
                    <table id="datatables-example" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th>Concepto</th>
                          <th>Debe</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr class="bg-info">
                          <td colspan='2' align='center'>ACTIVO</td>
                        </tr>
                        <tr class="bg-light">
                          <td colspan='2' align='center'>ACTIVO CORRIENTE</td>
                        </tr>
                        <tr class="bg-light">
                          <td align="left">EFECTIVO Y EQUIVALENTES</td>
                          <td align="center"><?php echo "$".$saldoEYE ?></td>
                        </tr>
                        <tr class="bg-light">
                          <td align="left">CUENTAS POR COBRAR COMERCIALES</td>
                          <td align="center"><?php echo "$".$saldoPCC ?></td>
                        </tr>
                        <tr class="bg-light">
                          <td align="left">IVA CREDITO FISCAL</td>
                          <td align="center"><?php echo "$".$saldoIVACF ?></td>
                        </tr>
                        <tr class="bg-light">
                          <td align="left">INVENTARIO FINAL</td>
                          <td align="center"><?php echo "$".$inventariofinal ?></td>
                        </tr>
                        <tr class='bg-light'>
                          <td align='center'>TOTAL ACTIVO CORRIENTE</td>
                          <td align='center'><?php echo "$".$saldoActivoCorriente=$saldoEYE+$saldoPCC+$saldoIVACF+$inventariofinal?></td>
                        </tr>
                        <tr class="bg-light">
                          <td colspan='2' align='center'>ACTIVO NO CORRIENTE</td>
                        </tr>
                        <tr class='bg-light'>
                          <td align='center'>TOTAL ACTIVO NO CORRIENTE</td>
                          <td align='center'><?php echo "$".$saldoActivoNC=0?></td>
                        </tr>
                        <tr class="bg-info">
                          <td align="center">TOTAL ACTIVO</td>
                          <td align="center"><?php echo "$".$saldoTotalActivos=$saldoActivoCorriente+$saldoActivoNC?></td>
                        </tr>
                        <tr class='bg-info'>
                          <td colspan='2' align='center'>PASIVO</td>
                        </tr>
                        <tr class='bg-light'>
                          <td colspan='2' align='center'>PASIVO CORRIENTE</td>
                        </tr>
                        <tr class="bg-light">
                          <td align="left">CUENTAS POR PAGAR COMERCIALES</td>
                          <td align="center"><?php echo "$".$saldoCPP?></td>
                        </tr>
                        <tr class="bg-light">
                          <td align="left">IVA DEBITO FISCAL</td>
                          <td align="center"><?php echo "$".$saldoIVADF?></td>
                        </tr>
                        <tr class='bg-light'>
                          <td align='center'>TOTAL PASIVO CORRIENTE</td>
                          <td align='center'><?php echo "$".$saldoPasivoC=$saldoCPP+$saldoIVADF?></td>
                        </tr>
                        <tr class="bg-light">
                          <td colspan='2' align='center'>PASIVO NO CORRIENTE</td>
                        </tr>
                        <tr class='bg-light'>
                          <td align='center'>TOTAL PASIVO NO CORRIENTE</td>
                          <td align='center'><?php echo "$".$saldoPasivoNC=0?></td>
                        </tr>
                        <tr class="bg-info">
                          <td align="center">TOTAL PASIVO</td>
                          <td align="center"><?php echo "$".$saldoTotalPasivos=$saldoPasivoC+$saldoPasivoNC?></td>
                        </tr>
                        <tr class="bg-info">
                          <td colspan="2" align="center">PATRIMONIO</td>
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
                          <td align="center">TOTAL PATRIMONIO</td>
                          <td align="center"></td>
                        </tr>
                        <tr class="bg-info">
                          <td align="center">TOTAL PASIVO + PATRIMONIO</td>
                          <td align="center"></td>
                        </tr>
                      <?php
                        $result = $conexion->query("select c.nombrecuenta as nombre,c.idcatalogo as id, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','2') as codigocorto FROM catalogo as c where SUBSTRING(c.codigocuenta,1,'2')='13' and c.nivel='3' ORDER BY c.codigocuenta ASC");
                        
                        
                        // PATRIMONO
                        $result = $conexion->query("select c.nombrecuenta as nombre,c.idcatalogo as id, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','2') as codigocorto FROM catalogo as c where SUBSTRING(c.codigocuenta,1,'2')='31' and c.nivel='3' ORDER BY c.codigocuenta ASC");
                        /*if ($result) {
                          while ($fila = $result->fetch_object()) {
                              echo "<tr class='bg-success'>";
                              echo "<td  align='left'>".$fila->nombre."</td>";
                              $codigo=$fila->codigo;
                              $loncadena=strlen($codigo);
                              $id=$fila->id;
                              $result2 = $conexion->query("select c.nombrecuenta as nombre, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','".$loncadena."') as codigocorto, p.idpartida as npartida, p.concepto as concepto, p.fecha as fecha, l.debe as debe, l.haber as haber FROM catalogo as c,partida as p, ldiario as l where SUBSTRING(c.codigocuenta,1,'".$loncadena."')='".$codigo."' and p.idpartida=l.idpartida and l.idcatalogo=c.idcatalogo and p.idanio='".$anioActivo."' ORDER BY p.idpartida ASC");
                              if ($result2) {
                                while ($fila2 = $result2->fetch_object()) {
                                  $cuenta=$fila2->idcatalogo;
                                  $debe=$fila2->debe;
                                  $haber=$fila2->haber;
                                  $saldo=$saldo-($debe)+($haber);
                                }
                                echo "<td  align='center'> $ ".$saldo."</td>";
                                $saldoTotal=$saldoTotal+$saldo;
                                echo "</tr>";
                                $saldo=0;
                              }
                          }

                          echo "<tr class='bg-success'>";
                          echo "<td  align='left'>Reserva Legal</td>";
                          $UAIR=(((($saldoV-$saldoRDV)-(((($saldoComp+$saldoGasComp)-$saldoRDC)+$saldoII)-$inventariofinal))-($saldoGA+$saldoGV+$saldoGF))-$saldoOG)+$saldoOI;
                          $RL=($UAIR*0.07);
                          echo "<td  align='center'> $ ".$RL."</td>";
                          echo "</tr>";
                          echo "<tr class='bg-success'>";
                          echo "<td  align='left'>Utilidad del Ejercicio</td>";
                          $UAIR-$RL;
                          $ISR=($UAIR-$RL)*0.30;
                          $UE=($UAIR-$RL)-$ISR;
                          echo "<td  align='center'> $ ".$UE."</td>";
                          echo "</tr>";
                          echo "<tr class='bg-warning'>";
                          echo "<td align='center'>TOTAL PATRIMONIO:</td>";
                          echo "<td align='right'> $  ".($saldoTotal+$RL+$UE)."</td>";
                          echo "</tr>";
                          echo "<tr class='bg-warning'>";
                          echo "<td align='center'>TOTAL PASIVO+PATRIMONIO:</td>";
                          echo "<td align='right'> $  ".(($saldoTotal+$RL+$UE)+$tp)."</td>";
                          echo "</tr>";
                          $saldoTotal=0;
                          }*/
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

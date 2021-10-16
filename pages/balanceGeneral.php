<?php
//Codigo que muestra solo los errores exceptuando los notice.
error_reporting(E_ALL & ~E_NOTICE);
?>
<?php
session_start();
$inventariofinal=$_REQUEST["if"];
if($_SESSION["logueado"] == TRUE) {
  include '../config/conexion.php';
  $result = $conexion->query("select * from anio where estado=1");
  if($result)
  {
    while ($fila=$result->fetch_object()) {
      $anioActivo=$fila->idanio;
    }
  }
  $result = $conexion->query("select MIN(fecha) as fecha from partida where idanio=".$anioActivo);
  if ($result) {
    while ($fila = $result->fetch_object()) {
      $fechaMinima=$fila->fecha;
      $fechaMinima=date("d-m-Y",strtotime($fechaMinima));
    }
  }
  $result2 = $conexion->query("select MAX(fecha) as fecha from partida where idanio=".$anioActivo);
  if ($result2) {
    while ($fila = $result2->fetch_object()) {
      $fechaMaxima=$fila->fecha;
      $fechaMaxima =date("d-m-Y",strtotime($fechaMaxima));
    }
  }

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

  <link rel="shortcut icon" href="../asset/img/logomi.png">
  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
      <script type="text/javascript">
        function modify(id)
        {
          //alert("entra");
          document.getElementById('bandera').value='enviar';
          document.getElementById('baccion').value=id;
         document.turismo.submit();
        }
         function confirmar(id)
        {
          if (confirm("!!Advertencia!! Desea Eliminar Este Registro?")) {
            document.getElementById('bandera').value='desaparecer';
            document.getElementById('baccion').value=id;
            alert(id);
            document.turismo.submit();
          }else
          {
            alert("No entra");
          }

        }
          //funcion para exportar la tabla del catalogo a excell
        function balanceExcell()
        {
          var inv=document.getElementById("if").value;
          var anio=document.getElementById("anioActivo").value;
          const ventana = window.open("reportes/BalanceExecell.php?if="+inv+"&anio="+anio+"","_blank");
        }
        function balancePDF()
        {
          var inv=document.getElementById("if").value;
          var anio=document.getElementById("anioActivo").value;
          const ventana = window.open("reportes/BalancePDF.php?if="+inv+"&anio="+anio+"","_blank");
        }
        function diarioPDF()
        {
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
                        <p class="animated fadeInDown">
                          Tabla <span class="fa-angle-right fa"></span> Datos de la tabla.
                        </p>
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
                      <h3>Balance General del periodo.</h3>
                      
                      <input type="hidden" name="anioActivo" id="anioActivo" value="<?php echo $anioActivo; ?>">

                      <input type="hidden" name="if" id="if" value="<?php echo $inventariofinal; ?>">
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
                    //para activo corriente
include "../config/conexion.php";
$resultIVA= $conexion->query("select c.nombrecuenta as nombre, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','3') as codigocorto, p.idpartida as npartida, p.concepto as concepto, p.fecha as fecha, l.debe as debe, l.haber as haber FROM catalogo as c,partida as p, ldiario as l where SUBSTRING(c.codigocuenta,1,'3')='120' and p.idpartida=l.idpartida and l.idcatalogo=c.idcatalogo and p.idanio='".$anioActivo."' ORDER BY p.idpartida ASC");
if ($resultIVA) {
    while ($fila = $resultIVA->fetch_object()) {
      
        $IVA=$IVA+($fila->debe)-($fila->haber);

      }
  }
echo "<tr class='danger'>";
echo "<td colspan='2' align='center'>ACTIVO</td>";
echo "</tr>";
echo "<tr class='info'>";
echo "<td colspan='2' align='center'>ACTIVO CORRIENTE</td>";
echo "</tr>";
$result = $conexion->query("select c.nombrecuenta as nombre,c.idcatalogo as id, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','2') as codigocorto FROM catalogo as c where SUBSTRING(c.codigocuenta,1,'2')='11' and c.codigocuenta!='118' and c.nivel='3' ORDER BY c.codigocuenta ASC");
if ($result) {
  while ($fila = $result->fetch_object()) {
      echo "<tr class='success'>";
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
          $saldo=$saldo+($debe)-($haber);
        }
        echo "<td  align='center'> $  ".$saldo."</td>";
        $saldoTotal=$saldoTotal+$saldo;
        echo "</tr>";
        $saldo=0;
      }

  }
  echo "<tr class='success'>";
  echo "<td  align='left'>Inventario Final</td>";
  echo "<td  align='center'> $  ".$inventariofinal."</td>";
    echo "</tr>";
  echo "<tr class='success'>";
  echo "<td  align='left'>IVA CREDITO:</td>";
  echo "<td  align='center'> $  ".$IVA."</td>";

    echo "</tr>";

  echo "<tr class='warning'>";
  echo "<td align='center'>TOTAL ACTIVO CORRIENTE:</td>";
  echo "<td align='right'>$ ".($saldoTotal+$inventariofinal+$IVA)."</td>";
  echo "</tr>";
   $AC=($saldoTotal+$inventariofinal+$IVA);
  $saldoTotal=0;
}
//Activo no CORRIENTE
// echo "<tr class='danger'>";
// echo "<td colspan='2' align='center'>ACTIVO</td>";
// echo "</tr>";
echo "<tr class='info'>";
echo "<td colspan='2' align='center'>ACTIVO NO CORRIENTE</td>";
echo "</tr>";
$result = $conexion->query("select c.nombrecuenta as nombre,c.idcatalogo as id, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','2') as codigocorto FROM catalogo as c where SUBSTRING(c.codigocuenta,1,'2')='13' and c.nivel='3' ORDER BY c.codigocuenta ASC");
if ($result) {
  while ($fila = $result->fetch_object()) {
      echo "<tr class='success'>";
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
          $saldo=$saldo+($debe)-($haber);
        }
        echo "<td  align='center'> $  ".$saldo."</td>";
        $saldoTotal=$saldoTotal+$saldo;
        echo "</tr>";
        $saldo=0;
      }
  }
  echo "<tr class='warning'>";
  echo "<td align='center'>TOTAL ACTIVO NO CORRIENTE:</td>";
  echo "<td align='right'>$ ".$saldoTotal."</td>";
  echo "</tr>";
  $ANC=$saldoTotal;
  echo "<tr class='warning'>";
  echo "<td align='center'>TOTAL ACTIVO :</td>";
  echo "<td align='right'>$ ".($AC+$ANC)."</td>";
  echo "</tr>";
  $saldoTotal=0;
}
//saldo de ventas
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
//PASIVO
echo "<tr class='danger'>";
echo "<td colspan='2' align='center'>PASIVO</td>";
echo "</tr>";
echo "<tr class='info'>";
echo "<td colspan='2' align='center'>PASIVO CORRIENTE</td>";
echo "</tr>";
$result = $conexion->query("select c.nombrecuenta as nombre,c.idcatalogo as id, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','2') as codigocorto FROM catalogo as c where SUBSTRING(c.codigocuenta,1,'2')='21' and c.nivel='3' ORDER BY c.codigocuenta ASC");
if ($result) {
  while ($fila = $result->fetch_object()) {
      echo "<tr class='success'>";
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
  $UAIR=(((($saldoV-$saldoRDV)-(((($saldoComp+$saldoGasComp)-$saldoRDC)+$saldoII)-$inventariofinal))-($saldoGA+$saldoGV+$saldoGF))-$saldoOG)+$saldoOI;
  $RL=($UAIR*0.07);
  $UAIR-$RL;
  $ISR=($UAIR-$RL)*0.30;
  echo "<tr class='success'>";
  echo "<td align='left'>Impuesto Sobre la Renta</td>";
  echo "<td align='right'> $  ".$ISR."</td>";
  echo "</tr>";
  echo "<tr class='warning'>";
  echo "<td align='center'>TOTAL PASIVO CORRIENTE:</td>";
  echo "<td align='right'> $  ".($saldoTotal+$ISR)."</td>";
  echo "</tr>";
  $PC=($saldoTotal+$ISR);
  $saldoTotal=0;
}
//Pasivo no corriente
echo "<tr class='info'>";
echo "<td colspan='2' align='center'>PASIVO NO CORRIENTE</td>";
echo "</tr>";
$result = $conexion->query("select c.nombrecuenta as nombre,c.idcatalogo as id, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','2') as codigocorto FROM catalogo as c where SUBSTRING(c.codigocuenta,1,'2')='22' and c.nivel='3' ORDER BY c.codigocuenta ASC");
if ($result) {
  while ($fila = $result->fetch_object()) {
      echo "<tr class='success'>";
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
  echo "<tr class='warning'>";
  echo "<td align='center'>TOTAL PASIVO NO CORRIENTE:</td>";
  echo "<td align='right'> $ ".$saldoTotal."</td>";
  echo "</tr>";
  $PNC=$saldoTotal;
  echo "<tr class='warning'>";
  echo "<td align='center'>TOTAL PASIVO:</td>";
  echo "<td align='right'>$ ".($PNC+$PC)."</td>";
  echo "</tr>";
  $tp=($PNC+$PC);
  $saldoTotal=0;
}
// PATRIMONO
echo "<tr class='danger'>";
echo "<td colspan='2' align='center'>PATRIMONIO</td>";
echo "</tr>";

$result = $conexion->query("select c.nombrecuenta as nombre,c.idcatalogo as id, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','2') as codigocorto FROM catalogo as c where SUBSTRING(c.codigocuenta,1,'2')='31' and c.nivel='3' ORDER BY c.codigocuenta ASC");
if ($result) {
  while ($fila = $result->fetch_object()) {
      echo "<tr class='success'>";
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

  echo "<tr class='success'>";
  echo "<td  align='left'>Reserva Legal</td>";
  $UAIR=(((($saldoV-$saldoRDV)-(((($saldoComp+$saldoGasComp)-$saldoRDC)+$saldoII)-$inventariofinal))-($saldoGA+$saldoGV+$saldoGF))-$saldoOG)+$saldoOI;
  $RL=($UAIR*0.07);
  echo "<td  align='center'> $ ".$RL."</td>";
  echo "</tr>";
  echo "<tr class='success'>";
  echo "<td  align='left'>Utilidad del Ejercicio</td>";
  $UAIR-$RL;
  $ISR=($UAIR-$RL)*0.30;
  $UE=($UAIR-$RL)-$ISR;
  echo "<td  align='center'> $ ".$UE."</td>";
  echo "</tr>";
  echo "<tr class='warning'>";
  echo "<td align='center'>TOTAL PATRIMONIO:</td>";
  echo "<td align='right'> $  ".($saldoTotal+$RL+$UE)."</td>";
  echo "</tr>";
  echo "<tr class='warning'>";
  echo "<td align='center'>TOTAL PASIVO+PATRIMONIO:</td>";
  echo "<td align='right'> $  ".(($saldoTotal+$RL+$UE)+$tp)."</td>";
  echo "</tr>";
  $saldoTotal=0;
}
?>
                    </tbody>
                      </table>
                    </div>
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

      <!-- start: Mobile -->
      <div id="mimin-mobile" class="reverse">
        <div class="mimin-mobile-menu-list">
            <div class="col-md-12 sub-mimin-mobile-menu-list animated fadeInLeft">
                <ul class="nav nav-list">
                    <li class="active ripple">
                      <a class="tree-toggle nav-header">
                        <span class="fa-home fa"></span>Dashboard
                        <span class="fa-angle-right fa right-arrow text-right"></span>
                      </a>
                      <ul class="nav nav-list tree">
                          <li><a href="dashboard-v1.html">Dashboard v.1</a></li>
                          <li><a href="dashboard-v2.html">Dashboard v.2</a></li>
                      </ul>
                    </li>
                    <li class="ripple">
                      <a class="tree-toggle nav-header">
                        <span class="fa-diamond fa"></span>Layout
                        <span class="fa-angle-right fa right-arrow text-right"></span>
                      </a>
                      <ul class="nav nav-list tree">
                        <li><a href="topnav.html">Top Navigation</a></li>
                        <li><a href="boxed.html">Boxed</a></li>
                      </ul>
                    </li>
                    <li class="ripple">
                      <a class="tree-toggle nav-header">
                        <span class="fa-area-chart fa"></span>Charts
                        <span class="fa-angle-right fa right-arrow text-right"></span>
                      </a>
                      <ul class="nav nav-list tree">
                        <li><a href="chartjs.html">ChartJs</a></li>
                        <li><a href="morris.html">Morris</a></li>
                        <li><a href="flot.html">Flot</a></li>
                        <li><a href="sparkline.html">SparkLine</a></li>
                      </ul>
                    </li>
                    <li class="ripple">
                      <a class="tree-toggle nav-header">
                        <span class="fa fa-pencil-square"></span>Ui Elements
                        <span class="fa-angle-right fa right-arrow text-right"></span>
                      </a>
                      <ul class="nav nav-list tree">
                        <li><a href="color.html">Color</a></li>
                        <li><a href="weather.html">Weather</a></li>
                        <li><a href="typography.html">Typography</a></li>
                        <li><a href="icons.html">Icons</a></li>
                        <li><a href="buttons.html">Buttons</a></li>
                        <li><a href="media.html">Media</a></li>
                        <li><a href="panels.html">Panels & Tabs</a></li>
                        <li><a href="notifications.html">Notifications & Tooltip</a></li>
                        <li><a href="badges.html">Badges & Label</a></li>
                        <li><a href="progress.html">Progress</a></li>
                        <li><a href="sliders.html">Sliders</a></li>
                        <li><a href="timeline.html">Timeline</a></li>
                        <li><a href="modal.html">Modals</a></li>
                      </ul>
                    </li>
                    <li class="ripple">
                      <a class="tree-toggle nav-header">
                       <span class="fa fa-check-square-o"></span>Forms
                       <span class="fa-angle-right fa right-arrow text-right"></span>
                      </a>
                      <ul class="nav nav-list tree">
                        <li><a href="formelement.html">Form Element</a></li>
                        <li><a href="#">Wizard</a></li>
                        <li><a href="#">File Upload</a></li>
                        <li><a href="#">Text Editor</a></li>
                      </ul>
                    </li>
                    <li class="ripple">
                      <a class="tree-toggle nav-header">
                        <span class="fa fa-table"></span>Tables
                        <span class="fa-angle-right fa right-arrow text-right"></span>
                      </a>
                      <ul class="nav nav-list tree">
                        <li><a href="datatables.html">Data Tables</a></li>
                        <li><a href="handsontable.html">handsontable</a></li>
                        <li><a href="tablestatic.html">Static</a></li>
                      </ul>
                    </li>
                    <li class="ripple">
                      <a href="calendar.html">
                         <span class="fa fa-calendar-o"></span>Calendar
                      </a>
                    </li>
                    <li class="ripple">
                      <a class="tree-toggle nav-header">
                        <span class="fa fa-envelope-o"></span>Mail
                        <span class="fa-angle-right fa right-arrow text-right"></span>
                      </a>
                      <ul class="nav nav-list tree">
                        <li><a href="mail-box.html">Inbox</a></li>
                        <li><a href="compose-mail.html">Compose Mail</a></li>
                        <li><a href="view-mail.html">View Mail</a></li>
                      </ul>
                    </li>
                    <li class="ripple">
                      <a class="tree-toggle nav-header">
                        <span class="fa fa-file-code-o"></span>Pages
                        <span class="fa-angle-right fa right-arrow text-right"></span>
                      </a>
                      <ul class="nav nav-list tree">
                        <li><a href="forgotpass.html">Forgot Password</a></li>
                        <li><a href="login.html">SignIn</a></li>
                        <li><a href="reg.html">SignUp</a></li>
                        <li><a href="article-v1.html">Article v1</a></li>
                        <li><a href="search-v1.html">Search Result v1</a></li>
                        <li><a href="productgrid.html">Product Grid</a></li>
                        <li><a href="profile-v1.html">Profile v1</a></li>
                        <li><a href="invoice-v1.html">Invoice v1</a></li>
                      </ul>
                    </li>
                     <li class="ripple"><a class="tree-toggle nav-header"><span class="fa "></span> MultiLevel  <span class="fa-angle-right fa right-arrow text-right"></span> </a>
                      <ul class="nav nav-list tree">
                        <li><a href="view-mail.html">Level 1</a></li>
                        <li><a href="view-mail.html">Level 1</a></li>
                        <li class="ripple">
                          <a class="sub-tree-toggle nav-header">
                            <span class="fa fa-envelope-o"></span> Level 1
                            <span class="fa-angle-right fa right-arrow text-right"></span>
                          </a>
                          <ul class="nav nav-list sub-tree">
                            <li><a href="mail-box.html">Level 2</a></li>
                            <li><a href="compose-mail.html">Level 2</a></li>
                            <li><a href="view-mail.html">Level 2</a></li>
                          </ul>
                        </li>
                      </ul>
                    </li>
                    <li><a href="credits.html">Credits</a></li>
                  </ul>
            </div>
        </div>
      </div>
      <button id="mimin-mobile-menu-opener" class="animated rubberBand btn btn-circle btn-danger">
        <span class="fa fa-bars"></span>
      </button>
       <!-- end: Mobile -->

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
</body>
</html>
<?php

include "../config/conexion.php";

function msg($texto)
{
    echo "<script type='text/javascript'>";
    echo "alert('$texto');";
  //  echo "document.location.href='listacliente.php';";
    echo "</script>";
}

$bandera = $_REQUEST["bandera"];
$baccion = $_REQUEST["baccion"];
if ($bandera == "add") {
    $consulta  = "INSERT INTO cliente VALUES('null','" . $nombrecliente . "','" . $apellidocliente . "','" . $duicliente . "','" . $telefonocliente . "','" . $direccioncliente . "')";
    $resultado = $conexion->query($consulta);
    if ($resultado) {
        msg("Exito");
    } else {
        msg("No Exito");
    }
}
if ($bandera == "desaparecer") {
    $consulta  = "DELETE FROM cliente where idcliente='" . $baccion . "'";
    $resultado = $conexion->query($consulta);
    if ($resultado) {
        msg("Exito");
    } else {
        msg("No Exito");
    }
}
if ($bandera == 'enviar') {
    echo "<script type='text/javascript'>";
    echo "document.location.href='editcliente.php?id=" . $baccion . "';";
    echo "</script>";
    # code...
}

}else {
header("Location: ../index.php");
}
?>

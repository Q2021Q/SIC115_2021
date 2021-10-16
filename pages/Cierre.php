<?php
session_start();
$inventariofinal=$_REQUEST["if"];
$anioActivo=$_REQUEST["anioActivo"];
echo "If:".$inventariofinal;
echo "Anio:".$anioActivo;

include "../config/conexion.php";
$result = $conexion->query("select MAX(idpartida) as maxp from partida where idanio=".$anioActivo);
if ($result) {
  while ($fila=$result->fetch_object()) {
    $maxPartida=$fila->maxp;
  }
}
$consulta2 = "update anio set partidaf='".$maxPartida."', estado='0',inventariof='".$inventariofinal."' where idanio=".$anioActivo;
$resultado = $conexion->query($consulta2);


$resultRebDevVet= $conexion->query("select c.nombrecuenta as nombre, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','3') as codigocorto, p.idpartida as npartida, p.concepto as concepto, p.fecha as fecha, l.debe as debe, l.haber as haber FROM catalogo as c,partida as p, ldiario as l where SUBSTRING(c.codigocuenta,1,'3')='411' and p.idpartida=l.idpartida and l.idcatalogo=c.idcatalogo and p.idanio='".$anioActivo."' ORDER BY p.idpartida ASC");
if ($resultRebDevVet) {
    while ($fila = $resultRebDevVet->fetch_object()) {
      $saldoRDV=$saldoRDV+($fila->debe)-($fila->haber);
      }
}
//partida de ajuste 1
$maxPartida++;
$consultaA1  = "INSERT INTO partida VALUES('".$maxPartida."','Por ventas netas.','31-12".$anioActivo."','" . $anioActivo . "')";
$resultadoA1 = $conexion->query($consultaA1);
//ldiario
$consulta  = "INSERT INTO ldiario VALUES('null','" . $maxPartida . "','11','".$saldoRDV."','0','" . $anioActivo . "')";
$resultado = $conexion->query($consulta);
$consulta  = "INSERT INTO ldiario VALUES('null','" . $maxPartida . "','12','0','".$saldoRDV."','" . $anioActivo . "')";
$resultado = $conexion->query($consulta);


$resultGastoComp= $conexion->query("select c.nombrecuenta as nombre, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','2') as codigocorto, p.idpartida as npartida, p.concepto as concepto, p.fecha as fecha, l.debe as debe, l.haber as haber FROM catalogo as c,partida as p, ldiario as l where SUBSTRING(c.codigocuenta,1,'2')='44' and p.idpartida=l.idpartida and l.idcatalogo=c.idcatalogo and p.idanio='".$anioActivo."' ORDER BY p.idpartida ASC");
if ($resultGastoComp) {
    while ($fila = $resultGastoComp->fetch_object()) {
      $saldoGasComp=$saldoGasComp+($fila->debe)-($fila->haber);
      }
}
//partida de ajuste 2
$maxPartida++;
$consultaA1  = "INSERT INTO partida VALUES('".$maxPartida."','Por compras totales.','31-12".$anioActivo."','" . $anioActivo . "')";
$resultadoA1 = $conexion->query($consultaA1);
//ldiario
$consulta  = "INSERT INTO ldiario VALUES('null','" . $maxPartida . "','14','".$saldoGasComp."','0','" . $anioActivo . "')";
$resultado = $conexion->query($consulta);
$consulta  = "INSERT INTO ldiario VALUES('null','" . $maxPartida . "','15','0','".$saldoGasComp."','" . $anioActivo . "')";
$resultado = $conexion->query($consulta);

$resultCompras= $conexion->query("select c.nombrecuenta as nombre, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','2') as codigocorto, p.idpartida as npartida, p.concepto as concepto, p.fecha as fecha, l.debe as debe, l.haber as haber FROM catalogo as c,partida as p, ldiario as l where SUBSTRING(c.codigocuenta,1,'2')='43' and p.idpartida=l.idpartida and l.idcatalogo=c.idcatalogo and p.idanio='".$anioActivo."' ORDER BY p.idpartida ASC");
if ($resultCompras) {
    while ($fila = $resultCompras->fetch_object()) {
      $saldoComp=$saldoComp+($fila->debe)-($fila->haber);
      }
}
//partida de ajuste 3
$maxPartida++;
$consultaA1  = "INSERT INTO partida VALUES('".$maxPartida."','Por compras netas.','31-12".$anioActivo."','" . $anioActivo . "')";
$resultadoA1 = $conexion->query($consultaA1);
//ldiario
$consulta  = "INSERT INTO ldiario VALUES('null','" . $maxPartida . "','16','".$saldoComp."','0','" . $anioActivo . "')";
$resultado = $conexion->query($consulta);
$consulta  = "INSERT INTO ldiario VALUES('null','" . $maxPartida . "','14','0','".$saldoComp."','" . $anioActivo . "')";
$resultado = $conexion->query($consulta);

$resulII= $conexion->query("select c.nombrecuenta as nombre, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','3') as codigocorto, p.idpartida as npartida, p.concepto as concepto, p.fecha as fecha, l.debe as debe, l.haber as haber FROM catalogo as c,partida as p, ldiario as l where SUBSTRING(c.codigocuenta,1,'3')='118' and p.idpartida=l.idpartida and l.idcatalogo=c.idcatalogo and p.idanio='".$anioActivo."' ORDER BY p.idpartida ASC");
if ($resulII) {
    while ($fila = $resulII->fetch_object()) {
      $saldoII=$saldoII+($fila->debe)-($fila->haber);
      }
}
//partida de ajuste 4
$maxPartida++;
$consultaA1  = "INSERT INTO partida VALUES('".$maxPartida."','Por mercaderia disponible.','31-12".$anioActivo."','" . $anioActivo . "')";
$resultadoA1 = $conexion->query($consultaA1);
//ldiario
$consulta  = "INSERT INTO ldiario VALUES('null','" . $maxPartida . "','14','".$saldoII."','0','" . $anioActivo . "')";
$resultado = $conexion->query($consulta);
$consulta  = "INSERT INTO ldiario VALUES('null','" . $maxPartida . "','22','0','".$saldoII."','" . $anioActivo . "')";
$resultado = $conexion->query($consulta);

$resultCompras= $conexion->query("select c.nombrecuenta as nombre, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','2') as codigocorto, p.idpartida as npartida, p.concepto as concepto, p.fecha as fecha, l.debe as debe, l.haber as haber FROM catalogo as c,partida as p, ldiario as l where SUBSTRING(c.codigocuenta,1,'2')='43' and p.idpartida=l.idpartida and l.idcatalogo=c.idcatalogo and p.idanio='".$anioActivo."' ORDER BY p.idpartida ASC");
if ($resultCompras) {
    while ($fila = $resultCompras->fetch_object()) {
      $saldoComp=$saldoComp+($fila->debe)-($fila->haber);
      }
}
//partida de ajuste 4
$maxPartida++;
$consultaA1  = "INSERT INTO partida VALUES('".$maxPartida."','Por costo de venta.','31-12".$anioActivo."','" . $anioActivo . "')";
$resultadoA1 = $conexion->query($consultaA1);
//ldiario
$consulta  = "INSERT INTO ldiario VALUES('null','" . $maxPartida . "','22','".$saldoComp."','0','" . $anioActivo . "')";
$resultado = $conexion->query($consulta);
$consulta  = "INSERT INTO ldiario VALUES('null','" . $maxPartida . "','14','0','".$saldoComp."','" . $anioActivo . "')";
$resultado = $conexion->query($consulta);

$resultCompras= $conexion->query("select c.nombrecuenta as nombre, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','2') as codigocorto, p.idpartida as npartida, p.concepto as concepto, p.fecha as fecha, l.debe as debe, l.haber as haber FROM catalogo as c,partida as p, ldiario as l where SUBSTRING(c.codigocuenta,1,'2')='43' and p.idpartida=l.idpartida and l.idcatalogo=c.idcatalogo and p.idanio='".$anioActivo."' ORDER BY p.idpartida ASC");
if ($resultCompras) {
    while ($fila = $resultCompras->fetch_object()) {
      $saldoComp=$saldoComp+($fila->debe)-($fila->haber);
      }
}
//partida de ajuste 5
$maxPartida++;
$consultaA1  = "INSERT INTO partida VALUES('".$maxPartida."','Por utilidad bruta.','31-12".$anioActivo."','" . $anioActivo . "')";
$resultadoA1 = $conexion->query($consultaA1);
//ldiario
$consulta  = "INSERT INTO ldiario VALUES('null','" . $maxPartida . "','11','".$saldoComp."','0','" . $anioActivo . "')";
$resultado = $conexion->query($consulta);
$consulta  = "INSERT INTO ldiario VALUES('null','" . $maxPartida . "','14','0','".$saldoComp."','" . $anioActivo . "')";
$resultado = $conexion->query($consulta);

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
//partida de ajuste 5
$maxPartida++;
$consultaA1  = "INSERT INTO partida VALUES('".$maxPartida."','Por eliminar cuenta ventas.','31-12".$anioActivo."','" . $anioActivo . "')";
$resultadoA1 = $conexion->query($consultaA1);
//ldiario
$consulta  = "INSERT INTO ldiario VALUES('null','" . $maxPartida . "','11','".$saldoV."','0','" . $anioActivo . "')";
$resultado = $conexion->query($consulta);
$consulta  = "INSERT INTO ldiario VALUES('null','" . $maxPartida . "','35','0','".$saldoV."','" . $anioActivo . "')";
$resultado = $conexion->query($consulta);

 ?>

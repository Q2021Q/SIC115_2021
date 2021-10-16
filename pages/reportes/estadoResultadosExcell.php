<?php
//Codigo que muestra solo los errores exceptuando los notice.
error_reporting(E_ALL & ~E_NOTICE);
?>
<?php
/** Incluir la libreria PHPExcel */
include '../../Classes/PHPExcel.php';
include "../../config/conexion.php";
$anioActivo=$_REQUEST["anio"];
$result = $conexion->query("select MIN(fecha) as fecha from partida where idanio=".$anioActivo);
if ($result) {
  while ($fila = $result->fetch_object()) {
    $fechaMinima=$fila->fecha;
    $fechaMinima=date("d-m-Y",strtotime($fechaMinima));
  }
}
$result2 = $conexion->query("select MAX(fecha) as fecha from partida  where idanio=".$anioActivo);
if ($result2) {
  while ($fila2 = $result2->fetch_object()) {
    $fechaMaxima=$fila2->fecha;
    $fechaMaxima =date("d-m-Y",strtotime($fechaMaxima));
  }
}
$inventarioF=$_REQUEST["if"];
// Crea un nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Establecer propiedades
$objPHPExcel->getProperties()
->setCreator("Cattivo")
->setLastModifiedBy("Cattivo")
->setTitle("EstadoResultados-PACHOLI")
->setSubject("EstadoResultados.")
->setDescription("Se mostrara el la mayorizacion por cada cuenta")
->setKeywords("Excel Office 2007 openxml php")
->setCategory("Libro Diario.");
//arrays que contendran los formatos de las fuentes para las celdas.
$styleArray = array(
    'font' => array(
        'bold' => false,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
    ),


);
$styleArray2 = array(
    'font' => array(
        'bold' => false,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
    ),

);
$styleArray3 = array(
    'font' => array(
        'bold' => false,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
    ),
  );
  $styleArray4 = array(
      'font' => array(
          'bold' => true,
      ),
      'alignment' => array(
          'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
      ),
    );

$cont=4;
// Agregar Informacion

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(44);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);

$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray4);
$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray4);
$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($styleArray4);
$objPHPExcel->getActiveSheet()->getStyle('B3')->applyFromArray($styleArray4);
$objPHPExcel->getActiveSheet()->getStyle('C3')->applyFromArray($styleArray4);

$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'Estado de Resultados ')
->mergeCells('A1:C1')
->setCellValue('A2', 'Del: '.$fechaMinima.'  al  '.$fechaMaxima.'')
->mergeCells('A2:C2')
->setCellValue('A3', '.')
->setCellValue('B3', '.')
->setCellValue('C3', '.');

//recuperamos de la bd y procedemos a insertar en las celdas.
include "../../config/conexion.php";
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
$objPHPExcel->getActiveSheet()->getStyle('A'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."4","Ventas");
$objPHPExcel->getActiveSheet()->getStyle('C'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('C'."4",$saldoV);
$objPHPExcel->getActiveSheet()->getStyle('A'."5")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."5","(-)  Rebajas y devoluciones S/Ventas");
$objPHPExcel->getActiveSheet()->getStyle('C'."5")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('C'."5",$saldoRDV);
$objPHPExcel->getActiveSheet()->getStyle('A'."6")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."6","(=)  Ventas Netas");
$objPHPExcel->getActiveSheet()->getStyle('C'."6")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('C'."6",$saldoV-$saldoRDV);
$VN=$saldoV-$saldoRDV;
//
$objPHPExcel->getActiveSheet()->getStyle('A'."7")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."7","(-)  Costo de Ventas");
$objPHPExcel->getActiveSheet()->getStyle('C'."7")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('C'."7",((($saldoComp+$saldoGasComp)-$saldoRDC))+$saldoII)-$inventarioF;
//
$objPHPExcel->getActiveSheet()->getStyle('A'."8")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."8","       Compras");
$objPHPExcel->getActiveSheet()->getStyle('B'."8")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('B'."8",$saldoComp);
//
$objPHPExcel->getActiveSheet()->getStyle('A'."9")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."9","       (+) Gastos s/ Compras");
$objPHPExcel->getActiveSheet()->getStyle('B'."9")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('B'."9",$saldoGasComp);
//
$objPHPExcel->getActiveSheet()->getStyle('A'."10")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."10","       (=) Compras Totales");
$objPHPExcel->getActiveSheet()->getStyle('B'."10")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('B'."10",$saldoComp+$saldoGasComp);
//
$objPHPExcel->getActiveSheet()->getStyle('A'."11")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."11","       (-) Rebajas y dev S/Compras");
$objPHPExcel->getActiveSheet()->getStyle('B'."11")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('B'."11",$saldoRDC);
//
$objPHPExcel->getActiveSheet()->getStyle('A'."12")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."12","       (=) Compras Netas");
$objPHPExcel->getActiveSheet()->getStyle('B'."12")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('B'."12",($saldoComp+$saldoGasComp)-$saldoRDC);
//
$objPHPExcel->getActiveSheet()->getStyle('A'."13")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."13","       (+) Inventario Inicial");
$objPHPExcel->getActiveSheet()->getStyle('B'."13")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('B'."13",$saldoII);
//
$objPHPExcel->getActiveSheet()->getStyle('A'."14")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."14","       (=) Mercaderia Disponible");
$objPHPExcel->getActiveSheet()->getStyle('B'."14")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('B'."14",(($saldoComp+$saldoGasComp)-$saldoRDC))+$saldoII;
//
$objPHPExcel->getActiveSheet()->getStyle('A'."15")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."15","       (-) Inventario Final");
$objPHPExcel->getActiveSheet()->getStyle('B'."15")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('B'."15",$inventarioF);
//
$CV=(((($saldoComp+$saldoGasComp)-$saldoRDC))+$saldoII)-$inventarioF;
$UB=$VN-$CV;
//
$objPHPExcel->getActiveSheet()->getStyle('A'."16")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."16","(=) Utilidad Bruta");
$objPHPExcel->getActiveSheet()->getStyle('C'."16")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('C'."16",$UB);
//

$objPHPExcel->getActiveSheet()->getStyle('A'."18")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."18","       Gastos de Administracion");
$objPHPExcel->getActiveSheet()->getStyle('B'."18")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('B'."18",$saldoGA);
//
$objPHPExcel->getActiveSheet()->getStyle('A'."19")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."19","       (+) Gastos de Ventas");
$objPHPExcel->getActiveSheet()->getStyle('B'."19")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('B'."19",$saldoGV);
//
$objPHPExcel->getActiveSheet()->getStyle('A'."20")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."20","       (+) Gastos Financieros");
$objPHPExcel->getActiveSheet()->getStyle('B'."20")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('B'."20",$saldoGF);
$GO=$saldoGA+$saldoGV+$saldoGF;
$objPHPExcel->getActiveSheet()->getStyle('A'."17")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."17","(-) Gastos de Operacion");
$objPHPExcel->getActiveSheet()->getStyle('C'."17")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('C'."17",$GO);
//
$objPHPExcel->getActiveSheet()->getStyle('A'."21")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."21","(=) Utilidad de Operacion");
$objPHPExcel->getActiveSheet()->getStyle('C'."21")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('C'."21",$UB-$GO);
$UO=$UB-$GO;
//
$objPHPExcel->getActiveSheet()->getStyle('A'."22")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."22","(-) Otros Gastos");
$objPHPExcel->getActiveSheet()->getStyle('C'."22")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('C'."22",$saldoOG);
//
$objPHPExcel->getActiveSheet()->getStyle('A'."23")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."23","(+) Otros Ingresos");
$objPHPExcel->getActiveSheet()->getStyle('C'."23")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('C'."23",$saldoOI);
//
$objPHPExcel->getActiveSheet()->getStyle('A'."24")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."24","(=) Utilidad antes de Impuesto y Reserva");
$objPHPExcel->getActiveSheet()->getStyle('C'."24")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('C'."24",$UO-$saldoOG+$saldoOI);
//
$UAIR=$UO-$saldoOG+$saldoOI;
$objPHPExcel->getActiveSheet()->getStyle('A'."25")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."25","(-) Reserva Legal");
$objPHPExcel->getActiveSheet()->getStyle('C'."25")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('C'."25",$UAIR*0.07);
$RL=$UAIR*0.07;
$objPHPExcel->getActiveSheet()->getStyle('A'."26")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."26","(=) Utilidad antes de Impuesto Sobre la Renta");
$objPHPExcel->getActiveSheet()->getStyle('C'."26")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('C'."26",$UAIR-$RL);
$UAISR=$UAIR-$RL;
$ISR=$UAISR*0.13;
$objPHPExcel->getActiveSheet()->getStyle('A'."27")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."27","(-) Impuesto sobre la Renta");
$objPHPExcel->getActiveSheet()->getStyle('C'."27")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('C'."27",$ISR);
$objPHPExcel->getActiveSheet()->getStyle('A'."28")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."28","(=) Utilidad del Ejercicio");
$objPHPExcel->getActiveSheet()->getStyle('C'."28")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('C'."28",$UAISR-$ISR);

// Renombrar Hoja
$objPHPExcel->getActiveSheet()->setTitle('EstadoResultados');
// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
$objPHPExcel->setActiveSheetIndex(0);
// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="EstadoResultados.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>

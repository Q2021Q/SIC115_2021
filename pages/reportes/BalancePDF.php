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
$rendererName = PHPExcel_Settings::PDF_RENDERER_TCPDF;
$rendererLibrary = 'tcpdf';
$rendererLibraryPath = '../../' . $rendererLibrary;
// Crea un nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Establecer propiedades
$objPHPExcel->getProperties()
->setCreator("Cattivo")
->setLastModifiedBy("Cattivo")
->setTitle("BalanceGeneral-PACHOLI")
->setSubject("BalanceGeneral.")
->setDescription("Se mostrara Se mostrara el balance general del ciclo")
->setKeywords("Excel Office 2007 openxml php")
->setCategory("Balance General.");
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
->setCellValue('A1', 'Balance General ')
->mergeCells('A1:C1')
->setCellValue('A2', 'Del: '.$fechaMinima.'  al  '.$fechaMaxima.'')
->mergeCells('A2:C2')
->setCellValue('A3', '.')
->setCellValue('B3', '.')
->setCellValue('C3', '.');

//recuperamos de la bd y procedemos a insertar en las celdas.
include "../../config/conexion.php";
$resultIVA= $conexion->query("select c.nombrecuenta as nombre, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','3') as codigocorto, p.idpartida as npartida, p.concepto as concepto, p.fecha as fecha, l.debe as debe, l.haber as haber FROM catalogo as c,partida as p, ldiario as l where SUBSTRING(c.codigocuenta,1,'3')='120' and p.idpartida=l.idpartida and l.idcatalogo=c.idcatalogo and p.idanio='".$anioActivo."' ORDER BY p.idpartida ASC");
if ($resultIVA) {
    while ($fila = $resultIVA->fetch_object()) {

        $IVA=$IVA+($fila->debe)-($fila->haber);

      }
  }
//Reporte Balance Genral
//para activo corriente
$objPHPExcel->getActiveSheet()->getStyle('A'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."3","ACTIVO")
->mergeCells('A3:C3');
$objPHPExcel->getActiveSheet()->getStyle('A'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."4","ACTIVO CORRIENTE")
->mergeCells('A4:B4');
$cont=5;
$result = $conexion->query("select c.nombrecuenta as nombre,c.idcatalogo as id, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','2') as codigocorto FROM catalogo as c where SUBSTRING(c.codigocuenta,1,'2')='11' and c.codigocuenta!='118' and c.nivel='3' ORDER BY c.codigocuenta ASC");
if ($result) {
while ($fila = $result->fetch_object()) {
  $objPHPExcel->getActiveSheet()->getStyle('A'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
  ->setCellValue('A'.$cont,$fila->nombre);
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
$objPHPExcel->getActiveSheet()->getStyle('B'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('B'.$cont,$saldo);
$saldoTotal=$saldoTotal+$saldo;
$cont++;
$saldo=0;
}
}
$objPHPExcel->getActiveSheet()->getStyle('A'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'.$cont,"Inventario Final");
$objPHPExcel->getActiveSheet()->getStyle('B'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('B'.$cont,$inventarioF);
$cont++;
$objPHPExcel->getActiveSheet()->getStyle('A'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'.$cont,"IVA Credito Fiscal");
$objPHPExcel->getActiveSheet()->getStyle('B'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('B'.$cont,$IVA);
$objPHPExcel->getActiveSheet()->getStyle('C'."4")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('C'."4",($saldoTotal+$inventarioF+$IVA));
 $AC=($saldoTotal+$inventarioF+$IVA);
 $saldoTotal=0;
$cont++;
}
// //Activo no CORRIENTE
$objPHPExcel->getActiveSheet()->getStyle('A'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."$cont","ACTIVO NO CORRIENTE")
->mergeCells('A'.$cont.':B'.$cont);
$Pos=$cont;
$cont++;
$result = $conexion->query("select c.nombrecuenta as nombre,c.idcatalogo as id, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','2') as codigocorto FROM catalogo as c where SUBSTRING(c.codigocuenta,1,'2')='13' and c.nivel='3' ORDER BY c.codigocuenta ASC");
if ($result) {
while ($fila = $result->fetch_object()) {
  $objPHPExcel->getActiveSheet()->getStyle('A'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
  ->setCellValue('A'.$cont,$fila->nombre);
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
$objPHPExcel->getActiveSheet()->getStyle('B'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('B'.$cont,$saldo);
$saldoTotal=$saldoTotal+$saldo;
$cont++;
$saldo=0;
}
}
$objPHPExcel->getActiveSheet()->getStyle('C'."$Pos")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('C'."$Pos",$saldoTotal);
 $ANC=$saldoTotal;
 $objPHPExcel->getActiveSheet()->getStyle('A'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
 ->setCellValue('A'."$cont","TOTAL ACTIVO")
 ->mergeCells('A'.$cont.':B'.$cont);
 $objPHPExcel->getActiveSheet()->getStyle('C'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
 ->setCellValue('C'."$cont",($AC+$ANC));
$saldoTotal=0;
$cont++;
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
// //PASIVO
$objPHPExcel->getActiveSheet()->getStyle('A'."$cont")->applyFromArray($styleArray4);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."$cont","PASIVO")
->mergeCells('A'.$cont.':C'.$cont);
$cont++;
$objPHPExcel->getActiveSheet()->getStyle('A'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."$cont","PASIVO CORRIENTE")
->mergeCells('A'.$cont.':B'.$cont);
$pos=$cont;
$cont++;
$result = $conexion->query("select c.nombrecuenta as nombre,c.idcatalogo as id, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','2') as codigocorto FROM catalogo as c where SUBSTRING(c.codigocuenta,1,'2')='21' and c.nivel='3' ORDER BY c.codigocuenta ASC");
if ($result) {
while ($fila = $result->fetch_object()) {
  $objPHPExcel->getActiveSheet()->getStyle('A'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
  ->setCellValue('A'.$cont,$fila->nombre);
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
$objPHPExcel->getActiveSheet()->getStyle('B'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('B'.$cont,$saldo);
$saldoTotal=$saldoTotal+$saldo;
$cont++;
$saldo=0;
}
}
$UAIR=(((($saldoV-$saldoRDV)-(((($saldoComp+$saldoGasComp)-$saldoRDC)+$saldoII)-$inventarioF))-($saldoGA+$saldoGV+$saldoGF))-$saldoOG)+$saldoOI;
$RL=($UAIR*0.07);
$UAIR-$RL;
$ISR=($UAIR-$RL)*0.30;
$objPHPExcel->getActiveSheet()->getStyle('A'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'.$cont,"Impuesto sobre la Renta (30%)");
$objPHPExcel->getActiveSheet()->getStyle('B'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('B'.$cont,$ISR);
$PC=($saldoTotal+$ISR);
$objPHPExcel->getActiveSheet()->getStyle('C'."$pos")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('C'.$pos,$PC);
$cont++;
$saldoTotal=0;
}
// //Pasivo no corriente
$objPHPExcel->getActiveSheet()->getStyle('A'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."$cont","PASIVO NO CORRIENTE")
->mergeCells('A'.$cont.':B'.$cont);
$pos=$cont;
$cont++;
$result = $conexion->query("select c.nombrecuenta as nombre,c.idcatalogo as id, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','2') as codigocorto FROM catalogo as c where SUBSTRING(c.codigocuenta,1,'2')='22' and c.nivel='3' ORDER BY c.codigocuenta ASC");
if ($result) {
while ($fila = $result->fetch_object()) {
  $objPHPExcel->getActiveSheet()->getStyle('A'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
  ->setCellValue('A'.$cont,$fila->nombre);
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
$objPHPExcel->getActiveSheet()->getStyle('B'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('B'.$cont,$saldo);
$saldoTotal=$saldoTotal+$saldo;
$cont++;
$saldo=0;
}
}
$objPHPExcel->getActiveSheet()->getStyle('C'."$pos")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('C'.$pos,$saldoTotal);
$PNC=$saldoTotal;
$objPHPExcel->getActiveSheet()->getStyle('A'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."$cont","TOTAL PASIVO")
->mergeCells('A'.$cont.':B'.$cont);
$objPHPExcel->getActiveSheet()->getStyle('C'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('C'."$cont",($PNC+$PC));
$tp=($PNC+$PC);
$cont++;
$saldoTotal=0;
}
// // PATRIMONO
$objPHPExcel->getActiveSheet()->getStyle('A'."$cont")->applyFromArray($styleArray4);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."$cont","PATRIMONIO")
->mergeCells('A'.$cont.':C'.$cont);
$cont++;
$pos=$cont;
$cont++;
$result = $conexion->query("select c.nombrecuenta as nombre,c.idcatalogo as id, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','2') as codigocorto FROM catalogo as c where SUBSTRING(c.codigocuenta,1,'2')='31' and c.nivel='3' ORDER BY c.codigocuenta ASC");
if ($result) {
while ($fila = $result->fetch_object()) {
  $objPHPExcel->getActiveSheet()->getStyle('A'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
  ->setCellValue('A'.$cont,$fila->nombre);
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
$objPHPExcel->getActiveSheet()->getStyle('B'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('B'.$cont,$saldo);
$saldoTotal=$saldoTotal+$saldo;
$cont++;
$saldo=0;
}
}
$UAIR=(((($saldoV-$saldoRDV)-(((($saldoComp+$saldoGasComp)-$saldoRDC)+$saldoII)-$inventarioF))-($saldoGA+$saldoGV+$saldoGF))-$saldoOG)+$saldoOI;
$RL=($UAIR*0.07);
$objPHPExcel->getActiveSheet()->getStyle('A'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'.$cont,"Reserva Legal");
$objPHPExcel->getActiveSheet()->getStyle('B'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('B'.$cont,$RL);
$cont++;
$UAIR-$RL;
$ISR=($UAIR-$RL)*0.30;
$UE=($UAIR-$RL)-$ISR;
$objPHPExcel->getActiveSheet()->getStyle('A'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'.$cont,"Utilidad del Ejercicio");
$objPHPExcel->getActiveSheet()->getStyle('B'."$cont")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('B'.$cont,$UE);

$objPHPExcel->getActiveSheet()->getStyle('A'."$pos")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('A'.$pos,"TOTAL PATRIMONIO")
->mergeCells('A'.$pos.':B'.$pos);
$objPHPExcel->getActiveSheet()->getStyle('C'."$pos")->applyFromArray($styleArray3);  $objPHPExcel->getActiveSheet()
->setCellValue('C'.$pos,($saldoTotal+$RL+$UE));
$cont++;
$objPHPExcel->getActiveSheet()->getStyle('A'."$cont")->applyFromArray($styleArray4);  $objPHPExcel->getActiveSheet()
->setCellValue('A'."$cont","TOTAL PASIVO + PATRIMONIO")
->mergeCells('A'.$cont.':C'.$cont);
$cont++;
$objPHPExcel->getActiveSheet()->getStyle('A'."$cont")->applyFromArray($styleArray4);  $objPHPExcel->getActiveSheet()
->setCellValue('A'.$cont,(($saldoTotal+$RL+$UE)+$tp))
->mergeCells('A'.$cont.':C'.$cont);
 $saldoTotal=0;
}


// Renombrar Hoja
$objPHPExcel->getActiveSheet()->setTitle('BalanceGeneral');
// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
$objPHPExcel->setActiveSheetIndex(0);
// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
if (!PHPExcel_Settings::setPdfRenderer(
		$rendererName,
		$rendererLibraryPath
	)) {
	die(
		'NOTICE: Please set the $rendererName and $rendererLibraryPath values' .
		'<br />' .
		'at the top of this script as appropriate for your directory structure'
	);
}
// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/pdf');
header('Content-Disposition: attachment;filename="BalanceGeneral.pdf"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
$objWriter->save('php://output');
exit;
?>

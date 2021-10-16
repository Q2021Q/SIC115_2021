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

$nivelMayorizacion=$_REQUEST["nivel"];
$rendererName = PHPExcel_Settings::PDF_RENDERER_TCPDF;
$rendererLibrary = 'tcpdf';
$rendererLibraryPath = '../../' . $rendererLibrary;
// Crea un nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Establecer propiedades
$objPHPExcel->getProperties()
->setCreator("Cattivo")
->setLastModifiedBy("Cattivo")
->setTitle("Libro Mayor-PACHOLI")
->setSubject("Libro Mayor.")
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
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
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

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(13);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(13);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(13);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(13);
$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray4);
$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray4);
$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($styleArray4);
$objPHPExcel->getActiveSheet()->getStyle('B3')->applyFromArray($styleArray4);
$objPHPExcel->getActiveSheet()->getStyle('C3')->applyFromArray($styleArray4);
$objPHPExcel->getActiveSheet()->getStyle('D3')->applyFromArray($styleArray4);
$objPHPExcel->getActiveSheet()->getStyle('E3')->applyFromArray($styleArray4);
$objPHPExcel->getActiveSheet()->getStyle('F3')->applyFromArray($styleArray4);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'LIBRO MAYOR     Nivel: '.$nivelMayorizacion)
->mergeCells('A1:F1')
->setCellValue('A2', 'Del: '.$fechaMinima.'  al  '.$fechaMaxima.'')
->mergeCells('A2:F2')
->setCellValue('A3', 'Fecha')
->setCellValue('B3', 'CONCEPTO')
->setCellValue('C3', 'P/F')
->setCellValue('D3', 'Debe')
->setCellValue('E3', 'Haber')
->setCellValue('F3', 'Saldo');
//recuperamos de la bd y procedemos a insertar en las celdas.
include "../../config/conexion.php";
$result = $conexion->query("select idcatalogo as id,saldo, nombrecuenta as nombre, codigocuenta as codigo from catalogo where nivel=".$nivelMayorizacion." order by codigocuenta");
if ($result) {
    while ($fila = $result->fetch_object()) {
      $nombre=$fila->nombre;
      $id=$fila->id;
      $codigo=$fila->codigo;
      $loncadena=strlen($codigo);
      $resultSubcuenta= $conexion->query("select c.nombrecuenta as nombre, c.codigocuenta as codigo, SUBSTRING(c.codigocuenta,'1','".$loncadena."') as codigocorto, p.idpartida as npartida, p.concepto as concepto, p.fecha as fecha, l.debe as debe, l.haber as haber FROM catalogo as c,partida as p, ldiario as l where SUBSTRING(c.codigocuenta,1,'".$loncadena."')='".$codigo."' and p.idpartida=l.idpartida and l.idcatalogo=c.idcatalogo and p.idanio='".$anioActivo."' ORDER BY p.idpartida ASC");
      if ($resultSubcuenta) {
          if (($resultSubcuenta->num_rows)<1) {
        }else {
          $objPHPExcel->getActiveSheet()->getStyle('A'."$cont")->applyFromArray($styleArray4);  $objPHPExcel->getActiveSheet()
          ->setCellValue('A'."$cont",$nombre)
          ->mergeCells("A".$cont.":F".$cont);
          $cont++;
          while ($fila2 = $resultSubcuenta->fetch_object()) {
            $fecha=$fila2->fecha;
            $concepto=$fila2->concepto;
            $npartida=$fila2->npartida;
            $debe=$fila2->debe;
            $haber=$fila2->haber;

            $objPHPExcel->getActiveSheet()->getStyle('A'."$cont")->applyFromArray($styleArray2);
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'."$cont",$fecha);
            $objPHPExcel->getActiveSheet()->getStyle('B'."$cont")->applyFromArray($styleArray2);
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B'."$cont",$concepto);
            $objPHPExcel->getActiveSheet()->getStyle('C'."$cont")->applyFromArray($styleArray2);
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C'."$cont",$npartida);
            $objPHPExcel->getActiveSheet()->getStyle('D'."$cont")->applyFromArray($styleArray2);
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D'."$cont",$debe);
            $objPHPExcel->getActiveSheet()->getStyle('E'."$cont")->applyFromArray($styleArray2);
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('E'."$cont",$haber);
              if ($fila->saldo=="DEUDOR") {
                $saldo=$saldo+($fila2->debe)-($fila2->haber);
              }else {
          $saldo=$saldo-($fila2->debe)+($fila2->haber);
          }
          $objPHPExcel->getActiveSheet()->getStyle('F'."$cont")->applyFromArray($styleArray2);
          $objPHPExcel->setActiveSheetIndex(0)
          ->setCellValue('F'."$cont",$saldo);
             $cont++;
            }

         $saldo=0;

        }

      }else {
        msg("Error");
      }
    }
  }

// fin de lirbo mayor
// Renombrar Hoja
$objPHPExcel->getActiveSheet()->setTitle('LibroMayor');
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
header('Content-Disposition: attachment;filename="librodiario.pdf"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
$objWriter->save('php://output');
exit;
?>

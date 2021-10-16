<?php
//Codigo que muestra solo los errores exceptuando los notice.
error_reporting(E_ALL & ~E_NOTICE);
?>
<?php
/** Incluir la libreria PHPExcel */
include '../../Classes/PHPExcel.php';
include "../../config/conexion.php";
$anioActivo=$_REQUEST["anio"];
$result = $conexion->query("select MIN(fecha) from partida  where idanio=".$anioActivo);
if ($result) {
  while ($fila = $result->fetch_object()) {
    $fechaMinima=$fila->fecha;
  }
}
$result = $conexion->query("select MAX(fecha) from partida  where idanio=".$anioActivo);
if ($result2) {
  while ($fila = $result2->fetch_object()) {
    $fechaMaxima=$fila->fecha;
  }
}


$rendererName = PHPExcel_Settings::PDF_RENDERER_TCPDF;
$rendererLibrary = 'tcpdf';
$rendererLibraryPath = '../../' . $rendererLibrary;
// Crea un nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Establecer propiedades
$objPHPExcel->getProperties()
->setCreator("Cattivo")
->setLastModifiedBy("Cattivo")
->setTitle("Libro Diario-PACHOLI")
->setSubject("Libro Diario.")
->setDescription("Se mostrara todo el registro diario de nuestras partidas")
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
    'borders' => array(
        'top' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
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

$cont=3;
// Agregar Informacion
$objPHPExcel->getActiveSheet()->getStyle('B1')
->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(11);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(9);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(28);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(13);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(13);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('B1', 'LIBRO DIARIO ')
->mergeCells('B1:F1')
->setCellValue('B2', 'Fecha')

->setCellValue('C2', 'Codigo')
->setCellValue('D2', 'Concepto')
->setCellValue('E2', 'Debe')
->setCellValue('F2', 'Haber');
//recuperamos de la bd y procedemos a insertar en las celdas.
include "../../config/conexion.php";
$result = $conexion->query("select * from partida where idanio='".$anioActivo."' order by idpartida ASC");
if ($result) {
  while ($fila = $result->fetch_object()) {
      $objPHPExcel->getActiveSheet()->getStyle('B'."$cont")->applyFromArray($styleArray);
      $objPHPExcel->getActiveSheet()->getStyle('C'."$cont")->applyFromArray($styleArray);
      $objPHPExcel->getActiveSheet()->getStyle('D'."$cont")->applyFromArray($styleArray);
      $objPHPExcel->getActiveSheet()->getStyle('E'."$cont")->applyFromArray($styleArray);
      $objPHPExcel->getActiveSheet()->getStyle('F'."$cont")->applyFromArray($styleArray);
      $objPHPExcel->getActiveSheet()->getStyle('C'."$cont")->applyFromArray($styleArray4);
        $objPHPExcel->getActiveSheet()
        ->setCellValue('B'."$cont",$fila->fecha)

        ->setCellValue('C'."$cont","Partida #".$fila->idpartida)
        ->mergeCells("C".$cont.":F".$cont);

      $cont++;
      $idpartida=$fila->idpartida;
      $result2 = $conexion->query("select * from ldiario where idpartida='".$idpartida."'order by debe DESC");
      if ($result2) {
        while ($fila2 = $result2->fetch_object()) {
          $cuenta=$fila2->idcatalogo;
          $debe=$fila2->debe;
          $haber=$fila2->haber;
          //para mostrar la Cuenta
          $result3 = $conexion->query("select * from catalogo where idcatalogo=".$cuenta);
          if ($result3) {
            while ($fila3 = $result3->fetch_object()) {
              $codigocuenta=$fila3->codigocuenta;
              $nombrecuenta=$fila3->nombrecuenta;
                // echo "<td align='center'> " . $codigocuenta . "</td>";
                $objPHPExcel->getActiveSheet()->getStyle('C'."$cont")->applyFromArray($styleArray2);
                $objPHPExcel->setActiveSheetIndex(0)
                // $objPHPExcel->getActiveSheet()
                ->setCellValue('C'."$cont",$codigocuenta);
                if ($debe>=$haber) {
                  $objPHPExcel->getActiveSheet()->getStyle('D'."$cont")->applyFromArray($styleArray2);
                  $objPHPExcel->getActiveSheet()
                  ->setCellValue('D'."$cont",$nombrecuenta);
                }else {
                  $objPHPExcel->getActiveSheet()->getStyle('D'."$cont")->applyFromArray($styleArray3);
                  $objPHPExcel->getActiveSheet()
                  ->setCellValue('D'."$cont",$nombrecuenta);
                }
                if ($debe==0) {
                  $objPHPExcel->getActiveSheet()->getStyle('E'."$cont")->applyFromArray($styleArray3);
                  $objPHPExcel->getActiveSheet()
                  ->setCellValue('E'."$cont","");
                }else {
                  $objPHPExcel->getActiveSheet()->getStyle('E'."$cont")->applyFromArray($styleArray2);
                  $objPHPExcel->getActiveSheet()
                  ->setCellValue('E'."$cont","$".$debe);
                }
                if ($haber==0) {
                  $objPHPExcel->getActiveSheet()->getStyle('F'."$cont")->applyFromArray($styleArray3);
                  $objPHPExcel->getActiveSheet()
                  ->setCellValue('F'."$cont","");
                }else {
                  $objPHPExcel->getActiveSheet()->getStyle('F'."$cont")->applyFromArray($styleArray2);
                  $objPHPExcel->getActiveSheet()
                  ->setCellValue('F'."$cont","$".$haber);
                }
                $cont++;
            }

          }
        }
        $objPHPExcel->getActiveSheet()->getStyle('C'."$cont")->applyFromArray($styleArray4);

        $objPHPExcel->getActiveSheet()
        ->setCellValue('C'."$cont","V/".$fila->concepto)
        ->mergeCells("C".$cont.":F".$cont);
        $cont++;
      }
}
}
// Renombrar Hoja
$objPHPExcel->getActiveSheet()->setTitle('LibroDiario');
// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
$objPHPExcel->setActiveSheetIndex(0);
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

<?php
//Codigo que muestra solo los errores exceptuando los notice.
error_reporting(E_ALL & ~E_NOTICE);
?>
<?php
/** Incluir la libreria PHPExcel */
include '../../Classes/PHPExcel.php';

// Crea un nuevo objeto PHPExcel
$objPHPExcel = new PHPExcel();

// Establecer propiedades
$objPHPExcel->getProperties()
->setCreator("Cattivo")
->setLastModifiedBy("Cattivo")
->setTitle("Catalogo de Cuentas-PACHOLI")
->setSubject("Catalogo de cuentas.")
->setDescription("Se van a almacenar todas las cuentas de nuestro catalogo.")
->setKeywords("Excel Office 2007 openxml php")
->setCategory("Catalogo.");
//arrays que contendran los formatos de las fuentes para las celdas.
$styleArray = array(
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
  $styleArray5 = array(
      'font' => array(
          'bold' => true,
      ),
      'alignment' => array(
          'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
      ),
    );
$cont=3;
// Agregar Informacion
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(28);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray4);
$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray5);
$objPHPExcel->getActiveSheet()->getStyle('B2')->applyFromArray($styleArray5);
$objPHPExcel->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray5);
$objPHPExcel->getActiveSheet()->getStyle('D2')->applyFromArray($styleArray5);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'CATALOGO DE CUENTAS')
->mergeCells('A1:D1')
->setCellValue('A2', 'Codigo')
->setCellValue('B2', 'Nombre')
->setCellValue('C2', 'Tipo')
->setCellValue('D2', 'Saldo');
//recuperamos de la bd y procedemos a insertar en las celdas.

include "../../config/conexion.php";
$result = $conexion->query("select * from catalogo order by codigocuenta");
if ($result) {
  while ($fila = $result->fetch_object()) {
    if($fila->tipocuenta=='CUENTASRESULTDEUDORA' || $fila->tipocuenta=='CUENTASRESULTACREEDO'){
      $tipo='CUENTA DE RESULTADO';
    }else{
        $tipo=$fila->tipocuenta;
      }
      $objPHPExcel->getActiveSheet()->getStyle('A'."$cont")->applyFromArray($styleArray);
      $objPHPExcel->setActiveSheetIndex(0)
      ->setCellValue('A'."$cont",$fila->codigocuenta)
      ->setCellValue('B'."$cont",$fila->nombrecuenta)
      ->setCellValue('C'."$cont",$tipo)
      ->setCellValue('D'."$cont",$fila->saldo);
        $cont++;
      }

}
// Renombrar Hoja
$objPHPExcel->getActiveSheet()->setTitle('CATALOGO DE CUENTAS');

// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
$objPHPExcel->setActiveSheetIndex(0);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Catalogo.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body onload="window.close()">

  </body>
</html>

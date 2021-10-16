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
$result2 = $conexion->query("select MAX(fecha) as fecha from partida where idanio=".$anioActivo);
if ($result2) {
  while ($fila = $result2->fetch_object()) {
    $fechaMaxima=$fila->fecha;
    $fechaMaxima =date("d-m-Y",strtotime($fechaMaxima));
  }
}


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
$objPHPExcel->getActiveSheet()->getColumnDimension('a')->setWidth(13);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(9);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(28);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(13);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(13);
$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray4);
$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray4);
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A1', 'LIBRO DIARIO ')
->mergeCells('A1:E1')
->setCellValue('A2', 'Del  '.$fechaMinima.'  al  '.$fechaMaxima)
->mergeCells('A2:E2')
->setCellValue('A3', 'Fecha')
->setCellValue('B3', 'Codigo')
->setCellValue('C3', 'Concepto')
->setCellValue('D3', 'Debe')
->setCellValue('E3', 'Haber');
//recuperamos de la bd y procedemos a insertar en las celdas.
include "../../config/conexion.php";
$result = $conexion->query("select * from partida where idanio='".$anioActivo."' order by idpartida ASC");
if ($result) {
  while ($fila = $result->fetch_object()) {
      $objPHPExcel->getActiveSheet()->getStyle('A'."$cont")->applyFromArray($styleArray);
      $objPHPExcel->getActiveSheet()->getStyle('B'."$cont")->applyFromArray($styleArray);
      $objPHPExcel->getActiveSheet()->getStyle('C'."$cont")->applyFromArray($styleArray);
      $objPHPExcel->getActiveSheet()->getStyle('D'."$cont")->applyFromArray($styleArray);
      $objPHPExcel->getActiveSheet()->getStyle('E'."$cont")->applyFromArray($styleArray);
      $objPHPExcel->getActiveSheet()->getStyle('B'."$cont")->applyFromArray($styleArray4);
        $objPHPExcel->getActiveSheet()
        ->setCellValue('A'."$cont",$fila->fecha)

        ->setCellValue('B'."$cont","Partida #".$fila->idpartida)
        ->mergeCells("B".$cont.":E".$cont);

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
                $objPHPExcel->getActiveSheet()->getStyle('B'."$cont")->applyFromArray($styleArray2);
                $objPHPExcel->setActiveSheetIndex(0)
                // $objPHPExcel->getActiveSheet()
                ->setCellValue('B'."$cont",$codigocuenta);
                if ($debe>=$haber) {
                  $objPHPExcel->getActiveSheet()->getStyle('C'."$cont")->applyFromArray($styleArray2);
                  $objPHPExcel->getActiveSheet()
                  ->setCellValue('C'."$cont",$nombrecuenta);
                }else {
                  $objPHPExcel->getActiveSheet()->getStyle('C'."$cont")->applyFromArray($styleArray3);
                  $objPHPExcel->getActiveSheet()
                  ->setCellValue('C'."$cont",$nombrecuenta);
                }
                if ($debe==0) {
                  $objPHPExcel->getActiveSheet()->getStyle('D'."$cont")->applyFromArray($styleArray3);
                  $objPHPExcel->getActiveSheet()
                  ->setCellValue('D'."$cont","");
                }else {
                  $objPHPExcel->getActiveSheet()->getStyle('D'."$cont")->applyFromArray($styleArray2);
                  $objPHPExcel->getActiveSheet()
                  ->setCellValue('D'."$cont","$".$debe);
                }
                if ($haber==0) {
                  $objPHPExcel->getActiveSheet()->getStyle('E'."$cont")->applyFromArray($styleArray3);
                  $objPHPExcel->getActiveSheet()
                  ->setCellValue('E'."$cont","");
                }else {
                  $objPHPExcel->getActiveSheet()->getStyle('E'."$cont")->applyFromArray($styleArray2);
                  $objPHPExcel->getActiveSheet()
                  ->setCellValue('E'."$cont","$".$haber);
                }
                $cont++;
            }

          }
        }
        $objPHPExcel->getActiveSheet()->getStyle('B'."$cont")->applyFromArray($styleArray4);

        $objPHPExcel->getActiveSheet()
        ->setCellValue('B'."$cont","V/".$fila->concepto)
        ->mergeCells("B".$cont.":E".$cont);
        $cont++;
      }

    //  echo "<td align='center' >V/ ".$fila->concepto."</td>";
}
}

// $result = $conexion->query("select * from catalogo order by codigocuenta");
// if ($result) {
//   while ($fila = $result->fetch_object()) {
//     $objPHPExcel->getActiveSheet()->getStyle('B'."$cont")->applyFromArray($styleArray);
//     $objPHPExcel->setActiveSheetIndex(0)
//     ->setCellValue('B'."$cont",$fila->codigocuenta)
//     ->setCellValue('C'."$cont",$fila->nombrecuenta)
//     ->setCellValue('D'."$cont",$fila->tipocuenta)
//     ->setCellValue('E'."$cont",$fila->saldo);
//       $cont++;
//
//   }
// }
// Renombrar Hoja
$objPHPExcel->getActiveSheet()->setTitle('LibroDiario');
// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
$objPHPExcel->setActiveSheetIndex(0);
// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="LibroDiario.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>

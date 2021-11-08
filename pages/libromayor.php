<?php
//Codigo que muestra solo los errores exceptuando los notice.
error_reporting(E_ALL & ~E_NOTICE);
?>
<?php
session_start();

if($_SESSION["logueado"] == TRUE) {
include "../config/conexion.php";
$result = $conexion->query("select * from anio where estado=1");
if($result)
{
  $fila = $result->fetch_object();
  $anioActivo = $fila->idanio;
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
  <link rel="stylesheet" type="text/css" href="../asset/css/plugins/font-awesome.min.css"/>
  <link rel="stylesheet" type="text/css" href="../asset/css/plugins/datatables.bootstrap.min.css"/>
  <link rel="stylesheet" type="text/css" href="../asset/css/plugins/animate.min.css"/>
  <link href="../asset/css/style.css" rel="stylesheet">
  <!-- end: Css -->

  <link rel="shortcut icon" href="../asset/img/logomi.jpg">
      <script type="text/javascript">
          //funcion para exportar la tabla del catalogo a excell
        function mayorExcell()
        {
          var nivel=document.getElementById("nivel").value;
          var anio=document.getElementById("anioActivo").value;
          const ventana = window.open("reportes/libromayorExcell.php?nivel="+nivel+"&anio="+anio+"","_blank");
          //window.setTimeout(cerrarVentana(ventana), 80000);
        }
        /*function cerrarVentana(ventana){
          ventana.close();
        }*/
        function mayorPDF()
        {
          var nivel=document.getElementById("nivel").value;
          var anio=document.getElementById("anioActivo").value;
          const ventana = window.open("reportes/libromayorPDF.php?nivel="+nivel+"&anio="+anio+"","_blank");
        }
      </script>
</head>

<body id="mimin" class="dashboard">
       <?php include "header.php"?>
          <div class="col-md-12 nav-wrapper">
              <li><a href="">Inicio</a></li>
              <li><a href="">Servicios</a>
          </div>

      <div class="container-fluid mimin-wrapper">

<?php include "menu.php";?>
            <!-- start: Content -->
            <div id="content">
               <div class="panel box-shadow-none content-header">
                  <div class="panel-body">
                    <div class="col-md-12">
                        <h3 class="animated fadeInLeft">Libro Mayor</h3>
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
                     <a class="btn btn-primary" href="./lmayorAexcel.php">Descargar Libro Mayor</a>
                     <center>
                      <h3>Libro Mayor</h3>
                     </center>
                  </div>
                  <div class="panel-body">
                    <div class="responsive-table">
                    <table id="datatables-example" class="table table-striped table-bordered table-hover " width="100%" cellspacing="0">
                    <thead>
                      <tr>
                      <th style="width:125px;" >Concepto</th>
                        <th>Fecha</th>
                        <th>Debe</th>
                        <th>Haber</th>
                        <th >Saldo</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                    //Agarrar las cuentas del catalogo de acuerdo al nivel seleccionado
                    //si no se selecciona nivel, por defecto sera nivel 1
                    //inicio consulta por nivel
                    $saldo=0;
                    $result = $conexion->query("select idcatalogo as id,saldo, nombrecuenta as nombre, codigocuenta as codigo from catalogo where nivel=3 order by codigocuenta asc");
                    if ($result) {
                        while ($fila = $result->fetch_object()) {
                          $nombre=$fila->nombre;
                          $id=$fila->id;
                          $codigo=$fila->codigo;
                          //obtener total de caracteres del codigo segun el nivelcuenta
                          //$loncadena=strlen($codigo);
                          //inicio de la consulta para encontrar las cuentas que son subcuentas de la cuenta anterior
                          $resultSubcuenta= $conexion->query("Select c.nombrecuenta as nombre, c.codigocuenta as codigo, C.saldo as saldo, p.idpartida as npartida, p.concepto, p.fecha as fecha, l.debe as debe, l.haber as haber FROM catalogo c INNER JOIN ldiario l ON c.idcatalogo = l.idcatalogo INNER JOIN partida p ON l.idpartida = p.idpartida WHERE  SUBSTRING(c.codigocuenta, 1,4) = '".$codigo."' AND p.idanio='".$anioActivo."' ORDER BY fecha ASC");
                          if ($resultSubcuenta) {
                              if (($resultSubcuenta->num_rows)>0) {
                                echo <<< END
                                <td class='bg-success' colspan='1'>Cuenta $codigo</td>      
                                <td class='bg-success' colspan='5' align='center'>$nombre</td></tr>
                                END;
                                $fecha = '0000-00-00';
                                $debe_sub = 0;
                                $haber_sub = 0;
                                $saldofinal = 0;

                                $subcuenta = $resultSubcuenta->fetch_object();
                                
                                //Almacenando primer objeto de la consulta
                                $fecha = $subcuenta->fecha;
                                $debe_sub = $subcuenta->debe;
                                $haber_sub = $subcuenta->haber;
                                if($subcuenta->saldo == "DEUDOR"){
                                  $saldofinal = $saldofinal + ($subcuenta->debe)-($subcuenta->haber);
                                }else{
                                  $saldofinal = $saldofinal - ($subcuenta->debe)+($subcuenta->haber);
                                }
                                
                                while ($subcuenta = $resultSubcuenta->fetch_object()){
                                  if ($fecha == $subcuenta->fecha){
                                    $debe_sub = $debe_sub + $subcuenta->debe;
                                    $haber_sub = $haber_sub + $subcuenta->haber;
                                    if($subcuenta->saldo == "DEUDOR"){
                                      $saldofinal = $saldofinal + ($subcuenta->debe)-($subcuenta->haber);
                                    }else{
                                      $saldofinal = $saldofinal - ($subcuenta->debe)+($subcuenta->haber);
                                    }
                                  }else{
                                    $debimp = $debe_sub==0 ? '--' : '$'.number_format($debe_sub,2);
                                    $habimp = $haber_sub==0 ? '--' : '$'.number_format($haber_sub,2);
                                    $salimp = $saldofinal==0 ? '--' : '$'.number_format($saldofinal,2);
                                    echo <<< END
                                    <tr>
                                    <td>Movimiento del día</td>
                                    <td>$fecha</td>
                                    <td class='bg-info'>$debimp</td>
                                    <td class='bg-danger'>$habimp</td>
                                    <td class='bg-warning'>$salimp</td>
                                    </tr>
                                    END;

                                    $debe_sub = $subcuenta->debe;
                                    $haber_sub = $subcuenta->haber;
                                    if($subcuenta->saldo == "DEUDOR"){
                                      $saldofinal = $saldofinal + ($subcuenta->debe)-($subcuenta->haber);
                                    }else{
                                      $saldofinal = $saldofinal - ($subcuenta->debe)+($subcuenta->haber);
                                    }
                                    $fecha = $subcuenta->fecha;
                                  }
                                }
                                $debimp = $debe_sub==0 ? '--' : '$'.number_format($debe_sub,2);
                                $habimp = $haber_sub==0 ? '--' : '$'.number_format($haber_sub,2);
                                $salimp = $saldofinal==0 ? '--' : '$'.number_format($saldofinal,2);
                                echo <<< END
                                    <tr>
                                    <td>Movimiento del día</td>
                                    <td>$fecha</td>
                                    <td class='bg-info'>$debimp</td>
                                    <td class='bg-danger'>$habimp</td>
                                    <td class='bg-warning'>$salimp</td>
                                    </tr>
                                    END;

                            }
                          }else {
                            msg("Error");
                          }
                        }//cierre while consulta 1
                      }//cierre result consulta 1
                    //fin consulta por nivel
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
}else {
header("Location: ../index.php");
}
?>

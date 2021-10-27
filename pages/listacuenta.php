<?php
//Codigo que muestra solo los errores exceptuando los notice.
error_reporting(E_ALL & ~E_NOTICE);
?>
<?php
session_start();
if($_SESSION["logueado"] == TRUE) {
  include "../config/conexion.php";
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
  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
      <script type="text/javascript">
          //funcion para exportar la tabla del catalogo a excell
        function catalogoExcell()
        {
          const ventana = window.open("reportes/catalogoExcell.php","_blank");
          //window.setTimeout(cerrarVentana(ventana), 80000);
        }
        function catalogoPDF()
        {
          const ventana = window.open("reportes/catalogoPDF.php","_blank");
          //window.setTimeout(cerrarVentana(ventana), 80000);
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
                        <h3 class="animated fadeInLeft">Lista Cuentas</h3>
                        <p class="animated fadeInDown">
                          Tabla <span class="fa-angle-right fa"></span> Datos de las cuentas
                        </p>
                    </div>
                  </div>
              </div>
              <div class="col-md-12 top-20 padding-0">
                <div class="col-md-12">
                <div class="panel">
                  <div class="panel-heading">
                    <center>
                      <h3>Lista De Cuentas</h3>
                    </center>
                  </div>
                  <div class="panel-body">
                    <div class="responsive-table">
                    <table id="datatables-example" class="table table-striped table-bordered" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Saldo</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                    $result = $conexion->query("select * from catalogo order by codigocuenta");
                    if ($result) {
                      while ($fila = $result->fetch_object()) {
                          echo "<tr>";
                          echo "<td></td>";
                          echo "<td>" . $fila->codigocuenta . "</td>";
                          echo "<td>" . $fila->nombrecuenta . "</td>";
                          echo "<td>" . $fila->tipocuenta . "</td>";
                          echo "<td>" . $fila->saldo . "</td>";
                          echo "</tr>";
                      }
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
} else {
header("Location: ../index.php");
}
?>

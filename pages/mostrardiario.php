<?php
//Codigo que muestra solo los errores exceptuando los notice.
error_reporting(E_ALL & ~E_NOTICE);
?>
<?php
session_start();
if($_SESSION["logueado"] == TRUE) {
  include '../config/conexion.php';
  $result = $conexion->query("select * from anio where estado=1");
  if($result)
  {
    while ($fila=$result->fetch_object()) {
      $anioActivo=$fila->idanio;
    }
  }
 ?>
<!DOCTYPE html>
<html lang="en">
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
        function diarioExcell()
        {
          var anio=document.getElementById("anioActivo").value;
          const ventana = window.open("reportes/librodiarioExcell.php?anio="+anio+"","_blank");
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
                        <h3 class="animated fadeInLeft">Libro Diario</h3>
                        <p class="animated fadeInDown">
                          Table <span class="fa-angle-right fa"></span> Data Tables
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
                    <a class="btn btn-primary" href="./libroDiarioAexcel.php">Descargar Libro Diario</a>
                    <center>
                      <h3>Libro Diario</h3>
                      <input type="hidden" name="anioActivo" id="anioActivo" value="<?php echo $anioActivo; ?>">
                    </center>
                  </div>
                  <div class="panel-body">
                    <div class="responsive-table">
                    <table id="datatables-example" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                      <th style="width:73px;" >Fecha</th>
                        <th style="width:73px;">Codigo</th>
                        <th>Concepto</th>
                        <th>Debe</th>
                        <th>Haber</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php

$result = $conexion->query("select * from partida where idanio='".$anioActivo."' order by idpartida ASC");
if ($result) {
  while ($fila = $result->fetch_object()) {
      echo "<tr class='bg-success'>";
      //echo "<tr>";
      //echo "<td><img src='img/modificar.png' style='width:30px; height:30px' onclick=modify(".$fila->idasignatura.",'".$fila->codigo."','".$fila->nombre."');></td>";
      //echo "<td><img src='img/eliminar.png' style='width:30px; height:30px' onclick=elyminar(".$fila->idasignatura.",'".$fila->nombre."');></td>";

      echo "<td>" . $fila->fecha . "</td>";
      echo "<td colspan='4' align='center'>Partida #" . $fila->idpartida . "</td>";
    //  echo "<td>" . $fila->tipocuenta . "</td>";
    //  echo "<td>" . $fila->saldo . "</td>";

      echo "</tr>";
      $idpartida=$fila->idpartida;
      $result2 = $conexion->query("select * from ldiario where idpartida='".$idpartida."'order by idldiario asc");

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
              echo "<tr>";
                echo "<td> </td>";
                echo "<td align='center'> " . $codigocuenta . "</td>";
                if ($debe>=$haber) {
                  echo "<td align='left'>" . $nombrecuenta . "</td>";
                }else {

                  echo "<td align='center'>" . $nombrecuenta . "</td>";
                }
                if ($debe==0) {
                    echo "<td align='center' class='bg-info'>--</td>";
                }else {
                  echo "<td align='left' class='bg-info'>$ " . $debe . "</td>";
                }
                if ($haber==0) {
                    echo "<td align='center' class='bg-danger'>--</td>";
                }else {
                  echo "<td align='left' class='bg-danger'>$ " . $haber . "</td>";
                }


              echo "</tr>";
            }
          }
        }
      }
      echo "<tr class='bg-warning'>";
      echo "<td> </td>";
      echo "<td> </td>";
      echo "<td align='center' >V/ ".$fila->concepto."</td>";
      echo "<td> </td>";
      echo "<td> </td>";
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

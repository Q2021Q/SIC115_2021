<?php
//Codigo que muestra solo los errores exceptuando los notice.
error_reporting(E_ALL & ~E_NOTICE);
?>
<?php
session_start();
$nivelMayorizacion=$_REQUEST["nivelMayorizacion"];
if($_SESSION["logueado"] == TRUE) {
 ?>
<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta name="description" content="Miminium Admin Template v.1">
  <meta name="author" content="Isna Nur Azis">
  <meta name="keyword" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PACHOLI2018</title>

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
        function enviar()
        {
          alert(document.getElementById("nivelCuenta").value);
          location.href ="mostrarmayor.php?nivelMayorizacion="+document.getElementById("nivelCuenta").value;
        }
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
        function catalogoExcell()
        {
          const ventana = window.open("reportes/catalogoExcell.php","_blank");
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
                        <h3 class="animated fadeInLeft">Libro Diario</h3>
                        <p class="animated fadeInDown">
                          Nivel Para la mayorizacion.
                        </p>
                        <select class="selectpicker" name="nivelCuenta" id="nivelCuenta" onchange="enviar()">
                          <option value="Seleccion">Seleccione</option>
                          <?php
                          include "../config/conexion.php";
                          $result = $conexion->query("select nivel from catalogo group by nivel order by nivel ASC");
                          if ($result) {
                              while ($fila = $result->fetch_object()) {
                                echo "<option value='".$fila->nivel."'>".$fila->nivel."</option>";
                              }
                            }
                           ?>
                        </select>
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
                    <center>
                      <h3>Libro Diario</h3>
                          <button class='btn ripple-infinite btn-round btn-success' onclick='catalogoExcell()';>
                            <div>
                              <span>EXCELL</span>
                            </div>
                          </button>
                          <button class='btn ripple-infinite btn-round btn-primary' onclick='catalogoWord()';>
                            <div>
                              <span>WORD</span>
                            </div>
                          </button>
                          <button class='btn ripple-infinite btn-round btn-danger' onclick='catalogoPdf()';>
                            <div>
                              <span>PDF</span>
                            </div>
                          </button>
                    </center>
                  </div>
                  <div class="panel-body">
                    <div class="responsive-table">
                    <table id="datatables-example" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                      <th style="width:73px;" >Fecha</th>
                        <th>Concepto</th>
                        <th style="width:73px;" >P/F</th>
                        <th>Debe</th>
                        <th>Haber</th>
                        <th>Saldo</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
include "../config/conexion.php";
$result2 = $conexion->query("select idcatalogo as id, nombrecuenta as nombre from catalogo where nivel=".$nivelMayorizacion);
if ($result2) {
  while ($fila2 = $result2->fetch_object()) {
    $id=$fila2->id;
    $nombre=$fila2->nombre;
    echo "<tr class'success'>";
    echo "<td colspan='6' align='center'>" . $fila2->nombre . "</td>";
    echo "</tr>";
    $result = $conexion->query("select p.fecha as fecha, p.concepto as concepto, p.idpartida as npartida, l.debe as debe,l.haber as haber, a.idanio as anio from partida as p, ldiario as l, anio as a where a.idanio=2017 and l.idpartida=p.idpartida and l.idcatalogo='".$id."' order by p.idpartida ASC");
    if ($result) {
      while ($fila = $result->fetch_object()) {
          echo "<tr class='success'>";
          echo "<td>" . $fila->fecha . "</td>";

        //  echo "<td>" . $fila->tipocuenta . "</td>";
        //  echo "<td>" . $fila->saldo . "</td>";
          echo "</tr>";
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
                  echo "<tr>";
                    echo "<td> </td>";
                    echo "<td align='center'> " . $codigocuenta . "</td>";
                    if ($debe>=$haber) {
                      echo "<td align='left'>" . $nombrecuenta . "</td>";
                    }else {

                      echo "<td align='center'>" . $nombrecuenta . "</td>";
                    }
                    if ($debe==0) {
                        echo "<td align='center' class='info'>--</td>";
                    }else {
                      echo "<td align='left' class='info'>$ " . $debe . "</td>";
                    }
                    if ($haber==0) {
                        echo "<td align='center' class='danger'>--</td>";
                    }else {
                      echo "<td align='left' class='danger'>$ " . $haber . "</td>";
                    }
                    echo "<td align='center'> " . $codigocuenta . "</td>";

                  echo "</tr>";
                }
              }
            }
          }
          echo "<tr class='warning'>";
          echo "<td> </td>";
          echo "<td> </td>";
          echo "<td align='center' >V/ ".$fila->concepto."</td>";
          echo "<td> </td>";
          echo "<td> </td>";
          echo "</tr>";
          echo "Segunda ejecucion";
      }
    }

  }
}else {
  msg("Error");
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

      <!-- start: Mobile -->
      <div id="mimin-mobile" class="reverse">
        <div class="mimin-mobile-menu-list">
            <div class="col-md-12 sub-mimin-mobile-menu-list animated fadeInLeft">
                <ul class="nav nav-list">
                    <li class="active ripple">
                      <a class="tree-toggle nav-header">
                        <span class="fa-home fa"></span>Dashboard
                        <span class="fa-angle-right fa right-arrow text-right"></span>
                      </a>
                      <ul class="nav nav-list tree">
                          <li><a href="dashboard-v1.html">Dashboard v.1</a></li>
                          <li><a href="dashboard-v2.html">Dashboard v.2</a></li>
                      </ul>
                    </li>
                    <li class="ripple">
                      <a class="tree-toggle nav-header">
                        <span class="fa-diamond fa"></span>Layout
                        <span class="fa-angle-right fa right-arrow text-right"></span>
                      </a>
                      <ul class="nav nav-list tree">
                        <li><a href="topnav.html">Top Navigation</a></li>
                        <li><a href="boxed.html">Boxed</a></li>
                      </ul>
                    </li>
                    <li class="ripple">
                      <a class="tree-toggle nav-header">
                        <span class="fa-area-chart fa"></span>Charts
                        <span class="fa-angle-right fa right-arrow text-right"></span>
                      </a>
                      <ul class="nav nav-list tree">
                        <li><a href="chartjs.html">ChartJs</a></li>
                        <li><a href="morris.html">Morris</a></li>
                        <li><a href="flot.html">Flot</a></li>
                        <li><a href="sparkline.html">SparkLine</a></li>
                      </ul>
                    </li>
                    <li class="ripple">
                      <a class="tree-toggle nav-header">
                        <span class="fa fa-pencil-square"></span>Ui Elements
                        <span class="fa-angle-right fa right-arrow text-right"></span>
                      </a>
                      <ul class="nav nav-list tree">
                        <li><a href="color.html">Color</a></li>
                        <li><a href="weather.html">Weather</a></li>
                        <li><a href="typography.html">Typography</a></li>
                        <li><a href="icons.html">Icons</a></li>
                        <li><a href="buttons.html">Buttons</a></li>
                        <li><a href="media.html">Media</a></li>
                        <li><a href="panels.html">Panels & Tabs</a></li>
                        <li><a href="notifications.html">Notifications & Tooltip</a></li>
                        <li><a href="badges.html">Badges & Label</a></li>
                        <li><a href="progress.html">Progress</a></li>
                        <li><a href="sliders.html">Sliders</a></li>
                        <li><a href="timeline.html">Timeline</a></li>
                        <li><a href="modal.html">Modals</a></li>
                      </ul>
                    </li>
                    <li class="ripple">
                      <a class="tree-toggle nav-header">
                       <span class="fa fa-check-square-o"></span>Forms
                       <span class="fa-angle-right fa right-arrow text-right"></span>
                      </a>
                      <ul class="nav nav-list tree">
                        <li><a href="formelement.html">Form Element</a></li>
                        <li><a href="#">Wizard</a></li>
                        <li><a href="#">File Upload</a></li>
                        <li><a href="#">Text Editor</a></li>
                      </ul>
                    </li>
                    <li class="ripple">
                      <a class="tree-toggle nav-header">
                        <span class="fa fa-table"></span>Tables
                        <span class="fa-angle-right fa right-arrow text-right"></span>
                      </a>
                      <ul class="nav nav-list tree">
                        <li><a href="datatables.html">Data Tables</a></li>
                        <li><a href="handsontable.html">handsontable</a></li>
                        <li><a href="tablestatic.html">Static</a></li>
                      </ul>
                    </li>
                    <li class="ripple">
                      <a href="calendar.html">
                         <span class="fa fa-calendar-o"></span>Calendar
                      </a>
                    </li>
                    <li class="ripple">
                      <a class="tree-toggle nav-header">
                        <span class="fa fa-envelope-o"></span>Mail
                        <span class="fa-angle-right fa right-arrow text-right"></span>
                      </a>
                      <ul class="nav nav-list tree">
                        <li><a href="mail-box.html">Inbox</a></li>
                        <li><a href="compose-mail.html">Compose Mail</a></li>
                        <li><a href="view-mail.html">View Mail</a></li>
                      </ul>
                    </li>
                    <li class="ripple">
                      <a class="tree-toggle nav-header">
                        <span class="fa fa-file-code-o"></span>Pages
                        <span class="fa-angle-right fa right-arrow text-right"></span>
                      </a>
                      <ul class="nav nav-list tree">
                        <li><a href="forgotpass.html">Forgot Password</a></li>
                        <li><a href="login.html">SignIn</a></li>
                        <li><a href="reg.html">SignUp</a></li>
                        <li><a href="article-v1.html">Article v1</a></li>
                        <li><a href="search-v1.html">Search Result v1</a></li>
                        <li><a href="productgrid.html">Product Grid</a></li>
                        <li><a href="profile-v1.html">Profile v1</a></li>
                        <li><a href="invoice-v1.html">Invoice v1</a></li>
                      </ul>
                    </li>
                     <li class="ripple"><a class="tree-toggle nav-header"><span class="fa "></span> MultiLevel  <span class="fa-angle-right fa right-arrow text-right"></span> </a>
                      <ul class="nav nav-list tree">
                        <li><a href="view-mail.html">Level 1</a></li>
                        <li><a href="view-mail.html">Level 1</a></li>
                        <li class="ripple">
                          <a class="sub-tree-toggle nav-header">
                            <span class="fa fa-envelope-o"></span> Level 1
                            <span class="fa-angle-right fa right-arrow text-right"></span>
                          </a>
                          <ul class="nav nav-list sub-tree">
                            <li><a href="mail-box.html">Level 2</a></li>
                            <li><a href="compose-mail.html">Level 2</a></li>
                            <li><a href="view-mail.html">Level 2</a></li>
                          </ul>
                        </li>
                      </ul>
                    </li>
                    <li><a href="credits.html">Credits</a></li>
                  </ul>
            </div>
        </div>
      </div>
      <button id="mimin-mobile-menu-opener" class="animated rubberBand btn btn-circle btn-danger">
        <span class="fa fa-bars"></span>
      </button>
       <!-- end: Mobile -->

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

include "../config/conexion.php";

function msg($texto)
{
    echo "<script type='text/javascript'>";
    echo "alert('$texto');";
  //  echo "document.location.href='listacliente.php';";
    echo "</script>";
}

$bandera = $_REQUEST["bandera"];
$baccion = $_REQUEST["baccion"];
if ($bandera == "add") {
    $consulta  = "INSERT INTO cliente VALUES('null','" . $nombrecliente . "','" . $apellidocliente . "','" . $duicliente . "','" . $telefonocliente . "','" . $direccioncliente . "')";
    $resultado = $conexion->query($consulta);
    if ($resultado) {
        msg("Exito");
    } else {
        msg("No Exito");
    }
}
if ($bandera == "desaparecer") {
    $consulta  = "DELETE FROM cliente where idcliente='" . $baccion . "'";
    $resultado = $conexion->query($consulta);
    if ($resultado) {
        msg("Exito");
    } else {
        msg("No Exito");
    }
}
if ($bandera == 'enviar') {
    echo "<script type='text/javascript'>";
    echo "document.location.href='editcliente.php?id=" . $baccion . "';";
    echo "</script>";
    # code...
}

}else {
header("Location: ../index.php");
}
?>

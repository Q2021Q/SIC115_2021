<?php
//Codigo que muestra solo los errores exceptuando los notice.
error_reporting(E_ALL & ~E_NOTICE);
?>
<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta name="description" content="Miminium Admin Template v.1">
  <meta name="author" content="Isna Nur Azis">
  <meta name="keyword" content="">
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

  <link rel="shortcut icon" href="../asset/img/logomi.png">
  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->

</head>

<body id="mimin" class="dashboard">
      <?php include "header.php"?>

      <div class="container-fluid mimin-wrapper">

<?php include "menu.php";?>
            <div id="content">
               <div class="panel box-shadow-none content-header">
                  <div class="panel-body">
                    <div class="col-md-12">
                        <h3 class="animated fadeInLeft">COSTEO</h3>
                        <p class="animated fadeInDown">
                          Tabla <span class="fa-angle-right fa"></span> Costeo
                        </p>
                    </div>
                  </div>
              </div>
              <form action="costeo.php" method="post">
              <div class="col-md-12 top-10 padding-0">
               <div class="col-md-4">


                            <div class="form-group form-animate-text" style="margin-top:1px !important;">
                              <input type="text" class="form-text" name="unidadesIniciadasPeriodo" required>
                              <span class="bar"></span>
                              <label>Unidades iniciadas en el periodo</label>
                            </div>
                            <div class="form-group form-animate-text" style="margin-top:1px !important;">
                              <input type="text" class="form-text" name="inventarioInicial" required>
                              <span class="bar"></span>
                              <label>Inventario inicial de productos en proceso</label>
                            </div>

                            <div class="form-group form-animate-text" style="margin-top:1px !important;">
                              <input type="text" class="form-text" name="gradoInventarioInicial" required>
                              <span class="bar"></span>
                              <label>Grado de avance del inventario inicial</label>
                            </div>

                            <div class="form-group form-animate-text" style="margin-top:1px !important;">
                              <input type="text" class="form-text" name="inventarioFinal" required>
                              <span class="bar"></span>
                              <label>Inventario final de productos en proceso</label>
                            </div>

                            <div class="form-group form-animate-text" style="margin-top:1px !important;">
                              <input type="text" class="form-text" name="gradoInventarioFinal" required>
                              <span class="bar"></span>
                              <label>Grado de avance del inventario Final</label>
                            </div>

                            <div class="form-group form-animate-text" style="margin-top:1px !important;">
                              <input type="text" class="form-text" name="materiaPrima"  required>
                              <span class="bar"></span>
                              <label>Materia prima utilizada</label>
                            </div>

                            <div class="form-group form-animate-text" style="margin-top:1px !important;">
                              <input type="text" class="form-text" name="costosConversion" required>
                              <span class="bar"></span>
                              <label>Costos de conversion</label>
                            </div>

                            <div class="form-group form-animate-text" style="margin-top:1px !important;">
                              <input type="text" class="form-text"  name="costosMateriaPrima" required>
                              <span class="bar"></span>
                              <label>Costos de materia prima del Inventario inicial</label>
                            </div>

                            <div class="form-group form-animate-text" style="margin-top:1px !important;">
                              <input type="text" class="form-text" name="costosConversionInventarioInicial" required>
                              <span class="bar"></span>
                              <label>Costos de Conversion del Inventario inicial</label>
                            </div>


                            <div class="col-md-3">
                              <button type="submit" class="btn-flip btn btn-gradient btn-primary">
                                <div class="flip">
                                  <div class="side">
                                    Generar costeo <span class="fa fa-floppy-o"></span>
                                  </div>
                                  <div class="side back">
                                    continuar?
                                  </div>
                                </div>
                                <span class="icon"></span>
                              </button>
                          </div>
                </div>

                <div class="col-md-8">
                  <div class="col-md-12">
                  <div class="panel">
                    <div class="panel-heading"><h3>Cédula de unidades físicas</h3></div>
                    <div class="panel-body">
                      <div class="responsive-table">
                      <table class="table table-striped table-bordered table-dark" width="100%" cellspacing="0">
                      <thead >
                        <tr>
                          <th>Descripcion</th>
                          <th>Valor</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php

        $unidadesIniciadasPeriodo  = $_POST['unidadesIniciadasPeriodo'];
        $inventarioInicial = $_POST['inventarioInicial'];
        $gradoInventarioInicial  = $_POST['gradoInventarioInicial'];
        $inventarioFinal = $_POST['inventarioFinal'];
        $gradoInventarioFinal            = $_POST['gradoInventarioFinal'];
        $materiaPrima        = $_POST['materiaPrima'];
        $costosConversion        = $_POST['costosConversion'];
        $costosMateriaPrima   = $_POST['costosMateriaPrima'];
        $costosConversionInventarioInicial = $_POST['costosConversionInventarioInicial'];

        //Variables de proceso

        
        $unidadesDisponibles = $unidadesIniciadasPeriodo + $inventarioInicial;
        $unidadesTransferidas = $unidadesDisponibles - $inventarioFinal;
        $inventarioFinalGradoAvance = $inventarioFinal*$gradoInventarioFinal;
        $totalUnidadesEquivalentes1 = $unidadesTransferidas + $inventarioFinal;
        $totalUnidadesEquivalentes2 = $unidadesTransferidas + $inventarioFinalGradoAvance;
        $inventarioInicialGradoAvance = $inventarioInicial*$gradoInventarioInicial;
        $unidadesEquivalentes1 = $totalUnidadesEquivalentes1 - $inventarioInicial;
        $unidadesEquivalentes2 = $totalUnidadesEquivalentes2 - $inventarioInicialGradoAvance;


       if($unidadesIniciadasPeriodo==0){
        $puUnidadesIniciadas=0;}
      else
        $puUnidadesIniciadas = bcdiv($materiaPrima,$unidadesIniciadasPeriodo,2);


      if ($unidadesEquivalentes2==0) {
        $puCOCO=0; }
        else
          $puCOCO = bcdiv($costosConversion,$unidadesEquivalentes2,2);

     $totalUnidadesIniciadas = $materiaPrima + $costosConversion;
    
     if ($inventarioInicial==0) {
      $puInventarioInicial=0; }
      else
        $puInventarioInicial = bcdiv($costosMateriaPrima,$inventarioInicial,2);

     if ($inventarioInicialGradoAvance==0) {
      $puInventarioInicialCOCO=0;}
      else
        $puInventarioInicialCOCO = bcdiv($costosConversionInventarioInicial,$inventarioInicialGradoAvance,2);
     
      $totalInventarioInicial = $costosMateriaPrima + $costosConversionInventarioInicial;

      $unidadesDisponiblesTotales = $unidadesIniciadasPeriodo + $inventarioInicial;
      $unidadesDisponiblesTotales2 = $materiaPrima + $costosMateriaPrima;
        

      if ($unidadesDisponiblesTotales==0) {
      $puUnidadesDisponibles=0;}
      else
        $puUnidadesDisponibles = bcdiv($unidadesDisponiblesTotales2,$unidadesDisponiblesTotales,2);

     $unidadesDisponiblesTotalesCOCO = $unidadesEquivalentes2+$inventarioInicialGradoAvance;
     $unidadesDisponiblesTotalesCOCO2 = $costosConversion+$costosConversionInventarioInicial; 
        
      if ($unidadesDisponiblesTotalesCOCO==0) {
      $puUnidadesDisponiblesCOCO=0;}
      else
         $puUnidadesDisponiblesCOCO = bcdiv($unidadesDisponiblesTotalesCOCO2,$unidadesDisponiblesTotalesCOCO,2);

      $totalUnidadesDisponibles = $unidadesDisponiblesTotales2+$unidadesDisponiblesTotalesCOCO2;
      $cmpTotal= $inventarioFinal*$puUnidadesDisponibles;
      $cocoTotal= $inventarioFinalGradoAvance*$puCOCO;
      $totalInventarioFinal = $cmpTotal+$cocoTotal;
      $tot1=$unidadesDisponiblesTotales-$inventarioFinal;
      $tot2=$unidadesDisponiblesTotales2-$cmpTotal;
     
      if ($tot1==0) {
      $tot3=0;}
      else
        $tot3=bcdiv($tot2, $tot1,2);

     $tot4=$unidadesDisponiblesTotalesCOCO-$inventarioFinalGradoAvance;
     $tot5=$unidadesDisponiblesTotalesCOCO2-$cocoTotal;

      if ($tot4==0) {
      $tot6=0;}
      else
        $tot6=bcdiv($tot5, $tot4,2);

     $tot7=$tot5+$tot2;

      if ($tot4==0) {
      $precioUnitario=0;}
      else
        $precioUnitario=bcdiv($tot7, $tot4,2);


          echo "<tr>";
             echo "<td>Unidades Iniciadas en el periodo</td>";
             echo "<td>". $unidadesIniciadasPeriodo ."</td>";
          echo "</tr>";

          echo "<tr>";
             echo "<td>(+) Inventario inicial de productos en procesos</td>";
             echo "<td>". $inventarioInicial ."</td>";
           echo "</tr>";

           echo "<tr>";
             echo "<td>(=) unidades disponibles</td>";
             echo "<td>". $unidadesDisponibles ."</td>";
           echo "</tr>";

           echo "<tr>";
             echo "<td>(-) inventario final de productos en procesos</td>";
             echo "<td>". $inventarioFinal ."</td>";
           echo "</tr>";

           echo "<tr>";
             echo "<td>(=) unidades transferidas al siguiente departamento</td>";
             echo "<td>". $unidadesTransferidas ."</td>";
           echo "</tr>";



?>
                      </tbody>
                        </table>
                      </div>
                  </div>
                </div>
              </div>
              </div>






              <div class="col-md-8">
                  <div class="col-md-12">
                  <div class="panel">
                    <div class="panel-heading"><h3>Cédula de unidades Equivalentes</h3></div>
                    <div class="panel-body">
                      <div class="responsive-table">
                      <table class="table table-striped table-bordered table-dark" width="100%" cellspacing="0">
                      <thead >
                        <tr>
                          <th>Descripcion</th>
                          <th>Unidades de materia prima (MP)</th>
                          <th>Unidades de costos de conversión (COCO)</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php

      
          echo "<tr>";
             echo "<td>Unidades terminadas en el periodo</td>";
             echo "<td>". $unidadesTransferidas ."</td>";
             echo "<td>". $unidadesTransferidas ."</td>";
          echo "</tr>";

          echo "<tr>";
             echo "<td>(+) Inventario final de pp * grado de avance (". $gradoInventarioFinal .")</td>";
             echo "<td>". $inventarioFinal ."</td>";
             echo "<td>". $inventarioFinalGradoAvance ."</td>";
           echo "</tr>";

           echo "<tr>";
             echo "<td>(=) Total de unidades equivalentes</td>";
             echo "<td>". $totalUnidadesEquivalentes1 ."</td>";
             echo "<td>". $totalUnidadesEquivalentes2 ."</td>";
           echo "</tr>";

           echo "<tr>";
             echo "<td>(-) inventario inicial de pp * grado de avance (". $gradoInventarioInicial .")</td>";
             echo "<td>". $inventarioInicial ."</td>";
             echo "<td>". $inventarioInicialGradoAvance ."</td>";
           echo "</tr>";

           echo "<tr>";
             echo "<td>(=) Unidades equivalentes producidas en el periodo</td>";
             echo "<td>". $unidadesEquivalentes1 ."</td>";
             echo "<td>". $unidadesEquivalentes2 ."</td>";
           echo "</tr>";



?>
                      </tbody>
                        </table>
                      </div>
                  </div>
                </div>
              </div>
              </div>

              <div class="col-md-8">
                  <div class="col-md-12">
                  <div class="panel">
                    <div class="panel-heading"><h3>Cédula de Asignacion de Costos</h3></div>
                    <div class="panel-body">
                      <div class="responsive-table">
                      <table class="table table-striped table-bordered table-dark" width="100%" cellspacing="0">
                      <thead >
                        <tr>
                          <th>Descripcion</th>
                          <th colspan="3">Materia Prima</th>
                          <th colspan="3">COCO</th>
                          <th rowspan="2">Total</th>
                        </tr> 
                        <tr>
                          <th>Cédula de asignacion de costos CPP</th>
                          <th>Unidades</th>
                          <th>CMP($)</th>
                          <th>PU</th>
                          <th>Unidades</th>
                          <th>COCO($)</th>
                          <th>PU</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php

          echo "<tr>";
             echo "<td>Unidades Iniciadas</td>";
             echo "<td>". $unidadesIniciadasPeriodo ."</td>";
             echo "<td>". $materiaPrima ."</td>";
             echo "<td>". $puUnidadesIniciadas ."</td>";
             echo "<td>". $unidadesEquivalentes2 ."</td>";
             echo "<td>". $costosConversion ."</td>";
             echo "<td>". $puCOCO ."</td>";
             echo "<td>". $totalUnidadesIniciadas ."</td>";
          echo "</tr>";

          echo "<tr>";
             echo "<td>(+) Inventario inicial de productos en procesos</td>";
             echo "<td>". $inventarioInicial ."</td>";
             echo "<td>". $costosMateriaPrima ."</td>";
             echo "<td>". $puInventarioInicial ."</td>";
             echo "<td>". $inventarioInicialGradoAvance ."</td>";
             echo "<td>". $costosConversionInventarioInicial ."</td>";
             echo "<td>". $puInventarioInicialCOCO ."</td>";
             echo "<td>". $totalInventarioInicial ."</td>";
           echo "</tr>";

           echo "<tr>";//3ra FILA
             echo "<td>(=) Unidades disponibles</td>";
             echo "<td>". $unidadesDisponiblesTotales ."</td>";
             echo "<td>". $unidadesDisponiblesTotales2 ."</td>";
             echo "<td>". $puUnidadesDisponibles ."</td>";
             echo "<td>". $unidadesDisponiblesTotalesCOCO ."</td>";
             echo "<td>". $unidadesDisponiblesTotalesCOCO2 ."</td>";
             echo "<td>". $puUnidadesDisponiblesCOCO ."</td>";
             echo "<td>". $totalUnidadesDisponibles ."</td>";
           echo "</tr>";

           echo "<tr>";//4ta FILA
             echo "<td>(-) Inventario final de productos en procesos</td>";
             echo "<td>". $inventarioFinal ."</td>";
             echo "<td>". $cmpTotal ."</td>";
             echo "<td>". $puUnidadesDisponibles ."</td>";
             echo "<td>". $inventarioFinalGradoAvance ."</td>";
             echo "<td>". $cocoTotal ."</td>";
             echo "<td>". $puCOCO ."</td>";
             echo "<td>". $totalInventarioFinal ."</td>";
           echo "</tr>";

           echo "<tr>";//5ta FILA 
             echo "<td>(=) Unidades Terminadas</td>";
             echo "<td>". $tot1 ."</td>";
             echo "<td>". $tot2 ."</td>";
             echo "<td>". $tot3 ."</td>";
             echo "<td>". $tot4 ."</td>";
             echo "<td>". $tot5 ."</td>";
             echo "<td>". $tot6 ."</td>";
             echo "<td>". $tot7 ."</td>";
           echo "</tr>";



?>
                      </tbody>
                        </table>
                      </div>
                  </div>
                </div>
              </div>
              </div>


              <div class="col-md-12" >
                  <div class="col-md-12">
                  <div class="panel" >
                    <div class="panel-heading"><h3>Producto FINAL</h3></div>
                    <div class="panel-body">
                      <div class="responsive-table">
                      <table class="table table-striped table-bordered table-dark" width="100%" cellspacing="0">
                      <thead >
                        <tr>
                          <th>Descripcion</th>
                          <th>Valor</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php

      
          echo "<tr>";
             echo "<td>Costo Total del proceso</td>";
             echo "<td>". $tot7 ."</td>";
          echo "</tr>";

          echo "<tr>";
             echo "<td>Precio Unitario</td>";
             echo "<td>". $precioUnitario ."</td>";
           echo "</tr>";

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

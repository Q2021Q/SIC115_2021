<?php
//Codigo que muestra solo los errores exceptuando los notice.
error_reporting(E_ALL & ~E_NOTICE);
?>
<?php
session_start();
if($_SESSION["logueado"] == TRUE) {

$id  = $_REQUEST["id"];
$aux = " ";
$anio=$_REQUEST["anio"];
include "../config/conexion.php";
$result = $conexion->query("select * from usuario where idusuario=" . $id);
if ($result) {
    while ($fila = $result->fetch_object()) {
        $idusuarioR   = $fila->idusuario;
        $nombreR  = $fila->nombre;
        $passR = $fila->pass;
        $mailR = $fila->mail;
        $telefonoR  = $fila->telefono;
        $fechaR = $fila->fecha;
        $usuarioR= $fila->usuario;
    }
    $aux = "modificar";
}
if(empty($anio))
{

}else
{
  $consulta  = "INSERT INTO anio VALUES('".$anio."','0','-1','0')";
  $resultado = $conexion->query($consulta);
  if ($resultado) {
      //msg("Exito");
  } else {
      //msg(mysqli_error($conexion));
  }
}

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
      <script type="text/javascript">
      function validar()
      {
        var v=document.getElementById("anio").value;
        if (v.length>4) {
          document.getElementById("anio").value=v.slice(0,-1);
        }
      }
        function confirmar(id)
        {
            if (confirm("!!Advertencia!! Desea Activar Este Registro?")) {
            document.getElementById('bandera').value='activar';
            document.getElementById('baccion').value=id;
            document.turismo.submit();
          }else
          {
            alert("No entra");
          }
        }
        function agregar(){
          alert("Funcion agg");
          var v=document.getElementById("anio").value;
            if (document.getElementById("anio").value=="") {
              alert("Campo obligatorio");

            }else {
              if (v.length<4) {
                alert("Por favor ingrese un año valido.")
              }else {
                var anio=document.getElementById("anio").value;
                location.href="main.php?accion=guardar&anio="+anio;
              }

              }
            }



      </script>
</head>

<body id="mimin" class="dashboard">
      <?php include "header.php"?>

      <div class="container-fluid mimin-wrapper">

<?php include "menu.php";?>
            <div id="content">
               <div class="panel box-shadow-none content-header">
                  <div class="panel-body">
                    <div class="col-md-12">
                        <h3 class="animated fadeInLeft">BIENVENIDO A SISTEMA CONTABLE 2020</h3>
                        <p class="animated fadeInDown">

                        </p>
                    </div>
                  </div>
              </div>
              <form id="turismo" name="turismo" action="" method="">
              <input type="hidden" name="bandera" id="bandera">
              <input type="hidden" name="baccion" id="baccion"  >
              <input type="hidden" name="aux" id="aux" >
              <input type="hidden" name="r" id="r" value="">
              <div class="col-md-12 top-20 padding-0">

                 <div class="col-md-7">
                   <div class="col-md-12">
                   <div class="panel">
                     <div class="panel-heading"><h3>Ciclos Contables</h3>
                       <?php
                       include "../config/conexion.php";
                       $result = $conexion->query("select * from anio");
                       if($result->num_rows<1){
                         ?>

                       <button type="button" class="btn-flip btn btn-gradient btn-primary" data-toggle='modal' data-target='#myModal'>
                             <div class="flip">
                               <div class="side">
                                 Agregar Nuevo <span class="fa fa-edit"></span>
                               </div>
                               <div class="side back">
                                 Continuar?

                               </div>
                             </div>
                             <span class="icon"></span>
                           </button>
                           <?php
                            }
                             ?>
                          </div>
                     <div class="panel-body">
                       <div class="responsive-table">
                       <table id="datatables-example" class="table table-striped table-bordered" width="100%" cellspacing="0" >
                       <thead>
                         <tr>
                             <th>Año</th>
                            <th >Estado</th>
                            <th style="width:30px;">Activar/Desactivar</th>


                         </tr>
                       </thead>
                       <tbody>
                       <?php
 include "../config/conexion.php";
 $result = $conexion->query("select * from anio order by idanio");
 if ($result) {
     while ($fila = $result->fetch_object()) {
         echo "<tr>";
         echo "<td>" . $fila->idanio . "</td>";

         //echo "<tr>";
         //echo "<td><img src='img/modificar.png' style='width:30px; height:30px' onclick=modify(".$fila->idasignatura.",'".$fila->codigo."','".$fila->nombre."');></td>";
         //echo "<td><img src='img/eliminar.png' style='width:30px; height:30px' onclick=elyminar(".$fila->idasignatura.",'".$fila->nombre."');></td>";
         if ($fila->estado==1) {
                      echo "<td>Activo</td>";
                      //echo "<td><img src='imagenes.php?id=" . $fila->idclientes . "&tipo=cliente' width=100 height=180></td>";
                      echo "<td style='text-align:center;'>Ciclo actual.</td>";
                   }else
                    if ($fila->estado==0) {

                      echo "<td>Inactivo</td>";
                     //  echo "<td><img src='imagenes.php?id=" . $fila->idclientes . "&tipo=cliente' width=100 height=180></td>";
                      echo "<td style='text-align:center;'><button align='center' type='button' class='btn btn-default' onclick=confirmar(" . $fila->idanio . ");><i class='fa fa-check'></i>
                         </button></td>";
                   }
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



<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Agregar nuevo ciclo contable</h4>
      </div>
      <div class="modal-body">
        <div class="form-group form-animate-text" style="margin-top:0px !important;">
          <input type="number" min="0" class="form-text" id="anio" name="anio" onkeyup="validar()">
          <span class="bar"></span>
          <label>Año Contable:</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default"  onclick="agregar()">Agregar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>

      </div>
    </div>

  </div>
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

  $("#formcliente").validate({
    errorElement: "em",
    errorPlacement: function(error, element) {
      $(element.parent("div").addClass("form-animate-error"));
      error.appendTo(element.parent("div"));
    },
    success: function(label) {
      $(label.parent("div").removeClass("form-animate-error"));
    },
    rules: {
      nombrecliente: "required",
      apellidocliente: "required",
      duicliente: "required",
      telefonocliente: "required",
      direccioncliente: "required"
    },
    messages: {
      nombrecliente: "Digita tu nombre",
      apellidocliente: "Digita tu apellido",
      duicliente: "Digita tu DUI",
      telefonocliente: "Digita tu numero telefonico",
      direccioncliente: "Digita tu direcci&oacuten"
    }
  });

  // propose username by combining first- and lastname
  $("#username").focus(function() {
    var firstname = $("#firstname").val();
    var lastname = $("#lastname").val();
    if (firstname && lastname && !this.value) {
      this.value = firstname + "." + lastname;
    }
  });


  $('.mask-dui').mask('00000000-0');
  $('.mask-time').mask('00:00:00');
  $('.mask-anio').mask('0000');
  $('.mask-date_time').mask('00/00/0000 00:00:00');
  $('.mask-cep').mask('00000-000');
  $('.mask-telefono').mask('0000-0000');
  $('.mask-nit').mask('0000-000000-000-0');
  $('.mask-phone_with_ddd').mask('(00) 0000-0000');
  $('.mask-phone_us').mask('(000) 000-0000');
  $('.mask-mixed').mask('AAA 000-S0S');
  $('.mask-cpf').mask('000.000.000-00', {reverse: true});
  $('.mask-money').mask('000.000.000.000.000,00', {reverse: true});
  $('.mask-money2').mask("#.##0,00", {reverse: true});
  $('.mask-ip_address').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
    translation: {
      'Z': {
        pattern: /[0-9]/, optional: true
      }
    }
  });
  $('.mask-ip_address').mask('099.099.099.099');
  $('.mask-percent').mask('##0,00%', {reverse: true});
  $('.mask-clear-if-not-match').mask("00/00/0000", {clearIfNotMatch: true});
  $('.mask-placeholder').mask("00/00/0000", {placeholder: "__/__/____"});
  $('.mask-fallback').mask("00r00r0000", {
    translation: {
      'r': {
        pattern: /[\/]/,
        fallback: '/'
      },
      placeholder: "__/__/____"
    }
  });
  $('.mask-selectonfocus').mask("00/00/0000", {selectOnFocus: true});

  var options =  {onKeyPress: function(cep, e, field, options){
    var masks = ['00000-000', '0-00-00-00'];
    mask = (cep.length>7) ? masks[1] : masks[0];
    $('.mask-crazy_cep').mask(mask, options);
  }};

  $('.mask-crazy_cep').mask('00000-000', options);


  var options2 =  {
    onComplete: function(cep) {
      alert('CEP Completed!:' + cep);
    },
    onKeyPress: function(cep, event, currentField, options){
      console.log('An key was pressed!:', cep, ' event: ', event,
        'currentField: ', currentField, ' options: ', options);
    },
    onChange: function(cep){
      console.log('cep changed! ', cep);
    },
    onInvalid: function(val, e, f, invalid, options){
      var error = invalid[0];
      console.log ("Digit: ", error.v, " is invalid for the position: ", error.p, ". We expect something like: ", error.e);
    }
  };

  $('.mask-cep_with_callback').mask('00000-000', options2);

  var SPMaskBehavior = function (val) {
    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
  },
  spOptions = {
    onKeyPress: function(val, e, field, options) {
      field.mask(SPMaskBehavior.apply({}, arguments), options);
    }
  };

  $('.mask-sp_celphones').mask(SPMaskBehavior, spOptions);



  var slider = document.getElementById('noui-slider');
  noUiSlider.create(slider, {
    start: [20, 80],
    connect: true,
    range: {
      'min': 0,
      'max': 100
    }
  });

  var slider = document.getElementById('noui-range');
  noUiSlider.create(slider, {
                      start: [ 20, 80 ], // Handle start position
                      step: 10, // Slider moves in increments of '10'
                      margin: 20, // Handles must be more than '20' apart
                      connect: true, // Display a colored bar between the handles
                      direction: 'rtl', // Put '0' at the bottom of the slider
                      orientation: 'vertical', // Orient the slider vertically
                      behaviour: 'tap-drag', // Move handle on tap, bar is draggable
                      range: { // Slider can select '0' to '100'
                      'min': 0,
                      'max': 100
                    },
                      pips: { // Show a scale with the slider
                        mode: 'steps',
                        density: 2
                      }
                    });



  $(".select2-A").select2({
    placeholder: "Select a state",
    allowClear: true
  });

  $(".select2-B").select2({
    tags: true
  });

  $("#range1").ionRangeSlider({
    type: "double",
    grid: true,
    min: -1000,
    max: 1000,
    from: -500,
    to: 500
  });

  $('.dateAnimate').bootstrapMaterialDatePicker({ weekStart : 0, time: false,animation:true});
  $('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false});
  $('.time').bootstrapMaterialDatePicker({ date: false,format:'HH:mm',animation:true});
  $('.datetime').bootstrapMaterialDatePicker({ format : 'dddd DD MMMM YYYY - HH:mm',animation:true});
  $('.date-fr').bootstrapMaterialDatePicker({ format : 'DD/MM/YYYY HH:mm', lang : 'fr', weekStart : 1, cancelText : 'ANNULER'});
  $('.min-date').bootstrapMaterialDatePicker({ format : 'DD/MM/YYYY HH:mm', minDate : new Date() });


  $(".dial").knob({
    height:80
  });

  $('.dial1').trigger(
   'configure',
   {
     "min":10,
     "width":80,
     "max":80,
     "fgColor":"#FF6656",
     "skin":"tron"
   }
   );

  $('.dial2').trigger(
   'configure',
   {

     "width":80,
     "fgColor":"#FF6656",
     "skin":"tron",
     "cursor":true
   }
   );

  $('.dial3').trigger(
   'configure',
   {

     "width":80,
     "fgColor":"#27C24C",
   }
   );
});
</script>
<!-- end: Javascript -->
</body>
</html>
<?php

include "../config/conexion.php";

$bandera      = $_REQUEST["bandera"];
$baccion      = $_REQUEST["baccion"];


if ($bandera == "desactivar") {
  $result2 = $conexion->query("select * from anio");
  if ($result2) {
    while ($fila = $result2->fetch_object()) {
      $idanio=$fila->idanio;
      $consulta1 = "update anio set estado='1' where idanio=".$idanio;
      $resultado = $conexion->query($consulta1);
    }
  }
  $consulta4 = "update anio SET estado='0' WHERE idanio='".$baccion."'";
    $resultado = $conexion->query($consulta4);
    if ($resultado) {
        //msg("Exito");
        echo "<script type='text/javascript'>";
        echo "alert('Exito');";
        echo "document.location.href='main.php';";
        echo "</script>";
    } else {
        msg("No Exito");
    }
}
if ($bandera == "activar") {
  $result2 = $conexion->query("select * from anio");
  if ($result2) {
    while ($fila = $result2->fetch_object()) {
      $idanio=$fila->idanio;
      $consulta = "update anio set estado='0' where idanio=".$idanio;
      $resultado = $conexion->query($consulta);
    }
  }

  $consulta2 = "update anio set estado='1' where idanio=".$baccion;
    $resultado = $conexion->query($consulta2);
    if ($resultado) {

      echo "<script type='text/javascript'>";
      echo "alert('Exito');";
      echo "document.location.href='main.php';";
      echo "</script>";
    } else {
        msg("No Exito");
    }

}



function msg($texto)
{
    echo "<script type='text/javascript'>";
    echo "alert('$texto');";
    echo "document.location.href='usuario.php';";
    echo "</script>";
}

} else {
header("Location: ../index.php");
}

?>

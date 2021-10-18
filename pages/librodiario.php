<?php
//Codigo que muestra solo los errores exceptuando los notice.
error_reporting(E_ALL & ~E_NOTICE);
?>
<?php session_start();
$accion=$_REQUEST['accion'];
include "../config/conexion.php";
$result = $conexion->query("select * from anio where estado=1");
if($result)
{
  while ($fila=$result->fetch_object()) {
    $anioActivo=$fila->idanio;
  }
}
$result = $conexion->query("select * from partida where idanio=".$anioActivo);
$numeroPartida=($result->num_rows)+1;
//codigo para agregar la partida a la base de llenarDatos
if($accion=="procesar")
{
  $totalcargo=0;
  $totalabono=0;
  $acumulador=$_SESSION['acumulador'];
  $matriz=$_SESSION['matriz'];
  for ($i=1; $i <=$acumulador ; $i++) {
  	if (array_key_exists($i, $matriz)) {
  		$totalcargo=$totalcargo+$matriz[$i][1];
  		$totalabono=$totalabono+$matriz[$i][2];
  	}
  }
  if($totalcargo!=$totalabono)
  {
  	$prueba= "El total del cargo es distinto al abono.";
    mensajes($prueba);
  }else
  {
    guardarPartida();
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
      function mostrarError(error)
      {
        alert(error);
      }
      //funcion para llenar los datos desde el modalForm
      function llenarDatos(codigo,id,nombre)
      {
        document.getElementById("codigoCuenta").value=codigo;
        document.getElementById("bandera").value=id;
      var str=  nombre.replace(".", " ");
        ajaxNombreCuenta(codigo);
      }

      function verificar(){
          if(document.getElementById('nivelcuenta').value=="" || document.getElementById('codigocuenta').value=="" || document.getElementById('nombrecuenta').value=="" || document.getElementById('tipocuenta').value=="SELECCIONE" || document.getElementById('saldocuenta').value=="SELECCIONE"){
            alert("Complete los campos");
          }else{
            if (document.getElementById("aux").value=="modificar") {
            comprobarR(document.getElementById('codigocuenta').value);
            document.getElementById('bandera').value="modificar";
            document.turismo.submit();
            }else
            {
              comprobarR(document.getElementById('codigocuenta').value);
            document.getElementById('bandera').value="add";
           document.turismo.submit();
            }
            }
        }
        function modify(id)
        {
         document.location.href='cuenta.php?id='+id;
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
            alert("Error al borrar.");
          }
        }
        //ajax para que se escriba el nombre de la cuenta en el inputcuenta
        function ajaxNombreCuenta(str){
          if (str=="") {
            str=document.getElementById("codigoCuenta").value;
          }

          if (str==""){
            document.getElementById("inputcuenta").innerHTML="";
            return;
          }
          if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp=new XMLHttpRequest();
        }else  {// code for IE6, IE5
          xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function(){
          if (xmlhttp.readyState==4 && xmlhttp.status==200){
            document.getElementById("inputcuenta").innerHTML=xmlhttp.responseText;
          }
        }

              xmlhttp.open("GET","ajaxNombreCuenta.php?codigo="+str,true);
              xmlhttp.send();


        }
        //funcionPara llenar la tabla con las partidas

        function aggPartida(str,id){
          if (str==""){
            document.getElementById("tablaPartida").innerHTML="";
            return;
          }
          if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp=new XMLHttpRequest();
        }else  {// code for IE6, IE5
          xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function(){
          if (xmlhttp.readyState==4 && xmlhttp.status==200){
            document.getElementById("tablaPartida").innerHTML=xmlhttp.responseText;
          }
        }
          if (str=="agg") {

            if (document.getElementById("bandera2").value!="") {
              var bandera=document.getElementById("bandera2").value;
            }else {
                var bandera=document.getElementById("bandera").value;
            }

            var codigoCuenta=document.getElementById("codigoCuenta").value;
            var nombreCuenta=document.getElementById("nombreCuenta").value;
            var montoPartida=document.getElementById("montoPartida").value;
            var opciones=document.getElementsByName("optradio");
            var accion="";
            for (var i = 0; i < opciones.length; i++) {
              if (opciones[i].checked==true) {
                accion=opciones[i].value;
              }
            }

            if (codigoCuenta=="" || nombreCuenta==""|| montoPartida=="" || bandera=="" ) {
              alert("Por Favor Llene los datos antes de ingresar la partida.");
            }else {
              if (montoPartida <=0) {
                alert("Por Favor utilice numeros positivos.");
                document.getElementById("montoPartida").value="";
              }else {
                xmlhttp.open("GET","AddCuenta.php?codigo="+codigoCuenta+"&concepto="+nombreCuenta+"&monto="+montoPartida+"&accion="+accion+"&id="+bandera+"&opcion=agregar",true);
                xmlhttp.send();
                document.getElementById("codigoCuenta").value="";
                document.getElementById("nombreCuenta").value="";
                document.getElementById("montoPartida").value="";
                document.getElementById("bandera").value="";
              }
            }
          }
          if (str=="quitar") {
              xmlhttp.open("GET","AddCuenta.php?id="+id+"&opcion="+str,true);
        xmlhttp.send();
          }
          if (str=="procesar") {
            alert("va aprocesar");
              xmlhttp.open("GET","AddCuenta.php?id="+id+"&opcion="+str,true);
        xmlhttp.send();
          }
          if (str=="mostrar") {
              xmlhttp.open("GET","AddCuenta.php?id="+id+"&opcion="+str,true);
        xmlhttp.send();
          }
        }



        //transaccionbod
        function procPartida(){
            if (document.getElementById("conceptoPartida").value=="" ) {
              alert("La partina necesita concepto ");
              if (document.getElementById("fechaPartida").value=="") {
                  alert("La partida necesita fecha");
              }
            }else {
              if (document.getElementById("fechaPartida").value=="") {
                  alert("La partida necesita fecha");
              }else{
                  //llamamos addCuenta}bod
                  location.href="librodiario.php?accion=procesar&concepto="+document.getElementById("conceptoPartida").value+"&fecha="+document.getElementById("fechaPartida").value;
              }
            }
        }
      </script>
</head>
<body id="mimin" class="dashboard" onload="aggPartida('mostrar',0)">
      <?php include "header.php"?>
      <div class="container-fluid mimin-wrapper">

<?php include "menu.php";?>
            <div id="content">
               <div class="panel box-shadow-none content-header">
                  <div class="panel-body">
                    <div class="col-md-12">
                        <h3 class="animated fadeInLeft">Libro Diario</h3>
                        <p class="animated fadeInDown">
                          Tabla <span class="fa-angle-right fa"></span> Partida
                        </p>
                    </div>
                  </div>
              </div>
              <form id="turismo" name="turismo" action="" method="post">
              <input type="hidden" name="bandera" id="bandera">
              <input type="hidden" name="baccion" id="baccion" value="" >
              <input type="hidden" name="aux" id="aux" value="<?php echo $aux; ?>">
              <input type="hidden" name="r" id="r" value="">
              <div class="col-md-12 top-20 padding-0">
               <div class="col-md-5">

                            <div class="form-group form-animate-text" style="margin-top:30px !important;">
                              <input type="text" class="form-text" id="codigoCuenta" name="codigoCuenta" onkeyup="ajaxNombreCuenta('');" >
                              <span class="bar"></span>
                              <label>Codigo</label>
                            </div>
                            <div class="form-group form-animate-text" style="margin-top:30px !important;" id="inputcuenta">
                              <!-- <input type="text" class="form-text" id="nombreCuenta" name="nombreCuenta"  >
                              <span class="bar"></span>
                              <label>Cuenta</label> -->
                            </div>
                            <span class="bar"></span>
                            <label>Monto $</label>
                            <div class="form-group form-animate-text" style="margin-top:0px !important;">
                              <input type="number" class="form-text" id="montoPartida" name="montoPartida" min="0" >
                            </div>
                            <div class="radio">
                            <label class="radio-inline" style="font-size:20px;padding:10px 20px;"><input type="radio" id="accion" name="optradio" style="width:20px;height:20px"checked="checked" value="cargo"> Cargo</label>

                            <label class="radio-inline" style="font-size:20px;padding:10px 100px;"><input type="radio" id="accion2" name="optradio" style="width:20px;height:20px" value="abono"> Abono</label>
                          </div>
                        </br>

                        <div class="col-md-4">
                          <button type="button" class="btn-flip btn btn-gradient btn-success" onclick="aggPartida('agg',0)" data-toggle="tooltip" data-placement="bottom" title="Se agregara la transaccion a la partida.">
                            <div class="flip">
                              <div class="side">
                                Agregar <span class="fa fa-edit"></span>
                              </div>
                              <div class="side back">
                                Partida
                              </div>
                            </div>
                            <span class="icon"></span>
                          </button>
                      </div>
                        <div class="col-md-4">
                          <button type="button" class="btn-flip btn btn-gradient btn-warning" onclick="verificar()" data-toggle="modal" data-target="#modalForm">
                            <div class="flip">
                              <div class="side">
                                Cuentas <span class="fa fa-edit"></span>
                              </div>
                              <div class="side back">
                                Mostrar
                              </div>
                            </div>
                            <span class="icon"></span>
                          </button>
                      </div>
                        <div class="col-md-2">
                            <button type="button" class="btn-flip btn btn-gradient btn-primary" data-toggle='modal' data-target='#myModal'>
                              <div class="flip">
                                <div class="side">
                                  Procesar <span class="fa fa-edit"></span>
                                </div>
                                <div class="side back">
                                  Continuar?

                                </div>
                              </div>
                              <span class="icon"></span>
                            </button>
                        </div>


                          <!-- Modal -->
<div class="modal fade" id="modalForm" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Seleccione la cuenta para agregar a la partida actual.</h4>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <p class="statusMsg"></p>
                  <table id="datatables-example" class="table table-striped table-bordered" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th></th>
                      <th>Codigo</th>
                      <th>Nombre</th>
                      <th>Saldo</th>

                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      include "tablaCuenta.php";
                     ?>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para los datos d ela partida(concepto, fecha)-->

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Datos de la partida # <?php echo $numeroPartida; ?></h4>
      </div>
      <div class="modal-body">
        <div class="form-group form-animate-text" style="margin-top:0px !important;">
          <input type="text" class="form-text" id="conceptoPartida" name="conceptoPartida" >
          <span class="bar"></span>
          <label>Concepto</label>
        </div>
        <div class="form-group form-animate-text" style="margin-top:30px !important;">
          <input type="date" class="form-text" id="fechaPartida" name="fechaPartida" min="<?php echo $anioActivo; ?>-01-01" max="<?php echo $anioActivo; ?>-12-31">
          <span class="bar"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default"  onclick="procPartida()">Guardar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>

      </div>
    </div>

  </div>
</div>






                </div>

                <div class="col-md-7">
                  <div class="col-md-12">
                  <div class="panel" >
                    <div class="col-md-2">

                    </div>



                    <div class="panel-heading"><h3 style="text-align: center;">Partida # <?php echo $numeroPartida; ?></h3></div>
                    <div class="panel-body" id="tablaPartida">
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

$bandera      = $_REQUEST["bandera"];
$baccion      = $_REQUEST["baccion"];
$nivelcuenta  = $_REQUEST["nivelcuenta"];
$nombrecuenta = $_REQUEST["nombrecuenta"];
$codigocuenta = $_REQUEST["codigocuenta"];
$tipocuenta   = $_REQUEST["tipocuenta"];
$saldocuenta  = $_REQUEST["saldocuenta"];
$saldocuenta  = $_REQUEST["saldocuenta"];
$r            = $_REQUEST["r"];
if ($bandera == "add") {
    $consulta  = "INSERT INTO catalogo VALUES('null','" . $codigocuenta . "','" . $nombrecuenta . "','" . $tipocuenta . "','" . $saldocuenta . "','" . $r . "','" . $nivelcuenta . "')";
    $resultado = $conexion->query($consulta);
    if ($resultado) {
        msg("Exito");
    } else {
        msg("No Exito");
    }
}
if ($bandera == "desaparecer") {
    $consulta  = "DELETE FROM catalogo where idcatalogo='" . $baccion . "'";
    $resultado = $conexion->query($consulta);
    if ($resultado) {
        msg("Exito");
    } else {
        msg("No Exito");
    }
}
if ($bandera == "modificar") {
    $consulta  = "UPDATE catalogo set codigocuenta='" . $codigocuenta . "',nombrecuenta='" . $nombrecuenta . "',tipocuenta='" . $tipocuenta . "',saldocuenta='" . $saldocuenta . "',r='" . $r . "',nivelcuenta='" . $nivelcuenta . "' where idcatalogo='" . $baccion . "'";
    $resultado = $conexion->query($consulta);
    if ($resultado) {
        msg("En Hora Buena");
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
function msg($texto)
{
    echo "<script type='text/javascript'>";
    echo "alert('$texto');";
    echo "document.location.href='librodiario.php';";
    echo "</script>";
}
function mensajes($texto)
{
    echo "<script type='text/javascript'>";
    echo "alert('$texto');";

    echo "</script>";
}

//$mPartida="La partida se ha guardado con exito.";
function msgDebeHaber($mPartida)
{
    echo "<script type='text/javascript'>";
    echo "alert('$mPartida');";

    echo "</script>";
}

function guardarPartida()
{
          include "../config/conexion.php";
  //OBTENER EL AÑO QUE SE ESTA TRABAJANDO
  $resultAnio=$conexion->query("select * from anio where estado=1");
    if ($resultAnio)
    {
      while ($fila=$resultAnio->fetch_object())
      {
                      $idanio=$fila->idanio;
                      msg($idanio);
      }
    }
  $concepto=$_REQUEST['concepto'];
  $fecha=$_REQUEST['fecha'];
  $acumulador=$_SESSION['acumulador'];
  $matriz=$_SESSION['matriz'];
  //codigo para obtener el numero de la partida.
  $result = $conexion->query("select * from partida");
  $numeroPartida1=($result->num_rows)+1;
    //verificamos que haya un debe y haber
  if(!empty($matriz))
  {
    //codigo para guardar en la tabla partida
    $consulta  = "INSERT INTO partida VALUES('".$numeroPartida1."','".$concepto."','".$fecha."','".$idanio."')";
    $resultado = $conexion->query($consulta);
    if ($resultado) {
        msg("Exito Partida");
      } else {
        msg("No Exito Partida  (ESTAS EN EL ARCHIVO CORRECTO)");
    }
  }else {
    msgDebeHaber("Debe haber almenos un debe y un haber en la partida.");
  }
  for ($i=1; $i <=$acumulador ; $i++) {
    if (array_key_exists($i, $matriz)) {//verifica si existe elk indice en la matriz antes de imprimir
      //codigo para libro diario
          include "../config/conexion.php";
          $idcatalogo=$matriz[$i][0];
          $debe=$matriz[$i][1];
          $haber=$matriz[$i][2];
          $consulta  = "INSERT INTO ldiario VALUES('null','" . $numeroPartida1 . "','" . $idcatalogo . "','" . $debe . "','" . $haber . "','" . $idanio . "');";
          $resultado = $conexion->query($consulta);
          if ($resultado) {
              msg("Exito Libro Diario.");
          } else {
              msg("No Exito Libro Diario.");
          }
    }
  }
  unset($_SESSION["acumulador"]);
  unset($_SESSION["matriz"]);
  
 /* $mPartida="La partida se ha guardado con exito.";
msgDebeHaber($mPartida);*/
}
?>
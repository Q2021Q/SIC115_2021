<?php
//Codigo que muestra solo los errores exceptuando los notice.
error_reporting(E_ALL & ~E_NOTICE);
?>
<?php
session_start();
if($_SESSION["logueado"] == TRUE) {
$id  = $_REQUEST["id"] ?? "";
$aux = " ";
include "../config/conexion.php";
$result = $conexion->query("select * from usuarios where idusuario=" . $id);
if ($result) {
    while ($fila = $result->fetch_object()) {
        $idusuarioR   = $fila->idusuario;
        $nombreR  = $fila->nombre;
        $passR = $fila->pass;
        $mailR   = $fila->mail;
        $telefonoR  = $fila->telefono;
        $fechaR            = $fila->fecha;
        $usuarioR        = $fila->usuario;
    }
    $aux = "modificar";
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

  <link rel="shortcut icon" href="../asset/img/logomi.jpg">
  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
      <script type="text/javascript">

      function verificar(){
          if(document.getElementById('nombre').value=="" || document.getElementById('correo').value=="" || document.getElementById('telefono').value=="" || document.getElementById('usuario').value=="" || document.getElementById('pass').value==""){
            alert("Complete los campos");
          }else{
            if (document.getElementById("aux").value=="modificar") {

            document.getElementById('bandera').value="modificar";
            document.turismo.submit();
            }else
            {

            document.getElementById('bandera').value="add";
           document.turismo.submit();
            }
            }
        }

        function modify(id)
        {
          document.getElementById('nombre').value="";
          document.getElementById('correo').value="";
          document.getElementById('pass').value="";
          document.getElementById('telefono').value="";
          document.getElementById('usuario').value="";

         document.location.href='usuario.php?id='+id;
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
                        <h3 class="animated fadeInLeft">USUARIOS</h3>
                        <p class="animated fadeInDown">
                          Tabla <span class="fa-angle-right fa"></span> Usuarios
                        </p>
                    </div>
                  </div>
              </div>
              <form id="turismo" name="turismo" action="" method="">
              <input type="hidden" name="bandera" id="bandera">
              <input type="hidden" name="baccion" id="baccion" value="<?php echo $idusuarioR; ?>" >
              <input type="hidden" name="aux" id="aux" value="<?php echo $aux; ?>">
              <input type="hidden" name="r" id="r" value="">
              <div class="col-md-12 top-20 padding-0">
               <div class="col-md-4">


                            <div class="form-group form-animate-text" style="margin-top:30px !important;">
                              <input type="text" class="form-text" id="nombre" name="nombre" value="<?php echo $nombreR ?? ""; ?>" required>
                              <span class="bar"></span>
                              <label>Nombre</label>
                            </div>
                            <div class="form-group form-animate-text" style="margin-top:30px !important;">
                              <input type="text" class="form-text" id="correo" name="correo" value="<?php echo $mailR ?? ""; ?>" required>
                              <span class="bar"></span>
                              <label>E-mail</label>
                            </div>
                            <div class="form-group form-animate-text" style="margin-top:30px !important;">
                              <input type="text" class="form-text" id="telefono" name="telefono" value="<?php echo $telefonoR ?? ""; ?>" required>
                              <span class="bar"></span>
                              <label>Telefono</label>
                            </div>
                            <div class="form-group form-animate-text" style="margin-top:30px !important;">
                              <input type="text" class="form-text" id="usuario" name="usuario" value="<?php echo $usuarioR ?? ""; ?>" required>
                              <span class="bar"></span>
                              <label>Usuario</label>
                            </div>
                            <div class="form-group form-animate-text" style="margin-top:30px !important;">
                              <input type="password" class="form-text" id="pass" name="pass" value="<?php echo $passR ?? ""; ?>" required>
                              <span class="bar"></span>
                              <label>Contraseña</label>
                            </div>

                            <div class="col-md-3">
                              <button type="button" class="btn-flip btn btn-gradient btn-primary" onclick="verificar()">
                                <div class="flip">
                                  <div class="side">
                                    Guardar <span class="fa fa-trash"></span>
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
                    <div class="panel-heading"><h3>Lista De Usuarios</h3></div>
                    <div class="panel-body">
                      <div class="responsive-table">
                      <table id="datatables-example" class="table table-striped table-bordered" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Codigo</th>
                          <th>Nombre</th>
                          <th>E-mail</th>
                          <th>Telefono</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
include "../config/conexion.php";
$result = $conexion->query("select * from usuarios order by idusuario");
if ($result) {
    while ($fila = $result->fetch_object()) {
        echo "<tr>";
        echo "<td>
          <div class='col-md-2' style='margin-top:1px'>
            <button class='btn ripple-infinite btn-round btn-warning' onclick='modify(" . $fila->idusuario . ")';>
            <div>
              <span>Editar</span>
            </div>
            </button>
            </div>
        </td>";
        //echo "<tr>";
        //echo "<td><img src='img/modificar.png' style='width:30px; height:30px' onclick=modify(".$fila->idasignatura.",'".$fila->codigo."','".$fila->nombre."');></td>";
        //echo "<td><img src='img/eliminar.png' style='width:30px; height:30px' onclick=elyminar(".$fila->idasignatura.",'".$fila->nombre."');></td>";
        echo "<td>" . $fila->idusuario . "</td>";
        echo "<td>" . $fila->nombre . "</td>";
        echo "<td>" . $fila->mail . "</td>";
        echo "<td>" . $fila->telefono . "</td>";
        echo "<td>
          <div class='col-md-2' style='margin-top:1px'>
            <button class='btn ripple-infinite btn-round btn-success' onclick='confirmar(" . $fila->idusuario . ")'>
            <div>
              <span>Borrar</span>
            </div>
            </button>
            </div>
        </td>";
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

include "../config/conexion.php";

$bandera      = $_REQUEST["bandera"] ?? "";
$baccion      = $_REQUEST["baccion"] ?? "";
$mail  = $_REQUEST["correo"] ?? "";
$nombre= $_REQUEST["nombre"] ?? "";
$pass = $_REQUEST["pass"] ?? "";
$telefono   = $_REQUEST["telefono"] ?? "";
$usuario  = $_REQUEST["usuario"] ?? "";

function msg($texto)
{
    echo "<script type='text/javascript'>";
    echo "alert('$texto');";
    echo "document.location.href='usuario.php';";
    echo "</script>";
}

if ($bandera == "add") {
    $consulta  = "INSERT INTO usuarios VALUES('null','" . $nombre . "','" . $pass . "','" . $mail . "','" . $telefono . "',current_timestamp,'" . $usuario . "')";
    $resultado = $conexion->query($consulta);
    if ($resultado) {
        msg("Exito");
    } else {
        msg("No Exito");
    }
}
if ($bandera == "desaparecer") {
    $consulta  = "DELETE FROM usuarios where idusuario='" . $baccion . "'";
    $resultado = $conexion->query($consulta);
    if ($resultado) {
        msg("Exito");
    } else {
        msg("No Exito");
    }
}
if ($bandera == "modificar") {
    $consulta  = "UPDATE usuarios set nombre='" . $nombre . "',pass='" . $pass . "',mail='" . $mail . "',telefono='" . $telefono . "',usuario='" . $usuario . "' where idusuario='" . $baccion . "'";
    $resultado = $conexion->query($consulta);
    if ($resultado) {
       // msg("En Hora Buena");
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

} else {
header("Location: ../index.php");
}
?>

<?php
//Codigo que muestra solo los errores exceptuando los notice.
error_reporting(E_ALL & ~E_NOTICE);
?>
<!-- start:Left Menu -->
<?php
include "../config/conexion.php";
$final = null;
$result = $conexion->query("select * from anio where estado=1");
if($result)
{
  while ($fila=$result->fetch_object()) {
    $anioActivo=$fila->idanio;
    $final = $fila->inventariof;
  }
}
 ?>
<script type="text/javascript">
/*function inventarioFinal() {
    $('#myModalE').modal('show');
}

function noir() {
    alert("Es necesario un conteo fisico del inventario final para realizar el estado de resultados.");
    $('#myModalE').modal('hide');
}

function ir() {
    document.location.href = "estado.php?if=" + document.getElementById("inventarioFinal").value;
}

function inventarioFinal2() {
    $('#myModalB').modal('show');
}

function noir2() {
    alert("Es necesario un conteo fisico del inventario final para realizar el Balance General.");
    $('#myModalB').modal('hide');
}

function ir2() {
    document.location.href = "balanceGeneral.php?if=" + document.getElementById("inventarioFinal2").value;
}*/

function cerrado() {
    alert("Este anio ya esta cerrado.")
}

function inventarioFinal3() {
    $('#myModalC').modal('show');

}

function noir3() {
    alert("Pacholi 2018");
    $('#myModalC').modal('hide');
}

function ir3() {
    document.location.href = "Cierre.php?if=" + document.getElementById("inventarioFinal3").value + "&anioActivo=" +
        document.getElementById("anioActivo").value;
}
</script>
<div id="left-menu">
    <div class="sub-left-menu scroll">
        <ul class="nav nav-list">
            <li>
                <div class="left-bg"></div>
            </li>
            <li class="time">
                <h1 class="animated fadeInLeft">21:00</h1>
                <p class="animated fadeInRight">Sat,October 1st 2029</p>
            </li>
            <li class="active ripple">
                <a class="tree-toggle nav-header"><span class="fa-users fa"></span> Usuarios
                    <span class="fa-angle-right fa right-arrow text-right"></span>
                </a>
                <ul class="nav nav-list tree">
                    <li><a href="usuario.php">Nueva cuenta</a></li>
                </ul>
                <input type="hidden" id="anioActivo" name="anioActivo" value="<?php echo $anioActivo;?>">
            </li>
            <li class="active ripple">
                <a class="tree-toggle nav-header"><span class="fa-list fa"></span> Catalogo
                    <span class="fa-angle-right fa right-arrow text-right"></span>
                </a>
                <ul class="nav nav-list tree">

                    <li><a href="cuenta.php">Gestionar Catálogo</a></li>
                </ul>
                <ul class="nav nav-list tree">

                    <li><a href="listacuenta.php">Lista</a></li>
                </ul>
            </li>
            <li class="active ripple">
                <a class="tree-toggle nav-header"><span class="fa-book fa"></span> Libro Diario
                    <span class="fa-angle-right fa right-arrow text-right"></span>
                </a>
                <ul class="nav nav-list tree">

                    <li><a href="librodiario.php">Registrar</a></li>
                </ul>
                <ul class="nav nav-list tree">

                    <li><a href="mostrardiario.php">Mostrar</a></li>
                </ul>
            </li>
            <li class="active ripple">
                <a class="tree-toggle nav-header"><span class="fa-book fa"></span> Estado Financieros
                    <span class="fa-angle-right fa right-arrow text-right"></span>
                </a>
                <ul class="nav nav-list tree">

                    <li><a href="libromayor.php">Libro Mayor</a></li>
                </ul>
                <ul class="nav nav-list tree">
                <li><a href="estado.php">Estado de Resultados</a></li>
                </ul>
                <ul class="nav nav-list tree">
                    <?php
                    /*
                        include "../config/conexion.php";
                        $result = $conexion->query("select * from anio where estado=1");
                        if($result)
                        {
                          while ($fila=$result->fetch_object()) {
                            $final=$fila->inventariof;
                          }
                        }
                    */
                        if ($final>-1) {
                          echo "<li><a  onclick=cerrado2();>Balance General</a></li>";
                        }else {
                          echo "<li><a  href='balanceGeneral.php';'>Balance General</a></li>";
                        }
                         ?>

                </ul>
            <li class="active ripple">
                <a class="tree-toggle nav-header"><span class="fa fa-credit-card"></span> Costeo
                    <span class="fa-angle-right fa right-arrow text-right"></span>
                </a>
                <ul class="nav nav-list tree">
                    <li><a href="costeo.php">Generar costeo</a></li>
                </ul>
            </li>
            </li>

            <?php
                    include "../config/conexion.php";
                    $result = $conexion->query("select * from anio where estado=1");
                    if($result)
                    {
                      while ($fila=$result->fetch_object()) {
                        $final=$fila->inventariof;
                      }
                    }
                    if ($final>-1) {

                    }
                     ?>

        </ul>
    </div>
</div>
<div id="myModalE" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Inventario Final</h4>
            </div>
            <div class="modal-body">
                <div class="form-group form-animate-text" style="margin-top:30px !important;">
                    <input type="number" class="form-text" id="inventarioFinal" name="inventarioFinal" value="1"
                        min="1">
                    <span class="bar"></span>
                    <label>Inventario final.</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="ir();" class="btn btn-default" data-dismiss="modal">Ir</button>
                <button type="button" onclick="noir();" class="btn btn-default" data-dismiss="modal">Cerrar</button>

            </div>
        </div>

    </div>
</div>
<div id="myModalB" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Inventario Final</h4>
            </div>
            <div class="modal-body">
                <div class="form-group form-animate-text" style="margin-top:30px !important;">
                    <input type="number" class="form-text" id="inventarioFinal2" name="inventarioFinal2" value="1"
                        min="1">
                    <span class="bar"></span>
                    <label>Inventario final.</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="ir2();" class="btn btn-default" data-dismiss="modal">Ir</button>
                <button type="button" onclick="noir2();" class="btn btn-default" data-dismiss="modal">Cerrar</button>

            </div>
        </div>

    </div>
</div>
<div id="myModalC" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">¿Esta seguro de ejecutar el cierre del ejercio?</h4>
                <br />
                <h5 class="modal-title">Si es asi ingrese el Inventario Final</h5>
            </div>
            <div class="modal-body">
                <div class="form-group form-animate-text" style="margin-top:30px !important;">
                    <input type="number" class="form-text" id="inventarioFinal3" name="inventarioFinal3" value="1"
                        min="1">
                    <span class="bar"></span>
                    <label>Inventario final.</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="ir3();" class="btn btn-default" data-dismiss="modal">Ir</button>
                <button type="button" onclick="noir3();" class="btn btn-default" data-dismiss="modal">Cerrar</button>

            </div>
        </div>

    </div>
</div>


<!-- end: Menu modificado abi -->
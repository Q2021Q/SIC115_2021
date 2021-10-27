<?php
//Codigo que muestra solo los errores exceptuando los notice.
error_reporting(E_ALL & ~E_NOTICE);
?>
<?php
session_start();
$opcion=$_GET["opcion"] ?? "";
if ($opcion=="agregar") {
	$idcatalogo=$_GET["id"] ?? "";
	$codigoCuenta=$_GET["codigo"] ?? "";
	$montoPartida=$_GET["monto"] ?? "";
	$accion=$_GET["accion"] ?? "";
	if ($accion=="cargo") {
			$cargo=$montoPartida;
			$abono=null;
	}else {
		$abono=$montoPartida;
		$cargo=null;
	}
	$acumulador=$_SESSION["acumulador"] ?? "";
	$matriz=$_SESSION["matriz"];
	$acumulador++;
	$matriz[$acumulador][0]=$idcatalogo;
	$matriz[$acumulador][1]=$cargo;
	$matriz[$acumulador][2]=$abono;
	$_SESSION['acumulador']=$acumulador;
	$_SESSION['matriz']=$matriz;
	impresion();
}
if($opcion=="quitar") {
	$indice=$_GET["id"] ?? "";
	$matriz=$_SESSION['matriz'] ?? "";
	unset($matriz[$indice]);//eliminacion de un indice en la matriz
	$_SESSION['matriz']=$matriz;
	impresion();
}
if($opcion=="mostrar") {
	impresion();
}

function impresion()
{
?>
<div class="responsive-table">
    <table id="datatables-example" class="table table-striped table-bordered" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Codigo</th>
                <th>Concepto</th>
                <th>Debe</th>
                <th>Haber</th>
                <th>Modificar</th>
            </tr>
            <?php
		$acumulador=$_SESSION['acumulador'] ?? "";
		$matriz=$_SESSION['matriz'] ?? "";
	for ($i=1; $i <=$acumulador ; $i++) {
		if (array_key_exists($i, $matriz)) {//verifica si existe elk indice en la matriz antes de imprimir
		 ?>
        </thead>
        <tbody>
            <tr>
                <?php
					include "../config/conexion.php";
					$idcatalogo=$matriz[$i][0];
					$result=$conexion->query("select * from catalogo where idcatalogo=".$idcatalogo." order by tipocuenta");
					if ($result) {
	 		while ($fila=$result->fetch_object()) {
	 			$codigo=$fila->codigocuenta;
	 			$nombrecuenta=$fila->nombrecuenta;
	 		}
	 	}
			 ?>
                <td><?php echo $codigo; ?></td>
                <td><?php echo $nombrecuenta; ?></td>
                <td><?php echo $matriz[$i][1]; ?></td>
                <td><?php echo $matriz[$i][2]; ?></td>
                <td>
                    <div class='col-md-2' style='margin-top:1px'>
                        <button type="button" class='btn ripple-infinite btn-round btn-success'
                            onclick="aggPartida('quitar','<?php echo $i ?>')">
                            <div>
                                <span>Quitar</span>
                            </div>
                        </button>
                    </div>
                </td>
            </tr>
            <?php
	}
	}
	?>
        </tbody>
    </table>
</div>
<?php } ?>
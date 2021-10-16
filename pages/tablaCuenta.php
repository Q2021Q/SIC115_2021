<?php
//Codigo que muestra solo los errores exceptuando los notice.
error_reporting(E_ALL & ~E_NOTICE);
?>
<?php
include "../config/conexion.php";
$result = $conexion->query("select * from catalogo where nivel>1 order by codigocuenta");
if ($result) {
while ($fila = $result->fetch_object()) {
echo "<tr>";
echo "<td>
<div class='col-md-2' style='margin-top:1px'>
<button type='button' class='btn ripple-infinite btn-round btn-success' data-dismiss='modal' data-toggle='tooltip' data-placement='left' title='Envio de datos al formulario.' onclick=llenarDatos(" . $fila->codigocuenta . "," . $fila->idcatalogo . ",'" . str_replace(" ",".",$fila->nombrecuenta) . "')>
<div>
<span>Enviar</span>
</div>
</button>
</div>
</td>";
//echo "<tr>";
//echo "<td><img src='img/modificar.png' style='width:30px; height:30px' onclick=modify(".$fila->idasignatura.",'".$fila->codigo."','".$fila->nombre."');></td>";
//echo "<td><img src='img/eliminar.png' style='width:30px; height:30px' onclick=elyminar(".$fila->idasignatura.",'".$fila->nombre."');></td>";
echo "<td>" . $fila->codigocuenta . "</td>";
echo "<td>" . $fila->nombrecuenta . "</td>";
echo "<td>" . $fila->saldo . "</td>";

echo "</tr>";
}
}
?>
      </tbody>
        </table>

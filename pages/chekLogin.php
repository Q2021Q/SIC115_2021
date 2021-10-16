<?php
//Codigo que muestra solo los errores exceptuando los notice.
error_reporting(E_ALL & ~E_NOTICE);
?>
<?php
	if(isset($_POST["enviar"])) {
			$loginNombre = $_POST["usuario"];
			$loginPassword =$_POST["pass"];
      $correcto=false;
    include "../config/conexion.php";
      $result = $conexion->query("select * from usuarios where usuario= '$loginNombre' AND pass='$loginPassword'");
      echo "<script>function hola(){
        alert('".$loginNombre."');
      }</script>";
if ($result) {
    while ($fila = $result->fetch_object()) {
        $passR = $fila->pass;
				$Nombre=$fila->nombre;
        if($passR==$loginPassword){
          $correcto=true;
        }
    }
}
			if(isset($loginNombre) && isset($loginPassword)) {
				if($correcto==true) {
					session_start();
					$_SESSION["logueado"] = TRUE;
					$_SESSION["usuario"] = $Nombre;
					header("Location: main.php");
				}
				else {
					Header("Location: ../index.php?error=login");
				}
			}
		} else {
			header("Location: ../index.php");
		}
 ?>

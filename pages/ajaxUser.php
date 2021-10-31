
<?php
session_start();
include "../config/conexion.php";
if (isset($_SESSION['usuario'])) {
	echo '<script> window.location="main.php";<script>';
}
echo '
<div class="login">
    <form method="post" action="pages/chekLogin.php" class="form-group">
    	<input type="text" style="font-size:1em;" name="usuario" id="usuario" placeholder="Usuario" required="required" />
        <input type="password" style="font-size:1em;" name="pass" id="pass" placeholder="Contraseña" required="required" />
        <button type="submit" name="enviar" class="btn btn-primary btn-block btn-large">Entrar</button>
    </form>
</div>'
 ?>

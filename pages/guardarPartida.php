<?php
//Codigo que muestra solo los errores exceptuando los notice.
error_reporting(E_ALL & ~E_NOTICE);
?>
<?php
    function procesar()
    {
      $error="Correcto";
      msg2($error);
        //$totalcargo=0;
        //$totalabono=0;
        //$acumulador=$_SESSION['acumulador'];
        //$matriz=$_SESSION['matriz'];
        //for ($i=1; $i <=$acumulador ; $i++) {
        //	if (array_key_exists($i, $matriz)) {
        //		$totalcargo=$totalcargo+$matriz[$i][1];
        //		$totalabono=$totalabono+$matriz[$i][2];
        //	}
        //}
        //if($totalcargo!=$totalabono)
        //{
        //	$error="El debe y el haber, son diferentes.";
        //	msg2($error);
        //}else
        //{
        //	$error="Correcto";
        //	msg2($error);
        //}
    }
 ?>

 <?php
 function msg2($texto)
 {
     echo "<script type='text/javascript'>";
     echo "alert('$texto');";
     echo "</script>";
 }
?>

<?php
//Codigo que muestra solo los errores exceptuando los notice.
error_reporting(E_ALL & ~E_NOTICE);
?>
<?php 
session_start();
$logueado = $_SESSION["logueado"] ?? "";
if ($logueado==TRUE) {
	Header("Location: pages/main.php");
}else {
	$errorLogin=$_GET["error"] ?? "";
	if($errorLogin=="login") {
		$error="El usuario o contraseña es invalido.";
		msg($error);
	 }
}
 ?>
<!DOCTYPE HTML>
<html>

<head>
    <title>Crisales</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="assets/css/login.css" />
    
	<link rel="shortcut icon" href="./asset/img/logomi.jpg">
    <!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
    <!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
    <script type="text/javascript">
    function llamar() {
        mostraLog(" ");
        //	location.href="pages/Cuenta.php";
    }

    function mostraLog(str) {
        if (str == "") {
            document.getElementById("logindiv").innerHTML = "";
            return;
        }
        if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("logindiv").innerHTML = xmlhttp.responseText;
            }
        }

        xmlhttp.open("GET", "pages/ajaxUser.php", true);
        xmlhttp.send();
    }
    </script>

</head>

<body class="loading">
    <div id="wrapper">
        <div id="bg"></div>
        <div id="overlay"></div>
        <div id="main">
            <!-- Header -->

            <header id="header">
                <h1>Sistema Contable</h1>
                <p>SIC-115</p>
                <nav>
                    <ul>
                        <li class="tooltip"><a class="icon fa-user" onclick="llamar()"><span class="label btn tooltiptext">Iniciar Sesion</span></a></li>
                    </ul>

                    <br>
                    <ul>
                        <div id="logindiv">

                        </div>
                    </ul>
                </nav>
            </header>

        </div>
    </div>
    <!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
    <script>
    window.onload = function() {
        document.body.className = '';
    }
    window.ontouchmove = function() {
        return false;
    }
    window.onorientationchange = function() {
        document.body.scrollTop = 0;
    }
    </script>
</body>

</html>
<?php
function msg($texto)
{
    echo "<script type='text/javascript'>";
    echo "alert('$texto');";
  //  echo "document.location.href='cuenta.php';";
    echo "</script>";
}

 ?>
<?php
session_start();
if($_SESSION["logueado"] == TRUE){
  include "../config/conexion.php";
  $utilidad = $_REQUEST['utilidad'];
  if($utilidad!=''){
  //obteniendo el año activo
  $result = $conexion->query("select idanio, inventarioi, inventariof from anio where estado=1");
  if($result){
    $fila = $result->fetch_object();
    $anioActivo =  $fila->idanio;
    $invi = $fila->inventarioi;
    $invf = $fila->inventariof;
  }
  $result->close();

  //Agregando partida de cierre contable
  $result = $conexion->query("select idpartida from partida");
  if($result){
    $numeropartida = ($result->num_rows)+1;
  }
  $result->close();

  $consulta = "INSERT INTO partida VALUES ('".$numeropartida."','cierre de periodo contable','2022-12-31','2022')";
  $cierre_query = $conexion->query($consulta);
  if($cierre_query){
      echo "<script type='text/javascript'>";
      echo "alert('Exito al ingresar partida de cierre');";
      echo "</script>";
  }else{
    msg("Fallo al ingresar partida de cierre");
  }

  //Realizando cierre para metodo de inventario analitico

  $consulta = "Select idcatalogo as id FROM catalogo WHERE codigocuenta = '110701'";
  $result = $conexion->query($consulta);
  if($result){
    $registro = $result->fetch_object();
    $idcatalogo = $registro->id;
    $cerrarInv = "INSERT INTO ldiario VALUES ('null','".$numeropartida."','".$idcatalogo."','".$invf."','".$invi."','".$anioActivo."')";
    $resulta = $conexion->query($cerrarInv);

    if($resulta){
      echo "<script type='text/javascript'>";
      echo "alert('Exito al cerrar el inventario');";
      echo "</script>";
    }
  }

  //Agregando el inventario final a las perdidas y ganancias
  $consulta = "Select idcatalogo as id FROM catalogo WHERE codigocuenta = '610101'";
  $result = $conexion->query($consulta);
  if($result){
    $registro = $result->fetch_object();
    $idpg = $registro->id;
    $cerrarInv = "INSERT INTO ldiario VALUES ('null','".$numeropartida."','".$idpg."','".$invi."','".$invf."','".$anioActivo."')";
    $resulta = $conexion->query($cerrarInv);

    if($resulta){
      echo "<script type='text/javascript'>";
      echo "alert('Exito al cargar p/g');";
      echo "</script>";
    }
  }

   //Obteniendo cuentas de resultados
   for($i=4; $i < 6; $i++){
   $consulta = "Select c.idcatalogo as idcata, l.debe as debe , l.haber as haber FROM catalogo c INNER JOIN ldiario l ON c.idcatalogo = l.idcatalogo INNER JOIN partida p ON l.idpartida = p.idpartida WHERE substring(c.codigocuenta,1,1) = ".$i." and p.idanio='".$anioActivo."' order by c.codigocuenta asc;";
   $result = $conexion->query($consulta);
   if ($result){
     $idcatalogo = '';
     $saldofinal = 0;
 
     $registro = $result->fetch_object();
     //almacenando primer resultado de los registros
     $idcatalogo = $registro->idcata;
     $saldofinal = ($registro->debe) - ($registro->haber);
 
     while ($registro = $result->fetch_object()){
       if($idcatalogo == $registro->idcata){
         $saldofinal = $saldofinal + ($registro->debe) - ($registro->haber);
       }else{
 
         if($saldofinal>0){
           $consulta = "INSERT INTO ldiario VALUES ('null','".$numeropartida."','".$idcatalogo."','0','".$saldofinal."','".$anioActivo."')";
           $consultapg = "INSERT INTO ldiario VALUES ('null','".$numeropartida."','".$idpg."','".$saldofinal."','0','".$anioActivo."')";
         }elseif($saldofinal<0){
           $saldofinal = $saldofinal*-1;
           $consulta = "INSERT INTO ldiario VALUES ('null','".$numeropartida."','".$idcatalogo."','".$saldofinal."', '0','".$anioActivo."')";
           $consultapg = "INSERT INTO ldiario VALUES ('null','".$numeropartida."','".$idpg."','0','".$saldofinal."','".$anioActivo."')";
         }else{
           
         }
         $insertar = $conexion->query($consulta);
         $insertpg = $conexion->query($consultapg);
         if($insertar && $insertpg){
          // msg('cuenta'.$idcatalogo.' deudora saldada');
         }
 
         $idcatalogo = $registro->idcata;
         $saldofinal = ($registro->debe) - ($registro->haber);
       }
     }
 
     if($saldofinal>0){
       $consulta = "INSERT INTO ldiario VALUES ('null','".$numeropartida."','".$idcatalogo."','0','".$saldofinal."','".$anioActivo."')";
       $consultapg = "INSERT INTO ldiario VALUES ('null','".$numeropartida."','".$idpg."','".$saldofinal."','0','".$anioActivo."')";
     }elseif($saldofinal<0){
       $saldofinal = $saldofinal*-1;
       $consulta = "INSERT INTO ldiario VALUES ('null','".$numeropartida."','".$idcatalogo."','".$saldofinal."', '0','".$anioActivo."')";
       $consultapg = "INSERT INTO ldiario VALUES ('null','".$numeropartida."','".$idpg."','0','".$saldofinal."','".$anioActivo."')";
     }else{
       
     }
     $insertar = $conexion->query($consulta);
     $insertpg = $conexion->query($consultapg);
         if($insertar && $insertpg){
           //msg('cuenta'.$idcatalogo.' deudora saldada');
         }
   }
  }
  //Agregando a la cuenta de utilidad del ejercicio
  if($utilidad>0){

    $reslegal = $utilidad*0.07;
    $imprenta = ($utilidad - $reslegal)*0.3; 
    $ejercicio = $utilidad - $reslegal - $imprenta;

    //obteniendo id de reserva legal
    $consulta = "Select idcatalogo as id FROM catalogo WHERE codigocuenta = '310201'";
    $result = $conexion->query($consulta);
    if($result){
    $registro = $result->fetch_object();
    $idcatalogo = $registro->id;
    //haciendo cargo a pg y abonando a reserva legal
    $consultapg = "INSERT INTO ldiario VALUES ('null','".$numeropartida."','".$idpg."','".$reslegal."','0','".$anioActivo."')";
    $consulta = "INSERT INTO ldiario VALUES ('null','".$numeropartida."','".$idcatalogo."','0','".$reslegal."','".$anioActivo."')";
    $insertpg = $conexion->query($consultapg);
    $insertar = $conexion->query($consulta);
        if($insertar && $insertpg){
          //msg('cuenta'.$idcatalogo.' cargada');
    }
  }

    //obteniendo id de Impuesto sobre renta
    $consulta = "Select idcatalogo as id FROM catalogo WHERE codigocuenta = '211101'";
    $result = $conexion->query($consulta);
    if($result){
    $registro = $result->fetch_object();
    $idcatalogo = $registro->id;
    //haciendo cargo a pg y abonando a impuesto sobre la renta
    $consultapg = "INSERT INTO ldiario VALUES ('null','".$numeropartida."','".$idpg."','".$imprenta."','0','".$anioActivo."')";
    $consulta = "INSERT INTO ldiario VALUES ('null','".$numeropartida."','".$idcatalogo."','0','".$imprenta."','".$anioActivo."')";
    $insertpg = $conexion->query($consultapg);
    $insertar = $conexion->query($consulta);
        if($insertar && $insertpg){
          //msg('cuenta'.$idcatalogo.' cargada');
    }
  }

    //obteniendo id de utilidad del ejercicio
    $consulta = "Select idcatalogo as id FROM catalogo WHERE codigocuenta = '310302'";
    $result = $conexion->query($consulta);
    if($result){
    $registro = $result->fetch_object();
    $idcatalogo = $registro->id;
    //haciendo cargo a pg y abonando a utilidad del ejercicio
    $consultapg = "INSERT INTO ldiario VALUES ('null','".$numeropartida."','".$idpg."','".$ejercicio."','0','".$anioActivo."')";
    $consulta = "INSERT INTO ldiario VALUES ('null','".$numeropartida."','".$idcatalogo."','0','".$ejercicio."','".$anioActivo."')";
    $insertpg = $conexion->query($consultapg);
    $insertar = $conexion->query($consulta);
        if($insertar && $insertpg){
          //msg('cuenta'.$idcatalogo.' cargada');
    }
  }

    /*
    $consulta = "Select idcatalogo as id FROM catalogo WHERE codigocuenta = '310302'";
    $result = $conexion->query($consulta);
    $registro = $result->fetch_object();
    $idcatalogo = $registro->id;

    $consulta = "INSERT INTO ldiario VALUES ('null','".$numeropartida."','".$idcatalogo."','0','".$utilidad."','".$anioActivo."')";
    $insertar = $conexion->query($consulta);
        if($insertar){
          //msg('cuenta'.$idcatalogo.' cargada');
        }

        */
  }elseif($utilidad<0){
    $utilidad = $utilidad*-1;
    //obteniendo id de utilidad del ejercicio
    $consulta = "Select idcatalogo as id FROM catalogo WHERE codigocuenta = '310402'";
    $result = $conexion->query($consulta);
    if($result){
    $registro = $result->fetch_object();
    $idcatalogo = $registro->id;
    //haciendo abono a pg y cargando a perdida del ejercicio
    $consultapg = "INSERT INTO ldiario VALUES ('null','".$numeropartida."','".$idpg."','0','".$utilidad."','".$anioActivo."')";
    $consulta = "INSERT INTO ldiario VALUES ('null','".$numeropartida."','".$idcatalogo."','".$utilidad."','0','".$anioActivo."')";
    $insertpg = $conexion->query($consultapg);
    $insertar = $conexion->query($consulta);
        if($insertar && $insertpg){
          //msg('cuenta'.$idcatalogo.' cargada');
    }
  }
  }else{

  }


  header("Location: ./mostrardiario.php");
  }else{
    header("Location: ./estado.php");
  }
}else{
  header("Location: ../index.php");
}
 ?>
<?php
$conexion = new mysqli('localhost', 'root', '', 'sistemacontable');
if ($conexion->connect_errno) {
    echo "Error de conexion";
}

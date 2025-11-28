<?php
// conexion.php
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = ''; // pon tu contraseña si usas otra
$DB_NAME = 'proyecto';

$conexion = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conexion->connect_errno) {
    die("Error de conexión MySQL: " . $conexion->connect_error);
}
$conexion->set_charset("utf8mb4");
?>

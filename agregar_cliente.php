<?php
// agregar_cliente.php
session_start();
if (!isset($_SESSION['user_id'])) header("Location: index.php");
require_once "conexion.php";

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar'])) {
    $nombre = trim($_POST['nombre']);
    $cedula = trim($_POST['cedula']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);

    $stmt = $conexion->prepare("INSERT INTO clientes (nombre, cedula, telefono, direccion) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $cedula, $telefono, $direccion);
    if ($stmt->execute()) $msg = "Cliente guardado correctamente.";
    else {
        if ($conexion->errno === 1062) $msg = "Cédula ya registrada.";
        else $msg = "Error: " . $conexion->error;
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Agregar Cliente</title></head>
<body>
  <h2>Agregar Cliente</h2>
  <?php if ($msg) echo "<p>$msg</p>"; ?>
  <form method="post">
    <label>Nombre: <input type="text" name="nombre" required></label><br>
    <label>Cédula: <input type="text" name="cedula" required></label><br>
    <label>Teléfono: <input type="text" name="telefono"></label><br>
    <label>Dirección: <input type="text" name="direccion"></label><br>
    <button name="guardar" type="submit">Guardar</button>
  </form>
  <p><a href="dashboard.php">Volver</a></p>
</body>
</html>




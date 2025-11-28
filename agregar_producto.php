<?php
// agregar_producto.php
session_start();
if (!isset($_SESSION['user_id'])) header("Location: index.php");
require_once "conexion.php";

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar'])) {
    $nombre = trim($_POST['nombre']);
    $precio = floatval($_POST['precio']);
    $cantidad = intval($_POST['cantidad']);
    $descripcion = trim($_POST['descripcion']);

    $sql = "INSERT INTO productos (nombre, precio, cantidad, descripcion) VALUES (?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    die("ERROR EN PREPARE: " . $conexion->error . "<br>SQL: " . $sql);
}

    $stmt->bind_param("sdis", $nombre, $precio, $cantidad, $descripcion);
    if ($stmt->execute()) $msg = "Producto guardado correctamente.";
    else $msg = "Error: " . $conexion->error;
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Agregar Producto</title></head>
<body>
  <h2>Agregar Producto</h2>
  <?php if ($msg) echo "<p>$msg</p>"; ?>
  <form method="post">
    <label>Nombre: <input type="text" name="nombre" required></label><br>
    <label>Precio: <input type="number" step="0.01" name="precio" required></label><br>
    <label>Cantidad (stock): <input type="number" name="cantidad" required></label><br>
    <label>Descripción: <input type="text" name="descripcion"></label><br>
    <button name="guardar" type="submit">Guardar</button>
  </form>
  <p><a href="dashboard.php">Volver</a></p>
</body>
</html>


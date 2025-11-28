<?php
// listar_productos.php
session_start();
if (!isset($_SESSION['user_id'])) header("Location: index.php");
require_once "conexion.php";

$res = $conexion->query("SELECT * FROM productos ORDER BY nombre ASC");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Productos</title></head>
<body>
  <h2>Lista de productos</h2>
  <table border="1" cellpadding="6">
    <tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Stock</th><th>Descripción</th></tr>
    <?php while ($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['nombre']) ?></td>
        <td><?= number_format($row['precio'],2) ?></td>
        <td><?= $row['cantidad'] ?></td>
        <td><?= htmlspecialchars($row['descripcion']) ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
  <p><a href="dashboard.php">Volver</a></p>
</body>
</html>

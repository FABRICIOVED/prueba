<?php
// listar_clientes.php
session_start();
if (!isset($_SESSION['user_id'])) header("Location: index.php");
require_once "conexion.php";

$res = $conexion->query("SELECT * FROM clientes ORDER BY nombre ASC");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Clientes</title></head>
<body>
  <h2>Lista de clientes</h2>
  <table border="1" cellpadding="6">
    <tr><th>ID</th><th>Nombre</th><th>Cédula</th><th>Teléfono</th><th>Dirección</th></tr>
    <?php while ($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['nombre']) ?></td>
        <td><?= htmlspecialchars($row['cedula']) ?></td>
        <td><?= htmlspecialchars($row['telefono']) ?></td>
        <td><?= htmlspecialchars($row['direccion']) ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
  <p><a href="dashboard.php">Volver</a></p>
</body>
</html>

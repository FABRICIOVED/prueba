<?php
// listar_facturas.php (versión corregida y con manejo de errores)
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
require_once "conexion.php";

// Consulta SQL en una variable (más legible y fácil de depurar)
$sql = "
    SELECT 
        f.id, 
        f.fecha, 
        f.total, 
        c.nombre AS cliente, 
        u.nombre AS usuario
    FROM facturas f
    JOIN clientes c ON f.id_cliente = c.id
    LEFT JOIN usuarios u ON f.id_usuario = u.id
    ORDER BY f.fecha DESC
";

// Ejecutar la consulta y comprobar errores
$res = $conexion->query($sql);
if ($res === false) {
    // Mostrar el error de MySQL para identificar el problema (puedes comentar esto en producción)
    die("Error en la consulta SQL: " . $conexion->error);
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Facturas</title></head>
<body>
  <h2>Facturas</h2>
  <table border="1" cellpadding="6">
    <tr><th>ID</th><th>Fecha</th><th>Cliente</th><th>Total</th><th>Usuario</th></tr>
    <?php while ($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['id']) ?></td>
        <td><?= htmlspecialchars($row['fecha']) ?></td>
        <td><?= htmlspecialchars($row['cliente'] ?? '---') ?></td>
        <td><?= number_format(floatval($row['total']), 2) ?></td>
        <td><?= htmlspecialchars($row['usuario'] ?? 'Sistema') ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
  <p><a href="dashboard.php">Volver</a></p>
</body>
</html>


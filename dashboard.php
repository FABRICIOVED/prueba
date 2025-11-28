<?php
// dashboard.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Dashboard</title></head>
<body>
  <h1>Bienvenido <?= htmlspecialchars($_SESSION['user_name']) ?></h1>
  <ul>
    <li><a href="agregar_cliente.php">Agregar cliente</a></li>
    <li><a href="listar_clientes.php">Listar clientes</a></li>
    <li><a href="agregar_producto.php">Agregar producto</a></li>
    <li><a href="listar_productos.php">Listar productos</a></li>
    <li><a href="crear_factura.php">Crear factura</a></li>
    <li><a href="listar_facturas.php">Listar facturas</a></li>
    <li><a href="logout.php">Cerrar sesión</a></li>
  </ul>
</body>
</html>

<?php
// guardar_factura.php
session_start();
if (!isset($_SESSION['user_id'])) header("Location: index.php");
require_once "conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {

    $id_cliente = intval($_POST['id_cliente']);
    $id_productos = $_POST['id_producto'] ?? [];
    $cantidades = $_POST['cantidad'] ?? [];
    $total = floatval($_POST['total']);

    if (empty($id_cliente) || empty($id_productos) || count($id_productos) !== count($cantidades)) {
        die("Datos inválidos.");
    }

    $conexion->begin_transaction();

    try {

        // insertar factura (cabecera)
        $stmt = $conexion->prepare("INSERT INTO facturas (id_cliente, total, id_usuario) VALUES (?, ?, ?)");
        $stmt->bind_param("idi", $id_cliente, $total, $_SESSION['user_id']);
        $stmt->execute();

        $id_factura = $conexion->insert_id;

        // preparar detalle
        $stmt_det = $conexion->prepare("INSERT INTO factura_detalle (id_factura, id_producto, cantidad, precio, subtotal) 
                                        VALUES (?, ?, ?, ?, ?)");

        for ($i = 0; $i < count($id_productos); $i++) {

            $prod_id = intval($id_productos[$i]);
            $cant = intval($cantidades[$i]);

            // obtener precio y stock (columna correcta: cantidad)
            $res = $conexion->query("SELECT precio, cantidad FROM productos WHERE id = $prod_id LIMIT 1");
            
            if (!$res) {
                throw new Exception("Error SQL productos: " . $conexion->error);
            }

            if ($row = $res->fetch_assoc()) {

                $precio = floatval($row['precio']);
                $stock = intval($row['cantidad']);  // columna correcta

                if ($cant > $stock) {
                    throw new Exception("Stock insuficiente para el producto ID $prod_id");
                }

                $subtotal = $precio * $cant;

                // insertar detalle
                $stmt_det->bind_param("iiidd", $id_factura, $prod_id, $cant, $precio, $subtotal);
                $stmt_det->execute();

                // nuevo stock
                $new_stock = $stock - $cant;

                // actualizar productos correctamente
                $conexion->query("UPDATE productos SET cantidad = $new_stock WHERE id = $prod_id");

            } else {
                throw new Exception("Producto ID $prod_id no encontrado");
            }
        }

        // commit final
        $conexion->commit();
        header("Location: dashboard.php?msg=factura_ok");
        exit;

    } catch (Exception $ex) {
        $conexion->rollback();
        die("Error al guardar factura: " . $ex->getMessage());
    }

} else {
    header("Location: crear_factura.php");
}

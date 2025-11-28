<?php
// crear_factura.php
session_start();
if (!isset($_SESSION['user_id'])) header("Location: index.php");
require_once "conexion.php";

// obtener clientes y productos
$clientes = $conexion->query("SELECT id, nombre, cedula FROM clientes ORDER BY nombre");
$productos = $conexion->query("SELECT id, nombre, precio, cantidad FROM productos ORDER BY nombre");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Crear Factura</title></head>
<body>
  <h2>Crear factura</h2>
  <form method="post" action="guardar_factura.php" id="facturaForm">
    <label>Cliente:
      <select name="id_cliente" required>
        <option value="">-- Seleccione cliente --</option>
        <?php while ($c = $clientes->fetch_assoc()): ?>
          <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre'] . ' (CI:'.$c['cedula'].')') ?></option>
        <?php endwhile; ?>
      </select>
    </label><br><br>

    <h3>Productos</h3>
    <div id="lineas">
      <div class="linea">
        <select name="id_producto[]" class="prod" required>
          <option value="">-- Producto --</option>
          <?php
            $productos->data_seek(0);
            while ($p = $productos->fetch_assoc()):
          ?>
            <option value="<?= $p['id'] ?>" data-precio="<?= $p['precio'] ?>" data-stock="<?= $p['cantidad'] ?>">
              <?= htmlspecialchars($p['nombre']) ?> - <?= number_format($p['precio'],2) ?>
            </option>
          <?php endwhile; ?>
        </select>
        Cantidad: <input type="number" name="cantidad[]" class="cant" value="1" min="1" required>
        Subtotal: <span class="subtotal">0.00</span>
        <button type="button" class="remove">Eliminar</button>
      </div>
    </div>
    <button type="button" id="add">Agregar línea</button><br><br>
    Total: <input type="text" name="total" id="total" value="0.00" readonly><br><br>
    <button type="submit" name="crear">Guardar factura</button>
  </form>

  <p><a href="dashboard.php">Volver</a></p>

<script>
document.getElementById('add').addEventListener('click', function(){
  const origen = document.querySelector('.linea');
  const clone = origen.cloneNode(true);
  clone.querySelectorAll('input').forEach(i => { if (i.name === 'cantidad[]') i.value = 1; else i.value = ''; });
  clone.querySelector('.subtotal').textContent = '0.00';
  document.getElementById('lineas').appendChild(clone);
  calcularTotal();
});

document.addEventListener('click', function(e){
  if (e.target && e.target.classList.contains('remove')) {
    const lines = document.querySelectorAll('.linea');
    if (lines.length > 1) e.target.closest('.linea').remove();
    calcularTotal();
  }
});

function calcularTotal(){
  let total = 0;
  document.querySelectorAll('.linea').forEach(line => {
    const sel = line.querySelector('.prod');
    const cant = parseFloat(line.querySelector('.cant').value) || 0;
    const precio = parseFloat(sel.options[sel.selectedIndex].dataset.precio || 0);
    const sub = precio * cant;
    line.querySelector('.subtotal').textContent = sub.toFixed(2);
    total += sub;
  });
  document.getElementById('total').value = total.toFixed(2);
}

document.addEventListener('change', function(e){ if (e.target.matches('.prod, .cant')) calcularTotal(); });
document.addEventListener('input', function(e){ if (e.target.matches('.cant')) calcularTotal(); });
window.addEventListener('load', calcularTotal);
</script>
</body>
</html>


<?php
// registro.php
require_once "conexion.php";
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar'])) {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $clave = $_POST['clave'];

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Correo inválido.";
    } elseif (strlen($clave) < 6) {
        $mensaje = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $hash = password_hash($clave, PASSWORD_DEFAULT);
        $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, correo, contraseña) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $correo, $hash);
        if ($stmt->execute()) {
            $mensaje = "Registrado correctamente. <a href='index.php'>Inicia sesión</a>.";
        } else {
            if ($conexion->errno === 1062) $mensaje = "El correo ya está registrado.";
            else $mensaje = "Error al registrar: " . $conexion->error;
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Registro</title></head>
<body>
  <h2>Registro de usuario</h2>
  <?php if ($mensaje) echo "<p>$mensaje</p>"; ?>
  <form method="post">
    <label>Nombre completo: <input type="text" name="nombre" required></label><br>
    <label>Correo: <input type="email" name="correo" required></label><br>
    <label>Contraseña: <input type="password" name="clave" required></label><br>
    <button name="registrar" type="submit">Registrarse</button>
  </form>
  <p><a href="index.php">Volver a login</a></p>
</body>
</html>

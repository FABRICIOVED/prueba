<?php
// index.php
session_start();
require_once "conexion.php";

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['entrar'])) {
    $user = trim($_POST['user']);
    $pass = $_POST['pass'];

    $stmt = $conexion->prepare("SELECT id, nombre, correo, contraseña FROM usuarios WHERE correo = ? OR nombre = ? LIMIT 1");
    $stmt->bind_param("ss", $user, $user);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        if (password_verify($pass, $row['contraseña'])) {
            // login ok
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['nombre'];
            header("Location: dashboard.php");
            exit;
        }
    }
    $error = "Usuario o contraseña incorrectos.";
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Login</title></head>
<body>
  <h2>Iniciar sesión</h2>
  <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
  <form method="post">
    <label>Usuario o correo: <input type="text" name="user" required></label><br>
    <label>Contraseña: <input type="password" name="pass" required></label><br>
    <button name="entrar" type="submit">Entrar</button>
  </form>
  <p>¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
</body>
</html>

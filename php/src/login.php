<?php
require_once 'db.php';
session_start();
 
// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}
 
// Sanitizar entradas — NUNCA confiar en datos del usuario
$username = trim(htmlspecialchars($_POST['username'] ?? ''));
$password = $_POST['password'] ?? '';
 
if (empty($username) || empty($password)) {
    header('Location: index.php?error=credenciales');
    exit;
}
 
try {
    $db = DB::getInstance();
 
    // Consulta parametrizada — previene SQL Injection
    $stmt = $db->prepare(
        'SELECT id, username, email, password, rol
         FROM usuarios
         WHERE username = :username AND activo = 1
      LIMIT 1'
    );
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();
 
    // Verificar contraseña con bcrypt
    if (!$user || !password_verify($password, $user['password'])) {
        // Misma respuesta tanto si el usuario no existe como si la clave es incorrecta
        // Evita enumeration attacks
        header('Location: index.php?error=credenciales');
        exit;
    }
 
    // Regenerar ID de sesión — previene session fixation
    session_regenerate_id(true);
 
    // Guardar datos en sesión
    $_SESSION['usuario_id']  = $user['id'];
    $_SESSION['username']    = $user['username'];
    $_SESSION['email']       = $user['email'];
    $_SESSION['rol']         = $user['rol'];
    $_SESSION['login_time']  = time();
 
    // Actualizar último login en BD
    $upd = $db->prepare('UPDATE usuarios SET ultimo_login = NOW() WHERE id = :id');
    $upd->execute([':id' => $user['id']]);
 
    header('Location: dashboard.php');
    exit;
 
} catch (Exception $e) {
    error_log('Login error: ' . $e->getMessage());
    header('Location: index.php?error=sistema');
    exit;
}

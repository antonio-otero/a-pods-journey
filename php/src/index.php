<?php
session_start();
// Si ya hay sesión activa, redirigir al dashboard
if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portal Empresa — Login</title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family: 'Segoe UI', sans-serif; background: #1a1a2e;
           display:flex; justify-content:center; align-items:center; min-height:100vh; }
    .card { background:#fff; border-radius:12px; padding:40px; width:380px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4); }
    .logo { text-align:center; margin-bottom:30px; }
    .logo h1 { color:#1F4E79; font-size:24px; }
    .logo p  { color:#666; font-size:13px; margin-top:5px; }
    label { display:block; font-size:13px; font-weight:600; color:#333; margin-bottom:5px; }
    input[type=text], input[type=password] {
      width:100%; padding:12px; border:1px solid #ddd; border-radius:8px;
      font-size:14px; margin-bottom:18px; transition:border-color .2s; }
    input:focus { outline:none; border-color:#2E75B6; box-shadow:0 0 0 3px rgba(46,117,182,.15); }
    button { width:100%; padding:12px; background:#1F4E79; color:#fff;
             border:none; border-radius:8px; font-size:15px; font-weight:600;
             cursor:pointer; transition:background .2s; }
    button:hover { background:#2E75B6; }
    .error { background:#fee; border:1px solid #fcc; border-radius:6px;
             padding:10px 14px; color:#c00; font-size:13px; margin-bottom:16px; }
    .env-badge { text-align:center; margin-top:20px; font-size:11px; color:#aaa; }
  </style>
</head>
<body>
  <div class="card">
    <div class="logo">
      <h1>🏢 Portal Empresa</h1>
      <p>Sistema de Gestión Interna</p>
    </div>
    <?php if (isset($_GET['error'])): ?>
      <div class="error">
        <?= htmlspecialchars($_GET['error'] === 'credenciales'
            ? '❌ Usuario o contraseña incorrectos'
            : '⚠️ Error inesperado') ?>
      </div>
    <?php endif; ?>
    <form action="login.php" method="post">
      <label for="username">Usuario</label>
      <input type="text" id="username" name="username"
             placeholder="tu.usuario" required autocomplete="username">
      <label for="password">Contraseña</label>
      <input type="password" id="password" name="password"
             placeholder="••••••••" required autocomplete="current-password">
      <button type="submit">Entrar →</button>
    </form>
    <p class="env-badge">Entorno: <?= htmlspecialchars(getenv('APP_ENV') ?: 'development') ?></p>
  </div>
</body>
</html>

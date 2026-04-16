<?php
require_once 'db.php';
session_start();
 
// Proteger la página — redirigir si no hay sesión
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}
 
// Comprobar expiración de sesión (30 minutos de inactividad)
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > 1800) {
    session_destroy();
    header('Location: index.php?error=sesion_expirada');
    exit;
}
$_SESSION['login_time'] = time(); // Renovar timer
 
// Obtener listado de empleados
try {
    $db = DB::getInstance();
    $stmt = $db->query(
        'SELECT nombre, apellidos, departamento, puesto, salario, fecha_alta
         FROM empleados WHERE activo = 1 ORDER BY departamento, apellidos'
    );
    $empleados = $stmt->fetchAll();
} catch (Exception $e) {
    error_log('Dashboard error: ' . $e->getMessage());
    $empleados = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Dashboard — Portal Empresa</title>
  <style>
    body { font-family:'Segoe UI',sans-serif; background:#f0f4f8; margin:0; }
    header { background:#1F4E79; color:#fff; padding:16px 32px;
             display:flex; justify-content:space-between; align-items:center; }
    header h1 { font-size:20px; }
    .user-info { font-size:13px; }
    .user-info .rol { background:#2E75B6; padding:3px 10px;
                     border-radius:20px; margin-left:10px; }
    main { max-width:1100px; margin:32px auto; padding:0 20px; }
    h2 { color:#1F4E79; margin-bottom:20px; }
    table { width:100%; border-collapse:collapse; background:#fff;
            border-radius:10px; overflow:hidden;
            box-shadow:0 4px 15px rgba(0,0,0,.08); }
    th { background:#1F4E79; color:#fff; padding:14px 16px; text-align:left; font-size:13px; }
    td { padding:12px 16px; border-bottom:1px solid #eee; font-size:13px; }
    tr:hover td { background:#f5f9ff; }
    .dept { display:inline-block; padding:3px 10px; border-radius:20px;
            font-size:11px; font-weight:600; background:#e3effa; color:#1F4E79; }
    .salario { font-weight:600; color:#1E7145; }
    a.logout { background:#c0392b; color:#fff; padding:8px 18px;
               border-radius:6px; text-decoration:none; font-size:13px; }
    a.logout:hover { background:#e74c3c; }
  </style>
</head>
<body>
  <header>
    <h1>🏢 Portal Empresa — Dashboard</h1>
    <div class="user-info">
      👤 <?= htmlspecialchars($_SESSION['username']) ?>
      <span class="rol"><?= htmlspecialchars($_SESSION['rol']) ?></span>
      &nbsp;&nbsp;
      <a href="logout.php" class="logout">Cerrar sesión</a>
    </div>
  </header>
  <main>
    <h2>📋 Directorio de Empleados (<?= count($empleados) ?> registros)</h2>
    <table>
      <thead>
        <tr>
          <th>Nombre</th><th>Departamento</th><th>Puesto</th>
          <th>Salario</th><th>Alta</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($empleados as $emp): ?>
        <tr>
          <td><?= htmlspecialchars($emp['nombre'].' '.$emp['apellidos']) ?></td>
          <td><span class="dept"><?= htmlspecialchars($emp['departamento']) ?></span></td>
          <td><?= htmlspecialchars($emp['puesto']) ?></td>
          <td class="salario"><?= number_format($emp['salario'],2,',','.') ?> €</td>
          <td><?= htmlspecialchars($emp['fecha_alta']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
          </table>
  </main>
</body>
</html>


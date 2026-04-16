-- ═══════════════════════════════════════════════
-- Inicialización de base de datos empresa_db
-- Se ejecuta automáticamente al crear el contenedor
-- ═══════════════════════════════════════════════
 
USE empresa_db;
 
-- ── Tabla de usuarios (login) ─────────────────
CREATE TABLE IF NOT EXISTS usuarios (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  username    VARCHAR(50)  NOT NULL UNIQUE,
  email       VARCHAR(100) NOT NULL UNIQUE,
  password    VARCHAR(255) NOT NULL,   -- bcrypt hash
  rol         ENUM('admin','user','viewer') DEFAULT 'user',
  activo      TINYINT(1) DEFAULT 1,
  creado_en   DATETIME DEFAULT CURRENT_TIMESTAMP,
  ultimo_login DATETIME,
  INDEX idx_username (username),
  INDEX idx_email    (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
 
-- ── Tabla de empleados (dato de negocio) ──────
CREATE TABLE IF NOT EXISTS empleados (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  nombre      VARCHAR(100) NOT NULL,
  apellidos   VARCHAR(150) NOT NULL,
  departamento VARCHAR(80),
  puesto      VARCHAR(100),
  salario     DECIMAL(10,2),
  fecha_alta  DATE,
  email_corp  VARCHAR(120),
  activo      TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
 
-- ── Tabla de sesiones (gestión de sesión server-side) ─
CREATE TABLE IF NOT EXISTS sesiones (
  id          VARCHAR(128) PRIMARY KEY,
  usuario_id  INT NOT NULL,
  ip_origen   VARCHAR(45),
  user_agent  VARCHAR(255),
  creado_en   DATETIME DEFAULT CURRENT_TIMESTAMP,
  expira_en   DATETIME NOT NULL,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;
 
-- ── Datos de prueba ───────────────────────────
-- Contraseña: 'Admin1234!' — hash bcrypt generado con password_hash()
INSERT INTO usuarios (username, email, password, rol) VALUES
  ('admin',  'admin@empresa.local',
   '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
  ('jgarcia', 'jgarcia@empresa.local',
   '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
  ('mlopez',  'mlopez@empresa.local',
   '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'viewer');
 
INSERT INTO empleados (nombre, apellidos, departamento, puesto, salario, fecha_alta, email_corp) VALUES
  ('Juan',   'García Martínez', 'IT',         'SysAdmin Senior',  48000, '2020-03-15', 'jgarcia@empresa.local'),
  ('María',  'López Fernández', 'RRHH',       'Técnica RRHH',     36000, '2021-06-01', 'mlopez@empresa.local'),
  ('Carlos', 'Ruiz Sánchez',   'Desarrollo', 'Dev Backend',       52000, '2019-11-20', 'cruiz@empresa.local'),
  ('Ana',    'Pérez Torres',   'Marketing',  'Responsable SEO',   40000, '2022-01-10', 'aperez@empresa.local'),
  ('Pedro',  'Jiménez Gil',    'IT',         'DevOps Engineer',   58000, '2018-05-30', 'pjimenez@empresa.local');
 
-- ── Permisos del usuario de aplicación ────────
GRANT SELECT, INSERT, UPDATE ON empresa_db.usuarios  TO 'app_user'@'%';
GRANT SELECT                  ON empresa_db.empleados TO 'app_user'@'%';
GRANT SELECT, INSERT, DELETE  ON empresa_db.sesiones  TO 'app_user'@'%';
FLUSH PRIVILEGES;

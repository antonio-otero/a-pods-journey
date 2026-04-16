<?php
/**
 * db.php — Capa de acceso a datos (singleton PDO)
 * Usa variables de entorno para no hardcodear credenciales
 */
 
class DB {
    private static ?PDO $instance = null;
 
    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $host = getenv('DB_HOST') ?: '127.0.0.1';
            $port = getenv('DB_PORT') ?: '3306';
            $name = getenv('DB_NAME') ?: 'empresa_db';
            $user = getenv('DB_USER') ?: 'app_user';
            $pass = getenv('DB_PASS') ?: '';
 
            $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
            ];
            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                // En producción no mostrar detalles del error
                error_log('DB Connection error: ' . $e->getMessage());
                die(json_encode(['error' => 'Error de conexión a la base de datos']));
            }
        }
        return self::$instance;
    }
 
    // Prevenir clonación del singleton
    private function __clone() {}
}

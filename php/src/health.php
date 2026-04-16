<?php
// Endpoint de healthcheck para el contenedor y Kubernetes
require_once 'db.php';
try {
    DB::getInstance()->query('SELECT 1');
    http_response_code(200);
    echo json_encode(['status'=>'ok', 'db'=>'connected', 'ts'=>time()]);
} catch (Exception $e) {
    http_response_code(503);
    echo json_encode(['status'=>'error', 'db'=>'disconnected']);
}

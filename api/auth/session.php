<?php
session_start();
header('Content-Type: application/json');

if (!empty($_SESSION['logado'])) {
    echo json_encode([
        'logado'   => true,
        'nome'     => $_SESSION['nome'],
        'perfil'   => $_SESSION['perfil'],
        'plano_id' => $_SESSION['plano_id'] ?? 1
    ]);
} else {
    echo json_encode(['logado' => false]);
}
?>

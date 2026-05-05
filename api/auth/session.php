<?php
session_start();
header('Content-Type: application/json');

if (!empty($_SESSION['logado'])) {
    echo json_encode([
        'logado' => true,
        'nome'   => $_SESSION['nome'],
        'perfil' => $_SESSION['perfil']
    ]);
} else {
    echo json_encode(['logado' => false]);
}
?>

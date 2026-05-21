<?php
session_start();
header('Content-Type: application/json');

if (empty($_SESSION['logado']) || $_SESSION['perfil'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['ok' => false, 'erro' => 'Acesso negado']);
    exit();
}

require_once '../../model/dao/CategoriaDAO.php';

$categoriaDAO = new CategoriaDAO();
$categorias = $categoriaDAO->listarCategorias();

echo json_encode(['ok' => true, 'categorias' => $categorias]);
?>
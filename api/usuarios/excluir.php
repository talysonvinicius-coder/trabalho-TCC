<?php
session_start();
header('Content-Type: application/json');

if (empty($_SESSION['logado']) || $_SESSION['perfil'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['ok' => false, 'erro' => 'Acesso negado']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'erro' => 'Método inválido']);
    exit();
}

require_once '../../model/dao/UsuarioDAO.php';

$id = (int)($_POST['id'] ?? 0);

if (!$id) {
    echo json_encode(['ok' => false, 'erro' => 'ID inválido']);
    exit();
}

$usuarioDAO = new UsuarioDAO();

if ($usuarioDAO->excluirUsuario($id)) {
    echo json_encode(['ok' => true]);
} else {
    echo json_encode(['ok' => false, 'erro' => 'Erro ao excluir usuário']);
}
?>

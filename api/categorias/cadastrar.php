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

require_once '../../model/dao/CategoriaDAO.php';
require_once '../../model/dto/CategoriaDTO.php';

$nome = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$status = isset($_POST['status']) ? (int)$_POST['status'] : 1;

if (!$nome) {
    echo json_encode(['ok' => false, 'erro' => 'Informe o nome da categoria']);
    exit();
}

if (strlen($nome) > 100) {
    echo json_encode(['ok' => false, 'erro' => 'Nome deve ter até 100 caracteres']);
    exit();
}

$categoriaDTO = new CategoriaDTO();
$categoriaDTO->setNome($nome);
$categoriaDTO->setDescricao($descricao);
$categoriaDTO->setStatus($status);

$categoriaDAO = new CategoriaDAO();
if ($categoriaDAO->cadastrarCategoria($categoriaDTO)) {
    echo json_encode(['ok' => true]);
} else {
    echo json_encode(['ok' => false, 'erro' => 'Erro ao salvar categoria. Verifique se já existe um nome igual.']);
}
?>
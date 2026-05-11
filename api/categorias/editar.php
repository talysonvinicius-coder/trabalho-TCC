<?php
session_start();
header('Content-Type: application/json');

if (empty($_SESSION['logado']) || $_SESSION['perfil'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['ok' => false, 'erro' => 'Acesso negado']);
    exit();
}

require_once '../../model/dao/CategoriaDAO.php';
require_once '../../model/dto/CategoriaDTO.php';

$categoriaDAO = new CategoriaDAO();

// GET: busca dados da categoria para preencher o formulário
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = (int)($_GET['id'] ?? 0);
    if (!$id) {
        echo json_encode(['ok' => false, 'erro' => 'ID inválido']);
        exit();
    }
    $categoria = $categoriaDAO->buscarPorId($id);
    if ($categoria) {
        echo json_encode(['ok' => true, 'categoria' => $categoria]);
    } else {
        echo json_encode(['ok' => false, 'erro' => 'Categoria não encontrada']);
    }
    exit();
}

// POST: salva as alterações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;

    if (!$id || !$nome) {
        echo json_encode(['ok' => false, 'erro' => 'Dados inválidos']);
        exit();
    }

    $categoriaDTO = new CategoriaDTO();
    $categoriaDTO->setNome($nome);
    $categoriaDTO->setDescricao($descricao);
    $categoriaDTO->setStatus($status);

    if ($categoriaDAO->editarCategoria($id, $categoriaDTO)) {
        echo json_encode(['ok' => true]);
    } else {
        echo json_encode(['ok' => false, 'erro' => 'Erro ao salvar alterações']);
    }
    exit();
}

echo json_encode(['ok' => false, 'erro' => 'Método inválido']);
?>
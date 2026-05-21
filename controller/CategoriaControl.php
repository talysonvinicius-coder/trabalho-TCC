<?php
require_once '../model/dto/CategoriaDTO.php';
require_once '../model/dao/CategoriaDAO.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../categoria.php');
    exit();
}

$nome = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$status = isset($_POST['status']) ? (int)$_POST['status'] : 1;

if (!$nome) {
    header('Location: ../categoria.php?erro=' . urlencode('Informe o nome da categoria'));
    exit();
}

if (strlen($nome) > 100) {
    header('Location: ../categoria.php?erro=' . urlencode('Nome deve ter até 100 caracteres'));
    exit();
}

$categoriaDTO = new CategoriaDTO();
$categoriaDTO->setNome($nome);
$categoriaDTO->setDescricao($descricao);
$categoriaDTO->setStatus($status);

$categoriaDAO = new CategoriaDAO();
if ($categoriaDAO->cadastrarCategoria($categoriaDTO)) {
    header('Location: ../categoria.php?sucesso=' . urlencode('Categoria criada com sucesso'));
    exit();
}

header('Location: ../categoria.php?erro=' . urlencode('Erro ao salvar categoria. Verifique se já existe um nome igual.'));
exit();
?>
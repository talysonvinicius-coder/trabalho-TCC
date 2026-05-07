<?php
require_once '../model/dto/CategoriaDTO.php';
require_once '../model/dao/CategoriaDAO.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../categoria.html');
    exit();
}

$nome = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$status = isset($_POST['status']) ? (int)$_POST['status'] : 1;

if (!$nome) {
    header('Location: ../categoria.html?erro=' . urlencode('Informe o nome da categoria'));
    exit();
}

if (strlen($nome) > 100) {
    header('Location: ../categoria.html?erro=' . urlencode('Nome deve ter até 100 caracteres'));
    exit();
}

$categoriaDTO = new CategoriaDTO();
$categoriaDTO->setNome($nome);
$categoriaDTO->setDescricao($descricao);
$categoriaDTO->setStatus($status);

$categoriaDAO = new CategoriaDAO();
if ($categoriaDAO->cadastrarCategoria($categoriaDTO)) {
    header('Location: ../categoria.html?sucesso=' . urlencode('Categoria criada com sucesso'));
    exit();
}

header('Location: ../categoria.html?erro=' . urlencode('Erro ao salvar categoria. Verifique se já existe um nome igual.'));
exit();
?>
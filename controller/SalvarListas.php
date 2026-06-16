<?php
session_start();

if (!isset($_SESSION['plano_id']) || $_SESSION['plano_id'] != 2) {
    die("Acesso negado. Funcionalidade exclusiva para usuários Premium.");
}

require_once '../model/dao/ListasDAO.php';
$listaDAO = new ListasDAO();

$id = $_POST['id'] ?? '';
$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$usuario_id = $_SESSION['id'];

if (empty($id)) {
    $listaDAO->criarLista($usuario_id, $nome, $descricao);
} else {
    $listaDAO->atualizarLista($id, $nome, $descricao, $usuario_id);
}

header("Location: ../biblioteca.php");
exit;
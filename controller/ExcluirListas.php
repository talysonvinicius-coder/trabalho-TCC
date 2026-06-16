<?php
session_start();

if (!isset($_SESSION['plano_id']) || $_SESSION['plano_id'] != 2) {
    die("Acesso negado. Funcionalidade exclusiva para usuários Premium.");
}

require_once '../model/dao/ListasDAO.php';
$listaDAO = new ListasDAO();

$id = $_POST['id'];
$usuario_id = $_SESSION['id'];

$listaDAO->deletarLista($id, $usuario_id);

header("Location: ../biblioteca.php");
exit;
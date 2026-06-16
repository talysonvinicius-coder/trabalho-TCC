<?php
session_start();

if (!isset($_SESSION['plano_id']) || $_SESSION['plano_id'] != 2) {
    die("Acesso negado.");
}

require_once '../model/dao/ListasDAO.php';
$listaDAO = new ListasDAO();

$musica_id = $_POST['musica_id'];
$lista_id = $_POST['lista_id'];

$listaDAO->removerMusica($lista_id, $musica_id);

header("Location: ../verLista.php?id=" . $lista_id);
exit;
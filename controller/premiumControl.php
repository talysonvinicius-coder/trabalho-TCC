<?php
session_start();

if (empty($_SESSION['logado'])) {
    header("Location: ../login.html");
    exit;
}

require_once '../model/dao/Conexao.php';

$pdo = Conexao::getConexao();

$usuario_id = $_SESSION['id'];

$stmt = $pdo->prepare("UPDATE usuario SET plano_id = 2 WHERE id = ?");
$stmt->execute([$usuario_id]);

$_SESSION['plano_id'] = 2;

header("Location: ../biblioteca.php");
exit;
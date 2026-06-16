<?php
session_start();
header('Content-Type: application/json');

if (empty($_SESSION['logado'])) {
    echo json_encode(['ok' => false, 'erro' => 'Não autenticado']);
    exit;
}

$seguido_id = (int) ($_POST['seguido_id'] ?? 0);
$acao       = $_POST['acao'] ?? ''; // 'seguir' ou 'desseguir'

if (!$seguido_id || !in_array($acao, ['seguir', 'desseguir'])) {
    echo json_encode(['ok' => false, 'erro' => 'Dados inválidos']);
    exit;
}

$seguidor_id = (int) $_SESSION['id'];
if ($seguidor_id === $seguido_id) {
    echo json_encode(['ok' => false, 'erro' => 'Você não pode se seguir']);
    exit;
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=bdmusica;charset=utf8mb4', 'root', '');

    if ($acao === 'seguir') {
        $stmt = $pdo->prepare("INSERT IGNORE INTO seguidores (seguidor_id, seguido_id) VALUES (:f, :d)");
        $stmt->execute(['f' => $seguidor_id, 'd' => $seguido_id]);
        echo json_encode(['ok' => true, 'seguindo' => true]);
    } else {
        $stmt = $pdo->prepare("DELETE FROM seguidores WHERE seguidor_id = :f AND seguido_id = :d");
        $stmt->execute(['f' => $seguidor_id, 'd' => $seguido_id]);
        echo json_encode(['ok' => true, 'seguindo' => false]);
    }
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'erro' => 'Erro interno']);
}

<?php
session_start();
header('Content-Type: application/json');

if (empty($_SESSION['logado'])) {
    http_response_code(401);
    exit(json_encode(['ok' => false, 'erro' => 'Não autenticado']));
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=bdmusica;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    exit(json_encode(['ok' => false, 'erro' => 'Erro de conexão']));
}

$avaliacao_id   = $_POST['avaliacao_id'] ?? null;
$motivo         = trim($_POST['motivo'] ?? '');

if (!$avaliacao_id || !$motivo) {
    http_response_code(400);
    exit(json_encode(['ok' => false, 'erro' => 'Parâmetros faltando']));
}

// Verificar se já existe um comentário de sistema para essa avaliação, senão criar
// A tabela denuncias exige comentario_id. Usamos avaliacao_id para localizar/criar o vínculo.
// Buscamos qualquer comentário da avaliação para usar como referência
$stmt = $pdo->prepare("SELECT id FROM comentarios WHERE avaliacao_id = :aid LIMIT 1");
$stmt->execute(['aid' => $avaliacao_id]);
$comentario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$comentario) {
    // Criar comentário de sistema representando a denúncia da avaliação
    $stmt = $pdo->prepare("INSERT INTO comentarios (usuario_id, avaliacao_id, conteudo) VALUES (:uid, :aid, '[Denúncia]')");
    $stmt->execute(['uid' => $_SESSION['id'], 'aid' => $avaliacao_id]);
    $comentario_id = $pdo->lastInsertId();
} else {
    $comentario_id = $comentario['id'];
}

// Verificar se já denunciou
$stmt = $pdo->prepare("SELECT id FROM denuncias WHERE usuario_id = :uid AND comentario_id = :cid");
$stmt->execute(['uid' => $_SESSION['id'], 'cid' => $comentario_id]);
if ($stmt->fetch()) {
    exit(json_encode(['ok' => false, 'erro' => 'Você já denunciou este comentário']));
}

$stmt = $pdo->prepare("
    INSERT INTO denuncias (usuario_id, comentario_id, motivo, status)
    VALUES (:uid, :cid, :motivo, 'Pendente')
");
$stmt->execute(['uid' => $_SESSION['id'], 'cid' => $comentario_id, 'motivo' => $motivo]);

exit(json_encode(['ok' => true, 'msg' => 'Denúncia registrada']));
?>

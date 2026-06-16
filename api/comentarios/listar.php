<?php
session_start();
header('Content-Type: application/json');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=bdmusica;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    exit(json_encode(['ok' => false, 'erro' => 'Erro de conexão']));
}

$avaliacao_id = $_GET['avaliacao_id'] ?? null;
if (!$avaliacao_id || !is_numeric($avaliacao_id)) {
    http_response_code(400);
    exit(json_encode(['ok' => false, 'erro' => 'ID inválido']));
}

$stmt = $pdo->prepare("
    SELECT c.id, c.conteudo, c.data_comentario, u.nome, u.foto
    FROM comentarios c
    JOIN usuario u ON c.usuario_id = u.id
    WHERE c.avaliacao_id = :aid
    ORDER BY c.data_comentario ASC
");
$stmt->execute(['aid' => $avaliacao_id]);
$comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

exit(json_encode(['ok' => true, 'comentarios' => $comentarios]));
?>

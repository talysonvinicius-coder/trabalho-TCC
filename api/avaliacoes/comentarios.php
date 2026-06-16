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

$musica_id = $_GET['musica_id'] ?? null;
if (!$musica_id || !is_numeric($musica_id)) {
    http_response_code(400);
    exit(json_encode(['ok' => false, 'erro' => 'ID inválido']));
}

$stmt = $pdo->prepare("
    SELECT av.nota, av.comentario, av.data_avaliacao, u.nome, u.foto
    FROM avaliacoes av
    JOIN usuario u ON av.usuario_id = u.id
    WHERE av.musica_id = :id AND av.comentario IS NOT NULL AND av.comentario != ''
    ORDER BY av.data_avaliacao DESC
    LIMIT 5
");
$stmt->execute(['id' => $musica_id]);
$comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

exit(json_encode(['ok' => true, 'comentarios' => $comentarios]));
?>

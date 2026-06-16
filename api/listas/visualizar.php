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

$lista_id = $_GET['id'] ?? null;
if (!$lista_id || !is_numeric($lista_id)) {
    http_response_code(400);
    exit(json_encode(['ok' => false, 'erro' => 'ID de lista inválido']));
}

// Buscar informações da lista
$stmt = $pdo->prepare("
    SELECT ls.id, ls.nome, ls.descricao, ls.usuario_id
    FROM listas ls
    WHERE ls.id = :id
    LIMIT 1
");
$stmt->execute(['id' => $lista_id]);
$lista = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$lista) {
    http_response_code(404);
    exit(json_encode(['ok' => false, 'erro' => 'Lista não encontrada']));
}

// Buscar músicas da lista
$stmt = $pdo->prepare("
    SELECT m.id, m.titulo, a.nome AS artista, alb.nome AS album, m.duracao, m.spotify_link
    FROM lista_musicas lm
    JOIN musicas m ON lm.musica_id = m.id
    JOIN artista a ON m.artista_id = a.id
    JOIN album alb ON m.album_id = alb.id
    WHERE lm.lista_id = :lista_id
    ORDER BY lm.id ASC
");
$stmt->execute(['lista_id' => $lista_id]);
$musicas = $stmt->fetchAll(PDO::FETCH_ASSOC);

exit(json_encode([
    'ok' => true,
    'lista' => $lista,
    'musicas' => $musicas
]));
?>

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

$avaliacao_id = $_POST['avaliacao_id'] ?? null;
$conteudo = trim($_POST['conteudo'] ?? '');

if (!$avaliacao_id || !$conteudo) {
    http_response_code(400);
    exit(json_encode(['ok' => false, 'erro' => 'Parâmetros faltando']));
}

$stmt = $pdo->prepare("SELECT id FROM avaliacoes WHERE id = :id");
$stmt->execute(['id' => $avaliacao_id]);
if (!$stmt->fetch()) {
    http_response_code(404);
    exit(json_encode(['ok' => false, 'erro' => 'Avaliação não encontrada']));
}

$stmt = $pdo->prepare("INSERT INTO comentarios (usuario_id, avaliacao_id, conteudo) VALUES (:uid, :aid, :conteudo)");
$stmt->execute(['uid' => $_SESSION['id'], 'aid' => $avaliacao_id, 'conteudo' => $conteudo]);
$comentario_id = $pdo->lastInsertId();

$stmt = $pdo->prepare("SELECT nome, foto FROM usuario WHERE id = :id");
$stmt->execute(['id' => $_SESSION['id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

exit(json_encode([
    'ok' => true,
    'comentario' => [
        'id' => $comentario_id,
        'conteudo' => $conteudo,
        'nome' => $user['nome'],
        'foto' => $user['foto'],
        'data_comentario' => date('Y-m-d H:i:s')
    ]
]));
?>

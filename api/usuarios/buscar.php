<?php
session_start();
header('Content-Type: application/json');

if (empty($_SESSION['logado'])) {
    echo json_encode(['ok' => false, 'erro' => 'Não autenticado']);
    exit;
}

$q = trim($_GET['q'] ?? '');
if (strlen($q) < 2) {
    echo json_encode(['ok' => true, 'usuarios' => []]);
    exit;
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=bdmusica;charset=utf8mb4', 'root', '');
    $uid = (int) $_SESSION['id'];

    $stmt = $pdo->prepare("
        SELECT u.id, u.nome, u.foto, u.bio, pl.nome AS plano,
               COUNT(DISTINCT av.id)  AS total_avaliacoes,
               COUNT(DISTINCT seg.id) AS total_seguidores,
               MAX(CASE WHEN seg2.seguidor_id = :uid THEN 1 ELSE 0 END) AS eu_sigo
        FROM usuario u
        JOIN planos pl ON u.plano_id = pl.id
        LEFT JOIN avaliacoes av  ON av.usuario_id  = u.id
        LEFT JOIN seguidores seg ON seg.seguido_id  = u.id
        LEFT JOIN seguidores seg2 ON seg2.seguido_id = u.id AND seg2.seguidor_id = :uid2
        WHERE u.nome LIKE :q AND u.id != :uid3 AND u.status = 1
        GROUP BY u.id
        LIMIT 10
    ");
    $stmt->execute(['q' => '%' . $q . '%', 'uid' => $uid, 'uid2' => $uid, 'uid3' => $uid]);
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['ok' => true, 'usuarios' => $usuarios]);
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'erro' => 'Erro interno']);
}

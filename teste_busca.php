<?php
session_start();

// Simular uma sessão
if (empty($_SESSION['logado'])) {
    $_SESSION['logado'] = true;
    $_SESSION['id'] = 1;
}

$pdo = new PDO('mysql:host=localhost;dbname=bdmusica;charset=utf8mb4', 'root', '');
$uid = 1;
$q = 'maria';

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

echo "<pre>";
print_r($usuarios);
echo "</pre>";

// Verificar também para que valor de usuario temos
$stmt = $pdo->prepare("SELECT COUNT(*) FROM usuario WHERE nome LIKE ?");
$stmt->execute(['%' . $q . '%']);
echo "Total de usuários com 'maria' no nome: " . $stmt->fetchColumn() . "<br>";

// Listar usuários com maria no nome
$stmt = $pdo->prepare("SELECT id, nome, status FROM usuario WHERE nome LIKE ? LIMIT 10");
$stmt->execute(['%' . $q . '%']);
$usuarios_raw = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Usuários com 'maria' no nome:<br>";
echo "<pre>";
print_r($usuarios_raw);
echo "</pre>";

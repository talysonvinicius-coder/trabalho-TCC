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

$user_id = $_GET['id'] ?? null;
if (!$user_id || !is_numeric($user_id)) {
    http_response_code(400);
    exit(json_encode(['ok' => false, 'erro' => 'ID de usuário inválido']));
}

// Busca informações do usuário
$stmt = $pdo->prepare("
    SELECT u.id, u.nome, u.bio, u.foto, u.data_criacao, pl.nome AS plano, p.nome AS perfil, l.email
    FROM usuario u
    JOIN perfil p  ON u.perfil_id = p.id
    JOIN planos pl ON u.plano_id  = pl.id
    LEFT JOIN login l ON l.usuario_id = u.id
    WHERE u.id = :id
    LIMIT 1
");
$stmt->execute(['id' => $user_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    http_response_code(404);
    exit(json_encode(['ok' => false, 'erro' => 'Usuário não encontrado']));
}

// Contadores
$stmt = $pdo->prepare("SELECT COUNT(*) FROM seguidores WHERE seguido_id = :id");
$stmt->execute(['id' => $user_id]);
$total_seguidores = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM seguidores WHERE seguidor_id = :id");
$stmt->execute(['id' => $user_id]);
$total_seguindo = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM avaliacoes WHERE usuario_id = :id");
$stmt->execute(['id' => $user_id]);
$total_avaliacoes = $stmt->fetchColumn();

// Avaliações com comentário
$stmt = $pdo->prepare("
    SELECT av.id, av.nota, av.comentario, av.data_avaliacao,
           m.titulo, a.nome AS artista
    FROM avaliacoes av
    JOIN musicas m ON av.musica_id = m.id
    JOIN artista a ON m.artista_id = a.id
    WHERE av.usuario_id = :id AND av.comentario IS NOT NULL AND av.comentario != ''
    ORDER BY av.data_avaliacao DESC
    LIMIT 6
");
$stmt->execute(['id' => $user_id]);
$avaliacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Listas do usuário
$stmt = $pdo->prepare("
    SELECT ls.id, ls.nome, ls.descricao,
           COUNT(lm.musica_id) AS total_musicas
    FROM listas ls
    LEFT JOIN lista_musicas lm ON lm.lista_id = ls.id
    WHERE ls.usuario_id = :id
    GROUP BY ls.id
    ORDER BY ls.data_criacao DESC
    LIMIT 6
");
$stmt->execute(['id' => $user_id]);
$listas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verifica se o usuário logado segue este usuário
$eu_sigo = false;
if (!empty($_SESSION['id'])) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM seguidores WHERE seguidor_id = :seguidor AND seguido_id = :seguido");
    $stmt->execute(['seguidor' => $_SESSION['id'], 'seguido' => $user_id]);
    $eu_sigo = $stmt->fetchColumn() > 0;
}

exit(json_encode([
    'ok' => true,
    'usuario' => $usuario,
    'total_seguidores' => $total_seguidores,
    'total_seguindo' => $total_seguindo,
    'total_avaliacoes' => $total_avaliacoes,
    'avaliacoes' => $avaliacoes,
    'listas' => $listas,
    'eu_sigo' => $eu_sigo
]));
?>

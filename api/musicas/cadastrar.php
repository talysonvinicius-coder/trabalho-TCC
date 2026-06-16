<?php
session_start();
header('Content-Type: application/json');

if (empty($_SESSION['logado']) || $_SESSION['perfil'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['ok' => false, 'erro' => 'Acesso negado']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'erro' => 'Método inválido']);
    exit();
}

$titulo          = trim($_POST['titulo'] ?? '');
$artista         = trim($_POST['artista'] ?? '');
$album           = trim($_POST['album'] ?? '') ?: null;
$genero_id       = (int)($_POST['genero_id'] ?? 0) ?: null;
$data_lancamento = $_POST['data_lancamento'] ?? '' ?: null;
$duracao         = $_POST['duracao'] ?? '' ?: null;
$spotify_link    = trim($_POST['youtube_link'] ?? '');

if (!$titulo) {
    echo json_encode(['ok' => false, 'erro' => 'Informe o título da música']);
    exit();
}

if (!$artista) {
    echo json_encode(['ok' => false, 'erro' => 'Informe o artista']);
    exit();
}

if (!$spotify_link) {
    echo json_encode(['ok' => false, 'erro' => 'Informe o link do YouTube']);
    exit();
}

require_once __DIR__ . '/../../model/dao/Conexao.php';

try {
    $pdo = Conexao::getConexao();

    // Buscar ou criar artista
    $stmt = $pdo->prepare("SELECT id FROM artista WHERE nome = ?");
    $stmt->execute([$artista]);
    $artistaRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($artistaRow) {
        $artista_id = $artistaRow['id'];
    } else {
        $pdo->prepare("INSERT INTO artista (nome) VALUES (?)")->execute([$artista]);
        $artista_id = $pdo->lastInsertId();
    }

    // Buscar ou criar álbum (se informado)
    $album_id = null;
    if ($album) {
        $stmt = $pdo->prepare("SELECT id FROM album WHERE nome = ? AND artista_id = ?");
        $stmt->execute([$album, $artista_id]);
        $albumRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($albumRow) {
            $album_id = $albumRow['id'];
        } else {
            $pdo->prepare("INSERT INTO album (nome, artista_id, genero_id, data_lancamento) VALUES (?, ?, ?, ?)")
                ->execute([$album, $artista_id, $genero_id, $data_lancamento]);
            $album_id = $pdo->lastInsertId();
        }
    }

    // Inserir música
    $sql = "INSERT INTO musicas (titulo, artista_id, album_id, genero_id, data_lancamento, duracao, spotify_link)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $pdo->prepare($sql)->execute([
        $titulo,
        $artista_id,
        $album_id,
        $genero_id,
        $data_lancamento,
        $duracao,
        $spotify_link
    ]);

    echo json_encode(['ok' => true, 'id' => $pdo->lastInsertId()]);

} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'erro' => 'Erro ao cadastrar música: ' . $e->getMessage()]);
}
?>

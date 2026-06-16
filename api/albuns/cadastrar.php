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

$nome            = trim($_POST['nome'] ?? '');
$artista         = trim($_POST['artista'] ?? '');
$genero_id       = (int)($_POST['genero_id'] ?? 0) ?: null;
$data_lancamento = $_POST['data_lancamento'] ?? '' ?: null;
$status          = isset($_POST['status']) ? (int)$_POST['status'] : 1;

if (!$nome) {
    echo json_encode(['ok' => false, 'erro' => 'Informe o nome do álbum']);
    exit();
}

if (!$artista) {
    echo json_encode(['ok' => false, 'erro' => 'Informe o artista']);
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

    // Verificar se álbum já existe para o mesmo artista
    $stmt = $pdo->prepare("SELECT id FROM album WHERE nome = ? AND artista_id = ?");
    $stmt->execute([$nome, $artista_id]);
    if ($stmt->fetch()) {
        echo json_encode(['ok' => false, 'erro' => 'Este álbum já está cadastrado para esse artista']);
        exit();
    }

    // Inserir álbum
    $pdo->prepare("INSERT INTO album (nome, artista_id, genero_id, data_lancamento, status) VALUES (?, ?, ?, ?, ?)")
        ->execute([$nome, $artista_id, $genero_id, $data_lancamento, $status]);

    echo json_encode(['ok' => true, 'id' => $pdo->lastInsertId()]);

} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'erro' => 'Erro ao cadastrar álbum: ' . $e->getMessage()]);
}
?>

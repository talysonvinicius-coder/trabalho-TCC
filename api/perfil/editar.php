<?php
session_start();
header('Content-Type: application/json');

if (empty($_SESSION['logado'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'erro' => 'Não autenticado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'erro' => 'Método inválido']);
    exit;
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=bdmusica;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'erro' => 'Erro de conexão']);
    exit;
}

// Garante que a coluna foto existe na tabela usuario
$pdo->exec("ALTER TABLE usuario ADD COLUMN IF NOT EXISTS foto VARCHAR(255) NULL DEFAULT NULL");

// Busca o usuário logado pelo nome da sessão
$stmt = $pdo->prepare("SELECT id, foto FROM usuario WHERE nome = :nome LIMIT 1");
$stmt->execute(['nome' => $_SESSION['nome']]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo json_encode(['ok' => false, 'erro' => 'Usuário não encontrado']);
    exit;
}

$uid      = $usuario['id'];
$nome     = trim($_POST['nome'] ?? '');
$bio      = trim($_POST['bio']  ?? '');
$fotoPath = $usuario['foto']; // mantém a atual por padrão

if (!$nome) {
    echo json_encode(['ok' => false, 'erro' => 'Nome obrigatório']);
    exit;
}

// Upload da foto
if (!empty($_FILES['foto']['tmp_name'])) {
    $file    = $_FILES['foto'];
    $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    if (!in_array($ext, $allowed)) {
        echo json_encode(['ok' => false, 'erro' => 'Formato inválido. Use JPG, PNG, WEBP ou GIF.']);
        exit;
    }
    if ($file['size'] > 3 * 1024 * 1024) {
        echo json_encode(['ok' => false, 'erro' => 'Imagem muito grande. Máximo 3MB.']);
        exit;
    }

    $uploadDir = __DIR__ . '/../../assets/uploads/';
    $novoNome  = 'avatar_' . $uid . '_' . time() . '.' . $ext;
    $destino   = $uploadDir . $novoNome;

    if (!move_uploaded_file($file['tmp_name'], $destino)) {
        echo json_encode(['ok' => false, 'erro' => 'Falha ao salvar imagem']);
        exit;
    }

    // Remove foto antiga se existir
    if ($usuario['foto'] && file_exists($uploadDir . basename($usuario['foto']))) {
        unlink($uploadDir . basename($usuario['foto']));
    }

    $fotoPath = 'assets/uploads/' . $novoNome;
}

// Salva no banco
$stmt = $pdo->prepare("UPDATE usuario SET nome = :nome, bio = :bio, foto = :foto WHERE id = :id");
$stmt->execute(['nome' => $nome, 'bio' => $bio ?: null, 'foto' => $fotoPath, 'id' => $uid]);

// Atualiza a sessão com o novo nome
$_SESSION['nome'] = $nome;

echo json_encode([
    'ok'   => true,
    'nome' => $nome,
    'bio'  => $bio,
    'foto' => $fotoPath
]);

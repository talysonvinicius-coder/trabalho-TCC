<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['ok' => false, 'erro' => 'Usuário não autenticado.']);
    exit;
}

require_once '../../model/dao/Conexao.php';
$pdo = Conexao::getConexao();

$musica_id  = $_POST['musica_id'] ?? null;
$nota       = $_POST['nota'] ?? null;
$comentario = trim($_POST['comentario'] ?? '');

if (!$musica_id || !$nota) {
    echo json_encode(['ok' => false, 'erro' => 'Dados incompletos.']);
    exit;
}

try {
    $usuario_id = $_SESSION['id'];

    // Check if review already exists
    $stmt = $pdo->prepare("SELECT id FROM avaliacoes WHERE usuario_id = ? AND musica_id = ?");
    $stmt->execute([$usuario_id, $musica_id]);
    $existe = $stmt->fetch();

    if ($existe) {
        // Update
        $stmt_up = $pdo->prepare("UPDATE avaliacoes SET nota = ?, comentario = ?, data_avaliacao = NOW() WHERE id = ?");
        $stmt_up->execute([$nota, $comentario, $existe['id']]);
    } else {
        // Insert
        $stmt_in = $pdo->prepare("INSERT INTO avaliacoes (usuario_id, musica_id, nota, comentario) VALUES (?, ?, ?, ?)");
        $stmt_in->execute([$usuario_id, $musica_id, $nota, $comentario]);
    }

    echo json_encode(['ok' => true]);

} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'erro' => 'Erro no banco de dados.']);
}

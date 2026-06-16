<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'erro' => 'Método inválido']);
    exit();
}

$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);

if (!$email) {
    echo json_encode(['ok' => false, 'erro' => 'E-mail inválido']);
    exit();
}

require_once __DIR__ . '/../../model/dao/Conexao.php';

try {
    $pdo  = Conexao::getConexao();
    $stmt = $pdo->prepare("SELECT id FROM login WHERE email = ? AND ativo = 1");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        echo json_encode(['ok' => true]);
    } else {
        echo json_encode(['ok' => false, 'erro' => 'Nenhuma conta ativa encontrada com esse e-mail']);
    }
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'erro' => 'Erro interno. Tente novamente.']);
}
?>

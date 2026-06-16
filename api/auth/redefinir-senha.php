<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'erro' => 'Método inválido']);
    exit();
}

$email     = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$nova_senha = $_POST['nova_senha'] ?? '';

if (!$email) {
    echo json_encode(['ok' => false, 'erro' => 'E-mail inválido']);
    exit();
}

if (strlen($nova_senha) < 5) {
    echo json_encode(['ok' => false, 'erro' => 'Senha deve ter ao menos 5 caracteres']);
    exit();
}

require_once __DIR__ . '/../../model/dao/Conexao.php';

try {
    $pdo  = Conexao::getConexao();

    // Confirma que o e-mail ainda existe e está ativo
    $stmt = $pdo->prepare("SELECT id FROM login WHERE email = ? AND ativo = 1");
    $stmt->execute([$email]);

    if (!$stmt->fetch()) {
        echo json_encode(['ok' => false, 'erro' => 'E-mail não encontrado']);
        exit();
    }

    $hash = md5($nova_senha);
    $pdo->prepare("UPDATE login SET senha = ? WHERE email = ?")
        ->execute([$hash, $email]);

    echo json_encode(['ok' => true]);
} catch (PDOException $e) {
    echo json_encode(['ok' => false, 'erro' => 'Erro interno. Tente novamente.']);
}
?>

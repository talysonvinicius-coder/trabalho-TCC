<?php
session_start();
header('Content-Type: application/json');

if (empty($_SESSION['logado']) || $_SESSION['perfil'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Acesso negado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Método não permitido']);
    exit;
}

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['denuncia_id'])) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'ID da denúncia não fornecido']);
        exit;
    }
    
    require_once __DIR__ . '/../../model/dao/Conexao.php';
    $pdo = Conexao::getConexao();
    
    // Atualizar status da denúncia para 'Rejeitada'
    $stmt = $pdo->prepare("UPDATE denuncias SET status = 'Rejeitada' WHERE id = ?");
    $stmt->execute([$data['denuncia_id']]);
    
    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Denúncia rejeitada com sucesso'
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao rejeitar denúncia: ' . $e->getMessage()
    ]);
}
?>

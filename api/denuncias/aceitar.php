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
    
    // Iniciar transação
    $pdo->beginTransaction();
    
    // Buscar e salvar o conteúdo do comentário antes de deletar
    $stmt = $pdo->prepare("
        SELECT d.comentario_id, c.conteudo, c.usuario_id
        FROM denuncias d
        LEFT JOIN comentarios c ON d.comentario_id = c.id
        WHERE d.id = ?
    ");
    $stmt->execute([$data['denuncia_id']]);
    $denuncia = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$denuncia) {
        $pdo->rollBack();
        echo json_encode(['sucesso' => false, 'mensagem' => 'Denúncia não encontrada']);
        exit;
    }
    
    // Salvar conteúdo na denúncia antes de deletar
    if ($denuncia['conteudo']) {
        $stmt = $pdo->prepare("UPDATE denuncias SET conteudo_comentario = ? WHERE id = ?");
        $stmt->execute([$denuncia['conteudo'], $data['denuncia_id']]);
    }
    
    // Deletar o comentário denunciado
    $stmt = $pdo->prepare("DELETE FROM comentarios WHERE id = ?");
    $stmt->execute([$denuncia['comentario_id']]);
    
    // Atualizar status da denúncia para 'Resolvida'
    $stmt = $pdo->prepare("UPDATE denuncias SET status = 'Resolvida' WHERE id = ?");
    $stmt->execute([$data['denuncia_id']]);
    
    $pdo->commit();
    
    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Denúncia aceita e comentário removido com sucesso'
    ]);
    
} catch (Exception $e) {
    if (isset($pdo)) {
        $pdo->rollBack();
    }
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao processar denúncia: ' . $e->getMessage()
    ]);
}
?>

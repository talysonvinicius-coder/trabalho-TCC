<?php
session_start();
header('Content-Type: application/json');

if (empty($_SESSION['logado']) || $_SESSION['perfil'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['ok' => false, 'erro' => 'Acesso negado']);
    exit();
}

require_once '../../model/dao/UsuarioDAO.php';
require_once '../../model/dto/UsuarioDTO.php';

$usuarioDAO = new UsuarioDAO();

// GET: busca dados do usuário para preencher o formulário
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = (int)($_GET['id'] ?? 0);
    if (!$id) {
        echo json_encode(['ok' => false, 'erro' => 'ID inválido']);
        exit();
    }
    $usuario = $usuarioDAO->buscarPorId($id);
    if ($usuario) {
        echo json_encode(['ok' => true, 'usuario' => $usuario]);
    } else {
        echo json_encode(['ok' => false, 'erro' => 'Usuário não encontrado']);
    }
    exit();
}

// POST: salva as alterações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = (int)($_POST['id'] ?? 0);
    $nome  = trim($_POST['nome'] ?? '');
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'] ?? '';

    if (!$id || !$nome || !$email) {
        echo json_encode(['ok' => false, 'erro' => 'Dados inválidos']);
        exit();
    }

    $usuarioDTO = new UsuarioDTO();
    $usuarioDTO->setNome($nome);
    $usuarioDTO->setEmail($email);
    $usuarioDTO->setSenha($senha);

    if ($usuarioDAO->editarUsuario($id, $usuarioDTO)) {
        echo json_encode(['ok' => true]);
    } else {
        echo json_encode(['ok' => false, 'erro' => 'Erro ao salvar alterações']);
    }
    exit();
}

echo json_encode(['ok' => false, 'erro' => 'Método inválido']);
?>

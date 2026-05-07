<?php
session_start();
header('Content-Type: application/json');

require_once '../../model/dao/UsuarioDAO.php';
require_once '../../model/dto/UsuarioDTO.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'erro' => 'Método inválido']);
    exit();
}

$nome     = trim($_POST['nome'] ?? '');
$email    = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$senha    = $_POST['senha'] ?? '';
$perfilId = (int)($_POST['perfil_id'] ?? 1);
$planoId  = (int)($_POST['plano_id'] ?? 1);

if (!$nome || !$email || !$senha) {
    echo json_encode(['ok' => false, 'erro' => 'Preencha todos os campos']);
    exit();
}

if (strlen($nome) < 3) {
    echo json_encode(['ok' => false, 'erro' => 'Nome deve ter ao menos 3 caracteres']);
    exit();
}

if (strlen($senha) < 5) {
    echo json_encode(['ok' => false, 'erro' => 'Senha deve ter ao menos 5 caracteres']);
    exit();
}

$usuarioDTO = new UsuarioDTO();
$usuarioDTO->setNome($nome);
$usuarioDTO->setEmail($email);
$usuarioDTO->setSenha($senha);
$usuarioDTO->setPerfilId($perfilId);
$usuarioDTO->setPlanoId($planoId);

$usuarioDAO = new UsuarioDAO();

if ($usuarioDAO->cadastrarUsuario($usuarioDTO)) {
    echo json_encode(['ok' => true]);
} else {
    echo json_encode(['ok' => false, 'erro' => 'E-mail já cadastrado ou erro interno']);
}
?>

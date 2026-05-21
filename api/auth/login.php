<?php
session_start();
header('Content-Type: application/json');

require_once '../../model/dao/UsuarioDAO.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'erro' => 'Método inválido']);
    exit();
}

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$senha = $_POST['senha'] ?? '';

if (!$email || empty($senha)) {
    echo json_encode(['ok' => false, 'erro' => 'Preencha todos os campos']);
    exit();
}

$usuarioDAO = new UsuarioDAO();
$usuario = $usuarioDAO->buscarParaLogin($email);

if (!$usuario || md5($senha) !== $usuario['senha']) {
    echo json_encode(['ok' => false, 'erro' => 'E-mail ou senha inválidos']);
    exit();
}

if (!$usuario['ativo'] || !$usuario['status']) {
    echo json_encode(['ok' => false, 'erro' => 'Usuário desativado']);
    exit();
}

session_regenerate_id(true);
$_SESSION['logado']  = true;
$_SESSION['id']      = $usuario['id'];
$_SESSION['nome']    = $usuario['nome'];
$_SESSION['perfil']  = $usuario['perfil'];

echo json_encode(['ok' => true, 'perfil' => $usuario['perfil']]);
?>

<?php
require_once '../model/dto/UsuarioDTO.php';
require_once '../model/dao/UsuarioDAO.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../cadastro.php');
    exit();
}

$nome     = trim($_POST['nome'] ?? '');
$email    = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$senha    = $_POST['senha'] ?? '';
$confirma = $_POST['confirma'] ?? '';
$perfilId = (int)($_POST['perfil_id'] ?? 1);
$planoId  = (int)($_POST['plano_id'] ?? 1);

if (!$nome || !$email || !$senha || !$confirma) {
    header('Location: ../cadastro.php?erro=' . urlencode('Preencha todos os campos'));
    exit();
}

if (strlen($nome) < 3) {
    header('Location: ../cadastro.php?erro=' . urlencode('Nome deve ter ao menos 3 caracteres'));
    exit();
}

if (strlen($senha) < 5) {
    header('Location: ../cadastro.php?erro=' . urlencode('Senha deve ter ao menos 5 caracteres'));
    exit();
}

if ($senha !== $confirma) {
    header('Location: ../cadastro.php?erro=' . urlencode('As senhas não coincidem'));
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
    header('Location: ../login.html?cadastro=1');
} else {
    header('Location: ../cadastro.php?erro=' . urlencode('E-mail já cadastrado ou erro interno'));
}
exit();
?>

<?php
session_start();
require_once '../Model/dao/UsuarioDAO.php';

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $acao    = $_POST['acao']     ?? '';
    $email = $_POST['usuario'] ?? '';
    $nome    = $_POST['nome']    ?? '';
    $senha   = $_POST['senha']   ?? '';

    $usuarioDAO = new UsuarioDAO();

    // --- LOGIN ---
    if ($acao === 'login') {

        // Busca o usuário pelo email
        $usuarioDados = $usuarioDAO->buscarParaLogin($email);

        // Valida se o usuário existe e a senha confere
        if ($usuarioDados && md5($senha) === $usuarioDados['senha']) {
            if (!$usuarioDados['ativo']) {
                header('Location: ../index.php?erro=3');
                exit();
            }
            $_SESSION['logado']  = true;
            $_SESSION['usuario'] = $usuarioDados['nome'];
            $_SESSION['perfil']  = $usuarioDados['perfil'];
            $destino = $usuarioDados['perfil'] === 'admin' ? './view/homeAdmin.php' : './view/homeCliente.php';
            header('Location: ' . $destino);
            exit();
        } else {
            header('Location: ../index.php?erro=1');
            exit();
        }
    }

    // --- CADASTRO ---
    if ($acao === 'cadastro') {

        $usuarioDTO = new UsuarioDTO();
        $usuarioDTO->setNome($nome);
        $usuarioDTO->setEmail($email);
        $usuarioDTO->setSenha($senha);
        $perfil = $_POST['perfil'] ?? 'cliente';
        $usuarioDTO->setPerfilId($perfil === 'admin' ? 2 : 1);

        $resultado = $usuarioDAO->cadastrarUsuario($usuarioDTO);

        if ($resultado) {
            header('Location: ../index.php?cadastro=1');
            exit();
        } else {
            header('Location: ../index.php?erro=2');
            exit();
        }
    }
}
?>

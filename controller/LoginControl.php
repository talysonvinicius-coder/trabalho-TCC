<?php
session_start();
require_once '../model/dao/UsuarioDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $acao = $_POST['acao'] ?? '';
    $email = filter_input(INPUT_POST, 'usuario', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'] ?? '';

    $usuarioDAO = new UsuarioDAO();

    // LOGIN
    if ($acao === 'login') {

        if (!$email || empty($senha)) {
            header('Location: ../index.php?erro=4');
            exit();
        }

        $usuarioDados = $usuarioDAO->buscarParaLogin($email);

        if ($usuarioDados && md5($senha) === $usuarioDados['senha']) {

            // Verifica status (desativado pelo admin) e ativo (login bloqueado)
            if (!$usuarioDados['ativo'] || !$usuarioDados['status']) {
                header('Location: ../index.php?erro=3');
                exit();
            }

            session_regenerate_id(true);

            $_SESSION['logado']  = true;
            $_SESSION['id']      = $usuarioDados['id'];
            $_SESSION['nome']    = $usuarioDados['nome'];
            $_SESSION['perfil']  = $usuarioDados['perfil'];
            $_SESSION['plano_id']= $usuarioDados['plano_id'];

            $destino = $usuarioDados['perfil'] === 'admin'
                ? '../homeAdmin.php'
                : '../paginicial.php';

            header('Location: ' . $destino);
            exit();

        } else {
            header('Location: ../index.php?erro=1');
            exit();
        }
    }

    // CADASTRO
    if ($acao === 'cadastro') {

        require_once '../model/dto/UsuarioDTO.php';

        $nome  = $_POST['nome'] ?? '';
        $email = filter_input(INPUT_POST, 'usuario', FILTER_VALIDATE_EMAIL);
        $senha = $_POST['senha'] ?? '';

        if (!$nome || !$email || !$senha) {
            header('Location: ../index.php?erro=4');
            exit();
        }

        $usuarioDTO = new UsuarioDTO();
        $usuarioDTO->setNome($nome);
        $usuarioDTO->setEmail($email);
        $usuarioDTO->setSenha($senha);

        $perfil = $_POST['perfil'] ?? 'cliente';
        $usuarioDTO->setPerfilId($perfil === 'admin' ? 2 : 1);

        if ($usuarioDAO->cadastrarUsuario($usuarioDTO)) {
            header('Location: ../index.php?cadastro=1');
        } else {
            header('Location: ../index.php?erro=2');
        }
        exit();
    }
}
?>
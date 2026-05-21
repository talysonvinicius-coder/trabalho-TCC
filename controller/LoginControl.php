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

        // DEBUG - Salva em arquivo de log
        $debug = "Email enviado: " . $email . "\n";
        $debug .= "Senha enviada: " . $senha . "\n";
        $debug .= "MD5 da senha: " . md5($senha) . "\n";
        $debug .= "Dados do banco:\n";
        $debug .= print_r($usuarioDados, true) . "\n";
        $debug .= "Comparação: md5(senha) === banco_senha? " . (($usuarioDados && md5($senha) === $usuarioDados['senha']) ? 'SIM' : 'NÃO') . "\n";
        
        file_put_contents('../debug_login.txt', $debug);
        echo "<pre>" . htmlspecialchars($debug) . "</pre>";

        if ($usuarioDados && md5($senha) === $usuarioDados['senha']) {

            // Verifica status
            if (!$usuarioDados['ativo']) {
                header('Location: ../index.php?erro=3');
                exit();
            }

            session_regenerate_id(true);

            $_SESSION['logado']  = true;
            $_SESSION['usuario'] = $usuarioDados['nome'];
            $_SESSION['perfil']  = $usuarioDados['perfil'];

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
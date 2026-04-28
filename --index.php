<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SoundScore | Login</title>
    <link rel="stylesheet" href="assets/css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="main-wrapper">
    <h1 class="brand-name">SoundScore</h1>

    <div class="login-card">
        <h2>Fazer Login</h2>

        <!-- Mensagens -->
        <?php if (isset($_GET['erro'])): ?>
            <div class="alert alert-danger">
                <?php
                switch ($_GET['erro']) {
                    case 1: echo "Email ou senha inválidos"; break;
                    case 3: echo "Usuário desativado"; break;
                    case 4: echo "Preencha todos os campos"; break;
                }
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['cadastro'])): ?>
            <div class="alert alert-success">
                Cadastro realizado com sucesso!
            </div>
        <?php endif; ?>

        <form action="controller/LoginControl.php" method="POST">
            <input type="email" name="usuario" placeholder="Email" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <input type="hidden" name="acao" value="login">

            <button type="submit">Entrar</button>
        </form>

        <p>Não tem conta? <a href="cadastro.php">Registre-se</a></p>
    </div>
</div>

</body>
</html>
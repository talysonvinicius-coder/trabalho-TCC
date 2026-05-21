<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundScore | Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Seu CSS -->
    <link rel="stylesheet" href="assets/css/login.css">
</head>

<body>

<div class="main-wrapper">
    <h1 class="brand-name">SoundScore</h1>

    <div class="login-card">

        <h2 style="font-size: 20px; opacity: 0.5; letter-spacing: 4px; margin-bottom: 25px;">
            Fazer Login
        </h2>

        <!-- ERROS -->
        <?php if (isset($_GET['erro'])): ?>
            <div class="alert alert-danger" style="padding: 8px; font-size: 14px;">
                <?php
                switch ($_GET['erro']) {
                    case 1: echo "Usuário ou senha inválidos"; break;
                    case 3: echo "Usuário desativado"; break;
                    case 4: echo "Preencha todos os campos"; break;
                }
                ?>
            </div>
        <?php endif; ?>

        <!-- SUCESSO CADASTRO -->
        <?php if (isset($_GET['cadastro'])): ?>
            <div class="alert alert-success" style="padding: 8px; font-size: 14px;">
                Cadastro realizado com sucesso!
            </div>
        <?php endif; ?>

        <form action="controller/LoginControl.php" method="POST" autocomplete="on">
            <!-- Mantive o nome "usuario" porque seu backend usa -->
            <input type="email" name="usuario" placeholder="Insira seu email" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <input type="hidden" name="acao" value="login">
            <button type="submit">Entrar</button><br>
        </form>

        <p>Não tem conta? <a href="cadastro.php">Registre-se</a></p>
    </div>

    <!-- Ondas Neon (inalterado) -->
    <div class="waves-box">
        <svg class="waves-svg" viewBox="0 24 150 28" preserveAspectRatio="none">
            <defs>
                <path id="gentle-wave"
                    d="M-160 44c30 0 58-18 88-18s58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
            </defs>
            <g class="parallax">
                <use href="#gentle-wave" x="48" y="0" class="onda-ciano" />
                <use href="#gentle-wave" x="48" y="3" class="onda-roxa" />
                <use href="#gentle-wave" x="48" y="5" class="onda-rosa" />
                <use href="#gentle-wave" x="48" y="7" class="onda-branca" />
                <use href="#gentle-wave" x="48" y="9" class="onda-verde" />
            </g>
        </svg>
    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
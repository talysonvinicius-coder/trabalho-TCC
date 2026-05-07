<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundScore | Cadastro</title>
    <link rel="stylesheet" href="assets/css/cadastrarmusic.css">
</head>
<body>

<div class="container">
    <div class="title">
        <h1>Vem avaliar com a gente!</h1>
    </div>

    <div class="register-form">
        <form id="form-cadastro" action="controller/CadastroUsuarioControl.php" method="post" autocomplete="on">
            <h2>Dados pessoais</h2>

            <?php if (!empty($_GET['erro'])): ?>
                <div class="msg-erro"><?= htmlspecialchars($_GET['erro']) ?></div>
            <?php endif; ?>

            <p>
                <label for="inome">Nome:</label>
                <input type="text" name="nome" id="inome" placeholder="Nome Completo" minlength="3" maxlength="100" required>
            </p>

            <p>
                <label for="iemail">Email:</label>
                <input type="email" name="email" id="iemail" placeholder="seuemail@exemplo.com" required>
            </p>

            <p>
                <label for="isenha">Crie uma senha:</label>
                <input type="password" name="senha" id="isenha" placeholder="Mínimo 5 caracteres" minlength="5" required>
            </p>

            <p>
                <label for="iconfirma">Confirme a senha:</label>
                <input type="password" name="confirma" id="iconfirma" placeholder="Repita a senha" minlength="5" required>
            </p>

            <p>
                <label for="iperfil">Perfil:</label>
                <select name="perfil_id" id="iperfil" required>
                    <option value="1">Usuário</option>
                    <option value="2">Admin</option>
                </select>
            </p>

            <p>
                <label for="iplano">Plano:</label>
                <select name="plano_id" id="iplano" required>
                    <option value="1">Free</option>
                    <option value="2">Premium</option>
                </select>
            </p>

            <button type="submit">Cadastrar</button>
        </form>

        <p style="margin-top: 16px; text-align: center;">
            Já tem conta? <a href="index.html">Fazer login</a>
        </p>
    </div>
</div>

</body>
</html>

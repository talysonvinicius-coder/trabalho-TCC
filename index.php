<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" 
integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</head>
<body>


<!DOCTYPE html>
<html lang="pt-br">
<head>
   <!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundScore | Login</title>
   <link rel="stylesheet" href="assets/css/login.css">

</head>
<body>

    <div class="main-wrapper">
        <h1 class="brand-name">SoundScore</h1>

        <div class="login-card">
            <h2 style="font-size: 20px; opacity: 0.5; letter-spacing: 4px; margin-bottom: 25px;">Fazer Login</h2>
           <form action="controller/LoginControl.php" method="POST" autocomplete="on">
            <input type="text" name="usuario" placeholder="Usuário">
            <input type="password" name="senha" placeholder="Senha">
            <input type="hidden" name="acao" value="login">
            <button>Entrar</button> <br>
            </form>
            <p> Não tem conta? <a href="cadastro.php">Registre-se </a></p>
        </div>

        <!-- Ondas Neon Centralizadas -->
        <div class="waves-box">
            <svg class="waves-svg" viewBox="0 24 150 28" preserveAspectRatio="none">
                <defs>
                    <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
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

</body>
</html>

</style>



</body>
</html>
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
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: 'Segoe UI', sans-serif;
            background-color: #05070a; /* Fundo mais escuro para o neon brilhar */
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Container que centraliza tudo (Título, Ondas e Card) */
        .main-wrapper {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        .brand-name {
            color: #fff;
            font-size: 48px;
            font-weight: 200;
            letter-spacing: 12px;
            margin-bottom: 30px;
            text-transform: uppercase;
            text-shadow: 0 0 20px rgba(0, 255, 255, 0.8);
            z-index: 10;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(15px);
            padding: 40px;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.5);
            width: 320px;
            z-index: 10;
            text-align: center;
            color: white;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.2);
            color: white;
            outline: none;
            box-sizing: border-box;
        }

        button {
            width: 80%;
            padding: 9px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(45deg, #00f2ff, #7000ff);
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.4s;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        button:hover { 
            box-shadow: 0 0 20px rgba(0, 242, 255, 0.6);
            transform: scale(1.02);
        }

        /* ONDAS NEON CENTRALIZADAS */
        .waves-box {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%); /* Centraliza exatamente no meio */
            width: 150%; /* Maior que a tela para o movimento não cortar */
            height: 400px;
            z-index: 1;
            pointer-events: none; /* Não atrapalha o clique no login */
        }

        .waves-svg { width: 100%; height: 100%; }

        /* Estilo das Linhas Neon */
        .parallax > use {
            animation: move-forever 15s cubic-bezier(.55,.5,.45,.5) infinite;
            fill: none;
            stroke-width: 0.2; /* Deixa a onda fina */
            filter: drop-shadow(0 0 8px currentColor); /* Efeito Neon */
        }

        /* Cores e Velocidades das Ondas Finas */
        .onda-ciano { stroke: #00ffff; animation-duration: 7s !important; opacity: 0.8; }
        .onda-roxa { stroke: #8a2be2; animation-duration: 10s !important; opacity: 0.6; }
        .onda-rosa { stroke: #ff00ff; animation-duration: 13s !important; opacity: 0.5; }
        .onda-branca { stroke: #ffffff; animation-duration: 18s !important; opacity: 0.3; }
        .onda-verde { stroke: #10be12; animation-duration: 18s !important; opacity: 0.3; }


        @keyframes move-forever {
            0% { transform: translate3d(-90px, 0, 0); }
            100% { transform: translate3d(85px, 0, 0); }
        }
    </style>
</head>
<body>

    <div class="main-wrapper">
        <h1 class="brand-name">SoundScore</h1>

        <div class="login-card">
            <h2 style="font-size: 20px; opacity: 0.5; letter-spacing: 4px; margin-bottom: 25px;">Fazer Login</h2>
           <form action="../trabalho-html/paginicial.php">
            <input type="text" placeholder="Usuário">
            <input type="password" placeholder="Senha">
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
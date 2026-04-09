<?php
// Array atualizado com 12 gêneros musicais
$generos = [
    ['nome' => 'Hip-Hop', 'classe' => 'hiphop'],
    ['nome' => 'Jazz', 'classe' => 'jazz'],
    ['nome' => 'POP', 'classe' => 'pop'],
    ['nome' => 'Música Eletrônica', 'classe' => 'eletronica'],
    ['nome' => 'Rock', 'classe' => 'rock'],
    ['nome' => 'MPB', 'classe' => 'mpb'],
    ['nome' => 'Sertanejo', 'classe' => 'sertanejo'],
    ['nome' => 'Funk', 'classe' => 'funk'],
    ['nome' => 'Reggae', 'classe' => 'reggae'],
    ['nome' => 'Clássica', 'classe' => 'classica'],
    ['nome' => 'Lo-fi', 'classe' => 'lofi'],
    ['nome' => 'Country', 'classe' => 'country'],
];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundScore - Gêneros</title>
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: 'Segoe UI', Tahoma, sans-serif; 
        }

        body { 
            background-color: #0a0b10; 
            color: white; 
            display: flex; 
            flex-direction: column;
            align-items: center; 
            justify-content: center;
            min-height: 100vh; 
            overflow-x: hidden;
            position: relative;
            padding: 100px 0; /* Espaço para o header e footer absolutos */
        }

        /* HEADER */
        .header {
            position: fixed;
            top: 30px;
            text-align: center;
            z-index: 10;
        }

        .header h1 {
            font-size: 2.8rem;
            letter-spacing: 10px;
            text-transform: uppercase;
            color: #b3f0ff;
            text-shadow: 0 0 15px rgba(0, 225, 255, 0.5);
            font-weight: 300;
        }

        /* GRID */
        .grid-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            z-index: 2;
            width: 90%;
            max-width: 1100px;
        }

        .card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 25px 15px;
            text-align: center;
            transition: 0.3s;
        }

        .card:hover { 
            transform: translateY(-5px); 
            background: rgba(255, 255, 255, 0.08); 
            border-color: rgba(255, 255, 255, 0.2);
        }

        .card h2 { font-size: 1.1rem; margin-bottom: 12px; font-weight: 400; }

        .card button {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: rgba(255, 255, 255, 0.7);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            cursor: pointer;
            transition: 0.3s;
        }

        .card button:hover { background: white; color: black; }

        /* CORES DAS BORDAS - 12 ESTILOS */
        .hiphop { border-bottom: 3px solid #7b4fb6; }
        .jazz { border-bottom: 3px solid #3b5998; }
        .pop { border-bottom: 3px solid #3498db; }
        .eletronica { border-bottom: 3px solid #5d5d6a; }
        .rock { border-bottom: 3px solid #e74c3c; }
        .mpb { border-bottom: 3px solid #2ecc71; }
        .sertanejo { border-bottom: 3px solid #f1c40f; }
        .funk { border-bottom: 3px solid #e67e22; }
        .reggae { border-bottom: 3px solid #16a085; }
        .classica { border-bottom: 3px solid #ecf0f1; }
        .lofi { border-bottom: 3px solid #9b59b6; }
        .country { border-bottom: 3px solid #a0522d; }

        /* FOOTER */
        .footer-area {
            margin-top: 30px;
            text-align: center;
            z-index: 5;
        }

        .btn-voltar {
            background: transparent;
            border: 2px solid;
            border-image: linear-gradient(to right, #00d4ff, #9b59b6) 1;
            color: white;
            padding: 8px 25px;
            cursor: pointer;
            transition: 0.3s;
        }

        /* VISUALIZADOR PHP */
        .audio-visualizer {
            display: flex;
            align-items: flex-end;
            justify-content: center;
            gap: 4px;
            height: 50px;
            position: fixed;
            bottom: 0;
            width: 100%;
            padding-bottom: 10px;
            pointer-events: none;
        }

        .bar { width: 5px; border-radius: 2px 2px 0 0; }
    </style>
</head>
<body>

    <header class="header">
        <h1>SOUNDSCORE</h1>
    </header>

    <div class="grid-container">
        <?php foreach ($generos as $genero): ?>
            <div class="card <?php echo $genero['classe']; ?>">
                <h2><?php echo $genero['nome']; ?></h2>
                <form action="jazz.php" method="GET">
                    <input type="hidden" name="genero" value="<?php echo $genero['nome']; ?>">
                    <button type="submit">Visualizar Músicas</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="footer-area">
        <button class="btn-voltar" onclick="window.location.href='login.php'">Voltar para Login</button>
    </div>

    <div class="audio-visualizer">
        <?php 
        $cores_barra = ['#7b4fb6', '#00d4ff', '#9b59b6', '#e74c3c', '#f1c40f', '#2ecc71'];
        for ($i = 0; $i < 60; $i++) {
            $altura = rand(10, 45); 
            $cor = $cores_barra[$i % count($cores_barra)];
            echo "<div class='bar' style='height: {$altura}px; background: {$cor};'></div>";
        }
        ?>
    </div>

</body>
</html>

<?php
$pdo = new PDO('mysql:host=localhost;dbname=bdmusica;charset=utf8mb4', 'root', '');

$stmt = $pdo->query("SELECT id, nome FROM genero WHERE status = 1");
$generos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundScore - Gêneros</title>
    <link rel="stylesheet" href="./assets/css/paginicial.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .search-box {
            margin: 20px auto;
            max-width: 400px;
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-box input {
            width: 100%;
            padding: 12px 40px;
            border-radius: 30px;
            border: none;
            background-color: #2a2a2a;
            color: white;
            font-size: 16px;
            outline: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.3);
        }

        .search-box i {
            position: absolute;
            left: 15px;
            color: #b3b3b3;
        }

        /* Animação suave para quando o card sumir */
        .card { transition: transform 0.3s, opacity 0.3s; }
    </style>
</head>
<body>

    <header class="header">
        <h1>SOUNDSCORE</h1>
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="inputBusca" placeholder="Pesquisar gênero..." onkeyup="filtrar()">
        </div>
    </header>

    <div class="grid-container" id="gradeGeneros">
        <?php foreach ($generos as $g): ?>
            <div class="card <?php echo $g['classe']; ?>">
                <h2><?php echo $g['nome']; ?></h2>
                <form action="<?php echo $g['link']; ?>" method="GET">
                    <input type="hidden" name="genero" value="<?php echo $g['nome']; ?>">
                    <button type="submit">Visualizar Músicas</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="footer-area">
        <button class="btn-voltar" onclick="window.location.href='index.php'">Voltar Para Página Inicial</button>
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

    <script>
        function filtrar() {
            const input = document.getElementById('inputBusca').value.toLowerCase();
            const cards = document.querySelectorAll('.card');

            cards.forEach(card => {
                const nomeGenero = card.querySelector('h2').innerText.toLowerCase();
                if (nomeGenero.includes(input)) {
                    card.style.display = "block";
                    card.style.opacity = "1";
                } else {
                    card.style.display = "none";
                    card.style.opacity = "0";
                }
            });
        }
    </script>

</body>
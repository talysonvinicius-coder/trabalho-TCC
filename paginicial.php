<?php
// Array com os gêneros musicais
$generos = [
    ['nome' => 'Hip-Hop', 'classe' => 'hiphop', 'link' => 'hiphop.php'],
    ['nome' => 'Jazz', 'classe' => 'jazz', 'link' => 'jazz.php'],
    ['nome' => 'POP', 'classe' => 'pop', 'link' => 'pop.php'],
    ['nome' => 'Música Eletrônica', 'classe' => 'eletronica', 'link' => 'musicaeletro.php'],
    ['nome' => 'Rock', 'classe' => 'rock', 'link' => 'rock.php'],
    ['nome' => 'MPB', 'classe' => 'mpb', 'link' => 'mpb.php'],
    ['nome' => 'Sertanejo', 'classe' => 'sertanejo', 'link' => 'sertanejo.php'],
    ['nome' => 'Funk', 'classe' => 'funk', 'link' => 'funk.php'],
    ['nome' => 'Reggae', 'classe' => 'reggae', 'link' => 'reggae.php'],
    ['nome' => 'Clássica', 'classe' => 'classica', 'link' => 'classica.php'],
    ['nome' => 'Lo-fi', 'classe' => 'lofi', 'link' => 'lofi.php'],
    ['nome' => 'Country', 'classe' => 'country', 'link' => 'country.php'],
];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundScore - Gêneros</title>
  <link rel="stylesheet" href="./assets/css/paginicial.css">
</head>
<body>

    <header class="header">
        <h1>SOUNDSCORE</h1>
    </header>

    <div class="grid-container">
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
        <button class="btn-voltar" onclick="window.location.href='index.php'">Voltar para Login</button>
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
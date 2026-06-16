<?php
$id_genero = $_GET['id'] ?? null;

if (!$id_genero) {
    header("Location: buscar.php");
    exit;
}

$pdo = new PDO('mysql:host=localhost;dbname=bdmusica;charset=utf8mb4', 'root', '');

$stmt_genero = $pdo->prepare("SELECT nome, descricao FROM genero WHERE id = :id");
$stmt_genero->execute(['id' => $id_genero]);
$genero_atual = $stmt_genero->fetch(PDO::FETCH_ASSOC);

if (!$genero_atual) {
    header("Location: buscar.php");
    exit;
}

$stmt_musicas = $pdo->prepare("
    SELECT m.titulo, m.artista as artista, m.album AS album
    FROM musicas m
    WHERE m.genero_id = :id
");
$stmt_musicas->execute(['id' => $id_genero]);
$musicas = $stmt_musicas->fetchAll(PDO::FETCH_ASSOC);

// Ícones por gênero
$genreIcons = [
    'hip-hop' => '🎤', 'hiphop' => '🎤', 'rap' => '🎤', 'trap' => '🎤',
    'jazz'    => '🎷', 'blues'  => '🎸', 'rock' => '🎸', 'metal' => '🤘',
    'pop'     => '🎵', 'kpop'   => '💜', 'k-pop' => '💜',
    'eletrônica' => '🎧', 'eletronica' => '🎧', 'eletro' => '🎧',
    'mpb'     => '🇧🇷', 'sertanejo' => '🤠', 'forró' => '🪗', 'forro' => '🪗',
    'funk'    => '🔥', 'reggae' => '🌿', 'clássica' => '🎻', 'classica' => '🎻',
    'lo-fi'   => '☕', 'lofi'   => '☕', 'country' => '🪕', 'cristã' => '✝️', 'crista' => '✝️',
];

$nomeMin = mb_strtolower($genero_atual['nome']);
$icone = '🎶';
foreach ($genreIcons as $key => $emoji) {
    if (str_contains($nomeMin, $key)) { $icone = $emoji; break; }
}

// Notas musicais decorativas para as capas
$notas = ['🎵', '🎶', '🎼', '🎹', '🥁', '🎸', '🎷', '🎺', '🎻', '🪗'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundScore - <?php echo htmlspecialchars($genero_atual['nome']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/navbarPag.css">
    <link rel="stylesheet" href="assets/css/genero.css">
</head>
<body>
<?php include __DIR__ . '/navbarPag.php'; ?>
    <main class="content">
        <!-- Botão voltar -->
        <a href="paginicial.php" class="btn-voltar fade-in">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>

        <!-- Header do gênero -->
        <div class="genre-header fade-in">
            <div class="genre-header-icon"><?php echo $icone; ?></div>
            <div class="genre-header-info">
                <h2>Explore as melhores faixas de <?php echo htmlspecialchars($genero_atual['nome']); ?></h2>
                <p><?php echo htmlspecialchars($genero_atual['descricao']); ?></p>
            </div>
        </div>

        <!-- Grade de músicas -->
        <p class="section-title fade-in">
            <i class="fas fa-music" style="color:#7c4dff;"></i>
            <?php echo count($musicas); ?> música<?php echo count($musicas) !== 1 ? 's' : ''; ?> encontrada<?php echo count($musicas) !== 1 ? 's' : ''; ?>
        </p>

        <div class="musicas-grid fade-in">
            <?php if (count($musicas) > 0): ?>
                <?php foreach ($musicas as $i => $musica): ?>
                    <div class="musica-card">
                        <div class="musica-card-cover">
                            <?php echo $notas[$i % count($notas)]; ?>
                        </div>
                        <button class="musica-card-play"><i class="fas fa-play"></i></button>
                        <div class="musica-card-body">
                            <h3><?php echo htmlspecialchars($musica['artista']); ?></h3>
                            <p><?php echo htmlspecialchars($musica['titulo']); ?></p>
                        </div>
                        <div class="musica-card-bar">
                            <div class="musica-card-bar-fill"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">🎵</div>
                    <p>Nenhuma música encontrada para este gênero.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        // Fade-in ao rolar
        const observer = new IntersectionObserver(entries => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
        }, { threshold: 0.1 });
        document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
    </script>
</body>
</html>

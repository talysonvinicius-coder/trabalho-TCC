<?php
$musicas = [
    ['titulo' => 'Midnight City',   'artista' => 'M83',          'genero' => 'Eletrônica', 'nota' => 5, 'capa' => 'https://picsum.photos/seed/music1/200'],
    ['titulo' => 'Blinding Lights', 'artista' => 'The Weeknd',   'genero' => 'Pop',        'nota' => 4, 'capa' => 'https://picsum.photos/seed/music2/200'],
    ['titulo' => 'Breathe Deeper',  'artista' => 'Tame Impala',  'genero' => 'Rock',       'nota' => 5, 'capa' => 'https://picsum.photos/seed/music3/200'],
    ['titulo' => 'Levitating',      'artista' => 'Dua Lipa',     'genero' => 'Pop',        'nota' => 3, 'capa' => 'https://picsum.photos/seed/music4/200'],
    ['titulo' => 'Starboy',         'artista' => 'The Weeknd',   'genero' => 'R&B',        'nota' => 4, 'capa' => 'https://picsum.photos/seed/music5/200'],
    ['titulo' => 'Heat Waves',      'artista' => 'Glass Animals','genero' => 'Indie',      'nota' => 2, 'capa' => 'https://picsum.photos/seed/music6/200'],
];

$total = count($musicas);
$soma  = array_sum(array_column($musicas, 'nota'));
$media = $total > 0 ? round($soma / $total, 1) : 0;

$contagem = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
foreach ($musicas as $m) $contagem[$m['nota']]++;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundScore - Biblioteca</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/teste.css">
    <link rel="stylesheet" href="assets/css/biblioteca.css">
</head>
<body>

    <aside class="sidebar">
        <div class="logo">🎶 SoundScore</div>
        <nav>
            <ul>
                <li>
                    <a href="paginicial.php">
                        <i class="fas fa-home"></i> Início
                    </a>
                </li>
                <li class="active">
                    <i class="fas fa-book"></i> Sua Biblioteca
                </li>
                <li class="sidebar-profile">
                    <i class="fas fa-user"></i> Ver Perfil
                </li>
            </ul>
        </nav>
        <div class="sidebar-premium-banner" style="margin-top:10px;">
            <i class="fas fa-star mb-2" style="color:#ffc107;font-size:1.4rem"></i>
            <p>Ouça sem limites com o <strong>Premium</strong></p>
            <a href="premium.php" class="btn-premium-sidebar">Experimente grátis</a>
        </div>
    </aside>

    <main class="content">
        <header class="top-bar">
            <div class="search-bar-top">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar na biblioteca...">
            </div>
            <div class="user-controls">
                <a href="premium.php" class="badge-upgrade"><i class="fas fa-bolt me-1"></i>Upgrade</a>
            </div>
        </header>

        <!-- Painel de média de avaliações -->
        <div class="rating-panel mb-4">
            <div class="row align-items-center g-4">
                <div class="col-auto text-center" style="min-width:120px;">
                    <div class="avg-score"><?php echo $media; ?></div>
                    <div class="avg-stars mt-2">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="<?php echo $i <= round($media) ? 'fas' : 'far'; ?> fa-star"></i>
                        <?php endfor; ?>
                    </div>
                    <p style="color:rgba(255,255,255,0.4); font-size:0.78rem; margin-top:6px;">
                        <?php echo $total; ?> avaliações
                    </p>
                </div>
                <div class="col">
                    <p class="section-title mb-3"><i class="fas fa-chart-bar me-2" style="color:#ffc107;"></i>Média das suas avaliações</p>
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <?php $pct = $total > 0 ? ($contagem[$i] / $total) * 100 : 0; ?>
                        <div class="bar-row">
                            <span><?php echo $i; ?></span>
                            <i class="fas fa-star" style="color:#ffc107; font-size:0.75rem;"></i>
                            <div class="bar-track">
                                <div class="bar-fill" style="width:<?php echo $pct; ?>%"></div>
                            </div>
                            <span class="bar-count"><?php echo $contagem[$i]; ?></span>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

        <!-- Músicas avaliadas -->
        <div class="section-title"><i class="fas fa-music me-2" style="color:#1db954;"></i>Músicas Avaliadas</div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3">
            <?php foreach ($musicas as $m): ?>
            <div class="col">
                <div class="music-card">
                    <img src="<?php echo $m['capa']; ?>" alt="<?php echo $m['titulo']; ?>">
                    <div class="info">
                        <h6><?php echo $m['titulo']; ?></h6>
                        <p><?php echo $m['artista']; ?> • <?php echo $m['genero']; ?></p>
                        <div class="stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="<?php echo $i <= $m['nota'] ? 'fas' : 'far'; ?> fa-star <?php echo $i > $m['nota'] ? 'empty' : ''; ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

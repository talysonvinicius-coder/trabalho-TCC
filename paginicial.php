<?php
$generos = [
    ['nome' => 'Hip-Hop',           'classe' => 'hiphop',    'link' => 'hiphop.php',          'color' => '#e91e63'],
    ['nome' => 'Jazz',              'classe' => 'jazz',      'link' => 'jazz.php',            'color' => '#ff9800'],
    ['nome' => 'POP',               'classe' => 'pop',       'link' => 'pop.php',             'color' => '#9c27b0'],
    ['nome' => 'Música Eletrônica', 'classe' => 'eletronica','link' => 'musicaeletro.php',    'color' => '#00bcd4'],
    ['nome' => 'Rock',              'classe' => 'rock',      'link' => 'rock.php',            'color' => '#f44336'],
    ['nome' => 'MPB',               'classe' => 'mpb',       'link' => 'mpb.php',             'color' => '#4caf50'],
    ['nome' => 'Sertanejo',         'classe' => 'sertanejo', 'link' => 'sertanejo.php',       'color' => '#8d6e63'],
    ['nome' => 'Funk',              'classe' => 'funk',      'link' => 'funk.php',            'color' => '#ff5722'],
    ['nome' => 'Reggae',            'classe' => 'reggae',    'link' => 'reggae.php',          'color' => '#388e3c'],
    ['nome' => 'Clássica',          'classe' => 'classica',  'link' => 'classica.php',        'color' => '#5c6bc0'],
    ['nome' => 'Lo-fi',             'classe' => 'lofi',      'link' => 'lofi.php',            'color' => '#3f51b5'],
    ['nome' => 'Country',           'classe' => 'country',   'link' => 'country.php',         'color' => '#795548'],
    ['nome' => 'Forró & Brega',     'classe' => 'forro',     'link' => 'forro.php',           'color' => '#fbc02d'],
    ['nome' => 'Cristã',            'classe' => 'crista',    'link' => 'crista.php',          'color' => '#7986cb'],
    ['nome' => 'K-pop',             'classe' => 'kpop',      'link' => 'kpop.php',            'color' => '#ec407a'],
    ['nome' => 'Trap/Rap',          'classe' => 'rap-trap',  'link' => 'estilo-classico.php', 'color' => '#616161'],
];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundScore - Player</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/teste.css">
    <style>
        .sidebar nav ul li a {
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: center;
            width: 100%;
            height: 100%;
        }
        .card-img-overlay-play {
            position: absolute;
            bottom: 70px;
            right: 12px;
            opacity: 0;
            transition: opacity 0.3s ease, transform 0.3s ease;
            transform: translateY(8px);
        }
        .card:hover .card-img-overlay-play {
            opacity: 1;
            transform: translateY(0);
        }
        .card { position: relative; }
        .genre-card {
            background: rgba(255,255,255,0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            padding: 25px 15px;
            text-align: center;
            transition: 0.3s;
        }
        .genre-card:hover {
            transform: translateY(-5px);
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.2);
        }
        .genre-card h2 { font-size: 1.1rem; margin-bottom: 12px; font-weight: 400; color: #fff; }
        .genre-card button {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.7);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            cursor: pointer;
            transition: 0.3s;
        }
        .genre-card button:hover { background: white; color: black; }
        .genre-card.hiphop    { border-bottom: 3px solid #7b4fb6; }
        .genre-card.jazz      { border-bottom: 3px solid #3b5998; }
        .genre-card.pop       { border-bottom: 3px solid #3498db; }
        .genre-card.eletronica{ border-bottom: 3px solid #5d5d6a; }
        .genre-card.rock      { border-bottom: 3px solid #e74c3c; }
        .genre-card.mpb       { border-bottom: 3px solid #5c6bc0; }
        .genre-card.sertanejo { border-bottom: 3px solid #f1c40f; }
        .genre-card.funk      { border-bottom: 3px solid #e67e22; }
        .genre-card.reggae    { border-bottom: 3px solid #7e57c2; }
        .genre-card.classica  { border-bottom: 3px solid #ecf0f1; }
        .genre-card.lofi      { border-bottom: 3px solid #9b59b6; }
        .genre-card.country   { border-bottom: 3px solid #a0522d; }
        .genre-card.forro     { border-bottom: 3px solid #ff5733; }
        .genre-card.crista    { border-bottom: 3px solid #ffffff; }
        .genre-card.kpop      { border-bottom: 3px solid #ff69b4; }
        .genre-card.rap-trap  { border-bottom: 3px solid #616161; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="logo">🎶 SoundScore</div>
        <nav>
            <ul>
                <li class="active">
                    <i class="fas fa-home"></i> Início
                </li>
               <li>
                <a href="biblioteca.php">
                    <i class="fas fa-book"></i> Sua Biblioteca
                </a>
               </li>
                <li class="sidebar-profile">
                    <i class="fas fa-user"></i> Ver Perfil  
                </li>
            </ul>
        </nav>
        <div class="sidebar-premium-banner">
            <i class="fas fa-star mb-2" style="color:#ffc107;font-size:1.4rem"></i>
            <p>Ouça sem limites com o <strong>Premium</strong></p>
            <a href="premium.php" class="btn-premium-sidebar">Experimente grátis</a>
        </div>
    </aside>

    <main class="content">
        <header class="top-bar">
            <div class="search-bar-top">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="O que você quer ouvir?">
            </div>
            <div class="user-controls">
                <a href="premium.php" class="badge-upgrade"><i class="fas fa-bolt me-1"></i>Upgrade</a>
            </div>
        </header>

        <!-- Banner destaque -->
        <div class="featured-banner mb-4">
            <div class="featured-info">
                <span class="featured-tag">🔥 Em alta agora</span>
                <h1>Midnight City</h1>
                <p>M83 • Álbum: Hurry Up, We're Dreaming</p>
                <div class="d-flex gap-2 mt-3">
                    <button class="btn-play-featured"><i class="fas fa-play me-2"></i>Reproduzir</button>
                    <button class="btn-like-featured"><i class="far fa-heart me-2"></i>Curtir</button>
                </div>
            </div>
            <img src="https://picsum.photos/seed/music1/400/220" alt="Destaque" class="featured-img">
        </div>

        <section class="mb-4">
            <div class="section-header">
                <h2>Suas Categorias</h2>
                <a href="buscar.php" class="see-all">Ver tudo</a>
            </div>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                <?php foreach ($generos as $g): ?>
                <div class="col">
                    <div class="genre-card <?php echo $g['classe']; ?>">
                        <h2><?php echo $g['nome']; ?></h2>
                        <form action="<?php echo $g['link']; ?>" method="GET">
                            <input type="hidden" name="genero" value="<?php echo $g['nome']; ?>">
                            <button type="submit">Visualizar Músicas</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="mb-4">
            <div class="section-header">
                <h2>Avaliadas recentemente</h2>
                <a href="#" class="see-all">Ver tudo</a>
            </div>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                <div class="col"><div class="card">
                    <img src="https://picsum.photos/seed/music1/200" alt="Capa">
                    <button class="card-img-overlay-play"><i class="fas fa-play"></i></button>
                    <h4>Midnight City</h4>
                    <p>M83 • <span class="score-badge">9.8</span></p>
                </div></div>
                <div class="col"><div class="card">
                    <img src="https://picsum.photos/seed/music2/200" alt="Capa">
                    <button class="card-img-overlay-play"><i class="fas fa-play"></i></button>
                    <h4>Blinding Lights</h4>
                    <p>The Weeknd • <span class="score-badge">9.5</span></p>
                </div></div>
                <div class="col"><div class="card">
                    <img src="https://picsum.photos/seed/music3/200" alt="Capa">
                    <button class="card-img-overlay-play"><i class="fas fa-play"></i></button>
                    <h4>Breathe Deeper</h4>
                    <p>Tame Impala • <span class="score-badge">9.2</span></p>
                </div></div>
                <div class="col"><div class="card">
                    <img src="https://picsum.photos/seed/music4/200" alt="Capa">
                    <button class="card-img-overlay-play"><i class="fas fa-play"></i></button>
                    <h4>Levitating</h4>
                    <p>Dua Lipa • <span class="score-badge">9.0</span></p>
                </div></div>
            </div>
        </section>

        <section class="mb-4">
            <div class="section-header">
                <h2>Artistas Recomendados</h2>
                <a href="#" class="see-all">Ver tudo</a>
            </div>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                <div class="col"><div class="card artist">
                    <img src="https://picsum.photos/seed/art1/200" alt="Artista">
                    <h4>The Weeknd</h4>
                    <p><i class="fas fa-circle-check text-success me-1" style="font-size:.7rem"></i>Artista Verificado</p>
                </div></div>
                <div class="col"><div class="card artist">
                    <img src="https://picsum.photos/seed/art2/200" alt="Artista">
                    <h4>Tame Impala</h4>
                    <p><i class="fas fa-circle-check text-success me-1" style="font-size:.7rem"></i>Artista Verificado</p>
                </div></div>
                <div class="col"><div class="card artist">
                    <img src="https://picsum.photos/seed/art3/200" alt="Artista">
                    <h4>Dua Lipa</h4>
                    <p><i class="fas fa-circle-check text-success me-1" style="font-size:.7rem"></i>Artista Verificado</p>
                </div></div>
            </div>
        </section>



    </main>

    <footer class="player" style="padding:0; overflow:hidden; height:90px;">
        <div class="now-playing" style="width:100%; height:100%;">
            <img src="https://i0.statig.com.br/bancodeimagens/8g/0j/64/8g0j64xs7c6nw0zned15uclbq.jpg" alt="Atual" style="width:100%; height:100%; object-fit:cover; display:block;">
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Tooltips Bootstrap
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
        // Like toggle no player
        document.querySelector('.player-like').addEventListener('click', function() {
            this.classList.toggle('fas');
            this.classList.toggle('far');
            this.style.color = this.classList.contains('fas') ? '#1db954' : '';
        });
    </script>
</body>
</html>
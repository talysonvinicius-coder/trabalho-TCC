<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=bdmusica;charset=utf8mb4', 'root', '');
    $stmt = $pdo->query("SELECT id, nome FROM genero WHERE status = 1");
    $generos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $generos = [];
    error_log($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundScore - Início</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/navbarPag.css">
    <style>
        :root {
            --accent: #7c4dff;
            --bg-dark: #0a0a0a;
            --bg-card: #181818;
            --bg-card-hover: #282828;
            --text-muted-custom: #b3b3b3;
        }

        body { background: var(--bg-dark); color: #fff; font-family: 'Segoe UI', sans-serif; display: flex; height: 100vh; overflow: hidden; }
        main.content { margin-left: 240px; width: calc(100% - 240px); overflow-y: auto; padding: 20px 32px 80px; }

        /* Featured Banner */
        .featured-banner {
            border-radius: 16px;
            overflow: hidden;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            padding: 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            position: relative;
            animation: gradientShift 8s ease infinite;
            background-size: 200% 200%;
        }
        @keyframes gradientShift {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .featured-tag {
            background: linear-gradient(135deg, rgba(124,77,255,0.85), rgba(79,195,247,0.85));
            color: #fff;
            font-size: 0.75rem;
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }
        .featured-banner h1 { font-size: 2rem; font-weight: 800; margin-bottom: 4px; }
        .featured-banner p  { color: var(--text-muted-custom); margin-bottom: 0; }
        .featured-img {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.5);
            flex-shrink: 0;
        }
        .btn-play-featured {
            background: linear-gradient(135deg, #7c4dff, #4fc3f7);
            border: none;
            color: #fff;
            padding: 10px 22px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: filter 0.2s, transform 0.2s;
        }
        .btn-play-featured:hover { filter: brightness(1.15); transform: scale(1.04); }
        .btn-like-featured {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.3);
            color: #fff;
            padding: 10px 22px;
            border-radius: 25px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn-like-featured:hover { border-color: #7c4dff; color: #7c4dff; }
        .btn-like-featured.curtido { border-color: #7c4dff; color: #7c4dff; }

        /* Section header */
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .section-header h2 { font-size: 1.3rem; font-weight: 700; margin: 0; }
        .see-all { color: var(--text-muted-custom); font-size: 0.8rem; text-decoration: none; text-transform: uppercase; letter-spacing: 1px; transition: color 0.2s; }
        .see-all:hover { color: #fff; }

        /* Music Cards */
        .music-card {
            background: var(--bg-card);
            border-radius: 12px;
            overflow: hidden;
            transition: background 0.3s, transform 0.3s;
            cursor: pointer;
            position: relative;
            height: 100%;
        }
        .music-card:hover { background: var(--bg-card-hover); transform: translateY(-4px); }
        .music-card img { width: 100%; aspect-ratio: 1; object-fit: cover; display: block; }
        .music-card .card-body { padding: 12px; }
        .music-card h4 { font-size: 0.95rem; font-weight: 600; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .music-card p  { font-size: 0.8rem; color: var(--text-muted-custom); margin: 0; }
        .score-badge {
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: #000;
            font-weight: 700;
            font-size: 0.75rem;
            padding: 2px 8px;
            border-radius: 10px;
        }
        .play-overlay {
            position: absolute;
            bottom: 70px;
            right: 12px;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #7c4dff, #4fc3f7);
            border: none;
            border-radius: 50%;
            color: #fff;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transform: translateY(8px);
            transition: opacity 0.3s, transform 0.3s;
            box-shadow: 0 4px 12px rgba(124,77,255,0.45);
        }
        .music-card:hover .play-overlay { opacity: 1; transform: translateY(0); }

        /* Artist Cards */
        .artist-card {
            background: var(--bg-card);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: background 0.3s, transform 0.3s;
            cursor: pointer;
        }
        .artist-card:hover { background: var(--bg-card-hover); transform: translateY(-4px); }
        .artist-card img { width: 100%; aspect-ratio: 1; object-fit: cover; border-radius: 50%; margin-bottom: 12px; }
        .artist-card h4 { font-size: 0.95rem; font-weight: 600; margin-bottom: 4px; }
        .artist-card p  { font-size: 0.78rem; color: var(--text-muted-custom); margin: 0; }

        /* Genre Cards */
        .genre-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            padding: 22px 15px;
            text-align: center;
            transition: 0.3s;
            cursor: pointer;
            text-decoration: none;
            display: block;
            color: #fff;
        }
        .genre-card:hover { background: rgba(255,255,255,0.09); transform: translateY(-4px); color: #fff; border-color: rgba(255,255,255,0.2); }
        .genre-card .genre-icon { font-size: 1.6rem; margin-bottom: 8px; display: block; }
        .genre-card span.genre-name { font-size: 0.9rem; font-weight: 500; display: block; }

        /* Fade-in animation */
        .fade-in { opacity: 0; transform: translateY(20px); transition: opacity 0.5s ease, transform 0.5s ease; }
        .fade-in.visible { opacity: 1; transform: translateY(0); }

        /* Footer slideshow */
        .player { padding: 0; overflow: hidden; height: 50px; position: fixed; bottom: 0; left: 240px; width: calc(100% - 240px); z-index: 40; background: #000; }
        .player img { width: 100%; height: 100%; object-fit: fill; display: block; transition: opacity 1s ease; }

        /* Sidebar premium banner */
        .sidebar-premium-banner { margin-top: 10px; }

        /* Modal Avaliação */
        #modalAvaliar .modal-content {
            background: rgba(15,15,25,0.92);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            color: #fff;
        }
        .avaliar-header { display: flex; gap: 16px; align-items: center; padding: 20px 20px 0; }
        .avaliar-header img { width: 72px; height: 72px; border-radius: 10px; object-fit: cover; flex-shrink: 0; }
        .avaliar-header .info h5 { font-size: 1rem; font-weight: 700; margin: 0 0 4px; }
        .avaliar-header .info p  { font-size: 0.8rem; color: var(--text-muted-custom); margin: 0; }
        .star-rating { display: flex; gap: 6px; font-size: 1.6rem; cursor: pointer; }
        .star-rating span { color: rgba(255,255,255,0.2); transition: color 0.15s; }
        .star-rating span.active { color: #ffc107; }
        .mood-slider { -webkit-appearance: none; width: 100%; height: 6px; border-radius: 3px;
            background: linear-gradient(to right, #4fc3f7, #7c4dff, #ff4081); outline: none; }
        .mood-slider::-webkit-slider-thumb { -webkit-appearance: none; width: 18px; height: 18px;
            border-radius: 50%; background: #fff; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.4); }
        .mood-labels { display: flex; justify-content: space-between; font-size: 0.72rem; color: var(--text-muted-custom); margin-top: 4px; }
        .comentario-textarea { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);
            border-radius: 12px; color: #fff; resize: none; width: 100%; padding: 10px 14px; font-size: 0.85rem; }
        .comentario-textarea::placeholder { color: rgba(255,255,255,0.35); }
        .comentario-textarea:focus { outline: none; border-color: #7c4dff; }
        .feedback-chips { display: flex; flex-wrap: wrap; gap: 8px; }
        .chip { background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.15);
            border-radius: 20px; padding: 4px 12px; font-size: 0.78rem; cursor: pointer; transition: 0.2s; color: #fff; }
        .chip:hover, .chip.selected { background: rgba(124,77,255,0.35); border-color: #7c4dff; }
        .btn-avaliar-enviar { background: linear-gradient(135deg, #7c4dff, #4fc3f7);
            border: none; color: #fff; border-radius: 25px; padding: 10px 0;
            font-weight: 700; width: 100%; font-size: 0.95rem; transition: filter 0.2s; }
        .btn-avaliar-enviar:hover { filter: brightness(1.15); }
    </style>
</head>
<body>

<?php include __DIR__ . '/navbarPag.php'; ?>

<main class="content">
    <header class="top-bar">
        <div class="search-bar-top">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="O que você quer ouvir?" id="search-input">
        </div>
        <div class="user-controls">
            <a href="premium.php" class="badge-upgrade"><i class="fas fa-bolt me-1"></i>Upgrade</a>
            <a href="homeAdmin.php" id="btn-admin" style="display:none; background:linear-gradient(135deg,#7c4dff,#4fc3f7); border:none; color:#fff; font-size:0.78rem; font-weight:700; padding:6px 14px; border-radius:20px; text-decoration:none; transition:filter 0.2s;"><i class="fas fa-shield-alt me-1"></i>Ir para a Administração</a>
        </div>
    </header>

    <!-- Banner destaque -->
    <div class="featured-banner mb-4 fade-in">
        <div class="featured-info">
            <span class="featured-tag">🔥 Avaliações em alta</span>
            <h1>Midnight City</h1>
            <p>M83 • Álbum: Hurry Up, We're Dreaming</p>
            <div class="d-flex gap-2 mt-3">
                <button class="btn-play-featured" data-bs-toggle="modal" data-bs-target="#modalComentario">
                    <i class="fas fa-comment me-2"></i>Comentar
                </button>
                <button class="btn-like-featured" id="btn-curtir" onclick="toggleCurtir(this)">
                    <i class="far fa-heart me-2"></i>Curtir
                </button>
            </div>
        </div>
        <img src="https://picsum.photos/seed/music1/400/220" alt="Destaque" class="featured-img">
    </div>

    <!-- Categorias -->
    <section class="mb-5 fade-in">
        <div class="section-header">
            <h2>Categorias</h2>
            <a href="buscar.php" class="see-all">Ver tudo</a>
        </div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3" id="generos-container">
            <?php if (count($generos) > 0): ?>
                <?php foreach ($generos as $genero): ?>
                    <div class="col">
                        <a href="genero.php?id=<?php echo htmlspecialchars($genero['id']); ?>" class="genre-card">
                            <span class="genre-icon" data-genre="<?php echo strtolower(htmlspecialchars($genero['nome'])); ?>"></span>
                            <span class="genre-name"><?php echo htmlspecialchars($genero['nome']); ?></span>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">Nenhum gênero disponível.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Avaliadas recentemente -->
    <section class="mb-5 fade-in">
        <div class="section-header">
            <h2>Avaliadas recentemente</h2>
            <a href="#" class="see-all" data-bs-toggle="modal" data-bs-target="#modalAvaliadasRecente">Ver tudo</a>
        </div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
            <?php
            $musicas = [
                ['seed' => 'music1', 'titulo' => 'Midnight City',   'artista' => 'M83',         'score' => '9.8'],
                ['seed' => 'music2', 'titulo' => 'Blinding Lights', 'artista' => 'The Weeknd',  'score' => '9.5'],
                ['seed' => 'music3', 'titulo' => 'Breathe Deeper',  'artista' => 'Tame Impala', 'score' => '9.2'],
                ['seed' => 'music4', 'titulo' => 'Levitating',      'artista' => 'Dua Lipa',    'score' => '9.0'],
            ];
            foreach ($musicas as $m): ?>
            <div class="col">
                <div class="music-card">
                    <img src="https://picsum.photos/seed/<?php echo $m['seed']; ?>/200" alt="<?php echo $m['titulo']; ?>">
                    <button class="play-overlay"><i class="fas fa-play"></i></button>
                    <div class="card-body">
                        <h4><?php echo $m['titulo']; ?></h4>
                        <p><?php echo $m['artista']; ?> • <span class="score-badge"><?php echo $m['score']; ?></span></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <!-- Recomendação de avaliações -->
    <section class="mb-5 fade-in">
        <div class="section-header">
            <h2>Recomendações Para Você</h2>
            <a href="#" class="see-all" data-bs-toggle="modal" data-bs-target="#modalRecomendacoes">Ver tudo</a>
        </div>
        <div class="d-flex flex-nowrap gap-3 overflow-auto pb-2" style="scrollbar-width:thin;scrollbar-color:#7c4dff transparent;">
            <?php
            $musicas = [
                ['seed' => 'music1', 'titulo' => 'Bohemian Rhapsody ',   'artista' => 'Queen',         'score' => '9.8'],
                ['seed' => 'music2', 'titulo' => 'Imagine', 'artista' => 'John Lennon ',  'score' => '9.5'],
                ['seed' => 'music3', 'titulo' => 'Billie Jean ',  'artista' => ' Michael Jackson', 'score' => '9.2'],
                ['seed' => 'music4', 'titulo' => 'Smells Like Teen Spirit ',      'artista' => 'Nirvana',    'score' => '9.5'],
                ['seed' => 'music5', 'titulo' => 'Hotel California ',      'artista' => 'Eagles',    'score' => '6.5'],
                ['seed' => 'music6', 'titulo' => 'Shape of You',      'artista' => 'Ed Sheeran',    'score' => '6.7'],
                ['seed' => 'music7', 'titulo' => 'Rolling in the Deep ',      'artista' => 'Adele',    'score' => '8.9'],
                ['seed' => 'music8', 'titulo' => 'Hey Jude ',      'artista' => 'The Beatles',    'score' => '9.9'],
                ['seed' => 'music9', 'titulo' => 'Like a Prayer',      'artista' => 'Madonna',    'score' => '8.3'],
                ['seed' => 'music10', 'titulo' => 'Madonna',      'artista' => 'Oasis',    'score' => ''],
            ];
            foreach ($musicas as $m): ?>
            <div style="min-width:160px;max-width:160px;">
                <div class="music-card" onclick="abrirAvaliar('<?php echo $m['seed']; ?>','<?php echo addslashes(trim($m['titulo'])); ?>','<?php echo addslashes(trim($m['artista'])); ?>')">
                    <img src="https://picsum.photos/seed/<?php echo $m['seed']; ?>/200" alt="<?php echo $m['titulo']; ?>">
                    <button class="play-overlay"><i class="fas fa-play"></i></button>
                    <div class="card-body">
                        <h4><?php echo $m['titulo']; ?></h4>
                        <p><?php echo $m['artista']; ?> • <span class="score-badge"><?php echo $m['score']; ?></span></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Artistas Recomendados -->
    <section class="mb-5 fade-in">
        <div class="section-header">
            <h2>Artistas Recomendados</h2>
            <a href="#" class="see-all" data-bs-toggle="modal" data-bs-target="#modalArtistas">Ver tudo</a>
        </div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
            <?php
            $artistas = [
                ['seed' => 'art1', 'nome' => 'The Weeknd'],
                ['seed' => 'art2', 'nome' => 'Tame Impala'],
                ['seed' => 'art3', 'nome' => 'Dua Lipa'],
            ];
            foreach ($artistas as $a): ?>
            <div class="col">
                <div class="artist-card" onclick="abrirArtista('<?php echo $a['seed']; ?>','<?php echo addslashes($a['nome']); ?>')">
                    <img src="https://picsum.photos/seed/<?php echo $a['seed']; ?>/200" alt="<?php echo $a['nome']; ?>">
                    <h4><?php echo $a['nome']; ?></h4>
                    <p><i class="fas fa-circle-check text-success me-1" style="font-size:.7rem"></i>Artista Verificado</p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<!-- Modal Artista -->
<div class="modal fade" id="modalArtista" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
        <div class="modal-content" style="background:rgba(15,15,25,0.97);border:1px solid rgba(255,255,255,0.1);border-radius:20px;color:#fff;">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-0 px-4 pb-4">
                <!-- Foto centralizada -->
                <div class="text-center mb-3">
                    <img id="art-foto" src="" alt="" style="width:180px;height:180px;border-radius:12px;object-fit:cover;box-shadow:0 4px 24px rgba(0,0,0,0.4);">
                    <h4 id="art-nome" class="mt-3 mb-0 fw-bold"></h4>
                    <p style="font-size:0.78rem;color:#b3b3b3;"><i class="fas fa-circle-check text-success me-1" style="font-size:.7rem"></i>Artista Verificado</p>
                </div>
                <!-- Músicas -->
                <p class="mb-2" style="font-size:0.8rem;color:#b3b3b3;text-transform:uppercase;letter-spacing:1px;">Músicas populares</p>
                <div id="art-musicas" class="mb-4"></div>
                <!-- Álbuns -->
                <p class="mb-2" style="font-size:0.8rem;color:#b3b3b3;text-transform:uppercase;letter-spacing:1px;">Álbuns</p>
                <div id="art-albuns" class="d-flex gap-3 flex-wrap"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver Tudo - Artistas Recomendados -->
<div class="modal fade" id="modalArtistas" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content" style="background:#0a0a0a; color:#fff;">
            <div class="modal-header" style="border-bottom:1px solid rgba(255,255,255,0.1);">
                <h5 class="modal-title fw-bold"><i class="fas fa-microphone me-2" style="color:#7c4dff;"></i>Artistas Recomendados</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="overflow-y:auto;">
                <div class="row row-cols-2 row-cols-md-4 row-cols-lg-5 g-3">
                    <?php
                    $artistasAll = [
                        ['seed' => 'art1', 'nome' => 'The Weeknd'],
                        ['seed' => 'art2', 'nome' => 'Tame Impala'],
                        ['seed' => 'art3', 'nome' => 'Dua Lipa'],
                    ];
                    foreach ($artistasAll as $a): ?>
                    <div class="col">
                        <div class="artist-card">
                            <img src="https://picsum.photos/seed/<?php echo $a['seed']; ?>/200" alt="<?php echo $a['nome']; ?>">
                            <h4><?php echo $a['nome']; ?></h4>
                            <p><i class="fas fa-circle-check text-success me-1" style="font-size:.7rem"></i>Artista Verificado</p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver Tudo - Avaliadas Recentemente -->
<div class="modal fade" id="modalAvaliadasRecente" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content" style="background:#0a0a0a; color:#fff;">
            <div class="modal-header" style="border-bottom:1px solid rgba(255,255,255,0.1);">
                <h5 class="modal-title fw-bold"><i class="fas fa-clock me-2" style="color:#7c4dff;"></i>Avaliadas Recentemente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="overflow-y:auto;">
                <div class="row row-cols-2 row-cols-md-4 row-cols-lg-5 g-3">
                    <?php
                    $avaliadasAll = [
                        ['seed' => 'music1', 'titulo' => 'Midnight City',   'artista' => 'M83',         'score' => '9.8'],
                        ['seed' => 'music2', 'titulo' => 'Blinding Lights', 'artista' => 'The Weeknd',  'score' => '9.5'],
                        ['seed' => 'music3', 'titulo' => 'Breathe Deeper',  'artista' => 'Tame Impala', 'score' => '9.2'],
                        ['seed' => 'music4', 'titulo' => 'Levitating',      'artista' => 'Dua Lipa',    'score' => '9.0'],
                    ];
                    foreach ($avaliadasAll as $m): ?>
                    <div class="col">
                        <div class="music-card">
                            <img src="https://picsum.photos/seed/<?php echo $m['seed']; ?>/200" alt="<?php echo $m['titulo']; ?>">
                            <button class="play-overlay"><i class="fas fa-play"></i></button>
                            <div class="card-body">
                                <h4><?php echo $m['titulo']; ?></h4>
                                <p><?php echo $m['artista']; ?> &bull; <span class="score-badge"><?php echo $m['score']; ?></span></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver Tudo - Recomendações -->
<div class="modal fade" id="modalRecomendacoes" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content" style="background:#0a0a0a; color:#fff;">
            <div class="modal-header" style="border-bottom:1px solid rgba(255,255,255,0.1);">
                <h5 class="modal-title fw-bold"><i class="fas fa-music me-2" style="color:#7c4dff;"></i>Recomendações Para Você</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="overflow-y:auto;">
                <div class="row row-cols-2 row-cols-md-4 row-cols-lg-5 g-3">
                    <?php
                    $musicasAll = [
                        ['seed' => 'music1',  'titulo' => 'Bohemian Rhapsody',      'artista' => 'Queen',            'score' => '9.8'],
                        ['seed' => 'music2',  'titulo' => 'Imagine',                'artista' => 'John Lennon',       'score' => '9.5'],
                        ['seed' => 'music3',  'titulo' => 'Billie Jean',             'artista' => 'Michael Jackson',  'score' => '9.2'],
                        ['seed' => 'music4',  'titulo' => 'Smells Like Teen Spirit', 'artista' => 'Nirvana',          'score' => '9.5'],
                        ['seed' => 'music5',  'titulo' => 'Hotel California',        'artista' => 'Eagles',           'score' => '6.5'],
                        ['seed' => 'music6',  'titulo' => 'Shape of You',            'artista' => 'Ed Sheeran',       'score' => '6.7'],
                        ['seed' => 'music7',  'titulo' => 'Rolling in the Deep',     'artista' => 'Adele',            'score' => '8.9'],
                        ['seed' => 'music8',  'titulo' => 'Hey Jude',                'artista' => 'The Beatles',      'score' => '9.9'],
                        ['seed' => 'music9',  'titulo' => 'Like a Prayer',           'artista' => 'Madonna',          'score' => '8.3'],
                        ['seed' => 'music10', 'titulo' => 'Wonderwall',              'artista' => 'Oasis',            'score' => '8.0'],
                    ];
                    foreach ($musicasAll as $m): ?>
                    <div class="col">
                        <div class="music-card">
                            <img src="https://picsum.photos/seed/<?php echo $m['seed']; ?>/200" alt="<?php echo $m['titulo']; ?>">
                            <button class="play-overlay"><i class="fas fa-play"></i></button>
                            <div class="card-body">
                                <h4><?php echo $m['titulo']; ?></h4>
                                <p><?php echo $m['artista']; ?> • <span class="score-badge"><?php echo $m['score']; ?></span></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Avaliação -->
<div class="modal fade" id="modalAvaliar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content">
            <div class="avaliar-header">
                <img id="av-capa" src="" alt="capa">
                <div class="info">
                    <h5 id="av-titulo"></h5>
                    <p id="av-artista"></p>
                </div>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:20px;">
                <!-- Estrelas -->
                <p class="mb-1" style="font-size:0.8rem;color:#b3b3b3;">Sua nota</p>
                <div class="star-rating mb-3" id="stars">
                    <span data-v="1">&#9733;</span>
                    <span data-v="2">&#9733;</span>
                    <span data-v="3">&#9733;</span>
                    <span data-v="4">&#9733;</span>
                    <span data-v="5">&#9733;</span>
                </div>
                <!-- Comentário -->
                <p class="mb-2" style="font-size:0.8rem;color:#b3b3b3;">Comentário</p>
                <textarea id="av-comentario" class="comentario-textarea" rows="3"
                    placeholder="Ex: Uma das melhores músicas dos anos 90, marcante e poderosa..."></textarea>
                <button class="btn-avaliar-enviar mt-3" onclick="enviarAvaliacao()">Enviar Avaliação</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Comentário (Bootstrap) -->
<div class="modal fade" id="modalComentario" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background:rgba(25,25,35,0.97); border:1px solid rgba(255,255,255,0.12); border-radius:16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-white"><i class="fas fa-comment me-2"></i>Comentar</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Midnight City — M83</p>
                <textarea id="texto-comentario" rows="5" class="form-control"
                    placeholder="Escreva seu comentário..."
                    style="background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.15); color:#fff; border-radius:10px; resize:none;"></textarea>
            </div>
            <div class="modal-footer border-0 pt-0 gap-2">
                <button onclick="enviarComentario()" class="btn flex-fill" style="background:linear-gradient(135deg,#7c4dff,#4fc3f7); color:#fff; border-radius:20px; font-weight:600;">
                    <i class="fas fa-paper-plane me-2"></i>Enviar
                </button>
                <button data-bs-dismiss="modal" class="btn flex-fill" style="background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.15); color:rgba(255,255,255,0.7); border-radius:20px;">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Footer Slideshow -->
<footer class="player">
    <img id="slideshow-img" src="https://img.freepik.com/vetores-premium/um-cartaz-de-publicidade-musical-com-um-disco-de-vinil-e-notas-musicais-em-uma-ilustracao-vetorial-isolada_606304-808.jpg" alt="banner">
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Verificar se é admin
    (async () => {
        const sessao = await fetch('api/auth/session.php').then(r => r.json()).catch(() => null);
        if (sessao?.perfil === 'admin') {
            document.getElementById('btn-admin').style.display = 'inline-block';
        }
    })();

    // Bootstrap tooltips
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

    // Slideshow footer
    const slides = [
        'https://img.freepik.com/vetores-premium/um-cartaz-de-publicidade-musical-com-um-disco-de-vinil-e-notas-musicais-em-uma-ilustracao-vetorial-isolada_606304-808.jpg',
        'https://png.pngtree.com/thumb_back/fh260/background/20221224/pngtree-blue-musical-notes-background-image_1530362.jpg',
        'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT_5qs5i8zvZ2TQptBz3UOxKhFS-Te9heBoDA&s',
        'https://thumbs.dreamstime.com/b/grande-nota-musical-%C3%A9-uma-trepada-sobre-fundo-abstrato-para-publicidade-em-lojas-e-est%C3%BAdios-de-m%C3%BAsica-gerada-por-ai-334831956.jpg'
    ];
    let current = 0;
    const img = document.getElementById('slideshow-img');
    setInterval(() => {
        img.style.opacity = '0';
        setTimeout(() => {
            current = (current + 1) % slides.length;
            img.src = slides[current];
            img.style.opacity = '1';
        }, 1000);
    }, 30000);

    // Curtir toggle
    function toggleCurtir(btn) {
        const curtido = btn.classList.toggle('curtido');
        btn.style.borderColor = curtido ? '#7c4dff' : '';
        btn.style.color       = curtido ? '#7c4dff' : '';
        btn.innerHTML = curtido
            ? '<i class="fas fa-heart me-2"></i>Curtido'
            : '<i class="far fa-heart me-2"></i>Curtir';
    }

    // Enviar comentário
    function enviarComentario() {
        const texto = document.getElementById('texto-comentario').value.trim();
        if (!texto) return;
        bootstrap.Modal.getInstance(document.getElementById('modalComentario')).hide();
        document.getElementById('texto-comentario').value = '';
    }

    // Ícones dinâmicos por gênero
    const genreIcons = {
        'hip-hop': '🎤', 'hiphop': '🎤', 'rap': '🎤', 'trap': '🎤',
        'jazz': '🎷', 'blues': '🎸', 'rock': '🎸', 'metal': '🤘',
        'pop': '🎵', 'kpop': '💜', 'k-pop': '💜',
        'eletrônica': '🎧', 'eletronica': '🎧', 'eletro': '🎧',
        'mpb': '🇧🇷', 'sertanejo': '🤠', 'forró': '🪗', 'forro': '🪗',
        'funk': '🔥', 'reggae': '🌿', 'clássica': '🎻', 'classica': '🎻',
        'lo-fi': '☕', 'lofi': '☕', 'country': '🪕', 'cristã': '✝️', 'crista': '✝️',
    };
    document.querySelectorAll('.genre-icon').forEach(el => {
        const key = el.dataset.genre.toLowerCase().trim();
        el.textContent = Object.entries(genreIcons).find(([k]) => key.includes(k))?.[1] ?? '🎶';
    });

    // Fade-in ao rolar
    const observer = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));

    // Modal Avaliação
    document.querySelectorAll('#stars span').forEach(star => {
        star.addEventListener('click', function() {
            const val = +this.dataset.v;
            document.querySelectorAll('#stars span').forEach(s => s.classList.toggle('active', +s.dataset.v <= val));
        });
    });
    function toggleChip(el) { el.classList.toggle('selected'); }

    const artistaData = {
        'art1': {
            musicas: ['Blinding Lights','Starboy','Save Your Tears','Die For You','The Hills'],
            albuns:  [{seed:'alb1',nome:'After Hours'},{seed:'alb2',nome:'Starboy'},{seed:'alb3',nome:'Kiss Land'}]
        },
        'art2': {
            musicas: ['The Less I Know The Better','Feels Like We Only Go Backwards','Let It Happen','Eventually','New Person'],
            albuns:  [{seed:'alb4',nome:'Currents'},{seed:'alb5',nome:'Lonerism'},{seed:'alb6',nome:'Innerspeaker'}]
        },
        'art3': {
            musicas: ['Levitating','Don\'t Start Now','Physical','Break My Heart','New Rules'],
            albuns:  [{seed:'alb7',nome:'Future Nostalgia'},{seed:'alb8',nome:'Radical Optimism'},{seed:'alb9',nome:'Dua Lipa'}]
        }
    };
    function abrirArtista(seed, nome) {
        const d = artistaData[seed] || { musicas: [], albuns: [] };
        document.getElementById('art-foto').src = 'https://picsum.photos/seed/' + seed + '/200';
        document.getElementById('art-nome').textContent = nome;
        document.getElementById('art-musicas').innerHTML = d.musicas.map((m, i) =>
            `<div style="display:flex;align-items:center;gap:12px;padding:8px 0;border-bottom:1px solid rgba(255,255,255,0.06);">
                <span style="color:#b3b3b3;font-size:0.8rem;width:16px;text-align:right;">${i+1}</span>
                <i class="fas fa-music" style="color:#7c4dff;font-size:0.75rem;"></i>
                <span style="font-size:0.88rem;">${m}</span>
            </div>`
        ).join('');
        document.getElementById('art-albuns').innerHTML = d.albuns.map(a =>
            `<div style="text-align:center;width:90px;">
                <img src="https://picsum.photos/seed/${a.seed}/200" style="width:80px;height:80px;border-radius:8px;object-fit:cover;margin-bottom:6px;">
                <p style="font-size:0.72rem;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${a.nome}</p>
            </div>`
        ).join('');
        new bootstrap.Modal(document.getElementById('modalArtista')).show();
    }
    function abrirAvaliar(seed, titulo, artista) {
        document.getElementById('av-capa').src = 'https://picsum.photos/seed/' + seed + '/200';
        document.getElementById('av-titulo').textContent = titulo;
        document.getElementById('av-artista').textContent = artista;
        document.getElementById('av-comentario').value = '';
        document.querySelectorAll('#stars span').forEach(s => s.classList.remove('active'));
        new bootstrap.Modal(document.getElementById('modalAvaliar')).show();
    }
    function enviarAvaliacao() {
        bootstrap.Modal.getInstance(document.getElementById('modalAvaliar')).hide();
    }
</script>
</body>
</html>

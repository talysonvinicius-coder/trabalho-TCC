<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundScore - Perfil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/navbarPag.css">
    <style>
        :root {
            --accent: #7c4dff;
            --accent-2: #4fc3f7;
            --bg-dark: #0a0a0a;
            --bg-card: #181818;
            --bg-card-hover: #222232;
            --text-grey: #b3b3b3;
        }

        body { background: var(--bg-dark); color: #fff; font-family: 'Segoe UI', sans-serif; display: flex; height: 100vh; overflow: hidden; }
        main.content { margin-left: 240px; width: calc(100% - 240px); overflow-y: auto; padding: 0 0 80px; }

        /* ── Banner de capa ── */
        .profile-cover {
            width: 100%;
            height: 180px;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            background-size: 200% 200%;
            animation: gradientShift 8s ease infinite;
            position: relative;
        }
        @keyframes gradientShift {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .profile-cover::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 60% 50%, rgba(124,77,255,0.25), transparent 70%);
        }

        /* ── Cabeçalho do perfil ── */
        .profile-header {
            padding: 0 32px 24px;
            display: flex;
            align-items: flex-end;
            gap: 24px;
            margin-top: -60px;
            position: relative;
            z-index: 1;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            border: 4px solid var(--bg-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            flex-shrink: 0;
            box-shadow: 0 8px 32px rgba(124,77,255,0.4);
        }

        .profile-info { padding-bottom: 8px; flex: 1; }

        .profile-info h1 {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 2px;
        }

        .profile-info .profile-username {
            color: var(--text-grey);
            font-size: 0.88rem;
            margin-bottom: 12px;
        }

        /* ── Stats (seguidores/seguindo) ── */
        .profile-stats {
            display: flex;
            gap: 24px;
        }

        .stat-item { text-align: center; cursor: pointer; }

        .stat-item .stat-num {
            font-size: 1.2rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: block;
        }

        .stat-item .stat-label {
            font-size: 0.75rem;
            color: var(--text-grey);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-item:hover .stat-num { filter: brightness(1.2); }

        /* Botão editar perfil */
        .btn-edit-profile {
            margin-left: auto;
            padding-bottom: 8px;
            align-self: flex-end;
        }

        .btn-edit-profile button {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.15);
            color: #fff;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, border-color 0.2s;
        }

        .btn-edit-profile button:hover {
            background: rgba(124,77,255,0.2);
            border-color: rgba(124,77,255,0.5);
        }

        /* ── Conteúdo principal ── */
        .profile-body { padding: 0 32px; }

        /* Section header */
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .section-header h2 { font-size: 1.15rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px; }
        .section-header h2 i { color: var(--accent); }
        .see-all { color: var(--text-grey); font-size: 0.78rem; text-decoration: none; text-transform: uppercase; letter-spacing: 1px; transition: color 0.2s; }
        .see-all:hover { color: #fff; }

        /* ── Cards de comentário ── */
        .comment-card {
            background: var(--bg-card);
            border-radius: 12px;
            padding: 16px;
            border: 1px solid rgba(255,255,255,0.05);
            transition: background 0.3s, border-color 0.3s;
        }
        .comment-card:hover { background: var(--bg-card-hover); border-color: rgba(124,77,255,0.2); }

        .comment-card .comment-music {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .comment-cover {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .comment-music-info h5 { font-size: 0.88rem; font-weight: 600; margin: 0; }
        .comment-music-info span { font-size: 0.75rem; color: var(--text-grey); }

        .comment-text {
            font-size: 0.85rem;
            color: #ddd;
            line-height: 1.5;
            margin-bottom: 10px;
        }

        .comment-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .comment-date { font-size: 0.72rem; color: var(--text-grey); }

        .comment-stars i { font-size: 0.7rem; color: #ffc107; }

        /* ── Cards de playlist ── */
        .playlist-card {
            background: var(--bg-card);
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.05);
            transition: background 0.3s, transform 0.3s, border-color 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }
        .playlist-card:hover {
            background: var(--bg-card-hover);
            transform: translateY(-4px);
            border-color: rgba(124,77,255,0.3);
            box-shadow: 0 8px 24px rgba(124,77,255,0.2);
        }
        .playlist-cover {
            width: 100%;
            aspect-ratio: 1;
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            position: relative;
            overflow: hidden;
        }
        .playlist-cover::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 60% 40%, rgba(124,77,255,0.2), transparent 70%);
        }
        .playlist-card:hover .playlist-cover::after {
            background: radial-gradient(circle at 60% 40%, rgba(124,77,255,0.35), transparent 70%);
        }
        .playlist-info { padding: 8px 10px; }
        .playlist-info h5 { font-size: 0.8rem; font-weight: 700; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .playlist-info span { font-size: 0.7rem; color: var(--text-grey); display: block; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .playlist-count { font-size: 0.68rem; color: #c4a8ff; margin: 0; }

        /* Fade-in */
        .fade-in { opacity: 0; transform: translateY(20px); transition: opacity 0.5s ease, transform 0.5s ease; }
        .fade-in.visible { opacity: 1; transform: translateY(0); }

        /* Footer slideshow */
        .player { padding: 0; overflow: hidden; height: 50px; position: fixed; bottom: 0; left: 240px; width: calc(100% - 240px); z-index: 40; }
        .player img { width: 100%; height: 100%; object-fit: cover; display: block; transition: opacity 1s ease; }
    </style>
</head>
<body>

<?php include __DIR__ . '/navbarPag.php'; ?>

<main class="content">

    <!-- Capa -->
    <div class="profile-cover"></div>

    <!-- Header do perfil -->
    <div class="profile-header fade-in">
        <div class="profile-avatar" id="profile-avatar">
            <i class="fas fa-user"></i>
        </div>
        <div class="profile-info">
            <h1 id="profile-name">Carregando...</h1>
            <p class="profile-username" id="profile-username" style="margin-bottom: 8px;">@usuario</p>
            <?php if ($isPremium): ?>
                <span style="font-size:0.72rem; font-weight:700; padding:4px 12px; border-radius:20px; background:linear-gradient(135deg,rgba(124,77,255,0.2),rgba(79,195,247,0.1)); border:1px solid rgba(124,77,255,0.3); color:#c4a8ff; display:inline-block; margin-bottom: 12px;">
                    <i class="fas fa-crown me-1"></i>Premium
                </span>
            <?php else: ?>
                <div style="margin-bottom: 12px;"></div>
            <?php endif; ?>
            <div class="profile-stats">
                <div class="stat-item">
                    <span class="stat-num">248</span>
                    <span class="stat-label">Seguidores</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num">91</span>
                    <span class="stat-label">Seguindo</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num">34</span>
                    <span class="stat-label">Avaliações</span>
                </div>
            </div>
        </div>
        <div class="btn-edit-profile">
            <button><i class="fas fa-pen me-2"></i>Editar perfil</button>
        </div>
    </div>

    <div class="profile-body">

        <!-- Comentários recentes -->
        <section class="mb-5 fade-in">
            <div class="section-header">
                <h2><i class="fas fa-comment-dots"></i> Comentários recentes</h2>
                <a href="#" class="see-all">Ver todos</a>
            </div>
            <div class="row row-cols-1 row-cols-md-2 g-3">

                <?php
                $comentarios = [
                    ['musica' => 'Midnight City', 'artista' => 'M83',         'nota' => 5, 'texto' => 'Uma das melhores músicas eletrônicas já feitas. A intro é simplesmente icônica.', 'data' => 'há 2 dias',   'nota_emoji' => '🎧'],
                    ['musica' => 'Blinding Lights','artista' => 'The Weeknd',  'nota' => 4, 'texto' => 'Synthwave perfeito. The Weeknd no seu melhor momento criativo.',                'data' => 'há 5 dias',   'nota_emoji' => '🎵'],
                    ['musica' => 'Breathe Deeper', 'artista' => 'Tame Impala', 'nota' => 5, 'texto' => 'Kevin Parker é um gênio. Cada camada dessa música é incrível.',               'data' => 'há 1 semana', 'nota_emoji' => '🎶'],
                    ['musica' => 'Levitating',     'artista' => 'Dua Lipa',    'nota' => 3, 'texto' => 'Boa música pop, mas esperava mais da Dua Lipa nesse álbum.',                   'data' => 'há 2 semanas','nota_emoji' => '🎤'],
                ];
                foreach ($comentarios as $c): ?>
                <div class="col">
                    <div class="comment-card">
                        <div class="comment-music">
                            <div class="comment-cover"><?php echo $c['nota_emoji']; ?></div>
                            <div class="comment-music-info">
                                <h5><?php echo $c['musica']; ?></h5>
                                <span><?php echo $c['artista']; ?></span>
                            </div>
                        </div>
                        <p class="comment-text">"<?php echo $c['texto']; ?>"</p>
                        <div class="comment-meta">
                            <span class="comment-date"><i class="fas fa-clock me-1"></i><?php echo $c['data']; ?></span>
                            <div class="comment-stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="<?php echo $i <= $c['nota'] ? 'fas' : 'far'; ?> fa-star"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        </section>

        <!-- Listas criadas -->
        <section class="mb-5 fade-in">
            <div class="section-header">
                <h2><i class="fas fa-list-music"></i> Minhas listas</h2>
                <a href="#" class="see-all">Ver todas</a>
            </div>
            <div class="row row-cols-3 row-cols-md-4 row-cols-lg-6 g-3">

                <?php
                $notas_capa = ['🎵', '🎶', '🎼', '🎹', '🥁', '🎸'];
                $listas = [
                    ['nome' => 'Favoritas de 2025',  'qtd' => 12, 'desc' => 'As melhores do ano'],
                    ['nome' => 'Lo-fi para estudar', 'qtd' => 8,  'desc' => 'Foco total'],
                    ['nome' => 'Rock clássico',      'qtd' => 15, 'desc' => 'Clássicos imortais'],
                    ['nome' => 'Noite de sexta',     'qtd' => 10, 'desc' => 'Energia total'],
                    ['nome' => 'MPB essencial',      'qtd' => 9,  'desc' => 'Raízes brasileiras'],
                    ['nome' => 'Indie vibes',        'qtd' => 7,  'desc' => 'Sons alternativos'],
                ];
                foreach ($listas as $i => $l): ?>
                <div class="col">
                    <div class="playlist-card">
                        <div class="playlist-cover">
                            <?php echo $notas_capa[$i % count($notas_capa)]; ?>
                        </div>
                        <div class="playlist-info">
                            <h5><?php echo $l['nome']; ?></h5>
                            <span><?php echo $l['desc']; ?></span>
                            <p class="playlist-count"><i class="fas fa-music me-1"></i><?php echo $l['qtd']; ?> músicas</p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        </section>

    </div>
</main>

<!-- Footer Slideshow -->
<footer class="player">
    <img id="slideshow-img" src="https://img.freepik.com/vetores-premium/um-cartaz-de-publicidade-musical-com-um-disco-de-vinil-e-notas-musicais-em-uma-ilustracao-vetorial-isolada_606304-808.jpg" alt="banner">
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>
<script>
    // Carregar nome do usuário da sessão
    (async () => {
        const sessao = await verificarSessao();
        if (sessao) {
            document.getElementById('profile-name').textContent = sessao.nome ?? 'Usuário';
            document.getElementById('profile-username').textContent = '@' + (sessao.nome ?? 'usuario').toLowerCase().replace(/\s+/g, '');
        }
    })();

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

    // Fade-in
    const observer = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
</script>
</body>
</html>

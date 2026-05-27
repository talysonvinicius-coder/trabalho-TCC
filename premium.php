<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundScore - Premium</title>
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
        main.content { margin-left: 240px; width: calc(100% - 240px); overflow-y: auto; padding: 20px 32px 80px; }

        /* Top bar */
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; }
        .search-bar-top { display: flex; align-items: center; background: #2a2a2a; border-radius: 20px; padding: 8px 16px; gap: 10px; width: 280px; transition: background 0.2s; }
        .search-bar-top:focus-within { background: #333; }
        .search-bar-top i { color: var(--text-grey); font-size: 0.85rem; }
        .search-bar-top input { background: none; border: none; outline: none; color: #fff; font-size: 0.85rem; width: 100%; }
        .search-bar-top input::placeholder { color: var(--text-grey); }

        /* Tabs de navegação */
        .tabs { display: flex; gap: 4px; margin-bottom: 28px; background: rgba(255,255,255,0.04); border-radius: 12px; padding: 4px; width: fit-content; }
        .tab-btn {
            padding: 8px 20px;
            border-radius: 9px;
            border: none;
            background: transparent;
            color: var(--text-grey);
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
        }
        .tab-btn.ativo { background: linear-gradient(135deg, var(--accent), var(--accent-2)); color: #fff; }
        .tab-btn:hover:not(.ativo) { color: #fff; background: rgba(255,255,255,0.06); }

        /* Páginas */
        .page { display: none; animation: fadeIn 0.35s ease; }
        .page.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

        /* Section header */
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .section-header h2 { font-size: 1.3rem; font-weight: 700; margin: 0; }

        /* Banner hero premium */
        .premium-hero {
            border-radius: 16px;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            background-size: 200% 200%;
            animation: gradientShift 8s ease infinite;
            padding: 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            position: relative;
            overflow: hidden;
            margin-bottom: 32px;
        }
        @keyframes gradientShift { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        .premium-hero::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse at 70% 50%, rgba(124,77,255,0.2), transparent 60%); }
        .premium-hero-info { position: relative; z-index: 1; }
        .premium-hero-info .tag { background: linear-gradient(135deg, rgba(124,77,255,0.85), rgba(79,195,247,0.85)); color: #fff; font-size: 0.72rem; font-weight: 700; padding: 4px 12px; border-radius: 20px; display: inline-block; margin-bottom: 12px; }
        .premium-hero-info h1 { font-size: 2rem; font-weight: 800; margin-bottom: 6px; }
        .premium-hero-info p { color: var(--text-grey); font-size: 0.9rem; margin-bottom: 20px; }
        .premium-hero-icon { font-size: 5rem; position: relative; z-index: 1; filter: drop-shadow(0 0 20px rgba(124,77,255,0.5)); }

        /* Benefícios */
        .beneficios-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px; margin-bottom: 32px; }
        .beneficio-card {
            background: var(--bg-card);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid rgba(255,255,255,0.05);
            transition: background 0.3s, transform 0.3s, border-color 0.3s;
        }
        .beneficio-card:hover { background: var(--bg-card-hover); transform: translateY(-4px); border-color: rgba(124,77,255,0.25); }
        .beneficio-icon { width: 44px; height: 44px; border-radius: 10px; background: linear-gradient(135deg, rgba(124,77,255,0.2), rgba(79,195,247,0.1)); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; margin-bottom: 12px; border: 1px solid rgba(124,77,255,0.2); }
        .beneficio-card h5 { font-size: 0.92rem; font-weight: 700; margin-bottom: 4px; }
        .beneficio-card p { font-size: 0.78rem; color: var(--text-grey); margin: 0; }

        /* Mix diário */
        .mix-card {
            background: var(--bg-card);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid rgba(255,255,255,0.05);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: background 0.3s, border-color 0.3s;
            cursor: pointer;
        }
        .mix-card:hover { background: var(--bg-card-hover); border-color: rgba(124,77,255,0.2); }
        .mix-cover { width: 56px; height: 56px; border-radius: 10px; background: linear-gradient(135deg, #1a1a2e, #16213e); display: flex; align-items: center; justify-content: center; font-size: 1.6rem; flex-shrink: 0; }
        .mix-info h5 { font-size: 0.95rem; font-weight: 600; margin-bottom: 3px; }
        .mix-info p { font-size: 0.78rem; color: var(--text-grey); margin: 0; }
        .mix-play { margin-left: auto; width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, var(--accent), var(--accent-2)); border: none; color: #fff; font-size: 0.85rem; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; transition: filter 0.2s, transform 0.2s; }
        .mix-play:hover { filter: brightness(1.15); transform: scale(1.08); }

        /* Playlists */
        .btn-nova-playlist {
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            border: none;
            color: #fff;
            padding: 8px 18px;
            border-radius: 20px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: filter 0.2s, transform 0.2s;
        }
        .btn-nova-playlist:hover { filter: brightness(1.12); transform: scale(1.03); }

        .playlist-item {
            background: var(--bg-card);
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 8px;
            border: 1px solid rgba(255,255,255,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.2s, border-color 0.2s;
        }
        .playlist-item:hover { background: var(--bg-card-hover); border-color: rgba(124,77,255,0.2); }
        .playlist-item span { font-size: 0.9rem; font-weight: 500; }
        .tag-premium { font-size: 0.68rem; background: linear-gradient(135deg, #fbbf24, #f59e0b); color: #000; font-weight: 700; padding: 2px 8px; border-radius: 10px; margin-left: 8px; }
        .btn-delete { background: none; border: none; color: var(--text-grey); cursor: pointer; font-size: 1.1rem; transition: color 0.2s, transform 0.2s; padding: 4px 8px; border-radius: 6px; }
        .btn-delete:hover { color: #ef5350; transform: scale(1.15); }

        /* Fade-in */
        .fade-in { opacity: 0; transform: translateY(20px); transition: opacity 0.5s ease, transform 0.5s ease; }
        .fade-in.visible { opacity: 1; transform: translateY(0); }

        /* Footer */
        .player { padding: 0; overflow: hidden; height: 50px; position: fixed; bottom: 0; left: 240px; width: calc(100% - 240px); z-index: 40; }
        .player img { width: 100%; height: 100%; object-fit: cover; display: block; transition: opacity 1s ease; }
    </style>
</head>
<body>

<?php include __DIR__ . '/navbarPag.php'; ?>

<main class="content">
    <header class="top-bar">
        <div class="search-bar-top">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Buscar no Premium...">
        </div>
        <div class="d-flex align-items-center gap-2">
            <span style="font-size:0.78rem; font-weight:700; padding:6px 14px; border-radius:20px; background:linear-gradient(135deg,rgba(124,77,255,0.2),rgba(79,195,247,0.1)); border:1px solid rgba(124,77,255,0.3); color:#c4a8ff;">
                <i class="fas fa-crown me-1"></i>Premium
            </span>
        </div>
    </header>

    <!-- Tabs -->
    <div class="tabs fade-in">
        <button class="tab-btn ativo" onclick="showPage('home', this)"><i class="fas fa-home me-1"></i>Início</button>
        <button class="tab-btn" onclick="showPage('playlists', this)"><i class="fas fa-list me-1"></i>Minhas Listas</button>
        <button class="tab-btn" onclick="showPage('premium-info', this)"><i class="fas fa-star me-1"></i>Benefícios</button>
    </div>

    <!-- Página: Início -->
    <div id="home" class="page active">
        <div class="premium-hero fade-in">
            <div class="premium-hero-info">
                <span class="tag">👑 Plano Premium Ativo</span>
                <h1>Olá, Usuário Premium</h1>
                <p>Aproveite música sem limites, sem anúncios e com qualidade máxima.</p>
                <button class="btn-nova-playlist" onclick="showPage('playlists', document.querySelectorAll('.tab-btn')[1])">
                    <i class="fas fa-list me-2"></i>Ver minhas listas
                </button>
            </div>
            <div class="premium-hero-icon">👑</div>
        </div>

        <section class="fade-in">
            <div class="section-header">
                <h2><i class="fas fa-fire me-2" style="color:#7c4dff;"></i>Mix Diário</h2>
            </div>
            <div class="d-flex flex-column gap-2">
                <?php
                $mixes = [
                    ['emoji' => '🎧', 'nome' => 'Mix Diário 1', 'desc' => 'Artistas recomendados para você'],
                    ['emoji' => '🎵', 'nome' => 'Descobertas da Semana', 'desc' => 'Novidades baseadas no seu gosto'],
                    ['emoji' => '🌙', 'nome' => 'Noite Tranquila', 'desc' => 'Lo-fi e sons relaxantes'],
                ];
                foreach ($mixes as $m): ?>
                <div class="mix-card">
                    <div class="mix-cover"><?php echo $m['emoji']; ?></div>
                    <div class="mix-info">
                        <h5><?php echo $m['nome']; ?></h5>
                        <p><?php echo $m['desc']; ?></p>
                    </div>
                    <button class="mix-play"><i class="fas fa-play"></i></button>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>

    <!-- Página: Playlists -->
    <div id="playlists" class="page">
        <section class="fade-in">
            <div class="section-header">
                <h2><i class="fas fa-list me-2" style="color:#7c4dff;"></i>Minhas Listas</h2>
                <button class="btn-nova-playlist" onclick="createPlaylist()">
                    <i class="fas fa-plus me-1"></i>Nova Lista
                </button>
            </div>
            <div id="playlist-list">
                <div class="playlist-item">
                    <div>
                        <span>Favoritas de 2024</span>
                        <span class="tag-premium">PREMIUM</span>
                    </div>
                    <button class="btn-delete" onclick="this.closest('.playlist-item').remove()"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        </section>
    </div>

    <!-- Página: Benefícios -->
    <div id="premium-info" class="page">
        <section class="fade-in">
            <div class="section-header">
                <h2><i class="fas fa-star me-2" style="color:#7c4dff;"></i>Seu Plano Premium</h2>
            </div>
            <div class="beneficios-grid">
                <?php
                $beneficios = [
                    ['icon' => 'fas fa-music',        'titulo' => 'Áudio 320kbps',         'desc' => 'Qualidade máxima de som em todas as faixas'],
                    ['icon' => 'fas fa-ban',           'titulo' => 'Sem anúncios',           'desc' => 'Ouça sem interrupções publicitárias'],
                    ['icon' => 'fas fa-download',      'titulo' => 'Downloads ilimitados',   'desc' => 'Salve músicas para ouvir offline'],
                    ['icon' => 'fas fa-list',          'titulo' => 'Listas ilimitadas',      'desc' => 'Crie quantas playlists quiser'],
                    ['icon' => 'fas fa-random',        'titulo' => 'Modo aleatório',         'desc' => 'Shuffle em qualquer playlist'],
                    ['icon' => 'fas fa-headphones',    'titulo' => 'Acesso antecipado',      'desc' => 'Ouça lançamentos antes de todos'],
                ];
                foreach ($beneficios as $b): ?>
                <div class="beneficio-card">
                    <div class="beneficio-icon"><i class="<?php echo $b['icon']; ?>" style="color:#7c4dff;"></i></div>
                    <h5><?php echo $b['titulo']; ?></h5>
                    <p><?php echo $b['desc']; ?></p>
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
<script>
    function showPage(pageId, btn) {
        document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('ativo'));
        document.getElementById(pageId).classList.add('active');
        btn.classList.add('ativo');
        // re-trigger fade-in
        document.querySelectorAll('#' + pageId + ' .fade-in').forEach(el => {
            el.classList.remove('visible');
            setTimeout(() => el.classList.add('visible'), 50);
        });
    }

    function createPlaylist() {
        const name = prompt('Nome da nova lista:');
        if (!name?.trim()) return;
        const item = document.createElement('div');
        item.className = 'playlist-item';
        item.innerHTML = `<div><span>${name}</span><span class="tag-premium">PREMIUM</span></div><button class="btn-delete" onclick="this.closest('.playlist-item').remove()"><i class="fas fa-trash"></i></button>`;
        document.getElementById('playlist-list').prepend(item);
    }

    // Slideshow
    const slides = [
        'https://img.freepik.com/vetores-premium/um-cartaz-de-publicidade-musical-com-um-disco-de-vinil-e-notas-musicais-em-uma-ilustracao-vetorial-isolada_606304-808.jpg',
        'https://png.pngtree.com/thumb_back/fh260/background/20221224/pngtree-blue-musical-notes-background-image_1530362.jpg',
        'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT_5qs5i8zvZ2TQptBz3UOxKhFS-Te9heBoDA&s',
    ];
    let current = 0;
    const img = document.getElementById('slideshow-img');
    setInterval(() => { img.style.opacity='0'; setTimeout(() => { current=(current+1)%slides.length; img.src=slides[current]; img.style.opacity='1'; }, 1000); }, 30000);

    // Fade-in
    const observer = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
</script>
</body>
</html>

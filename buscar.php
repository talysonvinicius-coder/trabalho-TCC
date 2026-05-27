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
    <title>SoundScore - Buscar</title>
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

        /* Top bar */
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; }
        .search-bar-top { display: flex; align-items: center; background: #2a2a2a; border-radius: 20px; padding: 8px 16px; gap: 10px; width: 340px; transition: background 0.2s; }
        .search-bar-top:focus-within { background: #333; }
        .search-bar-top i { color: var(--text-muted-custom); font-size: 0.85rem; }
        .search-bar-top input { background: none; border: none; outline: none; color: #fff; font-size: 0.85rem; width: 100%; }
        .search-bar-top input::placeholder { color: var(--text-muted-custom); }
        .user-controls { display: flex; align-items: center; gap: 14px; }
        .badge-upgrade { background: transparent; border: 1px solid #fff; color: #fff; font-size: 0.78rem; font-weight: 700; padding: 6px 14px; border-radius: 20px; text-decoration: none; transition: background 0.2s, color 0.2s; }
        .badge-upgrade:hover { background: #fff; color: #000; }

        /* Section header */
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .section-header h2 { font-size: 1.3rem; font-weight: 700; margin: 0; }

        /* Contador */
        .result-count { font-size: 0.82rem; color: var(--text-muted-custom); }
        .result-count span { color: #fff; font-weight: 700; }

        /* Genre Cards */
        .genre-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            padding: 22px 15px;
            text-align: center;
            transition: background 0.3s, transform 0.3s, border-color 0.3s, box-shadow 0.3s;
            cursor: pointer;
            text-decoration: none;
            display: block;
            color: #fff;
        }
        .genre-card:hover {
            background: rgba(124,77,255,0.12);
            transform: translateY(-4px);
            color: #fff;
            border-color: rgba(124,77,255,0.4);
            box-shadow: 0 8px 24px rgba(124,77,255,0.2);
        }
        .genre-card .genre-icon { font-size: 1.8rem; margin-bottom: 8px; display: block; }
        .genre-card .genre-name { font-size: 0.9rem; font-weight: 600; display: block; }

        /* Fade-in */
        .fade-in { opacity: 0; transform: translateY(20px); transition: opacity 0.5s ease, transform 0.5s ease; }
        .fade-in.visible { opacity: 1; transform: translateY(0); }

        /* Vazio */
        .empty-state { text-align: center; padding: 60px 20px; color: var(--text-muted-custom); }
        .empty-state .empty-icon { font-size: 3rem; margin-bottom: 12px; opacity: 0.4; }

        /* Footer slideshow */
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
            <input type="text" id="inputBusca" placeholder="Pesquisar gênero..." oninput="filtrar()">
        </div>
        <div class="user-controls">
            <a href="premium.php" class="badge-upgrade"><i class="fas fa-bolt me-1"></i>Upgrade</a>
        </div>
    </header>

    <section class="mb-5 fade-in">
        <div class="section-header">
            <h2><i class="fas fa-compass me-2" style="color:#7c4dff;"></i>Explorar Gêneros</h2>
            <span class="result-count"><span id="count"><?php echo count($generos); ?></span> gêneros</span>
        </div>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3" id="gradeGeneros">
            <?php if (count($generos) > 0): ?>
                <?php foreach ($generos as $g): ?>
                    <div class="col genre-col">
                        <a href="genero.php?id=<?php echo htmlspecialchars($g['id']); ?>" class="genre-card">
                            <span class="genre-icon" data-genre="<?php echo strtolower(htmlspecialchars($g['nome'])); ?>"></span>
                            <span class="genre-name"><?php echo htmlspecialchars($g['nome']); ?></span>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state" id="empty-state">
                    <div class="empty-icon">🎵</div>
                    <p>Nenhum gênero disponível.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="empty-state d-none" id="no-results">
            <div class="empty-icon">🔍</div>
            <p>Nenhum gênero encontrado para "<span id="termo-busca"></span>"</p>
        </div>
    </section>
</main>

<!-- Footer Slideshow -->
<footer class="player">
    <img id="slideshow-img" src="https://img.freepik.com/vetores-premium/um-cartaz-de-publicidade-musical-com-um-disco-de-vinil-e-notas-musicais-em-uma-ilustracao-vetorial-isolada_606304-808.jpg" alt="banner">
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
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

    // Filtro de busca
    function filtrar() {
        const input = document.getElementById('inputBusca').value.toLowerCase().trim();
        const cols   = document.querySelectorAll('.genre-col');
        const noRes  = document.getElementById('no-results');
        const countEl = document.getElementById('count');
        let visiveis = 0;

        cols.forEach(col => {
            const nome = col.querySelector('.genre-name').textContent.toLowerCase();
            const visivel = nome.includes(input);
            col.style.display = visivel ? '' : 'none';
            if (visivel) visiveis++;
        });

        countEl.textContent = visiveis;
        noRes.classList.toggle('d-none', visiveis > 0);
        if (visiveis === 0) document.getElementById('termo-busca').textContent = input;
    }

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

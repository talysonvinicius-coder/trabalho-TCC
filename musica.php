<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundScore - Cadastrar Música</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/navbarPag.css">
    <style>
        body { display: flex; height: 100vh; overflow: hidden; }
        .content { margin-left: 240px; width: calc(100% - 240px); overflow-y: auto; padding: 20px 32px 80px; }

        /* Top bar */
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; }
        .search-bar-top { display: flex; align-items: center; background: #2a2a2a; border-radius: 20px; padding: 8px 16px; gap: 10px; width: 280px; }
        .search-bar-top i { color: #b3b3b3; font-size: 0.85rem; }
        .search-bar-top input { background: none; border: none; outline: none; color: #fff; font-size: 0.85rem; width: 100%; }
        .search-bar-top input::placeholder { color: #b3b3b3; }

        /* Botão voltar */
        .btn-voltar { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 24px; padding: 9px 20px; border-radius: 25px; background: rgba(124,77,255,0.12); border: 1px solid rgba(124,77,255,0.35); color: #c4a8ff; font-size: 0.85rem; font-weight: 600; text-decoration: none; transition: background 0.2s, border-color 0.2s, transform 0.2s; }
        .btn-voltar:hover { background: rgba(124,77,255,0.25); border-color: #7c4dff; color: #fff; transform: translateX(-3px); }
        .btn-voltar i { transition: transform 0.2s; }
        .btn-voltar:hover i { transform: translateX(-3px); }

        /* Layout principal */
        .page-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            align-items: start;
        }

        /* Card do formulário */
        .form-card {
            background: #181818;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 16px;
            padding: 28px;
        }

        .form-card h2 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-card h2 i { color: #7c4dff; }

        .form-group { margin-bottom: 16px; }

        .form-group label {
            display: block;
            font-size: 0.78rem;
            color: #b3b3b3;
            margin-bottom: 7px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            color: #fff;
            padding: 11px 14px;
            font-size: 0.88rem;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: rgba(124,77,255,0.6);
            background: rgba(255,255,255,0.08);
        }

        .form-group select option { background: #1a1a2e; }
        .form-group textarea { resize: vertical; min-height: 80px; }

        /* Input com ícone de preview */
        .input-with-btn { display: flex; gap: 8px; }
        .input-with-btn input { flex: 1; }
        .btn-preview-inline {
            background: rgba(124,77,255,0.2);
            border: 1px solid rgba(124,77,255,0.4);
            color: #c4a8ff;
            border-radius: 10px;
            padding: 0 16px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.2s, color 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .btn-preview-inline:hover { background: rgba(124,77,255,0.4); color: #fff; }

        /* Linha dupla */
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

        /* Botão salvar */
        .btn-salvar {
            width: 100%;
            background: linear-gradient(135deg, #7c4dff, #4fc3f7);
            border: none;
            color: #fff;
            padding: 13px;
            border-radius: 25px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: filter 0.2s, transform 0.2s;
            margin-top: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-salvar:hover { filter: brightness(1.12); transform: scale(1.02); }
        .btn-salvar:disabled { opacity: 0.5; cursor: not-allowed; transform: none; filter: none; }

        /* Card de preview */
        .preview-card {
            background: #181818;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 16px;
            overflow: hidden;
            position: sticky;
            top: 0;
        }

        .preview-card-header {
            padding: 20px 24px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .preview-card-header h3 { font-size: 1rem; font-weight: 700; margin: 0; }
        .preview-card-header i { color: #7c4dff; }

        /* Container do YouTube */
        .youtube-container {
            position: relative;
            width: 100%;
            aspect-ratio: 16/9;
            background: #0a0a0a;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .youtube-container iframe {
            width: 100%;
            height: 100%;
            border: none;
            display: none;
        }

        .youtube-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            color: #555;
            height: 100%;
            width: 100%;
            padding: 30px;
            text-align: center;
        }

        .youtube-placeholder i { font-size: 3rem; color: #333; }
        .youtube-placeholder p { font-size: 0.82rem; margin: 0; line-height: 1.5; }

        /* Info do preview */
        .preview-info { padding: 20px 24px; }

        .preview-titulo {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 4px;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .preview-titulo.vazio { color: #555; font-style: italic; font-weight: 400; }

        .preview-meta { font-size: 0.82rem; color: #b3b3b3; display: flex; align-items: center; gap: 6px; flex-wrap: wrap; margin-bottom: 14px; }
        .preview-meta .dot { color: #555; }

        .preview-tags { display: flex; gap: 8px; flex-wrap: wrap; }
        .preview-tag {
            background: rgba(124,77,255,0.12);
            border: 1px solid rgba(124,77,255,0.25);
            color: #c4a8ff;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
        }
        .preview-tag.empty { background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.08); color: #555; }

        /* Toast */
        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 13px 20px;
            border-radius: 12px;
            font-size: 0.88rem;
            font-weight: 600;
            z-index: 9999;
            display: none;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.3s ease;
            box-shadow: 0 4px 20px rgba(0,0,0,0.4);
        }
        .toast-notification.show { display: flex; }
        .toast-notification.sucesso { background: linear-gradient(135deg, #1db954, #17a846); color: #fff; }
        .toast-notification.erro    { background: rgba(229,57,53,0.9); color: #fff; }
        @keyframes slideIn { from { transform: translateX(300px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

        /* Fade-in */
        .fade-in { opacity: 0; transform: translateY(20px); transition: opacity 0.5s ease, transform 0.5s ease; }
        .fade-in.visible { opacity: 1; transform: translateY(0); }

        /* Footer */
        .player { padding: 0; overflow: hidden; height: 50px; position: fixed; bottom: 0; left: 240px; width: calc(100% - 240px); z-index: 40; }
        .player img { width: 100%; height: 100%; object-fit: cover; display: block; transition: opacity 1s ease; }

        /* Responsive */
        @media (max-width: 900px) {
            .page-grid { grid-template-columns: 1fr; }
            .preview-card { position: static; }
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/navbar.php'; ?>

<main class="content">
    <header class="top-bar">
        <div class="search-bar-top">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Buscar...">
        </div>
        <div class="d-flex align-items-center gap-3">
            <span style="font-size:0.78rem; font-weight:700; padding:6px 14px; border-radius:20px; background:transparent; border:1px solid #fff; color:#fff;">
                <i class="fas fa-shield-alt me-1"></i>Admin
            </span>
        </div>
    </header>

    <a href="homeAdmin.php" class="btn-voltar fade-in">
        <i class="fas fa-arrow-left"></i> Voltar
    </a>

    <div class="page-grid fade-in">

        <!-- Formulário -->
        <div class="form-card">
            <h2><i class="fas fa-music"></i> Cadastrar Música</h2>

            <form id="form-musica" novalidate>

                <div class="form-group">
                    <label>Título da música <span style="color:#ef5350;">*</span></label>
                    <input type="text" id="titulo" placeholder="Ex: Blinding Lights" maxlength="150">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Artista <span style="color:#ef5350;">*</span></label>
                        <input type="text" id="artista" placeholder="Ex: The Weeknd" maxlength="150">
                    </div>
                    <div class="form-group">
                        <label>Álbum</label>
                        <input type="text" id="album" placeholder="Ex: After Hours" maxlength="150">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Gênero</label>
                        <select id="genero">
                            <option value="">Selecione...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Data de lançamento</label>
                        <input type="date" id="data_lancamento">
                    </div>
                </div>

                <div class="form-group">
                    <label>Duração <span style="font-size:0.72rem; color:#555;">(opcional)</span></label>
                    <input type="time" id="duracao" step="1">
                </div>

                <div class="form-group">
                    <label>Link do YouTube <span style="color:#ef5350;">*</span></label>
                    <div class="input-with-btn">
                        <input type="url" id="youtube_link" placeholder="https://www.youtube.com/watch?v=..." oninput="atualizarPreview()">
                        <button type="button" class="btn-preview-inline" onclick="atualizarPreview()">
                            <i class="fab fa-youtube"></i> Prévia
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-salvar" id="btn-salvar">
                    <i class="fas fa-plus"></i> Cadastrar Música
                </button>

            </form>
        </div>

        <!-- Preview -->
        <div class="preview-card">
            <div class="preview-card-header">
                <i class="fab fa-youtube" style="font-size:1.1rem;"></i>
                <h3>Prévia do vídeo</h3>
            </div>

            <div class="youtube-container" id="youtube-container">
                <iframe id="youtube-iframe" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                <div class="youtube-placeholder" id="youtube-placeholder">
                    <i class="fab fa-youtube"></i>
                    <p>Cole um link do YouTube no campo ao lado para visualizar o vídeo aqui.</p>
                </div>
            </div>

            <div class="preview-info">
                <div class="preview-titulo vazio" id="preview-titulo">Título da música</div>
                <div class="preview-meta">
                    <span id="preview-artista" style="color:#b3b3b3;">—</span>
                    <span class="dot" id="preview-dot-album" style="display:none;">•</span>
                    <span id="preview-album" style="display:none;"></span>
                </div>
                <div class="preview-tags">
                    <span class="preview-tag empty" id="preview-genero"><i class="fas fa-tag me-1"></i>Gênero</span>
                    <span class="preview-tag empty" id="preview-data"><i class="fas fa-calendar me-1"></i>Data</span>
                    <span class="preview-tag empty" id="preview-duracao"><i class="fas fa-clock me-1"></i>Duração</span>
                </div>
            </div>
        </div>

    </div>
</main>

<div class="toast-notification" id="toast">
    <i id="toast-icon" class="fas fa-check-circle"></i>
    <span id="toast-msg">Ação realizada</span>
</div>

<footer class="player">
    <img id="slideshow-img" src="https://img.freepik.com/vetores-premium/um-cartaz-de-publicidade-musical-com-um-disco-de-vinil_606304-808.jpg" alt="banner">
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>
<script>
    // ── Carregar gêneros do banco ──────────────────────────────────
    (async () => {
        try {
            const res = await fetch('api/categorias/listar.php');
            const data = await res.json();
            if (data.ok && data.categorias) {
                const sel = document.getElementById('genero');
                data.categorias
                    .filter(g => g.status == 1)
                    .forEach(g => {
                        const opt = document.createElement('option');
                        opt.value = g.id;
                        opt.textContent = g.nome;
                        sel.appendChild(opt);
                    });
            }
        } catch (_) {}
    })();

    // ── Extrair ID do YouTube ──────────────────────────────────────
    function extrairYoutubeId(url) {
        if (!url) return null;
        const regexes = [
            /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\n?#]+)/,
            /youtube\.com\/shorts\/([^&\n?#]+)/,
        ];
        for (const r of regexes) {
            const m = url.match(r);
            if (m) return m[1];
        }
        return null;
    }

    // ── Atualizar preview ──────────────────────────────────────────
    function atualizarPreview() {
        const url = document.getElementById('youtube_link').value.trim();
        const id  = extrairYoutubeId(url);
        const iframe      = document.getElementById('youtube-iframe');
        const placeholder = document.getElementById('youtube-placeholder');

        if (id) {
            iframe.src = `https://www.youtube.com/embed/${id}`;
            iframe.style.display = 'block';
            placeholder.style.display = 'none';
        } else {
            iframe.style.display = 'none';
            iframe.src = '';
            placeholder.style.display = 'flex';
        }

        // Infos do card
        const titulo  = document.getElementById('titulo').value.trim();
        const artista = document.getElementById('artista').value.trim();
        const album   = document.getElementById('album').value.trim();
        const generoEl = document.getElementById('genero');
        const generoNome = generoEl.options[generoEl.selectedIndex]?.text ?? '';
        const data    = document.getElementById('data_lancamento').value;
        const duracao = document.getElementById('duracao').value;

        const elTitulo = document.getElementById('preview-titulo');
        elTitulo.textContent = titulo || 'Título da música';
        elTitulo.classList.toggle('vazio', !titulo);

        document.getElementById('preview-artista').textContent = artista || '—';

        const dotAlbum  = document.getElementById('preview-dot-album');
        const elAlbum   = document.getElementById('preview-album');
        if (album) {
            dotAlbum.style.display = '';
            elAlbum.style.display  = '';
            elAlbum.textContent    = album;
        } else {
            dotAlbum.style.display = 'none';
            elAlbum.style.display  = 'none';
        }

        const elGenero = document.getElementById('preview-genero');
        if (generoNome && generoNome !== 'Selecione...') {
            elGenero.innerHTML = `<i class="fas fa-tag me-1"></i>${generoNome}`;
            elGenero.classList.remove('empty');
        } else {
            elGenero.innerHTML = `<i class="fas fa-tag me-1"></i>Gênero`;
            elGenero.classList.add('empty');
        }

        const elData = document.getElementById('preview-data');
        if (data) {
            const [y, m, d] = data.split('-');
            elData.innerHTML = `<i class="fas fa-calendar me-1"></i>${d}/${m}/${y}`;
            elData.classList.remove('empty');
        } else {
            elData.innerHTML = `<i class="fas fa-calendar me-1"></i>Data`;
            elData.classList.add('empty');
        }

        const elDuracao = document.getElementById('preview-duracao');
        if (duracao) {
            elDuracao.innerHTML = `<i class="fas fa-clock me-1"></i>${duracao}`;
            elDuracao.classList.remove('empty');
        } else {
            elDuracao.innerHTML = `<i class="fas fa-clock me-1"></i>Duração`;
            elDuracao.classList.add('empty');
        }
    }

    // Atualiza preview ao digitar nos outros campos também
    ['titulo','artista','album','genero','data_lancamento','duracao'].forEach(id => {
        document.getElementById(id)?.addEventListener('input', atualizarPreview);
        document.getElementById(id)?.addEventListener('change', atualizarPreview);
    });

    // ── Envio do formulário ────────────────────────────────────────
    document.getElementById('form-musica').addEventListener('submit', async (e) => {
        e.preventDefault();

        const titulo   = document.getElementById('titulo').value.trim();
        const artista  = document.getElementById('artista').value.trim();
        const ytLink   = document.getElementById('youtube_link').value.trim();

        if (!titulo) { mostrarToast('Informe o título da música.', 'erro'); return; }
        if (!artista) { mostrarToast('Informe o artista.', 'erro'); return; }
        if (!ytLink || !extrairYoutubeId(ytLink)) {
            mostrarToast('Informe um link válido do YouTube.', 'erro');
            return;
        }

        const btn = document.getElementById('btn-salvar');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';

        const form = new FormData();
        form.append('titulo',          titulo);
        form.append('artista',         artista);
        form.append('album',           document.getElementById('album').value.trim());
        form.append('genero_id',       document.getElementById('genero').value);
        form.append('data_lancamento', document.getElementById('data_lancamento').value);
        form.append('duracao',         document.getElementById('duracao').value);
        form.append('youtube_link',    ytLink);

        try {
            const res  = await fetch('api/musicas/cadastrar.php', { method: 'POST', body: form });
            const data = await res.json();

            if (data.ok) {
                mostrarToast('Música cadastrada com sucesso!', 'sucesso');
                document.getElementById('form-musica').reset();
                document.getElementById('youtube-iframe').style.display = 'none';
                document.getElementById('youtube-iframe').src = '';
                document.getElementById('youtube-placeholder').style.display = 'flex';
                atualizarPreview();
            } else {
                mostrarToast(data.erro ?? 'Erro ao cadastrar música.', 'erro');
            }
        } catch (_) {
            mostrarToast('Erro de conexão com o servidor.', 'erro');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-plus"></i> Cadastrar Música';
        }
    });

    // ── Toast ──────────────────────────────────────────────────────
    function mostrarToast(msg, tipo = 'sucesso') {
        const t    = document.getElementById('toast');
        const icon = document.getElementById('toast-icon');
        t.className = `toast-notification show ${tipo}`;
        icon.className = tipo === 'sucesso' ? 'fas fa-check-circle' : 'fas fa-times-circle';
        document.getElementById('toast-msg').textContent = msg;
        setTimeout(() => t.classList.remove('show'), 3500);
    }

    // ── Logout ─────────────────────────────────────────────────────
    async function logout() {
        await fetch('api/auth/logout.php');
        window.location.href = 'login.html';
    }

    // ── Slideshow footer ───────────────────────────────────────────
    const slides = [
        'https://img.freepik.com/vetores-premium/um-cartaz-de-publicidade-musical-com-um-disco-de-vinil_606304-808.jpg',
        'https://png.pngtree.com/thumb_back/fh260/background/20221224/pngtree-blue-musical-notes-background-image_1530362.jpg',
        'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT_5qs5i8zvZ2TQptBz3UOxKhFS-Te9heBoDA&s',
    ];
    let current = 0;
    const img = document.getElementById('slideshow-img');
    setInterval(() => {
        img.style.opacity = '0';
        setTimeout(() => { current = (current + 1) % slides.length; img.src = slides[current]; img.style.opacity = '1'; }, 1000);
    }, 30000);

    // ── Fade-in ────────────────────────────────────────────────────
    const observer = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
</script>
</body>
</html>

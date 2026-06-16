<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundScore - Cadastrar Álbum</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/navbarPag.css">
    <style>
        body { display: flex; height: 100vh; overflow: hidden; }
        .content { margin-left: 240px; width: calc(100% - 240px); overflow-y: auto; padding: 20px 32px 80px; }

        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; }
        .search-bar-top { display: flex; align-items: center; background: #2a2a2a; border-radius: 20px; padding: 8px 16px; gap: 10px; width: 280px; }
        .search-bar-top i { color: #b3b3b3; font-size: 0.85rem; }
        .search-bar-top input { background: none; border: none; outline: none; color: #fff; font-size: 0.85rem; width: 100%; }
        .search-bar-top input::placeholder { color: #b3b3b3; }

        .btn-voltar { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 24px; padding: 9px 20px; border-radius: 25px; background: rgba(124,77,255,0.12); border: 1px solid rgba(124,77,255,0.35); color: #c4a8ff; font-size: 0.85rem; font-weight: 600; text-decoration: none; transition: background 0.2s, border-color 0.2s, transform 0.2s; }
        .btn-voltar:hover { background: rgba(124,77,255,0.25); border-color: #7c4dff; color: #fff; transform: translateX(-3px); }
        .btn-voltar i { transition: transform 0.2s; }
        .btn-voltar:hover i { transform: translateX(-3px); }

        .page-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; align-items: start; }

        .form-card { background: #181818; border: 1px solid rgba(255,255,255,0.06); border-radius: 16px; padding: 28px; }
        .form-card h2 { font-size: 1.2rem; font-weight: 700; margin-bottom: 22px; display: flex; align-items: center; gap: 10px; }
        .form-card h2 i { color: #7c4dff; }

        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 0.78rem; color: #b3b3b3; margin-bottom: 7px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-group input,
        .form-group select,
        .form-group textarea { width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; color: #fff; padding: 11px 14px; font-size: 0.88rem; outline: none; transition: border-color 0.2s, background 0.2s; font-family: inherit; }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus { border-color: rgba(124,77,255,0.6); background: rgba(255,255,255,0.08); }
        .form-group select option { background: #1a1a2e; }
        .form-group textarea { resize: vertical; min-height: 90px; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

        .btn-salvar { width: 100%; background: linear-gradient(135deg, #7c4dff, #4fc3f7); border: none; color: #fff; padding: 13px; border-radius: 25px; font-size: 0.95rem; font-weight: 700; cursor: pointer; transition: filter 0.2s, transform 0.2s; margin-top: 6px; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .btn-salvar:hover { filter: brightness(1.12); transform: scale(1.02); }
        .btn-salvar:disabled { opacity: 0.5; cursor: not-allowed; transform: none; filter: none; }

        /* Card de preview */
        .preview-card { background: #181818; border: 1px solid rgba(255,255,255,0.06); border-radius: 16px; overflow: hidden; position: sticky; top: 0; }
        .preview-card-header { padding: 20px 24px 16px; border-bottom: 1px solid rgba(255,255,255,0.06); display: flex; align-items: center; gap: 10px; }
        .preview-card-header h3 { font-size: 1rem; font-weight: 700; margin: 0; }
        .preview-card-header i { color: #7c4dff; }

        /* Capa do álbum */
        .album-cover-wrap {
            width: 100%;
            aspect-ratio: 1;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 14px;
            position: relative;
            overflow: hidden;
        }

        .album-cover-wrap::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 60% 40%, rgba(124,77,255,0.25), transparent 70%);
        }

        .album-cover-icon {
            font-size: 5rem;
            position: relative;
            z-index: 1;
            transition: transform 0.3s;
        }

        .album-cover-nome {
            font-size: 1.3rem;
            font-weight: 800;
            text-align: center;
            padding: 0 20px;
            position: relative;
            z-index: 1;
            color: #fff;
            text-shadow: 0 2px 12px rgba(0,0,0,0.6);
            word-break: break-word;
        }

        .album-cover-nome.vazio { color: #555; font-style: italic; font-weight: 400; font-size: 1rem; }

        /* Info do preview */
        .preview-info { padding: 20px 24px; }
        .preview-titulo { font-size: 1rem; font-weight: 700; margin-bottom: 4px; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .preview-titulo.vazio { color: #555; font-style: italic; font-weight: 400; }
        .preview-meta { font-size: 0.82rem; color: #b3b3b3; display: flex; align-items: center; gap: 6px; flex-wrap: wrap; margin-bottom: 14px; }
        .preview-meta .dot { color: #555; }

        .preview-tags { display: flex; gap: 8px; flex-wrap: wrap; }
        .preview-tag { background: rgba(124,77,255,0.12); border: 1px solid rgba(124,77,255,0.25); color: #c4a8ff; font-size: 0.72rem; font-weight: 600; padding: 3px 10px; border-radius: 20px; }
        .preview-tag.empty { background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.08); color: #555; }

        /* Toast */
        .toast-notification { position: fixed; top: 20px; right: 20px; padding: 13px 20px; border-radius: 12px; font-size: 0.88rem; font-weight: 600; z-index: 9999; display: none; align-items: center; gap: 10px; animation: slideIn 0.3s ease; box-shadow: 0 4px 20px rgba(0,0,0,0.4); }
        .toast-notification.show { display: flex; }
        .toast-notification.sucesso { background: linear-gradient(135deg, #1db954, #17a846); color: #fff; }
        .toast-notification.erro    { background: rgba(229,57,53,0.9); color: #fff; }
        @keyframes slideIn { from { transform: translateX(300px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

        .fade-in { opacity: 0; transform: translateY(20px); transition: opacity 0.5s ease, transform 0.5s ease; }
        .fade-in.visible { opacity: 1; transform: translateY(0); }

        .player { padding: 0; overflow: hidden; height: 50px; position: fixed; bottom: 0; left: 240px; width: calc(100% - 240px); z-index: 40; }
        .player img { width: 100%; height: 100%; object-fit: cover; display: block; transition: opacity 1s ease; }

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
            <h2><i class="fas fa-compact-disc"></i> Cadastrar Álbum</h2>

            <form id="form-album" novalidate>

                <div class="form-group">
                    <label>Nome do álbum <span style="color:#ef5350;">*</span></label>
                    <input type="text" id="nome" placeholder="Ex: After Hours" maxlength="150" oninput="atualizarPreview()">
                </div>

                <div class="form-group">
                    <label>Artista <span style="color:#ef5350;">*</span></label>
                    <input type="text" id="artista" placeholder="Ex: The Weeknd" maxlength="150" oninput="atualizarPreview()">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Gênero</label>
                        <select id="genero_id" onchange="atualizarPreview()">
                            <option value="">Selecione...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Data de lançamento</label>
                        <input type="date" id="data_lancamento" onchange="atualizarPreview()">
                    </div>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select id="status" onchange="atualizarPreview()">
                        <option value="1">Ativo</option>
                        <option value="0">Inativo</option>
                    </select>
                </div>

                <button type="submit" class="btn-salvar" id="btn-salvar">
                    <i class="fas fa-plus"></i> Cadastrar Álbum
                </button>

            </form>
        </div>

        <!-- Preview -->
        <div class="preview-card">
            <div class="preview-card-header">
                <i class="fas fa-compact-disc"></i>
                <h3>Prévia do álbum</h3>
            </div>

            <div class="album-cover-wrap">
                <div class="album-cover-icon" id="cover-icon">💿</div>
                <div class="album-cover-nome vazio" id="cover-nome">Nome do álbum</div>
            </div>

            <div class="preview-info">
                <div class="preview-titulo vazio" id="preview-nome">Nome do álbum</div>
                <div class="preview-meta">
                    <span id="preview-artista">—</span>
                </div>
                <div class="preview-tags">
                    <span class="preview-tag empty" id="preview-genero"><i class="fas fa-tag me-1"></i>Gênero</span>
                    <span class="preview-tag empty" id="preview-data"><i class="fas fa-calendar me-1"></i>Data</span>
                    <span class="preview-tag empty" id="preview-status"><i class="fas fa-circle me-1"></i>Status</span>
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
    // ── Carregar gêneros ───────────────────────────────────────────
    (async () => {
        try {
            const res  = await fetch('api/categorias/listar.php');
            const data = await res.json();
            if (data.ok && data.categorias) {
                const sel = document.getElementById('genero_id');
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

    // Ícones por gênero para a capa
    const genreIcons = {
        'rock': '🎸', 'metal': '🤘', 'pop': '🎵', 'jazz': '🎷',
        'eletrônica': '🎧', 'eletronica': '🎧', 'rap': '🎤', 'hip-hop': '🎤',
        'mpb': '🇧🇷', 'samba': '🥁', 'clássic': '🎻', 'classic': '🎻',
        'indie': '🎶', 'country': '🪕', 'reggae': '🌿', 'funk': '🔥',
    };

    function iconeGenero(nome) {
        if (!nome) return '💿';
        const n = nome.toLowerCase();
        for (const [k, v] of Object.entries(genreIcons)) {
            if (n.includes(k)) return v;
        }
        return '💿';
    }

    // ── Atualizar preview ──────────────────────────────────────────
    function atualizarPreview() {
        const nome    = document.getElementById('nome').value.trim();
        const artista = document.getElementById('artista').value.trim();
        const data    = document.getElementById('data_lancamento').value;
        const status  = document.getElementById('status').value;
        const generoEl   = document.getElementById('genero_id');
        const generoNome = generoEl.options[generoEl.selectedIndex]?.text ?? '';

        // Capa
        const coverNome = document.getElementById('cover-nome');
        coverNome.textContent = nome || 'Nome do álbum';
        coverNome.classList.toggle('vazio', !nome);
        document.getElementById('cover-icon').textContent = iconeGenero(generoNome);

        // Info
        const elNome = document.getElementById('preview-nome');
        elNome.textContent = nome || 'Nome do álbum';
        elNome.classList.toggle('vazio', !nome);

        document.getElementById('preview-artista').textContent = artista || '—';

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

        const elStatus = document.getElementById('preview-status');
        elStatus.innerHTML = status == 1
            ? `<i class="fas fa-circle me-1" style="color:#1db954;font-size:0.6rem;"></i>Ativo`
            : `<i class="fas fa-circle me-1" style="color:#b3b3b3;font-size:0.6rem;"></i>Inativo`;
        elStatus.classList.remove('empty');
    }

    // ── Envio do formulário ────────────────────────────────────────
    document.getElementById('form-album').addEventListener('submit', async (e) => {
        e.preventDefault();

        const nome    = document.getElementById('nome').value.trim();
        const artista = document.getElementById('artista').value.trim();

        if (!nome)    { mostrarToast('Informe o nome do álbum.', 'erro'); return; }
        if (!artista) { mostrarToast('Informe o artista.', 'erro'); return; }

        const btn = document.getElementById('btn-salvar');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';

        const form = new FormData();
        form.append('nome',            nome);
        form.append('artista',         artista);
        form.append('genero_id',       document.getElementById('genero_id').value);
        form.append('data_lancamento', document.getElementById('data_lancamento').value);
        form.append('status',          document.getElementById('status').value);

        try {
            const res  = await fetch('api/albuns/cadastrar.php', { method: 'POST', body: form });
            const data = await res.json();

            if (data.ok) {
                mostrarToast('Álbum cadastrado com sucesso!', 'sucesso');
                document.getElementById('form-album').reset();
                atualizarPreview();
            } else {
                mostrarToast(data.erro ?? 'Erro ao cadastrar álbum.', 'erro');
            }
        } catch (_) {
            mostrarToast('Erro de conexão com o servidor.', 'erro');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-plus"></i> Cadastrar Álbum';
        }
    });

    // ── Toast ──────────────────────────────────────────────────────
    function mostrarToast(msg, tipo = 'sucesso') {
        const t = document.getElementById('toast');
        document.getElementById('toast-icon').className = tipo === 'sucesso' ? 'fas fa-check-circle' : 'fas fa-times-circle';
        t.className = `toast-notification show ${tipo}`;
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

    // Preview inicial
    atualizarPreview();
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundScore - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/navbarPag.css">
    <link rel="stylesheet" href="assets/css/paginicial.css">
    <style>
        /* Music / Atalho Cards */
        .music-card {
            background: var(--bg-card);
            border-radius: 12px;
            overflow: hidden;
            transition: background 0.3s, transform 0.3s;
            cursor: pointer;
            position: relative;
        }
        .music-card:hover { background: var(--bg-card-hover); transform: translateY(-4px); }
        .music-card img { width: 100%; aspect-ratio: 1; object-fit: cover; display: block; }
        .music-card .card-body { padding: 12px; }
        .music-card h4 { font-size: 0.95rem; font-weight: 600; margin-bottom: 4px; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .music-card p  { font-size: 0.8rem; color: var(--text-grey); margin: 0; }

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
            cursor: pointer;
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
        .artist-card h4 { font-size: 0.95rem; font-weight: 600; margin-bottom: 4px; color: #fff; }
        .artist-card p  { font-size: 0.78rem; color: var(--text-grey); margin: 0; }

        /* Icon Cards (Criação rápida) */
        .icon-card {
            background: var(--bg-card);
            border-radius: 12px;
            padding: 28px 20px;
            text-align: center;
            transition: background 0.3s, transform 0.3s;
            cursor: pointer;
        }
        .icon-card:hover { background: var(--bg-card-hover); transform: translateY(-4px); }
        .icon-card .icon-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #7c4dff, #4fc3f7);
            border: none;
            color: #fff;
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
            box-shadow: 0 4px 16px rgba(124,77,255,0.4);
            transition: filter 0.2s, transform 0.2s;
        }
        .icon-card:hover .icon-btn { filter: brightness(1.15); transform: scale(1.08); }
        .icon-card h4 { font-size: 0.95rem; font-weight: 600; color: #fff; margin-bottom: 6px; }
        .icon-card p  { font-size: 0.8rem; color: var(--text-grey); margin: 0; }

        /* Score badge */
        .score-badge {
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: #000;
            font-weight: 700;
            font-size: 0.75rem;
            padding: 2px 8px;
            border-radius: 10px;
        }

        /* Section header */
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .section-header h2 { font-size: 1.3rem; font-weight: 700; margin: 0; }

        /* Fade-in */
        .fade-in { opacity: 0; transform: translateY(20px); transition: opacity 0.5s ease, transform 0.5s ease; }
        .fade-in.visible { opacity: 1; transform: translateY(0); }

        /* Featured banner override */
        .featured-banner {
            animation: gradientShift 8s ease infinite;
            background-size: 200% 200%;
        }
        @keyframes gradientShift {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .btn-play-featured {
            background: linear-gradient(135deg, #7c4dff, #4fc3f7) !important;
            border: none;
            transition: filter 0.2s, transform 0.2s !important;
        }
        .btn-play-featured:hover { filter: brightness(1.15); transform: scale(1.04) !important; background: linear-gradient(135deg, #7c4dff, #4fc3f7) !important; }
        .btn-like-featured:hover { border-color: #7c4dff; color: #7c4dff; }
        .btn-like-featured.ativo { border-color: #7c4dff; color: #7c4dff; }
        /* Footer slideshow */
        .player { left: 240px !important; width: calc(100% - 240px) !important; }
        body { display: flex; height: 100vh; overflow: hidden; }
        .content { margin-left: 240px; width: calc(100% - 240px); overflow-y: auto; padding: 20px 32px 80px; }
    </style>
</head>
<body>

    <?php include __DIR__ . '/navbar.php'; ?>

    <main class="content">
        <header class="top-bar">
            <div class="search-bar-top">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar usuários ou relatórios..." id="search-input">
            </div>
            <div class="user-controls">
                <span class="badge-upgrade"><i class="fas fa-shield-alt me-1"></i>Admin</span>
                <span id="nome-usuario" style="font-size:0.85rem; color:#b3b3b3;"></span>
            </div>
        </header>

        <!-- Banner destaque -->
        <div class="featured-banner mb-4 fade-in">
            <div class="featured-info">
                <span class="featured-tag">🔐 Área Administrativa</span>
                <h1>Bem-vindo, administrador</h1>
                <p>Controle usuários, revise relatórios e monitore atividades do sistema.</p>
                <div class="d-flex gap-2 mt-3">
                    <button class="btn-play-featured" onclick="window.location.href='usuario.php'">
                        <i class="fas fa-users-cog me-2"></i>Gerenciar Usuários
                    </button>
                    <button class="btn-like-featured" onclick="window.location.href='paginicial.php'">
                        <i class="fas fa-music me-2"></i>Ir para app
                    </button>
                </div>
            </div>
            <img src="https://picsum.photos/seed/admin1/400/220" alt="Admin" class="featured-img">
        </div>

        <!-- Atalhos rápidos -->
        <section class="mb-5 fade-in">
            <div class="section-header">
                <h2>Atalhos rápidos</h2>
                <a href="usuario.php" class="see-all">Ver tudo</a>
            </div>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                <div class="col">
                    <div class="music-card" onclick="window.location.href='usuario.php'">
                        <img src="https://picsum.photos/seed/admin2/200" alt="Usuários">
                        <button class="play-overlay"><i class="fas fa-users"></i></button>
                        <div class="card-body">
                            <h4>Usuários</h4>
                            <p>Gerencie contas, status e permissões.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="music-card" onclick="window.location.href='categoria.php'">
                        <img src="https://picsum.photos/seed/admin8/200" alt="Categorias">
                        <button class="play-overlay"><i class="fas fa-folder-open"></i></button>
                        <div class="card-body">
                            <h4>Categorias</h4>
                            <p>Gerencie categorias e visibilidade.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="music-card">
                        <img src="https://picsum.photos/seed/admin4/200" alt="Configurações">
                        <button class="play-overlay"><i class="fas fa-cogs"></i></button>
                        <div class="card-body">
                            <h4>Configurações</h4>
                            <p>Ajuste parciais e políticas do app.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Criação rápida -->
        <section class="mb-5 fade-in">
            <div class="section-header">
                <h2>Criação rápida</h2>
                <a href="#" class="see-all">Ver tudo</a>
            </div>
            <div class="row row-cols-1 row-cols-md-3 g-3">
                <div class="col">
                    <div class="icon-card" onclick="window.location.href='album.php'">
                        <button class="icon-btn"><i class="fas fa-compact-disc"></i></button>
                        <h4>Criar Álbum</h4>
                        <p>Cadastre álbuns novos para compor a biblioteca.</p>
                    </div>
                </div>
                <div class="col">
                    <div class="icon-card" onclick="window.location.href='musica.php'">
                        <button class="icon-btn"><i class="fas fa-music"></i></button>
                        <h4>Criar Música</h4>
                        <p>Adicione músicas e associe-as a álbuns e categorias.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Denúncias -->
        <section class="mb-5 fade-in">
            <div class="section-header">
                <h2>Denúncias</h2>
                <a href="denuncias.php" class="see-all">Ver todas</a>
            </div>
            <div class="row row-cols-1 row-cols-md-2 g-3">
                <div class="col">
                    <div class="d-flex align-items-center gap-3 p-3" style="background:var(--bg-card);border-radius:12px;transition:background 0.3s;" onmouseover="this.style.background='var(--bg-card-hover)'" onmouseout="this.style.background='var(--bg-card)'">
                        <div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#7c4dff,#4fc3f7);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-flag" style="color:#fff;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1" style="font-size:0.9rem;color:#fff;">Denúncias Pendentes</h4>
                            <p class="mb-0" style="font-size:0.78rem;color:var(--text-grey);">
                                <?php
                                    require_once __DIR__ . '/model/dao/Conexao.php';
                                    $pdo = Conexao::getConexao();
                                    $r = $pdo->query("SELECT COUNT(*) FROM denuncias WHERE status = 'Pendente'");
                                    echo '<span class="score-badge">' . ($r ? $r->fetchColumn() : 0) . '</span>';
                                ?> aguardando revisão
                            </p>
                        </div>
                        <button class="btn btn-sm px-3" style="background:linear-gradient(135deg,#7c4dff,#4fc3f7);color:#fff;border:none;border-radius:20px;font-size:0.75rem;white-space:nowrap;" onclick="window.location.href='denuncias.php'">Ver</button>
                    </div>
                </div>
                <div class="col">
                    <div class="d-flex align-items-center gap-3 p-3" style="background:var(--bg-card);border-radius:12px;transition:background 0.3s;" onmouseover="this.style.background='var(--bg-card-hover)'" onmouseout="this.style.background='var(--bg-card)'">
                        <div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#7c4dff,#4fc3f7);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-comment-slash" style="color:#fff;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1" style="font-size:0.9rem;color:#fff;">Comentários Denunciados</h4>
                            <p class="mb-0" style="font-size:0.78rem;color:var(--text-grey);">
                                <?php
                                    $r2 = $pdo->query("SELECT COUNT(DISTINCT comentario_id) FROM denuncias");
                                    echo '<span class="score-badge">' . ($r2 ? $r2->fetchColumn() : 0) . '</span>';
                                ?> comentários reportados
                            </p>
                        </div>
                        <button class="btn btn-sm px-3" style="background:linear-gradient(135deg,#7c4dff,#4fc3f7);color:#fff;border:none;border-radius:20px;font-size:0.75rem;white-space:nowrap;" onclick="window.location.href='denuncias.php'">Ver</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Resumo da plataforma -->
        <section class="mb-5 fade-in">
            <div class="section-header">
                <h2>Resumo da plataforma</h2>
                <a href="#" class="see-all">Detalhes</a>
            </div>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                <div class="col">
                    <div class="artist-card">
                        <img src="https://picsum.photos/seed/admin5/200" alt="Novos usuários">
                        <h4>Novos Usuários</h4>
                        <p><span class="score-badge">+24</span> nesta semana</p>
                    </div>
                </div>
                <div class="col">
                    <div class="artist-card">
                        <img src="https://picsum.photos/seed/admin6/200" alt="Atividade">
                        <h4>Atividade</h4>
                        <p><span class="score-badge">96%</span> do sistema ativo</p>
                    </div>
                </div>
                <div class="col">
                    <div class="artist-card">
                        <img src="https://picsum.photos/seed/admin7/200" alt="Suporte">
                        <h4>Suporte</h4>
                        <p><span class="score-badge">3</span> chamados abertos</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer Slideshow -->
    <footer class="player">
        <img id="slideshow-img" src="https://img.freepik.com/vetores-premium/um-cartaz-de-publicidade-musical-com-um-disco-de-vinil_606304-808.jpg" alt="Admin Player">
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script src="assets/js/homeAdmin.js"></script>
    <script>
        // Bootstrap tooltips
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

        // Slideshow footer
        const slides = [
            'https://img.freepik.com/vetores-premium/um-cartaz-de-publicidade-musical-com-um-disco-de-vinil_606304-808.jpg',
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

        // Fade-in ao rolar
        const observer = new IntersectionObserver(entries => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
        }, { threshold: 0.1 });
        document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
    </script>
</body>
</html>

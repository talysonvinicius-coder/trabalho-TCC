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
        /* Modal Resumo */
        .modal-resumo .modal-content {
            background: rgba(15,15,25,0.97);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            color: #fff;
        }
        .modal-resumo .modal-header {
            border-bottom: 1px solid rgba(255,255,255,0.08);
            padding: 20px 24px 16px;
        }
        .modal-resumo .modal-title { font-size: 1rem; font-weight: 700; }
        .resumo-row {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .resumo-row:last-child { border-bottom: none; }
        .resumo-row-icon {
            width: 36px; height: 36px; border-radius: 8px; flex-shrink: 0;
            background: linear-gradient(135deg, rgba(124,77,255,0.3), rgba(79,195,247,0.2));
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem; color: #c4a8ff;
        }
        .resumo-row-info { flex: 1; min-width: 0; }
        .resumo-row-info strong { display: block; font-size: 0.85rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .resumo-row-info small { color: #b3b3b3; font-size: 0.72rem; }
        .resumo-row-badge { font-size: 0.72rem; color: #b3b3b3; white-space: nowrap; flex-shrink: 0; }
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
            </div>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                <?php
                    $novos_usuarios = $pdo->query("
                        SELECT COUNT(*) FROM usuario
                        WHERE data_criacao >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                    ")->fetchColumn();
                    $total_avaliacoes = $pdo->query("SELECT COUNT(*) FROM avaliacoes")->fetchColumn();
                    $total_musicas    = $pdo->query("SELECT COUNT(*) FROM musicas")->fetchColumn();
                    $usuarios_premium = $pdo->query("SELECT COUNT(*) FROM usuario WHERE plano_id = 2")->fetchColumn();

                    // Últimos 5 usuários novos desta semana
                    $ult_usuarios = $pdo->query("
                        SELECT u.nome, u.data_criacao, pl.nome AS plano
                        FROM usuario u JOIN planos pl ON u.plano_id = pl.id
                        WHERE u.data_criacao >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                        ORDER BY u.data_criacao DESC LIMIT 5
                    ")->fetchAll(PDO::FETCH_ASSOC);

                    // Últimas 5 avaliações
                    $ult_avaliacoes = $pdo->query("
                        SELECT u.nome, m.titulo, av.nota, av.data_avaliacao
                        FROM avaliacoes av
                        JOIN usuario u ON av.usuario_id = u.id
                        JOIN musicas m ON av.musica_id = m.id
                        ORDER BY av.data_avaliacao DESC LIMIT 5
                    ")->fetchAll(PDO::FETCH_ASSOC);

                    // Últimas 5 músicas
                    $ult_musicas = $pdo->query("
                        SELECT m.titulo, a.nome AS artista, m.id
                        FROM musicas m
                        LEFT JOIN artista a ON m.artista_id = a.id
                        ORDER BY m.id DESC LIMIT 5
                    ")->fetchAll(PDO::FETCH_ASSOC);

                    // Últimos 5 usuários premium
                    $ult_premium = $pdo->query("
                        SELECT u.nome, u.data_criacao
                        FROM usuario u
                        WHERE u.plano_id = 2
                        ORDER BY u.data_criacao DESC LIMIT 5
                    ")->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <div class="col">
                    <div class="artist-card" onclick="abrirResumo('novos-usuarios')">
                        <img src="https://picsum.photos/seed/admin5/200" alt="Novos usuários">
                        <h4>Novos Usuários</h4>
                        <p><span class="score-badge">+<?php echo $novos_usuarios; ?></span> nesta semana</p>
                    </div>
                </div>
                <div class="col">
                    <div class="artist-card" onclick="abrirResumo('avaliacoes')">
                        <img src="https://picsum.photos/seed/admin6/200" alt="Avaliações">
                        <h4>Avaliações</h4>
                        <p><span class="score-badge"><?php echo $total_avaliacoes; ?></span> no total</p>
                    </div>
                </div>
                <div class="col">
                    <div class="artist-card" onclick="abrirResumo('musicas')">
                        <img src="https://picsum.photos/seed/admin7/200" alt="Músicas">
                        <h4>Músicas</h4>
                        <p><span class="score-badge"><?php echo $total_musicas; ?></span> cadastradas</p>
                    </div>
                </div>
                <div class="col">
                    <div class="artist-card" onclick="abrirResumo('premium')">
                        <img src="https://picsum.photos/seed/admin9/200" alt="Premium">
                        <h4>Usuários Premium</h4>
                        <p><span class="score-badge"><?php echo $usuarios_premium; ?></span> assinantes</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Modal Novos Usuários -->
    <div class="modal fade modal-resumo" id="modal-novos-usuarios" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-plus me-2" style="color:#7c4dff;"></i>Novos Usuários — Últimos 7 dias</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:20px;">
                    <?php if (empty($ult_usuarios)): ?>
                        <p style="text-align:center;color:#b3b3b3;">Nenhum usuário cadastrado esta semana.</p>
                    <?php else: foreach ($ult_usuarios as $u): ?>
                        <div class="resumo-row">
                            <div class="resumo-row-icon"><i class="fas fa-user"></i></div>
                            <div class="resumo-row-info">
                                <strong><?php echo htmlspecialchars($u['nome']); ?></strong>
                                <small><?php echo htmlspecialchars($u['plano']); ?></small>
                            </div>
                            <span class="resumo-row-badge"><?php echo date('d/m/Y', strtotime($u['data_criacao'])); ?></span>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Avaliações -->
    <div class="modal fade modal-resumo" id="modal-avaliacoes" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-star me-2" style="color:#ffc107;"></i>Avaliações Recentes</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:20px;">
                    <?php if (empty($ult_avaliacoes)): ?>
                        <p style="text-align:center;color:#b3b3b3;">Nenhuma avaliação registrada.</p>
                    <?php else: foreach ($ult_avaliacoes as $av): ?>
                        <div class="resumo-row">
                            <div class="resumo-row-icon"><i class="fas fa-music"></i></div>
                            <div class="resumo-row-info">
                                <strong><?php echo htmlspecialchars($av['titulo']); ?></strong>
                                <small><?php echo htmlspecialchars($av['nome']); ?></small>
                            </div>
                            <span class="resumo-row-badge" style="color:#ffc107;"><?php echo str_repeat('★', (int)$av['nota']) . str_repeat('☆', 5 - (int)$av['nota']); ?></span>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Músicas -->
    <div class="modal fade modal-resumo" id="modal-musicas" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-compact-disc me-2" style="color:#4fc3f7;"></i>Músicas Cadastradas Recentemente</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:20px;">
                    <?php if (empty($ult_musicas)): ?>
                        <p style="text-align:center;color:#b3b3b3;">Nenhuma música cadastrada.</p>
                    <?php else: foreach ($ult_musicas as $m): ?>
                        <div class="resumo-row">
                            <div class="resumo-row-icon"><i class="fas fa-music"></i></div>
                            <div class="resumo-row-info">
                                <strong><?php echo htmlspecialchars($m['titulo']); ?></strong>
                                <small><?php echo htmlspecialchars($m['artista'] ?? 'Artista desconhecido'); ?></small>
                            </div>
                            <span class="resumo-row-badge">#<?php echo $m['id']; ?></span>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Usuários Premium -->
    <div class="modal fade modal-resumo" id="modal-premium" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-crown me-2" style="color:#ffc107;"></i>Assinantes Premium Recentes</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:20px;">
                    <?php if (empty($ult_premium)): ?>
                        <p style="text-align:center;color:#b3b3b3;">Nenhum usuário premium.</p>
                    <?php else: foreach ($ult_premium as $u): ?>
                        <div class="resumo-row">
                            <div class="resumo-row-icon"><i class="fas fa-crown" style="color:#ffc107;"></i></div>
                            <div class="resumo-row-info">
                                <strong><?php echo htmlspecialchars($u['nome']); ?></strong>
                                <small>Assinante Premium</small>
                            </div>
                            <span class="resumo-row-badge"><?php echo date('d/m/Y', strtotime($u['data_criacao'])); ?></span>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </div>
    </div>

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

        function abrirResumo(tipo) {
            const ids = {
                'novos-usuarios': 'modal-novos-usuarios',
                'avaliacoes':     'modal-avaliacoes',
                'musicas':        'modal-musicas',
                'premium':        'modal-premium'
            };
            new bootstrap.Modal(document.getElementById(ids[tipo])).show();
        }
    </script>
</body>
</html>

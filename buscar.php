<?php
session_start();
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
        .badge-plano { display: inline-block; background: linear-gradient(135deg, #7c4dff, #4fc3f7); color: #fff; font-size: 0.7rem; font-weight: 700; padding: 4px 10px; border-radius: 12px; }

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

        /* Busca de usuários */
        .user-search-wrap { position: relative; }
        .user-search-input {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 25px; color: #fff;
            padding: 10px 18px 10px 42px;
            font-size: 0.88rem; width: 100%; outline: none;
            transition: border-color 0.2s;
        }
        .user-search-input::placeholder { color: rgba(255,255,255,0.35); }
        .user-search-input:focus { border-color: #7c4dff; }
        .user-search-icon {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%); color: #b3b3b3; font-size: 0.85rem;
            pointer-events: none;
        }
        .user-search-results {
            position: absolute; top: calc(100% + 6px); left: 0; right: 0;
            background: rgba(18,18,28,0.98); backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1); border-radius: 14px;
            z-index: 999; overflow: hidden; display: none;
            box-shadow: 0 8px 32px rgba(0,0,0,0.5);
        }
        .user-result-item {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 16px; transition: background 0.2s; cursor: default;
        }
        .user-result-item:hover { background: rgba(255,255,255,0.05); }
        .user-result-avatar {
            width: 42px; height: 42px; border-radius: 50%; flex-shrink: 0;
            background: linear-gradient(135deg, #7c4dff, #4fc3f7);
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; font-weight: 700; color: #fff; overflow: hidden;
        }
        .user-result-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
        .user-result-info { flex: 1; min-width: 0; }
        .user-result-info strong { font-size: 0.88rem; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-result-info span { font-size: 0.72rem; color: #b3b3b3; }
        .btn-seguir {
            flex-shrink: 0; border: none; border-radius: 20px;
            padding: 5px 14px; font-size: 0.75rem; font-weight: 700;
            cursor: pointer; transition: 0.2s; white-space: nowrap;
        }
        .btn-seguir.seguir { background: linear-gradient(135deg, #7c4dff, #4fc3f7); color: #fff; }
        .btn-seguir.seguindo { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.2); color: #b3b3b3; }
        .btn-seguir.seguindo:hover { background: rgba(255,60,60,0.15); border-color: #ff4444; color: #ff6b6b; }
        .user-search-empty { padding: 20px; text-align: center; color: #b3b3b3; font-size: 0.82rem; }

        /* Modal Perfil Usuário */
        #modalPerfilUsuario .modal-content {
            background: rgba(15,15,25,0.97);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            color: #fff;
        }
        .perfil-usuario-header {
            padding: 24px; border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex; gap: 16px; align-items: flex-start;
        }
        .perfil-usuario-avatar {
            width: 80px; height: 80px; border-radius: 50%;
            background: linear-gradient(135deg, #7c4dff, #4fc3f7);
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; flex-shrink: 0; overflow: hidden;
        }
        .perfil-usuario-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
        .perfil-usuario-info { flex: 1; }
        .perfil-usuario-info h5 { font-size: 1rem; font-weight: 700; margin-bottom: 4px; }
        .perfil-usuario-sub { font-size: 0.78rem; color: #b3b3b3; margin-bottom: 10px; }
        .perfil-stats { display: flex; gap: 16px; font-size: 0.85rem; }
        .perfil-stats div { text-align: center; }
        .perfil-stats strong { display: block; font-size: 1.1rem; color: #7c4dff; }

        /* Modal Perfil Usuário - Melhorias */
        .perfil-header-actions { display: flex; gap: 8px; align-items: center; }
        .btn-seguir-perfil { background: linear-gradient(135deg, #7c4dff, #4fc3f7); border: none; color: #fff; padding: 8px 16px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.3s; }
        .btn-seguir-perfil:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(124, 77, 255, 0.3); }
        .btn-seguir-perfil.seguindo { background: rgba(255,255,255,0.1); border: 1px solid rgba(124, 77, 255, 0.5); }
        
        .comentario-card { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; padding: 12px; margin-bottom: 10px; }
        .comentario-musica { display: flex; gap: 10px; margin-bottom: 8px; align-items: flex-start; }
        .comentario-musica-info { flex: 1; min-width: 0; }
        .comentario-musica-info strong { display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 0.9rem; }
        .comentario-musica-info small { color: #b3b3b3; display: block; }
        .comentario-texto { color: #ddd; font-size: 0.85rem; margin-bottom: 8px; line-height: 1.4; }
        .comentario-footer { display: flex; justify-content: space-between; align-items: center; font-size: 0.75rem; color: #b3b3b3; }
        .comentario-acoes { display: flex; gap: 8px; }
        .btn-acao { background: none; border: none; color: #7c4dff; cursor: pointer; padding: 2px 6px; font-size: 0.75rem; transition: all 0.2s; }
        .btn-acao:hover { color: #4fc3f7; }
        .btn-acao.curtido { color: #ff4081; }
        .btn-acao.denunciado { color: #ff6b35; }

        /* Respostas */
        .respostas-container { margin-top: 10px; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 10px; display: none; }
        .resposta-item { display: flex; gap: 8px; margin-bottom: 8px; }
        .resposta-avatar { width: 28px; height: 28px; border-radius: 50%; background: linear-gradient(135deg,#7c4dff,#4fc3f7); display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700; flex-shrink: 0; overflow: hidden; }
        .resposta-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
        .resposta-corpo { background: rgba(255,255,255,0.04); border-radius: 8px; padding: 8px 10px; flex: 1; }
        .resposta-corpo strong { font-size: 0.78rem; display: block; margin-bottom: 2px; }
        .resposta-corpo p { font-size: 0.8rem; color: #ddd; margin: 0; }
        .resposta-input-wrap { display: flex; gap: 8px; margin-top: 8px; }
        .resposta-input { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); border-radius: 20px; color: #fff; padding: 6px 14px; font-size: 0.8rem; flex: 1; outline: none; }
        .resposta-input:focus { border-color: #7c4dff; }
        .btn-enviar-resposta { background: linear-gradient(135deg,#7c4dff,#4fc3f7); border: none; border-radius: 20px; color: #fff; padding: 6px 14px; font-size: 0.78rem; font-weight: 700; cursor: pointer; white-space: nowrap; }

        /* Modal Denúncia */
        #modalDenuncia .modal-content { background: rgba(15,15,25,0.97); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; color: #fff; }

        /* Modal Visualizar Lista */
        #modalVisualizarLista .modal-content {
            background: rgba(15,15,25,0.97);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            color: #fff;
        }
        #modalVisualizarLista .lista-header {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }
        #modalVisualizarLista .lista-header-info { display: flex; align-items: center; gap: 14px; }
        #modalVisualizarLista .lista-header-capa {
            width: 54px; height: 54px; border-radius: 10px; flex-shrink: 0;
            background: linear-gradient(135deg, rgba(124,77,255,0.4), rgba(79,195,247,0.25));
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem;
            border: 1px solid rgba(255,255,255,0.08);
        }
        #modalVisualizarLista .lista-header h5 { margin: 0; font-size: 1rem; font-weight: 700; }
        #modalVisualizarLista .lista-header small { color: #b3b3b3; font-size: 0.78rem; }
        .musica-row {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 12px;
            border-radius: 10px;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            transition: background 0.2s, border-color 0.2s;
            cursor: pointer;
        }
        .musica-row:hover { background: rgba(124,77,255,0.1); border-color: rgba(124,77,255,0.25); }
        .musica-row:hover .musica-row-num { display: none; }
        .musica-row-play { display: none; width: 22px; text-align: center; font-size: 0.75rem; color: #7c4dff; flex-shrink: 0; }
        .musica-row:hover .musica-row-play { display: block; }
        .musica-row-num { width: 22px; text-align: right; font-size: 0.75rem; color: #b3b3b3; flex-shrink: 0; }
        .musica-row-icon {
            width: 40px; height: 40px; border-radius: 8px; flex-shrink: 0;
            background: linear-gradient(135deg, rgba(124,77,255,0.3), rgba(79,195,247,0.2));
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
        }
        .musica-row-info { flex: 1; min-width: 0; }
        .musica-row-info strong { display: block; font-size: 0.88rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .musica-row-info small { color: #b3b3b3; font-size: 0.75rem; }
        .musica-row-duracao { color: #b3b3b3; font-size: 0.75rem; white-space: nowrap; flex-shrink: 0; }
        .motivo-btn { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #ddd; padding: 8px 12px; font-size: 0.82rem; cursor: pointer; transition: all 0.2s; text-align: left; width: 100%; margin-bottom: 6px; }
        .motivo-btn:hover, .motivo-btn.selecionado { background: rgba(124,77,255,0.2); border-color: #7c4dff; color: #fff; }
        .lista-total-badge { font-size: 0.72rem; color: #b3b3b3; margin-top: 2px; }

        /* Listas grid */
        .listas-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 10px; }
        .lista-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: background 0.25s, transform 0.25s, border-color 0.25s, box-shadow 0.25s;
        }
        .lista-card:hover {
            background: rgba(124,77,255,0.12);
            border-color: rgba(124,77,255,0.35);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(124,77,255,0.18);
        }
        .lista-card-capa {
            width: 100%; aspect-ratio: 1;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.2rem;
            background: linear-gradient(135deg, rgba(124,77,255,0.25), rgba(79,195,247,0.15));
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .lista-card-body { padding: 10px 10px 12px; }
        .lista-card-nome { font-weight: 700; font-size: 0.82rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 3px; color: #fff; }
        .lista-card-count { color: #b3b3b3; font-size: 0.72rem; display: flex; align-items: center; gap: 4px; }

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
            <?php if (!$isPremium): ?>
                <a href="premium.php" class="badge-upgrade"><i class="fas fa-bolt me-1"></i>Upgrade</a>
            <?php else: ?>
                <span style="font-size:0.78rem; font-weight:700; padding:6px 14px; border-radius:20px; background:linear-gradient(135deg,rgba(124,77,255,0.2),rgba(79,195,247,0.1)); border:1px solid rgba(124,77,255,0.3); color:#c4a8ff; display:inline-block;">
                    <i class="fas fa-crown me-1"></i>Premium
                </span>
            <?php endif; ?>
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

    <!-- Busca de usuários -->
    <section class="mb-5 fade-in">
        <div class="section-header">
            <h2><i class="fas fa-users me-2" style="color:#7c4dff;"></i>Buscar Usuários</h2>
        </div>
        <div class="user-search-wrap">
            <i class="fas fa-search user-search-icon"></i>
            <input type="text" id="user-search-input" class="user-search-input"
                placeholder="Pesquisar usuários pelo nome..." autocomplete="off">
            <div id="user-search-results" class="user-search-results"></div>
        </div>
    </section>
</main>

<!-- Modal Perfil Usuário -->
<div class="modal fade" id="modalPerfilUsuario" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:900px; max-height:90vh;">
        <div class="modal-content" style="max-height:90vh; display:flex; flex-direction:column;">
            <div class="perfil-usuario-header">
                <div class="perfil-usuario-avatar" id="perfil-avatar-img">👤</div>
                <div class="perfil-usuario-info">
                    <h5 id="perfil-nome"></h5>
                    <div class="perfil-usuario-sub" id="perfil-email"></div>
                    <div class="perfil-usuario-sub" id="perfil-plano"></div>
                    <div class="perfil-stats">
                        <div>
                            <strong id="perfil-seguidores">0</strong>
                            <small>Seguidores</small>
                        </div>
                        <div>
                            <strong id="perfil-seguindo">0</strong>
                            <small>Seguindo</small>
                        </div>
                        <div>
                            <strong id="perfil-avaliacoes">0</strong>
                            <small>Avaliações</small>
                        </div>
                    </div>
                </div>
                <div class="perfil-header-actions">
                    <button type="button" id="btn-seguir-modal" class="btn-seguir-perfil" style="display:none;"></button>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body" style="padding:20px; overflow-y:auto;">
                <div id="perfil-bio" class="mb-3" style="font-size:0.88rem; color:#ddd; font-style:italic;"></div>
                
                <!-- Avaliações -->
                <div class="mb-4">
                    <h6 style="font-size:0.85rem; color:#b3b3b3; margin-bottom:10px; font-weight:700;">
                        <i class="fas fa-comment-dots me-2" style="color:#7c4dff;"></i>Avaliações Recentes
                    </h6>
                    <div id="perfil-avaliacoes-list">
                        <p style="text-align:center; color:#b3b3b3; font-size:0.82rem;">Nenhuma avaliação com comentário</p>
                    </div>
                </div>

                <!-- Listas -->
                <div>
                    <h6 style="font-size:0.85rem; color:#b3b3b3; margin-bottom:10px; font-weight:700;">
                        <i class="fas fa-list me-2" style="color:#7c4dff;"></i>Listas
                    </h6>
                    <div id="perfil-listas-list" class="listas-grid">
                        <p style="color:#b3b3b3; font-size:0.82rem; grid-column:1/-1; text-align:center;">Nenhuma lista criada</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Avaliação -->
<div class="modal fade" id="modalAvaliar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content" style="background:rgba(15,15,25,0.97);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.1);border-radius:20px;color:#fff;">
            <div style="display:flex;gap:16px;align-items:center;padding:20px 20px 0;">
                <img id="av-capa" src="" alt="capa" style="width:72px;height:72px;border-radius:10px;object-fit:cover;flex-shrink:0;">
                <div style="flex:1;">
                    <h5 id="av-titulo" style="font-size:1rem;font-weight:700;margin:0 0 4px;"></h5>
                    <p id="av-artista" style="font-size:0.8rem;color:#b3b3b3;margin:0;"></p>
                </div>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:20px;">
                <input type="hidden" id="av-musica-id">
                <?php if (!$isPremium): ?>
                <div style="text-align:center;padding:20px 0 24px;background:rgba(255,255,255,0.03);border-radius:12px;border:1px dashed rgba(255,193,7,0.4);margin-bottom:16px;">
                    <i class="fas fa-lock" style="font-size:1.8rem;color:#ffc107;display:block;margin-bottom:10px;"></i>
                    <p style="font-weight:700;margin-bottom:4px;font-size:0.95rem;">Videoclipe exclusivo Premium</p>
                    <p style="color:#b3b3b3;font-size:0.8rem;margin-bottom:14px;">Assine o Premium para assistir e ouvir as prévias.</p>
                    <a href="premium.php" style="background:linear-gradient(135deg,#ffc107,#ff9800);color:#000;font-weight:700;font-size:0.82rem;padding:7px 20px;border-radius:20px;text-decoration:none;">
                        <i class="fas fa-bolt me-1"></i>Fazer Upgrade
                    </a>
                </div>
                <?php else: ?>
                <div id="yt-player-wrap" style="display:none;border-radius:12px;overflow:hidden;background:#000;margin-bottom:16px;">
                    <iframe id="yt-iframe" src="" allowfullscreen allow="autoplay;encrypted-media" style="width:100%;height:200px;display:block;border:none;"></iframe>
                </div>
                <div id="yt-no-video" style="display:none;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);border-radius:12px;padding:20px;text-align:center;color:#b3b3b3;font-size:0.82rem;margin-bottom:16px;">
                    <i class="fas fa-music mb-2" style="font-size:1.4rem;display:block;margin-bottom:8px;"></i>Prévia não disponível para esta música.
                </div>
                <?php endif; ?>
                <p style="font-size:0.8rem;color:#b3b3b3;margin-bottom:8px;">Sua nota</p>
                <div id="av-stars" style="display:flex;gap:6px;font-size:1.6rem;cursor:pointer;margin-bottom:16px;">
                    <span data-v="1" style="color:rgba(255,255,255,0.2);transition:color 0.15s;">★</span>
                    <span data-v="2" style="color:rgba(255,255,255,0.2);transition:color 0.15s;">★</span>
                    <span data-v="3" style="color:rgba(255,255,255,0.2);transition:color 0.15s;">★</span>
                    <span data-v="4" style="color:rgba(255,255,255,0.2);transition:color 0.15s;">★</span>
                    <span data-v="5" style="color:rgba(255,255,255,0.2);transition:color 0.15s;">★</span>
                </div>
                <p style="font-size:0.8rem;color:#b3b3b3;margin-bottom:8px;">Comentário</p>
                <textarea id="av-comentario" rows="3" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.12);border-radius:12px;color:#fff;resize:none;width:100%;padding:10px 14px;font-size:0.85rem;outline:none;" placeholder="Ex: Uma das melhores músicas..."></textarea>
                <button onclick="enviarAvaliacaoBuscar()" id="btn-enviar-av-buscar" style="margin-top:12px;background:linear-gradient(135deg,#7c4dff,#4fc3f7);border:none;color:#fff;border-radius:25px;padding:10px 0;font-weight:700;width:100%;font-size:0.95rem;cursor:pointer;">Enviar Avaliação</button>
                <div id="av-msg" style="display:none;margin-top:8px;text-align:center;font-size:0.85rem;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Denúncia -->
<div class="modal fade" id="modalDenuncia" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
        <div class="modal-content">
            <div style="padding:20px; border-bottom:1px solid rgba(255,255,255,0.08); display:flex; justify-content:space-between; align-items:center;">
                <h5 style="margin:0; font-size:1rem;"><i class="fas fa-flag me-2" style="color:#ff6b35;"></i>Denunciar Comentário</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:20px;">
                <p style="font-size:0.82rem; color:#b3b3b3; margin-bottom:14px;">Selecione o motivo da denúncia:</p>
                <div id="motivos-lista">
                    <button class="motivo-btn" onclick="selecionarMotivo(this)">🚫 Conteúdo ofensivo ou discurso de ódio</button>
                    <button class="motivo-btn" onclick="selecionarMotivo(this)">🔞 Conteúdo impróprio ou adulto</button>
                    <button class="motivo-btn" onclick="selecionarMotivo(this)">🤖 Spam ou conteúdo enganoso</button>
                    <button class="motivo-btn" onclick="selecionarMotivo(this)">⚠️ Assédio ou bullying</button>
                    <button class="motivo-btn" onclick="selecionarMotivo(this)">📋 Outro motivo</button>
                </div>
                <textarea id="denuncia-motivo-custom" class="resposta-input" style="border-radius:10px; width:100%; height:70px; resize:none; margin-top:10px; display:none;" placeholder="Descreva o motivo..."></textarea>
                <button id="btn-confirmar-denuncia" onclick="confirmarDenuncia()" style="margin-top:14px; background:linear-gradient(135deg,#ff6b35,#ff4081); border:none; border-radius:20px; color:#fff; padding:9px 20px; font-size:0.85rem; font-weight:700; cursor:pointer; width:100%;">
                    <i class="fas fa-flag me-2"></i>Confirmar Denúncia
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Visualizar Lista -->
<div class="modal fade" id="modalVisualizarLista" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:620px; max-height:90vh;">
        <div class="modal-content" style="max-height:90vh; display:flex; flex-direction:column;">
            <div class="lista-header">
                <div class="lista-header-info">
                    <div class="lista-header-capa" id="lista-modal-capa">🎵</div>
                    <div>
                        <h5 id="lista-modal-nome"></h5>
                        <small id="lista-modal-desc"></small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:16px 20px; overflow-y:auto;">
                <div id="lista-musicas-container" style="display:flex; flex-direction:column; gap:6px;">
                    <p style="text-align:center; color:#b3b3b3; padding:30px 0;">Carregando...</p>
                </div>
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
    // Abrir modal de avaliação
    function extrairVideoId(url) {
        if (!url) return null;
        const m = url.match(/(?:youtu\.be\/|[?&]v=|embed\/)([\w-]{11})/);
        return m ? m[1] : null;
    }

    document.querySelectorAll('#av-stars span').forEach(s => {
        s.addEventListener('mouseover', function() {
            const v = +this.dataset.v;
            document.querySelectorAll('#av-stars span').forEach(x => x.style.color = +x.dataset.v <= v ? '#ffc107' : 'rgba(255,255,255,0.2)');
        });
        s.addEventListener('mouseout', () => {
            const ativa = document.querySelector('#av-stars span.ativa');
            const val = ativa ? +ativa.dataset.v : 0;
            document.querySelectorAll('#av-stars span').forEach(x => x.style.color = +x.dataset.v <= val ? '#ffc107' : 'rgba(255,255,255,0.2)');
        });
        s.addEventListener('click', function() {
            document.querySelectorAll('#av-stars span').forEach(x => x.classList.remove('ativa'));
            this.classList.add('ativa');
            const val = +this.dataset.v;
            document.querySelectorAll('#av-stars span').forEach(x => x.style.color = +x.dataset.v <= val ? '#ffc107' : 'rgba(255,255,255,0.2)');
        });
    });

    function abrirAvaliar(musicaId, seed, titulo, artista, youtubeUrl) {
        document.getElementById('av-musica-id').value = musicaId;
        document.getElementById('av-titulo').textContent = titulo;
        document.getElementById('av-artista').textContent = artista;
        document.getElementById('av-comentario').value = '';
        document.getElementById('av-msg').style.display = 'none';
        document.querySelectorAll('#av-stars span').forEach(s => { s.classList.remove('ativa'); s.style.color = 'rgba(255,255,255,0.2)'; });

        const videoId = extrairVideoId(youtubeUrl);
        document.getElementById('av-capa').src = videoId
            ? 'https://img.youtube.com/vi/' + videoId + '/hqdefault.jpg'
            : 'https://picsum.photos/seed/' + seed + '/200';

        const playerWrap = document.getElementById('yt-player-wrap');
        const noVideo    = document.getElementById('yt-no-video');
        const iframe     = document.getElementById('yt-iframe');
        if (playerWrap && noVideo && iframe) {
            if (videoId) {
                iframe.src = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0';
                playerWrap.style.display = 'block';
                noVideo.style.display = 'none';
            } else {
                iframe.src = '';
                playerWrap.style.display = 'none';
                noVideo.style.display = 'block';
            }
        }

        // Fechar modal da lista antes de abrir o de avaliação
        const modalLista = bootstrap.Modal.getInstance(document.getElementById('modalVisualizarLista'));
        if (modalLista) modalLista.hide();
        setTimeout(() => new bootstrap.Modal(document.getElementById('modalAvaliar')).show(), 300);
    }

    document.getElementById('modalAvaliar').addEventListener('hide.bs.modal', () => {
        const iframe = document.getElementById('yt-iframe');
        if (iframe) iframe.src = '';
    });

    async function enviarAvaliacaoBuscar() {
        const musicaId = document.getElementById('av-musica-id').value;
        const comentario = document.getElementById('av-comentario').value;
        const ativa = document.querySelector('#av-stars span.ativa');
        const nota = ativa ? +ativa.dataset.v : 0;

        const msg = document.getElementById('av-msg');
        if (!nota) {
            msg.style.display = 'block'; msg.style.color = '#ff6b6b';
            msg.textContent = 'Por favor, selecione uma nota.';
            return;
        }

        const btn = document.getElementById('btn-enviar-av-buscar');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enviando...';

        const fd = new FormData();
        fd.append('musica_id', musicaId);
        fd.append('nota', nota);
        fd.append('comentario', comentario);

        const res = await fetch('api/avaliacoes/avaliar.php', { method: 'POST', body: fd }).catch(() => null);
        const data = res ? await res.json() : null;

        msg.style.display = 'block';
        if (data?.ok) {
            msg.style.color = '#1db954';
            msg.textContent = 'Avaliação salva com sucesso!';
            setTimeout(() => { bootstrap.Modal.getInstance(document.getElementById('modalAvaliar')).hide(); btn.disabled = false; btn.innerHTML = 'Enviar Avaliação'; }, 1500);
        } else {
            msg.style.color = '#ff6b6b';
            msg.textContent = data?.erro || 'Erro ao salvar.';
            btn.disabled = false;
            btn.innerHTML = 'Enviar Avaliação';
        }
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

    // Busca de usuários
    const userInput   = document.getElementById('user-search-input');
    const userResults = document.getElementById('user-search-results');
    let searchTimer;

    userInput.addEventListener('input', function() {
        clearTimeout(searchTimer);
        const q = this.value.trim();
        if (q.length < 2) { userResults.style.display = 'none'; return; }
        searchTimer = setTimeout(() => buscarUsuarios(q), 300);
    });

    document.addEventListener('click', e => {
        if (!userInput.contains(e.target) && !userResults.contains(e.target))
            userResults.style.display = 'none';
    });
    userInput.addEventListener('focus', () => {
        if (userInput.value.trim().length >= 2) userResults.style.display = 'block';
    });

    async function buscarUsuarios(q) {
        const res  = await fetch('api/usuarios/buscar.php?q=' + encodeURIComponent(q)).catch(() => null);
        const data = res ? await res.json() : null;
        if (!data?.ok || !data.usuarios.length) {
            userResults.innerHTML = '<div class="user-search-empty"><i class="fas fa-user-slash me-2"></i>Nenhum usuário encontrado</div>';
            userResults.style.display = 'block';
            return;
        }
        userResults.innerHTML = data.usuarios.map(u => {
            const inicial = u.nome.charAt(0).toUpperCase();
            const avatar  = u.foto ? `<img src="${u.foto}" alt="${u.nome}">` : inicial;
            const seguindo = u.eu_sigo == 1;
            const meta = [];
            if (u.total_avaliacoes > 0) meta.push(`${u.total_avaliacoes} avaliações`);
            if (u.total_seguidores > 0) meta.push(`${u.total_seguidores} seguidores`);
            if (u.plano === 'premium') meta.push('⭐ Premium');
            return `
            <div class="user-result-item" onclick="abrirPerfilUsuario(${u.id})" style="cursor:pointer;">
                <div class="user-result-avatar">${avatar}</div>
                <div class="user-result-info">
                    <strong>${u.nome}</strong>
                    <span>${meta.join(' • ') || 'Novo por aqui'}</span>
                </div>
                <button class="btn-seguir ${seguindo ? 'seguindo' : 'seguir'}"
                    data-id="${u.id}" data-seguindo="${seguindo ? '1' : '0'}"
                    onclick="event.stopPropagation(); toggleSeguir(this)">
                    ${seguindo ? '<i class="fas fa-user-check me-1"></i>Seguindo' : '<i class="fas fa-user-plus me-1"></i>Seguir'}
                </button>
            </div>`;
        }).join('');
        userResults.style.display = 'block';
    }

    async function toggleSeguir(btn) {
        const id       = btn.dataset.id;
        const seguindo = btn.dataset.seguindo === '1';
        const acao     = seguindo ? 'desseguir' : 'seguir';
        btn.disabled   = true;
        const fd = new FormData();
        fd.append('seguido_id', id);
        fd.append('acao', acao);
        const res  = await fetch('api/usuarios/seguir.php', { method: 'POST', body: fd }).catch(() => null);
        const data = res ? await res.json() : null;
        if (data?.ok) {
            const agora = data.seguindo;
            btn.dataset.seguindo = agora ? '1' : '0';
            btn.className = 'btn-seguir ' + (agora ? 'seguindo' : 'seguir');
            btn.innerHTML = agora
                ? '<i class="fas fa-user-check me-1"></i>Seguindo'
                : '<i class="fas fa-user-plus me-1"></i>Seguir';
        }
        btn.disabled = false;
    }

    // Abrir perfil do usuário pesquisado
    async function abrirPerfilUsuario(userId) {
        const res = await fetch('api/perfil/visualizar.php?id=' + userId).catch(() => null);
        const data = res ? await res.json() : null;

        if (!data?.ok) {
            alert('Erro ao carregar perfil');
            return;
        }

        const u = data.usuario;
        const notas = ['🎧', '🎵', '🎶', '🎤', '🎼', '🎸'];
        window.perfilUsuarioAtual = { id: u.id, nome: u.nome, eu_sigo: data.eu_sigo };

        // Atualizar header do perfil
        document.getElementById('perfil-nome').textContent = u.nome;
        document.getElementById('perfil-email').textContent = u.email || '';
        document.getElementById('perfil-plano').innerHTML = `
            <span class="badge-plano">${u.plano}</span>
            ${u.perfil === 'admin' ? '<span class="badge-plano" style="background: linear-gradient(135deg,#ff4081,#f50057); margin-left:6px;">Admin</span>' : ''}
        `;

        // Avatar
        const avatarEl = document.getElementById('perfil-avatar-img');
        if (u.foto) {
            avatarEl.innerHTML = `<img src="${u.foto}" alt="${u.nome}">`;
        } else {
            avatarEl.innerHTML = u.nome.charAt(0).toUpperCase();
        }

        // Stats
        document.getElementById('perfil-seguidores').textContent = data.total_seguidores;
        document.getElementById('perfil-seguindo').textContent = data.total_seguindo;
        document.getElementById('perfil-avaliacoes').textContent = data.total_avaliacoes;

        // Bio
        const bioEl = document.getElementById('perfil-bio');
        if (u.bio) {
            bioEl.innerHTML = `"${u.bio}"`;
        } else {
            bioEl.innerHTML = '';
        }

        // Botão de Seguir/Parar de Seguir
        const btnSeguir = document.getElementById('btn-seguir-modal');
        btnSeguir.style.display = 'block';
        btnSeguir.dataset.userId = userId;
        btnSeguir.className = 'btn-seguir-perfil' + (data.eu_sigo ? ' seguindo' : '');
        btnSeguir.innerHTML = data.eu_sigo 
            ? '<i class="fas fa-user-check me-1"></i>Seguindo'
            : '<i class="fas fa-user-plus me-1"></i>Seguir';
        btnSeguir.onclick = () => toggleSeguirModal(btnSeguir);

        // Avaliações com melhor renderização
        const avaliacoesEl = document.getElementById('perfil-avaliacoes-list');
        if (data.avaliacoes.length > 0) {
            avaliacoesEl.innerHTML = data.avaliacoes.map((av, i) => `
                <div class="comentario-card">
                    <div class="comentario-musica">
                        <span style="font-size:1.4rem;">${notas[i % notas.length]}</span>
                        <div class="comentario-musica-info">
                            <strong>${av.titulo}</strong>
                            <small>${av.artista}</small>
                        </div>
                        <span style="color:#ffc107; font-size:0.8rem; white-space:nowrap;">
                            ${'★'.repeat(Math.round(av.nota))}${'☆'.repeat(5 - Math.round(av.nota))}
                        </span>
                    </div>
                    <p class="comentario-texto">"${av.comentario}"</p>
                    <div class="comentario-footer">
                        <span>${new Date(av.data_avaliacao).toLocaleDateString('pt-BR')}</span>
                        <div class="comentario-acoes">
                            <button class="btn-acao" onclick="curtirComentario(this, ${av.id})" title="Curtir">
                                <i class="far fa-heart"></i> Curtir
                            </button>
                            <button class="btn-acao" onclick="responderComentario(${av.id})" title="Responder">
                                <i class="fas fa-reply"></i> Responder
                            </button>
                            <button class="btn-acao" data-denuncia-id="${av.id}" onclick="denunciarComentario(${av.id})" title="Denunciar">
                                <i class="fas fa-flag"></i> Denunciar
                            </button>
                        </div>
                    </div>
                    <div class="respostas-container" id="respostas-${av.id}">
                        <div class="respostas-lista"></div>
                        <div class="resposta-input-wrap">
                            <input type="text" id="input-resposta-${av.id}" class="resposta-input" placeholder="Escreva uma resposta..." onkeydown="if(event.key==='Enter') enviarResposta(${av.id})">
                            <button class="btn-enviar-resposta" onclick="enviarResposta(${av.id})"><i class="fas fa-paper-plane"></i></button>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            avaliacoesEl.innerHTML = '<p style="text-align:center; color:#b3b3b3; font-size:0.82rem;">Nenhuma avaliação com comentário</p>';
        }

        // Listas
        const listasEl = document.getElementById('perfil-listas-list');
        if (data.listas.length > 0) {
            listasEl.innerHTML = data.listas.map((l, i) => `
                <div class="lista-card" onclick="abrirVisualizadorLista(${l.id}, '${l.nome.replace(/'/g, "\\'").replace(/"/g, '&quot;')}', '${(l.descricao || '').replace(/'/g, "\\'").replace(/"/g, '&quot;')}')">
                    <div class="lista-card-capa">${notas[i % notas.length]}</div>
                    <div class="lista-card-body">
                        <div class="lista-card-nome" title="${l.nome}">${l.nome}</div>
                        <div class="lista-card-count"><i class="fas fa-music" style="font-size:0.65rem;color:#7c4dff;"></i>${l.total_musicas} música${l.total_musicas != 1 ? 's' : ''}</div>
                    </div>
                </div>
            `).join('');
        } else {
            listasEl.innerHTML = '<p style="color:#b3b3b3; font-size:0.82rem; grid-column:1/-1; text-align:center;">Nenhuma lista criada</p>';
        }

        // Abrir modal
        new bootstrap.Modal(document.getElementById('modalPerfilUsuario')).show();
    }

    // Seguir/Parar de Seguir a partir do modal
    async function toggleSeguirModal(btn) {
        const userId = btn.dataset.userId;
        const seguindo = btn.classList.contains('seguindo');
        const acao = seguindo ? 'desseguir' : 'seguir';
        
        btn.disabled = true;
        const fd = new FormData();
        fd.append('seguido_id', userId);
        fd.append('acao', acao);
        
        const res = await fetch('api/usuarios/seguir.php', { method: 'POST', body: fd }).catch(() => null);
        const data = res ? await res.json() : null;
        
        if (data?.ok) {
            const agora = data.seguindo;
            btn.classList.toggle('seguindo');
            btn.innerHTML = agora 
                ? '<i class="fas fa-user-check me-1"></i>Seguindo'
                : '<i class="fas fa-user-plus me-1"></i>Seguir';
            
            // Atualizar stats
            const currentCount = parseInt(document.getElementById('perfil-seguidores').textContent);
            document.getElementById('perfil-seguidores').textContent = agora ? currentCount + 1 : Math.max(0, currentCount - 1);
        }
        btn.disabled = false;
    }

    // Curtir comentário
    async function curtirComentario(btn, avaliacaoId) {
        if (!avaliacaoId) return;
        btn.classList.toggle('curtido');
        btn.innerHTML = btn.classList.contains('curtido')
            ? '<i class="fas fa-heart"></i> Curtido'
            : '<i class="far fa-heart"></i> Curtir';
    }

    // Respostas
    async function responderComentario(avaliacaoId) {
        if (!avaliacaoId) return;
        const wrap = document.getElementById('respostas-' + avaliacaoId);
        if (!wrap) return;
        const aberto = wrap.style.display === 'block';
        if (aberto) { wrap.style.display = 'none'; return; }
        wrap.style.display = 'block';
        const lista = wrap.querySelector('.respostas-lista');
        lista.innerHTML = '<span style="font-size:0.78rem;color:#b3b3b3;">Carregando...</span>';
        const res = await fetch('api/comentarios/listar.php?avaliacao_id=' + avaliacaoId).catch(() => null);
        const data = res ? await res.json() : null;
        renderRespostas(lista, data?.comentarios || []);
    }

    function renderRespostas(lista, comentarios) {
        if (!comentarios.length) {
            lista.innerHTML = '<span style="font-size:0.78rem;color:#b3b3b3;">Nenhuma resposta ainda.</span>';
            return;
        }
        lista.innerHTML = comentarios.map(c => {
            const av = c.foto ? `<img src="${c.foto}" alt="">` : c.nome.charAt(0).toUpperCase();
            return `<div class="resposta-item">
                <div class="resposta-avatar">${av}</div>
                <div class="resposta-corpo">
                    <strong>${c.nome} <small style="color:#b3b3b3;font-weight:400;">${new Date(c.data_comentario).toLocaleDateString('pt-BR')}</small></strong>
                    <p>${c.conteudo}</p>
                </div>
            </div>`;
        }).join('');
    }

    async function enviarResposta(avaliacaoId) {
        const input = document.getElementById('input-resposta-' + avaliacaoId);
        const texto = input.value.trim();
        if (!texto) return;
        const fd = new FormData();
        fd.append('avaliacao_id', avaliacaoId);
        fd.append('conteudo', texto);
        const res = await fetch('api/comentarios/criar.php', { method: 'POST', body: fd }).catch(() => null);
        const data = res ? await res.json() : null;
        if (data?.ok) {
            input.value = '';
            const lista = document.querySelector('#respostas-' + avaliacaoId + ' .respostas-lista');
            const c = data.comentario;
            const av = c.foto ? `<img src="${c.foto}" alt="">` : c.nome.charAt(0).toUpperCase();
            lista.innerHTML += `<div class="resposta-item">
                <div class="resposta-avatar">${av}</div>
                <div class="resposta-corpo">
                    <strong>${c.nome} <small style="color:#b3b3b3;font-weight:400;">agora</small></strong>
                    <p>${c.conteudo}</p>
                </div>
            </div>`;
            // Limpar mensagem de vazio se existia
            const empty = lista.querySelector('span');
            if (empty) empty.remove();
        }
    }

    // Denúncia
    let _denunciaAvaliacaoId = null;
    let _denunciaMotivoSelecionado = null;

    function denunciarComentario(avaliacaoId) {
        if (!avaliacaoId) return;
        _denunciaAvaliacaoId = avaliacaoId;
        _denunciaMotivoSelecionado = null;
        document.querySelectorAll('.motivo-btn').forEach(b => b.classList.remove('selecionado'));
        document.getElementById('denuncia-motivo-custom').style.display = 'none';
        document.getElementById('denuncia-motivo-custom').value = '';
        new bootstrap.Modal(document.getElementById('modalDenuncia')).show();
    }

    function selecionarMotivo(btn) {
        document.querySelectorAll('.motivo-btn').forEach(b => b.classList.remove('selecionado'));
        btn.classList.add('selecionado');
        _denunciaMotivoSelecionado = btn.textContent.replace(/^[^a-zA-Z]+/, '').trim();
        const custom = document.getElementById('denuncia-motivo-custom');
        if (btn.textContent.includes('Outro')) {
            custom.style.display = 'block';
            _denunciaMotivoSelecionado = null;
        } else {
            custom.style.display = 'none';
        }
    }

    async function confirmarDenuncia() {
        const custom = document.getElementById('denuncia-motivo-custom');
        const motivo = _denunciaMotivoSelecionado || custom.value.trim();
        if (!motivo) { custom.focus(); return; }
        const fd = new FormData();
        fd.append('avaliacao_id', _denunciaAvaliacaoId);
        fd.append('motivo', motivo);
        const res = await fetch('api/denuncias/criar.php', { method: 'POST', body: fd }).catch(() => null);
        const data = res ? await res.json() : null;
        bootstrap.Modal.getInstance(document.getElementById('modalDenuncia')).hide();
        if (data?.ok) {
            // Marcar botão como denunciado
            const btn = document.querySelector(`[data-denuncia-id="${_denunciaAvaliacaoId}"]`);
            if (btn) { btn.classList.add('denunciado'); btn.innerHTML = '<i class="fas fa-flag"></i> Denunciado'; btn.disabled = true; }
        } else {
            alert(data?.erro || 'Erro ao registrar denúncia');
        }
    }

    // Abrir visualizador de lista
    async function abrirVisualizadorLista(listaId, listaNome, listaDesc) {
        const container = document.getElementById('lista-musicas-container');
        container.innerHTML = '<p style="text-align:center;color:#b3b3b3;padding:30px 0;"><i class="fas fa-spinner fa-spin me-2"></i>Carregando...</p>';
        document.getElementById('lista-modal-nome').textContent = listaNome;
        document.getElementById('lista-modal-desc').textContent = listaDesc || '';
        document.getElementById('lista-modal-capa').textContent = '🎵';
        new bootstrap.Modal(document.getElementById('modalVisualizarLista')).show();

        const res = await fetch(`api/listas/visualizar.php?id=${listaId}`).catch(() => null);
        const data = res ? await res.json() : null;

        if (!data?.ok) {
            container.innerHTML = '<p style="text-align:center;color:#ff6b6b;padding:30px 0;"><i class="fas fa-exclamation-circle me-2"></i>Erro ao carregar lista</p>';
            return;
        }

        const musicas = data.musicas || [];
        const notas = ['🎧', '🎵', '🎶', '🎤', '🎼', '🎸'];
        document.getElementById('lista-modal-capa').textContent = notas[musicas.length % notas.length];
        document.getElementById('lista-modal-desc').textContent = listaDesc
            ? `${listaDesc} • ${musicas.length} música${musicas.length !== 1 ? 's' : ''}`
            : `${musicas.length} música${musicas.length !== 1 ? 's' : ''}`;

        if (musicas.length === 0) {
            container.innerHTML = '<p style="text-align:center;color:#b3b3b3;padding:30px 0;"><i class="fas fa-music me-2"></i>Nenhuma música nesta lista</p>';
            return;
        }

        container.innerHTML = musicas.map((m, i) => `
            <div class="musica-row" onclick="abrirAvaliar('${m.id}', 'lista${m.id}', '${m.titulo.replace(/'/g,"\\'").replace(/"/g,'&quot;')}', '${m.artista.replace(/'/g,"\\'").replace(/"/g,'&quot;')}', '${(m.spotify_link||'').replace(/'/g,"\\'")}')"
                style="cursor:pointer;">
                <span class="musica-row-num">${i + 1}</span>
                <span class="musica-row-play"><i class="fas fa-play"></i></span>
                <div class="musica-row-icon">${notas[i % notas.length]}</div>
                <div class="musica-row-info">
                    <strong>${m.titulo}</strong>
                    <small>${m.artista}${m.album ? ' • ' + m.album : ''}</small>
                </div>
                <span class="musica-row-duracao">${m.duracao || '--:--'}</span>
            </div>
        `).join('');
    }
</script>
</body>
</html>

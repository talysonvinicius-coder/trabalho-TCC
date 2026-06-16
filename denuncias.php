<!DOCTYPE html>
<?php
session_start();
if (empty($_SESSION['logado']) || $_SESSION['perfil'] !== 'admin') {
    header('Location: index.html');
    exit();
}
?>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundScore - Denúncias</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/navbarPag.css">
    <style>
        body { display: flex; height: 100vh; overflow: hidden; }
        .content { margin-left: 240px; width: calc(100% - 240px); overflow-y: auto; padding: 20px 32px 80px; }

        /* Top bar */
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; }
        .search-bar-top { display: flex; align-items: center; background: #2a2a2a; border-radius: 20px; padding: 8px 16px; gap: 10px; width: 280px; transition: background 0.2s; }
        .search-bar-top:focus-within { background: #333; }
        .search-bar-top i { color: #b3b3b3; font-size: 0.85rem; }
        .search-bar-top input { background: none; border: none; outline: none; color: #fff; font-size: 0.85rem; width: 100%; }
        .search-bar-top input::placeholder { color: #b3b3b3; }
        .badge-upgrade { background: transparent; border: 1px solid #fff; color: #fff; font-size: 0.78rem; font-weight: 700; padding: 6px 14px; border-radius: 20px; text-decoration: none; transition: background 0.2s, color 0.2s; }
        .badge-upgrade:hover { background: #fff; color: #000; }

        /* Section header */
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .section-header h2 { font-size: 1.3rem; font-weight: 700; margin: 0; }

        /* Botão voltar */
        .btn-voltar { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 20px; padding: 9px 20px; border-radius: 25px; background: rgba(124,77,255,0.12); border: 1px solid rgba(124,77,255,0.35); color: #c4a8ff; font-size: 0.85rem; font-weight: 600; text-decoration: none; transition: background 0.2s, border-color 0.2s, transform 0.2s; }
        .btn-voltar:hover { background: rgba(124,77,255,0.25); border-color: #7c4dff; color: #fff; transform: translateX(-3px); }
        .btn-voltar i { transition: transform 0.2s; }
        .btn-voltar:hover i { transform: translateX(-3px); }

        /* Filtros */
        .filtros { display: flex; gap: 8px; margin-bottom: 24px; flex-wrap: wrap; }
        .btn-filtro {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: #b3b3b3;
            padding: 7px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn-filtro:hover { background: rgba(124,77,255,0.15); border-color: rgba(124,77,255,0.4); color: #fff; }
        .btn-filtro.ativo { background: linear-gradient(135deg, #7c4dff, #4fc3f7); border-color: transparent; color: #fff; }

        /* Card de denúncia */
        .denuncia-card {
            background: #181818;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 16px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .denuncia-card:hover { border-color: rgba(124,77,255,0.3); box-shadow: 0 8px 24px rgba(124,77,255,0.1); }

        .denuncia-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding-bottom: 14px; border-bottom: 1px solid rgba(255,255,255,0.06); }
        .denuncia-id { font-size: 0.82rem; color: #b3b3b3; font-weight: 600; }

        .badge-motivo { background: rgba(229,57,53,0.15); border: 1px solid rgba(229,57,53,0.3); color: #ef5350; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }

        /* Status badges */
        .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 600; }
        .status-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
        .status-pendente  { background: rgba(255,193,7,0.12);  color: #ffc107; border: 1px solid rgba(255,193,7,0.3); }
        .status-aceita    { background: rgba(29,185,84,0.12);  color: #1db954; border: 1px solid rgba(29,185,84,0.3); }
        .status-rejeitada { background: rgba(229,57,53,0.12);  color: #ef5350; border: 1px solid rgba(229,57,53,0.3); }

        /* Comentário denunciado */
        .comentario-denunciado { background: rgba(124,77,255,0.06); border-left: 3px solid #7c4dff; padding: 14px; border-radius: 8px; margin-bottom: 14px; }

        .usuario-info { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
        .usuario-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #7c4dff, #4fc3f7); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 0.85rem; flex-shrink: 0; }
        .usuario-nome { font-weight: 600; color: #fff; display: block; font-size: 0.88rem; }
        .usuario-email { font-size: 0.78rem; color: #b3b3b3; }

        .conteudo-comentario { color: #ddd; padding: 10px 12px; background: rgba(255,255,255,0.04); border-radius: 6px; font-size: 0.88rem; line-height: 1.5; margin-bottom: 8px; }
        .data-info { font-size: 0.78rem; color: #b3b3b3; }

        /* Motivo */
        .motivo-denuncia { background: rgba(229,57,53,0.07); border-left: 3px solid #ef5350; padding: 12px; border-radius: 8px; margin-bottom: 14px; }
        .motivo-titulo { font-weight: 600; color: #ef5350; font-size: 0.82rem; margin-bottom: 4px; }
        .motivo-texto { color: #ddd; font-size: 0.88rem; }

        /* Ações */
        .acoes-denuncia { display: flex; gap: 8px; justify-content: flex-end; padding-top: 14px; border-top: 1px solid rgba(255,255,255,0.06); }
        .btn-aceitar { background: rgba(29,185,84,0.15); border: 1px solid rgba(29,185,84,0.3); color: #1db954; padding: 7px 16px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: filter 0.2s, transform 0.15s; display: flex; align-items: center; gap: 6px; }
        .btn-rejeitar { background: rgba(229,57,53,0.12); border: 1px solid rgba(229,57,53,0.3); color: #ef5350; padding: 7px 16px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: filter 0.2s, transform 0.15s; display: flex; align-items: center; gap: 6px; }
        .btn-aceitar:hover, .btn-rejeitar:hover { filter: brightness(1.2); transform: scale(1.04); }

        /* Vazio */
        .sem-denuncias { text-align: center; padding: 60px 20px; color: #b3b3b3; }
        .sem-denuncias i { font-size: 3rem; margin-bottom: 16px; color: #7c4dff; opacity: 0.5; }
        .sem-denuncias h3 { color: #fff; margin-bottom: 8px; font-size: 1.1rem; }

        /* Toast */
        .toast-notification { position: fixed; top: 20px; right: 20px; background: linear-gradient(135deg, #7c4dff, #4fc3f7); color: #fff; padding: 12px 20px; border-radius: 10px; box-shadow: 0 4px 16px rgba(124,77,255,0.4); z-index: 9999; display: none; font-size: 0.88rem; font-weight: 600; }
        .toast-notification.show { display: flex; align-items: center; gap: 8px; animation: slideIn 0.3s ease; }
        @keyframes slideIn { from { transform: translateX(300px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

        /* Fade-in */
        .fade-in { opacity: 0; transform: translateY(20px); transition: opacity 0.5s ease, transform 0.5s ease; }
        .fade-in.visible { opacity: 1; transform: translateY(0); }

        /* Footer */
        .player { padding: 0; overflow: hidden; height: 50px; position: fixed; bottom: 0; left: 240px; width: calc(100% - 240px); z-index: 40; }
        .player img { width: 100%; height: 100%; object-fit: cover; display: block; transition: opacity 1s ease; }
    </style>
</head>
<body>

<?php include __DIR__ . '/navbar.php'; ?>

<main class="content">
    <header class="top-bar">
        <div class="search-bar-top">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Buscar denúncias...">
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="badge-upgrade"><i class="fas fa-shield-alt me-1"></i>Admin</span>
        </div>
    </header>

    <a href="homeAdmin.php" class="btn-voltar fade-in">
        <i class="fas fa-arrow-left"></i> Voltar
    </a>

    <section class="fade-in">
        <div class="section-header">
            <h2><i class="fas fa-flag me-2" style="color:#7c4dff;"></i>Denúncias</h2>
        </div>

        <div class="filtros">
            <?php $filtroAtual = $_GET['filtro'] ?? 'pendentes'; ?>
            <button class="btn-filtro <?php echo $filtroAtual === 'pendentes' ? 'ativo' : ''; ?>" onclick="filtrarDenuncias('pendentes', this)"><i class="fas fa-hourglass-half me-1"></i>Pendentes</button>
            <button class="btn-filtro <?php echo $filtroAtual === 'aceitas' ? 'ativo' : ''; ?>" onclick="filtrarDenuncias('aceitas', this)"><i class="fas fa-check me-1"></i>Aceitas</button>
            <button class="btn-filtro <?php echo $filtroAtual === 'rejeitadas' ? 'ativo' : ''; ?>" onclick="filtrarDenuncias('rejeitadas', this)"><i class="fas fa-times me-1"></i>Rejeitadas</button>
            <button class="btn-filtro <?php echo $filtroAtual === 'todas' ? 'ativo' : ''; ?>" onclick="filtrarDenuncias('todas', this)"><i class="fas fa-list me-1"></i>Todas</button>
        </div>

        <div id="denuncias-list">
            <?php
            require_once __DIR__ . '/model/dao/Conexao.php';
            try {
                $pdo = Conexao::getConexao();

                $filtro = $_GET['filtro'] ?? 'pendentes';
                $whereClause = match($filtro) {
                    'aceitas'    => "WHERE d.status = 'Resolvida'",
                    'rejeitadas' => "WHERE d.status = 'Rejeitada'",
                    'todas'      => '',
                    default      => "WHERE d.status = 'Pendente'",
                };
                $statusBadgeClass = match($filtro) {
                    'aceitas'    => 'status-aceita',
                    'rejeitadas' => 'status-rejeitada',
                    default      => 'status-pendente',
                };

                $query = "
                    SELECT d.id, d.motivo, d.status, d.data_denuncia,
                           c.conteudo, c.data_comentario,
                           u_comentario.id as usuario_comentario_id,
                           u_comentario.nome as usuario_comentario_nome,
                           l.email as usuario_email,
                           u_denuncia.nome as usuario_denuncia_nome
                    FROM denuncias d
                    INNER JOIN comentarios c ON d.comentario_id = c.id
                    INNER JOIN usuario u_comentario ON c.usuario_id = u_comentario.id
                    INNER JOIN login l ON u_comentario.id = l.usuario_id
                    INNER JOIN usuario u_denuncia ON d.usuario_id = u_denuncia.id
                    $whereClause
                    ORDER BY d.data_denuncia DESC
                ";
                $denuncias = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

                if (count($denuncias) > 0):
                    foreach ($denuncias as $d):
                        $letra = strtoupper(mb_substr($d['usuario_comentario_nome'], 0, 1));
                        $dataFormatada = date('d/m/Y H:i', strtotime($d['data_denuncia']));
                        $badgeClass = match($d['status']) {
                            'Resolvida' => 'status-aceita',
                            'Rejeitada' => 'status-rejeitada',
                            default     => 'status-pendente',
                        };
            ?>
            <div class="denuncia-card" data-status="<?php echo htmlspecialchars($d['status']); ?>">
                <div class="denuncia-header">
                    <div class="d-flex align-items-center gap-2">
                        <span class="denuncia-id">#<?php echo $d['id']; ?></span>
                        <span class="status-badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($d['status']); ?></span>
                    </div>
                    <span class="badge-motivo"><i class="fas fa-flag me-1"></i><?php echo htmlspecialchars($d['motivo'] ?? 'Sem motivo'); ?></span>
                </div>

                <div class="comentario-denunciado">
                    <div class="usuario-info">
                        <div class="usuario-avatar"><?php echo $letra; ?></div>
                        <div>
                            <span class="usuario-nome"><?php echo htmlspecialchars($d['usuario_comentario_nome']); ?></span>
                            <span class="usuario-email"><?php echo htmlspecialchars($d['usuario_email']); ?></span>
                        </div>
                    </div>
                    <div class="conteudo-comentario"><?php echo htmlspecialchars($d['conteudo']); ?></div>
                    <div class="data-info"><i class="fas fa-calendar-alt me-1"></i>Comentado em: <?php echo date('d/m/Y H:i', strtotime($d['data_comentario'])); ?></div>
                </div>

                <div class="motivo-denuncia">
                    <div class="motivo-titulo"><i class="fas fa-info-circle me-1"></i>Motivo da Denúncia</div>
                    <div class="motivo-texto"><?php echo htmlspecialchars($d['motivo'] ?? 'Sem detalhes'); ?></div>
                </div>

                <div class="data-info mb-3">
                    <i class="fas fa-user me-1"></i>Denunciado por: <strong><?php echo htmlspecialchars($d['usuario_denuncia_nome'] ?? 'Anônimo'); ?></strong>
                    &nbsp;•&nbsp;
                    <i class="fas fa-calendar-alt me-1"></i><?php echo $dataFormatada; ?>
                </div>

                <div class="acoes-denuncia">
                    <button class="btn-rejeitar" onclick="rejeitarDenuncia(<?php echo $d['id']; ?>)">
                        <i class="fas fa-times"></i>Rejeitar
                    </button>
                    <button class="btn-aceitar" onclick="aceitarDenuncia(<?php echo $d['id']; ?>, <?php echo $d['usuario_comentario_id']; ?>)">
                        <i class="fas fa-check"></i>Aceitar
                    </button>
                </div>
            </div>
            <?php
                    endforeach;
                else:
            ?>
            <div class="sem-denuncias">
                <i class="fas fa-check-circle"></i>
                <h3>Nenhuma denúncia pendente</h3>
                <p>Não há denúncias aguardando revisão no momento.</p>
            </div>
            <?php
                endif;
            } catch (Exception $e) {
                echo '<div style="color:#ef5350; padding:20px;">Erro ao buscar denúncias: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
            ?>
        </div>
    </section>
</main>

<div class="toast-notification" id="toast">
    <i class="fas fa-check-circle"></i>
    <span id="toast-message">Ação realizada com sucesso</span>
</div>

<footer class="player">
    <img id="slideshow-img" src="https://img.freepik.com/vetores-premium/um-cartaz-de-publicidade-musical-com-um-disco-de-vinil_606304-808.jpg" alt="banner">
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>
<script>
    function aceitarDenuncia(denunciaId, usuarioComentarioId) {
        if (!confirm('Aceitar esta denúncia? O comentário será removido.')) return;
        fetch('api/denuncias/aceitar.php', { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify({ denuncia_id: denunciaId, usuario_comentario_id: usuarioComentarioId }) })
        .then(r => r.json()).then(data => {
            mostrarToast(data.sucesso ? 'Denúncia aceita com sucesso!' : 'Erro: ' + data.mensagem);
            if (data.sucesso) setTimeout(() => location.reload(), 1500);
        });
    }

    function rejeitarDenuncia(denunciaId) {
        if (!confirm('Rejeitar esta denúncia?')) return;
        fetch('api/denuncias/rejeitar.php', { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify({ denuncia_id: denunciaId }) })
        .then(r => r.json()).then(data => {
            mostrarToast(data.sucesso ? 'Denúncia rejeitada!' : 'Erro: ' + data.mensagem);
            if (data.sucesso) setTimeout(() => location.reload(), 1500);
        });
    }

    function filtrarDenuncias(filtro, btn) {
        document.querySelectorAll('.btn-filtro').forEach(b => b.classList.remove('ativo'));
        btn.classList.add('ativo');
        window.location.href = '?filtro=' + filtro;
    }

    function mostrarToast(msg) {
        const t = document.getElementById('toast');
        document.getElementById('toast-message').textContent = msg;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 3000);
    }

    // Slideshow
    const slides = [
        'https://img.freepik.com/vetores-premium/um-cartaz-de-publicidade-musical-com-um-disco-de-vinil_606304-808.jpg',
        'https://png.pngtree.com/thumb_back/fh260/background/20221224/pngtree-blue-musical-notes-background-image_1530362.jpg',
        'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT_5qs5i8zvZ2TQptBz3UOxKhFS-Te9heBoDA&s',
    ];
    let current = 0;
    const img = document.getElementById('slideshow-img');
    setInterval(() => { img.style.opacity='0'; setTimeout(() => { current=(current+1)%slides.length; img.src=slides[current]; img.style.opacity='1'; }, 1000); }, 30000);

    // Fade-in
    const observer = new IntersectionObserver(entries => { entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); }); }, { threshold: 0.1 });
    document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
</script>
</body>
</html>

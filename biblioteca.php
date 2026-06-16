<?php
session_start();
$listasUsuario = [];
$isPremium = isset($_SESSION['plano_id']) && $_SESSION['plano_id'] == 2 && isset($_SESSION['id']);

if ($isPremium) {
    require_once 'model/dao/ListasDAO.php';
    $listaDAO = new ListasDAO();
    $listasUsuario = $listaDAO->listarPorUsuario($_SESSION['id']);
}

$musicas   = [];
$media     = 0;
$total     = 0;
$contagem  = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];

if (!empty($_SESSION['id'])) {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=bdmusica;charset=utf8mb4', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Últimas 6 músicas avaliadas pelo usuário
        $stmt = $pdo->prepare("
            SELECT av.nota, av.data_avaliacao,
                   m.titulo, m.spotify_link,
                   a.nome AS artista,
                   g.nome AS genero
            FROM avaliacoes av
            JOIN musicas m  ON av.musica_id  = m.id
            JOIN artista a  ON m.artista_id  = a.id
            LEFT JOIN genero g ON m.genero_id = g.id
            WHERE av.usuario_id = :uid
            ORDER BY av.data_avaliacao DESC
            LIMIT 6
        ");
        $stmt->execute(['uid' => $_SESSION['id']]);
        $musicas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Média e contagem de todas as avaliações do usuário
        $stmt2 = $pdo->prepare("
            SELECT nota, COUNT(*) AS qtd
            FROM avaliacoes
            WHERE usuario_id = :uid
            GROUP BY nota
        ");
        $stmt2->execute(['uid' => $_SESSION['id']]);
        $rows = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        $soma = 0;
        foreach ($rows as $r) {
            $n = (int) round($r['nota']);
            $q = (int) $r['qtd'];
            if (isset($contagem[$n])) $contagem[$n] += $q;
            $soma  += $r['nota'] * $q;
            $total += $q;
        }
        $media = $total > 0 ? round($soma / $total, 1) : 0;

    } catch (PDOException $e) {
        error_log($e->getMessage());
    }
}

function ytThumbBib($url) {
    if (!$url) return null;
    preg_match('/(?:youtu\.be\/|[?&]v=|embed\/|shorts\/)([\w-]{11})/', $url, $m);
    return isset($m[1]) ? 'https://img.youtube.com/vi/' . $m[1] . '/hqdefault.jpg' : null;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundScore - Biblioteca</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/navbarPag.css">
    <link rel="stylesheet" href="assets/css/teste.css">
    <link rel="stylesheet" href="assets/css/biblioteca.css">
    <link rel="stylesheet" href="assets/css/listas.css">
    <style>
        body { display: flex; height: 100vh; overflow: hidden; }
        main.content { margin-left: 240px; width: calc(100% - 240px); overflow-y: auto; padding: 20px 32px 80px; }
    </style>
</head>
<body>

<?php include __DIR__ . '/navbarPag.php'; ?>

    <main class="content">
        <header class="top-bar">
            <div class="search-bar-top">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar na biblioteca...">
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

        <!-- Minhas Listas -->
        <div class="container-listas mb-5" style="padding: 0;">
            <header class="header-listas mb-3" style="display:flex; justify-content:space-between; align-items:center;">
                <div class="section-title mb-0"><i class="fas fa-list me-2" style="color:#7c4dff;"></i>Minhas Listas</div>
                <?php if ($isPremium && !empty($listasUsuario)): ?>
                    <button onclick="abrirModalNovaLista()" class="btn flex-fill" style="background:linear-gradient(135deg,#7c4dff,#4fc3f7); color:#fff; border-radius:20px; font-weight:600; padding: 6px 16px; border:none; max-width: 150px;">+ Nova Lista</button>
                <?php endif; ?>
            </header>

            <?php if ($isPremium): ?>
                <div class="grid-letterboxd">
                    <?php if (empty($listasUsuario)): ?>
                        <div style="text-align: center; padding: 60px 20px; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px dashed rgba(255,255,255,0.1); width: 100%; grid-column: 1 / -1;">
                            <i class="fas fa-list-ul mb-3" style="font-size: 3rem; color: rgba(255,255,255,0.15);"></i>
                            <h4 class="text-white" style="font-weight: 700;">Nenhuma lista encontrada</h4>
                            <p class="text-muted mb-4">Você ainda não criou nenhuma playlist. Que tal começar agora?</p>
                            <button onclick="abrirModalNovaLista()" class="btn" style="background:linear-gradient(135deg,#7c4dff,#4fc3f7); color:#fff; border-radius:20px; font-weight:600; padding: 8px 24px; border:none;">
                                <i class="fas fa-plus me-2"></i>Criar Minha Primeira Lista
                            </button>
                        </div>
                    <?php else: ?>
                        <?php foreach($listasUsuario as $lista): ?>
                            <div class="card-lista">
                                <a href="verLista.php?id=<?php echo $lista['id']; ?>" class="stack-capas">
                                    <img src="https://picsum.photos/seed/<?php echo $lista['id']; ?>1/100" alt="capa1">
                                    <img src="https://picsum.photos/seed/<?php echo $lista['id']; ?>2/100" alt="capa2">
                                    <img src="https://picsum.photos/seed/<?php echo $lista['id']; ?>3/100" alt="capa3">
                                </a>
                                <div class="info-lista">
                                    <h3><?php echo htmlspecialchars($lista['nome']); ?></h3>
                                    <span class="badge-count"><?php echo $lista['total_musicas']; ?> músicas</span>
                                    <div class="acoes-lista">
                                        <button type="button" onclick="abrirModalEditar(<?php echo $lista['id']; ?>, '<?php echo addslashes(htmlspecialchars($lista['nome'])); ?>', '<?php echo addslashes(htmlspecialchars($lista['descricao'] ?? '')); ?>')" style="background: none; border: none; padding: 0; cursor: pointer; color: #b3b3b3;"><i class="fas fa-pen"></i></button>
                                        <form action="controller/ExcluirListas.php" method="POST" onsubmit="return confirm('Tem certeza que deseja remover esta playlist?');" class="form-excluir">
                                            <input type="hidden" name="id" value="<?php echo $lista['id']; ?>">
                                            <button type="submit"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="premium-locked-banner" style="text-align: center; padding: 40px; background: rgba(255,255,255,0.04); border-radius: 12px; border: 1px dashed #ffc107;">
                    <i class="fas fa-lock" style="font-size: 2.5rem; color: #ffc107; margin-bottom: 16px;"></i>
                    <h4 style="color: #fff; font-weight: 700; margin-bottom: 10px;">Funcionalidade Exclusiva Premium</h4>
                    <p style="color: #b3b3b3; margin-bottom: 20px; font-size: 0.9rem;">Crie, edite e organize suas próprias listas de músicas. Assine o plano Premium para liberar esta função!</p>
                    <a href="premium.php" class="btn-upgrade" style="display: inline-block; padding: 8px 24px; background: #ffc107; color: #000; text-decoration: none; border-radius: 20px; font-weight: bold; transition: transform 0.2s;">Fazer Upgrade Agora</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Painel de média de avaliações -->
        <div class="rating-panel mb-4">
            <div class="row align-items-center g-4">
                <div class="col-auto text-center" style="min-width:120px;">
                    <div class="avg-score"><?php echo $media; ?></div>
                    <div class="avg-stars mt-2">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="<?php echo $i <= round($media) ? 'fas' : 'far'; ?> fa-star"></i>
                        <?php endfor; ?>
                    </div>
                    <p style="color:rgba(255,255,255,0.4); font-size:0.78rem; margin-top:6px;">
                        <?php echo $total; ?> avaliações
                    </p>
                </div>
                <div class="col">
                    <p class="section-title mb-3"><i class="fas fa-chart-bar me-2" style="color:#ffc107;"></i>Média das suas avaliações</p>
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <?php $pct = $total > 0 ? ($contagem[$i] / $total) * 100 : 0; ?>
                        <div class="bar-row">
                            <span><?php echo $i; ?></span>
                            <i class="fas fa-star" style="color:#ffc107; font-size:0.75rem;"></i>
                            <div class="bar-track">
                                <div class="bar-fill" style="width:<?php echo $pct; ?>%"></div>
                            </div>
                            <span class="bar-count"><?php echo $contagem[$i]; ?></span>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

        <!-- Músicas avaliadas -->
        <div class="section-title"><i class="fas fa-music me-2" style="color:#1db954;"></i>Músicas Avaliadas</div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3">
            <?php if (empty($musicas)): ?>
            <div class="col-12">
                <div style="text-align:center;padding:40px;background:rgba(255,255,255,0.03);border-radius:12px;border:1px dashed rgba(255,255,255,0.1);color:#b3b3b3;font-size:0.88rem;">
                    <i class="fas fa-star mb-3" style="font-size:2rem;color:rgba(255,193,7,0.3);display:block;"></i>
                    Você ainda não avaliou nenhuma música.
                </div>
            </div>
            <?php else: ?>
            <?php foreach ($musicas as $m):
                $nota  = (int) round($m['nota']);
                $thumb = ytThumbBib($m['spotify_link']);
                $capa  = $thumb ?? 'https://picsum.photos/seed/' . urlencode($m['titulo']) . '/200';
            ?>
            <div class="col">
                <div class="music-card">
                    <img src="<?php echo $capa; ?>" alt="<?php echo htmlspecialchars($m['titulo']); ?>">
                    <div class="info">
                        <h6><?php echo htmlspecialchars($m['titulo']); ?></h6>
                        <p><?php echo htmlspecialchars($m['artista']); ?> • <?php echo htmlspecialchars($m['genero'] ?? ''); ?></p>
                        <div class="stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="<?php echo $i <= $nota ? 'fas' : 'far'; ?> fa-star <?php echo $i > $nota ? 'empty' : ''; ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div id="modal-add-lista" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background: rgba(30,30,40,0.95); border: 1px solid rgba(255,255,255,0.15); color: #fff;">
                    <div class="modal-header border-0">
                        <h5 class="modal-title">Adicionar à Lista</h5>
                        <button type="button" class="btn-close btn-close-white" onclick="fecharModalAddLista()"></button>
                    </div>
                    <div class="modal-body">
                        <form action="controller/AdicionarMusicaListaControl.php" method="POST">
                            <input type="hidden" name="musica_id" id="add-musica-id">
                            
                            <div class="mb-3">
                                <label class="form-label">Escolha a Playlist</label>
                                <select name="lista_id" class="form-select" style="background: rgba(255,255,255,0.07); color: #fff; border: 1px solid rgba(255,255,255,0.15);" required>
                                    <?php foreach($listasUsuario as $lst): ?>
                                        <option value="<?php echo $lst['id']; ?>" style="color: #000;"><?php echo htmlspecialchars($lst['nome']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn flex-fill" style="background: #1db954; color: #fff; border-radius: 20px;">Adicionar</button>
                                <button type="button" class="btn flex-fill" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #fff; border-radius: 20px;" onclick="fecharModalAddLista()">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="modal-lista" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background: rgba(30,30,40,0.95); border: 1px solid rgba(255,255,255,0.15); color: #fff;">
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="modal-titulo">Nova Lista</h5>
                        <button type="button" class="btn-close btn-close-white" onclick="fecharModal()"></button>
                    </div>
                    <div class="modal-body">
                        <form action="controller/SalvarListas.php" method="POST">
                            <input type="hidden" name="id" id="lista-id">
                            
                            <div class="mb-3">
                                <label class="form-label">Nome da Playlist</label>
                                <input type="text" name="nome" id="lista-nome" class="form-control" style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.15); color: #fff;" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Descrição (opcional)</label>
                                <textarea name="descricao" id="lista-descricao" class="form-control" rows="3" style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.15); color: #fff;"></textarea>
                            </div>
                            
                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn flex-fill" style="background: #1db954; color: #fff; border-radius: 20px;">Salvar</button>
                                <button type="button" class="btn flex-fill" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #fff; border-radius: 20px;" onclick="fecharModal()">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/listas.js"></script>
</body>
</html>

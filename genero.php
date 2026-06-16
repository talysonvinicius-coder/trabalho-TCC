<?php
session_start();
$id_genero = $_GET['id'] ?? null;

if (!$id_genero) {
    header("Location: buscar.php");
    exit;
}

$pdo = new PDO('mysql:host=localhost;dbname=bdmusica;charset=utf8mb4', 'root', '');

$stmt_genero = $pdo->prepare("SELECT nome, descricao FROM genero WHERE id = :id");
$stmt_genero->execute(['id' => $id_genero]);
$genero_atual = $stmt_genero->fetch(PDO::FETCH_ASSOC);

if (!$genero_atual) {
    header("Location: buscar.php");
    exit;
}

$stmt_musicas = $pdo->prepare("
    SELECT m.id, m.titulo, a.nome AS artista, al.nome AS album, m.spotify_link
    FROM musicas m
    LEFT JOIN artista a ON m.artista_id = a.id
    LEFT JOIN album al ON m.album_id = al.id
    WHERE m.genero_id = :id
");
$stmt_musicas->execute(['id' => $id_genero]);
$musicas = $stmt_musicas->fetchAll(PDO::FETCH_ASSOC);

// Verifica se o usuário é premium (plano_id 2) ou admin (perfil admin ou perfil_id 2)
$isPremium = (!empty($_SESSION['plano_id']) && (int)$_SESSION['plano_id'] === 2)
          || (!empty($_SESSION['perfil'])   && $_SESSION['perfil'] === 'admin')
          || (!empty($_SESSION['perfil'])   && $_SESSION['perfil'] === 'cliente' && !empty($_SESSION['plano_id']) && (int)$_SESSION['plano_id'] === 2);

// Se admin tem plano_id=2 no banco, força premium
if (!$isPremium && !empty($_SESSION['logado'])) {
    try {
        $stmt_plano = $pdo->prepare("SELECT u.plano_id, p.nome AS perfil FROM usuario u JOIN perfil p ON u.perfil_id = p.id JOIN login l ON l.usuario_id = u.id WHERE u.nome = :nome LIMIT 1");
        $stmt_plano->execute(['nome' => $_SESSION['nome'] ?? '']);
        $row = $stmt_plano->fetch(PDO::FETCH_ASSOC);
        if ($row && ((int)$row['plano_id'] === 2 || $row['perfil'] === 'admin')) {
            $isPremium = true;
        }
    } catch (Exception $e) {}
}

// Ícones por gênero
$genreIcons = [
    'hip-hop' => '🎤', 'hiphop' => '🎤', 'rap' => '🎤', 'trap' => '🎤',
    'jazz'    => '🎷', 'blues'  => '🎸', 'rock' => '🎸', 'metal' => '🤘',
    'pop'     => '🎵', 'kpop'   => '💜', 'k-pop' => '💜',
    'eletrônica' => '🎧', 'eletronica' => '🎧', 'eletro' => '🎧',
    'mpb'     => '🇧🇷', 'sertanejo' => '🤠', 'forró' => '🪗', 'forro' => '🪗',
    'funk'    => '🔥', 'reggae' => '🌿', 'clássica' => '🎻', 'classica' => '🎻',
    'lo-fi'   => '☕', 'lofi'   => '☕', 'country' => '🪕', 'cristã' => '✝️', 'crista' => '✝️',
];

$nomeMin = mb_strtolower($genero_atual['nome']);
$icone = '🎶';
foreach ($genreIcons as $key => $emoji) {
    if (str_contains($nomeMin, $key)) { $icone = $emoji; break; }
}

// Notas musicais decorativas para as capas (fallback)
$notas = ['🎵', '🎶', '🎼', '🎹', '🥁', '🎸', '🎷', '🎺', '🎻', '🪗'];

function ytThumb($url) {
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
    <title>SoundScore - <?php echo htmlspecialchars($genero_atual['nome']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/navbarPag.css">
    <link rel="stylesheet" href="assets/css/genero.css">
    <style>
        #modalAvaliar .modal-content {
            background: rgba(15,15,25,0.92);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            color: #fff;
        }
        .avaliar-header { display: flex; gap: 16px; align-items: center; padding: 20px 20px 0; }
        .avaliar-header img { width: 72px; height: 72px; border-radius: 10px; object-fit: cover; flex-shrink: 0; }
        .avaliar-header .info h5 { font-size: 1rem; font-weight: 700; margin: 0 0 4px; }
        .avaliar-header .info p  { font-size: 0.8rem; color: #b3b3b3; margin: 0; }
        .star-rating { display: flex; gap: 6px; font-size: 1.6rem; cursor: pointer; }
        .star-rating span { color: rgba(255,255,255,0.2); transition: color 0.15s; }
        .star-rating span.active { color: #ffc107; }
        .yt-player-wrap { border-radius: 12px; overflow: hidden; background: #000; margin-bottom: 16px; }
        .yt-player-wrap iframe { width: 100%; height: 200px; display: block; border: none; }
        .yt-no-video { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px; padding: 20px; text-align: center; color: #b3b3b3;
            font-size: 0.82rem; margin-bottom: 16px; }
        .comentario-textarea { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);
            border-radius: 12px; color: #fff; resize: none; width: 100%; padding: 10px 14px; font-size: 0.85rem; }
        .comentario-textarea::placeholder { color: rgba(255,255,255,0.35); }
        .comentario-textarea:focus { outline: none; border-color: #7c4dff; }
        .btn-avaliar-enviar { background: linear-gradient(135deg, #7c4dff, #4fc3f7);
            border: none; color: #fff; border-radius: 25px; padding: 10px 0;
            font-weight: 700; width: 100%; font-size: 0.95rem; transition: filter 0.2s; }
        .btn-avaliar-enviar:hover { filter: brightness(1.15); }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    </style>
</head>
<body>
<?php include __DIR__ . '/navbarPag.php'; ?>
    <main class="content">
        <!-- Botão voltar -->
        <a href="javascript:history.back()" class="btn-voltar fade-in">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>

        <!-- Header do gênero -->
        <div class="genre-header fade-in">
            <div class="genre-header-icon"><?php echo $icone; ?></div>
            <div class="genre-header-info">
                <h2>Explore as melhores faixas de <?php echo htmlspecialchars($genero_atual['nome']); ?></h2>
                <p><?php echo htmlspecialchars($genero_atual['descricao']); ?></p>
            </div>
        </div>

        <!-- Grade de músicas -->
        <p class="section-title fade-in">
            <i class="fas fa-music" style="color:#7c4dff;"></i>
            <?php echo count($musicas); ?> música<?php echo count($musicas) !== 1 ? 's' : ''; ?> encontrada<?php echo count($musicas) !== 1 ? 's' : ''; ?>
        </p>

        <div class="musicas-grid fade-in">
            <?php if (count($musicas) > 0): ?>
                <?php foreach ($musicas as $i => $musica): ?>
                    <?php
                        $thumb = ytThumb($musica['spotify_link'] ?? '');
                    ?>
                    <div class="musica-card" onclick="abrirAvaliar('<?php echo $musica['id']; ?>','<?php echo 'music' . ($i+1); ?>','<?php echo addslashes(htmlspecialchars($musica['titulo'])); ?>','<?php echo addslashes(htmlspecialchars($musica['artista'])); ?>','<?php echo addslashes(htmlspecialchars($musica['spotify_link'] ?? '')); ?>')">
                        <div class="musica-card-cover" <?php if ($thumb): ?>style="background-image:url('<?php echo $thumb; ?>');background-size:cover;background-position:center;font-size:0;"<?php endif; ?>>
                            <?php if (!$thumb) echo $notas[$i % count($notas)]; ?>
                        </div>
                        <button class="musica-card-play"><i class="fas fa-play"></i></button>
                        <div class="musica-card-body">
                            <h3><?php echo htmlspecialchars($musica['artista']); ?></h3>
                            <p><?php echo htmlspecialchars($musica['titulo']); ?></p>
                        </div>
                        <div class="musica-card-bar">
                            <div class="musica-card-bar-fill"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">🎵</div>
                    <p>Nenhuma música encontrada para este gênero.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

<!-- Modal Avaliação -->
<div class="modal fade" id="modalAvaliar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content">
            <div class="avaliar-header">
                <img id="av-capa" src="" alt="capa">
                <div class="info">
                    <h5 id="av-titulo"></h5>
                    <p id="av-artista"></p>
                </div>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:20px;">
                <!-- Player YouTube - apenas premium -->
                <?php if ($isPremium): ?>
                <div id="yt-player-wrap" class="yt-player-wrap" style="display:none;">
                    <iframe id="yt-iframe" src="" allowfullscreen allow="autoplay; encrypted-media"></iframe>
                </div>
                <div id="yt-no-video" class="yt-no-video" style="display:none;">
                    <i class="fas fa-music mb-2" style="font-size:1.4rem;"></i><br>Prévia não disponível para esta música.
                </div>
                <?php else: ?>
                <div id="free-video-block" style="margin-bottom:16px;"></div>
                <?php endif; ?>
                <!-- Avaliação JS data -->
                <input type="hidden" id="av-musica-id" value="">
                
                <!-- Estrelas -->
                <p class="mb-1" style="font-size:0.8rem;color:#b3b3b3;">Sua nota</p>
                <div class="star-rating mb-3" id="stars">
                    <span data-v="1">&#9733;</span>
                    <span data-v="2">&#9733;</span>
                    <span data-v="3">&#9733;</span>
                    <span data-v="4">&#9733;</span>
                    <span data-v="5">&#9733;</span>
                </div>
                <!-- Comentário -->
                <p class="mb-2" style="font-size:0.8rem;color:#b3b3b3;">Comentário</p>
                <textarea id="av-comentario" class="comentario-textarea" rows="3"
                    placeholder="Ex: Uma das melhores músicas..."></textarea>
                <button class="btn-avaliar-enviar mt-3" id="btn-enviar-av" onclick="enviarAvaliacao()">Enviar Avaliação</button>
                <div id="av-msg" class="mt-2 text-center" style="font-size:0.85rem; display:none;"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fade-in ao rolar
        const observer = new IntersectionObserver(entries => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
        }, { threshold: 0.1 });
        document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));

        // Extrai o videoId de qualquer formato de URL do YouTube
        function extrairVideoId(url) {
            if (!url) return null;
            const m = url.match(/(?:youtu\.be\/|[?&]v=|embed\/)([\w-]{11})/);
            return m ? m[1] : null;
        }

        document.querySelectorAll('#stars span').forEach(star => {
            star.addEventListener('click', function() {
                const val = +this.dataset.v;
                document.querySelectorAll('#stars span').forEach(s => s.classList.toggle('active', +s.dataset.v <= val));
            });
        });

            const isPremium = <?php echo $isPremium ? 'true' : 'false'; ?>;

        function abrirAvaliar(musicaId, seed, titulo, artista, youtubeUrl) {
            document.getElementById('av-musica-id').value = musicaId;
            document.getElementById('av-titulo').textContent = titulo;
            document.getElementById('av-artista').textContent = artista;
            document.getElementById('av-comentario').value = '';
            document.getElementById('av-msg').style.display = 'none';
            document.querySelectorAll('#stars span').forEach(s => s.classList.remove('active'));

            const videoId = extrairVideoId(youtubeUrl);
            document.getElementById('av-capa').src = videoId
                ? 'https://img.youtube.com/vi/' + videoId + '/hqdefault.jpg'
                : 'https://picsum.photos/seed/' + seed + '/200';

            // Bloco free
            const freeBlock = document.getElementById('free-video-block');
            if (freeBlock) {
                if (videoId) {
                    freeBlock.innerHTML = `
                        <div style="border-radius:12px;overflow:hidden;position:relative;margin-bottom:4px;">
                            <img src="https://img.youtube.com/vi/${videoId}/hqdefault.jpg"
                                 style="width:100%;display:block;border-radius:12px;filter:brightness(0.45);">
                            <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;">
                                <a href="https://www.youtube.com/watch?v=${videoId}" target="_blank" rel="noopener"
                                   style="display:inline-flex;align-items:center;gap:8px;background:#ff0000;color:#fff;font-weight:700;font-size:0.88rem;padding:10px 22px;border-radius:25px;text-decoration:none;box-shadow:0 4px 16px rgba(255,0,0,0.4);"
                                   onmouseover="this.style.filter='brightness(1.15)'" onmouseout="this.style.filter=''">
                                    <i class="fab fa-youtube" style="font-size:1.1rem;"></i> Assistir no YouTube
                                </a>
                                <a href="premium.php" style="font-size:0.75rem;color:#ffc107;text-decoration:none;opacity:0.85;">
                                    <i class="fas fa-bolt me-1"></i>Assine o Premium para assistir aqui
                                </a>
                            </div>
                        </div>`;
                } else {
                    freeBlock.innerHTML = `
                        <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.1);border-radius:12px;padding:22px;text-align:center;margin-bottom:4px;">
                            <i class="fas fa-compact-disc" style="font-size:2rem;color:#555;display:block;margin-bottom:10px;animation:spin 4s linear infinite;"></i>
                            <p style="color:#b3b3b3;font-size:0.85rem;margin:0 0 12px;font-weight:600;">Música indisponível no momento</p>
                            <a href="premium.php" style="font-size:0.75rem;color:#ffc107;text-decoration:none;">
                                <i class="fas fa-bolt me-1"></i>Upgrade para mais conteúdo
                            </a>
                        </div>`;
                }
            }

            // Bloco premium
            if (isPremium && playerWrap && noVideo && iframe) {
                if (videoId) {
                    iframe.src = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0';
                    playerWrap.style.display = 'block';
                    noVideo.style.display    = 'none';
                } else {
                    iframe.src = '';
                    playerWrap.style.display = 'none';
                    noVideo.style.display    = 'block';
                }
            }

            new bootstrap.Modal(document.getElementById('modalAvaliar')).show();
        }

        // Para o vídeo ao fechar o modal
        document.getElementById('modalAvaliar').addEventListener('hide.bs.modal', function() {
            const iframe = document.getElementById('yt-iframe');
            if (iframe) iframe.src = '';
        });

        async function enviarAvaliacao() {
            const musicaId = document.getElementById('av-musica-id').value;
            const comentario = document.getElementById('av-comentario').value;
            
            // Conta estrelas
            let nota = 0;
            document.querySelectorAll('#stars span.active').forEach(s => {
                const val = parseInt(s.dataset.v);
                if (val > nota) nota = val;
            });

            if (nota === 0) {
                const msg = document.getElementById('av-msg');
                msg.style.display = 'block';
                msg.style.color = '#ff6b6b';
                msg.textContent = 'Por favor, selecione uma nota.';
                return;
            }

            const btn = document.getElementById('btn-enviar-av');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enviando...';

            const fd = new FormData();
            fd.append('musica_id', musicaId);
            fd.append('nota', nota);
            fd.append('comentario', comentario);

            try {
                const res = await fetch('api/avaliacoes/avaliar.php', { method: 'POST', body: fd });
                const data = await res.json();
                
                const msg = document.getElementById('av-msg');
                msg.style.display = 'block';
                if (data.ok) {
                    msg.style.color = '#1db954';
                    msg.textContent = 'Avaliação salva com sucesso!';
                    setTimeout(() => {
                        bootstrap.Modal.getInstance(document.getElementById('modalAvaliar')).hide();
                        btn.disabled = false;
                        btn.innerHTML = 'Enviar Avaliação';
                    }, 1500);
                } else {
                    msg.style.color = '#ff6b6b';
                    msg.textContent = data.erro || 'Erro ao salvar.';
                    btn.disabled = false;
                    btn.innerHTML = 'Enviar Avaliação';
                }
            } catch(e) {
                const msg = document.getElementById('av-msg');
                msg.style.display = 'block';
                msg.style.color = '#ff6b6b';
                msg.textContent = 'Erro de conexão.';
                btn.disabled = false;
                btn.innerHTML = 'Enviar Avaliação';
            }
        }
    </script>
</body>
</html>

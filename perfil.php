<?php
session_start();

if (empty($_SESSION['logado'])) {
    header("Location: login.php");
    exit;
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=bdmusica;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão.");
}

// Garante coluna foto
try { $pdo->exec("ALTER TABLE usuario ADD COLUMN IF NOT EXISTS foto VARCHAR(255) NULL DEFAULT NULL"); } catch(Exception $e){}

// Busca o usuário pelo nome da sessão (join com login para pegar o email)
$stmt = $pdo->prepare("
    SELECT u.id, u.nome, u.bio, u.foto, u.data_criacao, pl.nome AS plano, p.nome AS perfil, l.email
    FROM usuario u
    JOIN perfil p  ON u.perfil_id = p.id
    JOIN planos pl ON u.plano_id  = pl.id
    LEFT JOIN login l ON l.usuario_id = u.id
    WHERE u.nome = :nome
    LIMIT 1
");
$stmt->execute(['nome' => $_SESSION['nome']]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header("Location: login.php");
    exit;
}

$uid = $usuario['id'];

// Contadores
$stmt = $pdo->prepare("SELECT COUNT(*) FROM seguidores WHERE seguido_id = :id");
$stmt->execute(['id' => $uid]);
$total_seguidores = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM seguidores WHERE seguidor_id = :id");
$stmt->execute(['id' => $uid]);
$total_seguindo = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM avaliacoes WHERE usuario_id = :id");
$stmt->execute(['id' => $uid]);
$total_avaliacoes = $stmt->fetchColumn();

// Avaliações com comentário
$stmt = $pdo->prepare("
    SELECT av.nota, av.comentario, av.data_avaliacao,
           m.titulo, a.nome AS artista
    FROM avaliacoes av
    JOIN musicas m ON av.musica_id = m.id
    JOIN artista a ON m.artista_id = a.id
    WHERE av.usuario_id = :id AND av.comentario IS NOT NULL AND av.comentario != ''
    ORDER BY av.data_avaliacao DESC
    LIMIT 6
");
$stmt->execute(['id' => $uid]);
$avaliacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Listas do usuário
$stmt = $pdo->prepare("
    SELECT ls.id, ls.nome, ls.descricao,
           COUNT(lm.musica_id) AS total_musicas
    FROM listas ls
    LEFT JOIN lista_musicas lm ON lm.lista_id = ls.id
    WHERE ls.usuario_id = :id
    GROUP BY ls.id
    ORDER BY ls.data_criacao DESC
    LIMIT 6
");
$stmt->execute(['id' => $uid]);
$listas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$notas_capa = ['🎵', '🎶', '🎼', '🎹', '🥁', '🎸'];
$notas_comentario = ['🎧', '🎵', '🎶', '🎤', '🎼', '🎸'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundScore - Perfil</title>
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
        main.content { margin-left: 240px; width: calc(100% - 240px); overflow-y: auto; padding: 0 0 80px; }

        .profile-cover {
            width: 100%; height: 180px;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            background-size: 200% 200%;
            animation: gradientShift 8s ease infinite;
            position: relative;
        }
        @keyframes gradientShift {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .profile-cover::after {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse at 60% 50%, rgba(124,77,255,0.25), transparent 70%);
        }

        .profile-header {
            padding: 0 32px 24px;
            display: flex; align-items: flex-end; gap: 24px;
            margin-top: -60px; position: relative; z-index: 1;
        }

        .profile-avatar {
            width: 120px; height: 120px; border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            border: 4px solid var(--bg-dark);
            display: flex; align-items: center; justify-content: center;
            font-size: 3rem; flex-shrink: 0;
            box-shadow: 0 8px 32px rgba(124,77,255,0.4);
            overflow: hidden; position: relative; cursor: pointer;
        }
        .profile-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
        .avatar-overlay {
            position: absolute; inset: 0; border-radius: 50%;
            background: rgba(0,0,0,0.5);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; transition: opacity 0.2s;
            font-size: 1.4rem; color: #fff;
        }
        .profile-avatar:hover .avatar-overlay { opacity: 1; }

        .profile-info { padding-bottom: 8px; flex: 1; }
        .profile-info h1 { font-size: 1.8rem; font-weight: 800; margin-bottom: 2px; }
        .profile-info .profile-sub { color: var(--text-grey); font-size: 0.85rem; margin-bottom: 12px; display: flex; gap: 12px; align-items: center; flex-wrap: wrap; }

        .badge-plano {
            font-size: 0.7rem; font-weight: 700; padding: 2px 10px; border-radius: 20px;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            color: #fff; text-transform: uppercase; letter-spacing: 0.5px;
        }

        .profile-stats { display: flex; gap: 24px; }
        .stat-item { text-align: center; cursor: default; }
        .stat-item .stat-num {
            font-size: 1.2rem; font-weight: 800;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; display: block;
        }
        .stat-item .stat-label { font-size: 0.75rem; color: var(--text-grey); text-transform: uppercase; letter-spacing: 0.5px; }

        .btn-edit-profile { margin-left: auto; padding-bottom: 8px; align-self: flex-end; }
        .btn-edit-profile button {
            background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.15);
            color: #fff; padding: 8px 20px; border-radius: 20px; font-size: 0.85rem;
            font-weight: 600; cursor: pointer; transition: background 0.2s, border-color 0.2s;
        }
        .btn-edit-profile button:hover { background: rgba(124,77,255,0.2); border-color: rgba(124,77,255,0.5); }

        .bio-text { font-size: 0.85rem; color: var(--text-grey); margin-bottom: 12px; font-style: italic; }

        .profile-body { padding: 0 32px; }

        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .section-header h2 { font-size: 1.15rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px; }
        .section-header h2 i { color: var(--accent); }
        .see-all { color: var(--text-grey); font-size: 0.78rem; text-decoration: none; text-transform: uppercase; letter-spacing: 1px; transition: color 0.2s; }
        .see-all:hover { color: #fff; }

        /* Info do perfil */
        .info-card {
            background: var(--bg-card); border-radius: 12px; padding: 20px;
            border: 1px solid rgba(255,255,255,0.05); margin-bottom: 32px;
            display: flex; gap: 32px; flex-wrap: wrap;
        }
        .info-item { display: flex; align-items: center; gap: 10px; }
        .info-item i { color: var(--accent); width: 16px; }
        .info-item span { font-size: 0.85rem; color: var(--text-grey); }
        .info-item strong { color: #fff; }

        /* Cards de avaliação */
        .comment-card {
            background: var(--bg-card); border-radius: 12px; padding: 16px;
            border: 1px solid rgba(255,255,255,0.05);
            transition: background 0.3s, border-color 0.3s; height: 100%;
        }
        .comment-card:hover { background: var(--bg-card-hover); border-color: rgba(124,77,255,0.2); }
        .comment-music { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
        .comment-cover {
            width: 40px; height: 40px; border-radius: 6px;
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; flex-shrink: 0;
        }
        .comment-music-info h5 { font-size: 0.88rem; font-weight: 600; margin: 0; }
        .comment-music-info span { font-size: 0.75rem; color: var(--text-grey); }
        .comment-text { font-size: 0.85rem; color: #ddd; line-height: 1.5; margin-bottom: 10px; }
        .comment-meta { display: flex; align-items: center; justify-content: space-between; }
        .comment-date { font-size: 0.72rem; color: var(--text-grey); }
        .comment-stars i { font-size: 0.7rem; color: #ffc107; }

        /* Empty state */
        .empty-state {
            background: var(--bg-card); border-radius: 12px; padding: 40px;
            border: 1px solid rgba(255,255,255,0.05); text-align: center;
            color: var(--text-grey); font-size: 0.88rem;
        }
        .empty-state i { font-size: 2rem; color: rgba(124,77,255,0.4); margin-bottom: 12px; display: block; }

        /* Cards de lista */
        .playlist-card {
            background: var(--bg-card); border-radius: 12px; overflow: hidden;
            border: 1px solid rgba(255,255,255,0.05);
            transition: background 0.3s, transform 0.3s, border-color 0.3s, box-shadow 0.3s; cursor: pointer;
        }
        .playlist-card:hover { background: var(--bg-card-hover); transform: translateY(-4px); border-color: rgba(124,77,255,0.3); box-shadow: 0 8px 24px rgba(124,77,255,0.2); }
        .playlist-cover {
            width: 100%; aspect-ratio: 1;
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem; position: relative; overflow: hidden;
        }
        .playlist-cover::after { content: ''; position: absolute; inset: 0; background: radial-gradient(circle at 60% 40%, rgba(124,77,255,0.2), transparent 70%); }
        .playlist-info { padding: 8px 10px; }
        .playlist-info h5 { font-size: 0.8rem; font-weight: 700; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .playlist-info span { font-size: 0.7rem; color: var(--text-grey); display: block; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .playlist-count { font-size: 0.68rem; color: #c4a8ff; margin: 0; }

        .fade-in { opacity: 0; transform: translateY(20px); transition: opacity 0.5s ease, transform 0.5s ease; }
        .fade-in.visible { opacity: 1; transform: translateY(0); }

        /* ── Modal editar perfil ── */
        #modalEditarPerfil .modal-content {
            background: rgba(15,15,25,0.97);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            color: #fff;
        }
        #modalEditarPerfil .modal-header { border-bottom: 1px solid rgba(255,255,255,0.08); padding: 20px 24px 16px; }
        #modalEditarPerfil .modal-title { font-weight: 700; font-size: 1rem; }
        #modalEditarPerfil .modal-footer { border-top: 1px solid rgba(255,255,255,0.08); padding: 16px 24px; }

        .edit-avatar-wrap {
            display: flex; flex-direction: column; align-items: center; gap: 10px; margin-bottom: 20px;
        }
        .edit-avatar-preview {
            width: 96px; height: 96px; border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            border: 3px solid rgba(124,77,255,0.5);
            display: flex; align-items: center; justify-content: center;
            font-size: 2.4rem; overflow: hidden; position: relative; cursor: pointer;
            transition: border-color 0.2s;
        }
        .edit-avatar-preview img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
        .edit-avatar-preview:hover { border-color: var(--accent); }
        .edit-avatar-overlay {
            position: absolute; inset: 0; border-radius: 50%;
            background: rgba(0,0,0,0.55);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; transition: opacity 0.2s; font-size: 1.2rem; color: #fff;
        }
        .edit-avatar-preview:hover .edit-avatar-overlay { opacity: 1; }
        .edit-avatar-hint { font-size: 0.75rem; color: var(--text-grey); }

        .edit-field label { font-size: 0.78rem; color: var(--text-grey); margin-bottom: 6px; display: block; }
        .edit-input {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 10px; color: #fff;
            width: 100%; padding: 10px 14px; font-size: 0.9rem;
            transition: border-color 0.2s; outline: none;
        }
        .edit-input:focus { border-color: var(--accent); }
        .edit-input::placeholder { color: rgba(255,255,255,0.3); }
        textarea.edit-input { resize: none; }

        .btn-salvar {
            background: linear-gradient(135deg, #7c4dff, #4fc3f7);
            border: none; color: #fff; border-radius: 25px;
            padding: 10px 28px; font-weight: 700; font-size: 0.9rem;
            transition: filter 0.2s; cursor: pointer;
        }
        .btn-salvar:hover { filter: brightness(1.15); }
        .btn-cancelar {
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.7);
            border-radius: 25px; padding: 10px 28px;
            font-size: 0.9rem; cursor: pointer; transition: background 0.2s;
        }
        .btn-cancelar:hover { background: rgba(255,255,255,0.12); }

        .toast-perfil {
            position: fixed; bottom: 70px; left: 50%; transform: translateX(-50%);
            background: rgba(20,20,35,0.97); border: 1px solid rgba(124,77,255,0.4);
            color: #fff; border-radius: 12px; padding: 12px 24px;
            font-size: 0.88rem; z-index: 9999; opacity: 0;
            transition: opacity 0.3s; pointer-events: none;
            display: flex; align-items: center; gap: 10px;
        }
        .toast-perfil.show { opacity: 1; }
        .toast-perfil i { color: #4fc3f7; }
        .toast-perfil.erro i { color: #ff4081; }

        .player { padding: 0; overflow: hidden; height: 50px; position: fixed; bottom: 0; left: 240px; width: calc(100% - 240px); z-index: 40; }
        .player img { width: 100%; height: 100%; object-fit: cover; display: block; transition: opacity 1s ease; }
    </style>
</head>
<body>

<?php include __DIR__ . '/navbarPag.php'; ?>

<main class="content">

    <div class="profile-cover"></div>

    <div class="profile-header fade-in">
        <div class="profile-avatar" onclick="document.getElementById('modalEditarPerfil') && new bootstrap.Modal(document.getElementById('modalEditarPerfil')).show()">
            <?php if (!empty($usuario['foto'])): ?>
                <img id="avatar-img" src="<?php echo htmlspecialchars($usuario['foto']); ?>" alt="avatar">
            <?php else: ?>
                <span id="avatar-inicial"><?php echo strtoupper(mb_substr($usuario['nome'], 0, 1)); ?></span>
            <?php endif; ?>
            <div class="avatar-overlay"><i class="fas fa-camera"></i></div>
        </div>
        <div class="profile-info">
            <h1><?php echo htmlspecialchars($usuario['nome']); ?></h1>
            <div class="profile-sub">
                <span><?php echo htmlspecialchars($usuario['email'] ?? ''); ?></span>
                <span class="badge-plano"><?php echo htmlspecialchars($usuario['plano']); ?></span>
                <?php if ($usuario['perfil'] === 'admin'): ?>
                    <span class="badge-plano" style="background: linear-gradient(135deg,#ff4081,#f50057);">Admin</span>
                <?php endif; ?>
            </div>
            <?php if (!empty($usuario['bio'])): ?>
                <p class="bio-text">"<?php echo htmlspecialchars($usuario['bio']); ?>"</p>
            <?php endif; ?>
            <div class="profile-stats">
                <div class="stat-item">
                    <span class="stat-num"><?php echo $total_seguidores; ?></span>
                    <span class="stat-label">Seguidores</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num"><?php echo $total_seguindo; ?></span>
                    <span class="stat-label">Seguindo</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num"><?php echo $total_avaliacoes; ?></span>
                    <span class="stat-label">Avaliações</span>
                </div>
            </div>
        </div>
        <div class="btn-edit-profile">
            <button data-bs-toggle="modal" data-bs-target="#modalEditarPerfil">
                <i class="fas fa-pen me-2"></i>Editar perfil
            </button>
        </div>
    </div>

    <div class="profile-body">

        <!-- Info do perfil -->
        <div class="info-card fade-in">
            <div class="info-item">
                <i class="fas fa-calendar-alt"></i>
                <div>
                    <span>Membro desde</span><br>
                    <strong><?php echo date('d/m/Y', strtotime($usuario['data_criacao'])); ?></strong>
                </div>
            </div>
            <div class="info-item">
                <i class="fas fa-shield-alt"></i>
                <div>
                    <span>Perfil</span><br>
                    <strong><?php echo ucfirst(htmlspecialchars($usuario['perfil'])); ?></strong>
                </div>
            </div>
            <div class="info-item">
                <i class="fas fa-star"></i>
                <div>
                    <span>Plano</span><br>
                    <strong><?php echo ucfirst(htmlspecialchars($usuario['plano'])); ?></strong>
                </div>
            </div>
            <?php if (!empty($usuario['email'])): ?>
            <div class="info-item">
                <i class="fas fa-envelope"></i>
                <div>
                    <span>E-mail</span><br>
                    <strong><?php echo htmlspecialchars($usuario['email']); ?></strong>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Avaliações com comentários -->
        <section class="mb-5 fade-in">
            <div class="section-header">
                <h2><i class="fas fa-comment-dots"></i> Minhas avaliações</h2>
            </div>
            <?php if (count($avaliacoes) > 0): ?>
            <div class="row row-cols-1 row-cols-md-2 g-3">
                <?php foreach ($avaliacoes as $i => $av): ?>
                <div class="col">
                    <div class="comment-card">
                        <div class="comment-music">
                            <div class="comment-cover"><?php echo $notas_comentario[$i % count($notas_comentario)]; ?></div>
                            <div class="comment-music-info">
                                <h5><?php echo htmlspecialchars($av['titulo']); ?></h5>
                                <span><?php echo htmlspecialchars($av['artista']); ?></span>
                            </div>
                        </div>
                        <p class="comment-text">"<?php echo htmlspecialchars($av['comentario']); ?>"</p>
                        <div class="comment-meta">
                            <span class="comment-date">
                                <i class="fas fa-clock me-1"></i>
                                <?php echo date('d/m/Y', strtotime($av['data_avaliacao'])); ?>
                            </span>
                            <div class="comment-stars">
                                <?php
                                $nota_inteira = (int) round($av['nota']);
                                for ($s = 1; $s <= 5; $s++):
                                ?>
                                    <i class="<?php echo $s <= $nota_inteira ? 'fas' : 'far'; ?> fa-star"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-comment-slash"></i>
                Você ainda não fez avaliações com comentários.
            </div>
            <?php endif; ?>
        </section>

        <!-- Listas criadas -->
        <section class="mb-5 fade-in">
            <div class="section-header">
                <h2><i class="fas fa-list"></i> Minhas listas</h2>
            </div>
            <?php if (count($listas) > 0): ?>
            <div class="row row-cols-3 row-cols-md-4 row-cols-lg-6 g-3">
                <?php foreach ($listas as $i => $l): ?>
                <div class="col">
                    <div class="playlist-card">
                        <div class="playlist-cover"><?php echo $notas_capa[$i % count($notas_capa)]; ?></div>
                        <div class="playlist-info">
                            <h5><?php echo htmlspecialchars($l['nome']); ?></h5>
                            <span><?php echo htmlspecialchars($l['descricao'] ?? ''); ?></span>
                            <p class="playlist-count"><i class="fas fa-music me-1"></i><?php echo $l['total_musicas']; ?> música<?php echo $l['total_musicas'] != 1 ? 's' : ''; ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-list"></i>
                Você ainda não criou nenhuma lista.
            </div>
            <?php endif; ?>
        </section>

    </div>
</main>

<!-- Modal Editar Perfil -->
<div class="modal fade" id="modalEditarPerfil" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-pen me-2" style="color:#7c4dff;"></i>Editar perfil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:24px;">

                <!-- Preview do avatar -->
                <div class="edit-avatar-wrap">
                    <div class="edit-avatar-preview" onclick="document.getElementById('input-foto').click()">
                        <?php if (!empty($usuario['foto'])): ?>
                            <img id="edit-avatar-img" src="<?php echo htmlspecialchars($usuario['foto']); ?>" alt="avatar">
                        <?php else: ?>
                            <span id="edit-avatar-inicial"><?php echo strtoupper(mb_substr($usuario['nome'], 0, 1)); ?></span>
                        <?php endif; ?>
                        <div class="edit-avatar-overlay"><i class="fas fa-camera"></i></div>
                    </div>
                    <span class="edit-avatar-hint">Clique para trocar a foto &bull; JPG, PNG, WEBP (max. 3MB)</span>
                    <input type="file" id="input-foto" accept="image/jpeg,image/png,image/webp,image/gif" style="display:none;">
                </div>

                <!-- Nome -->
                <div class="edit-field mb-3">
                    <label>Nome</label>
                    <input type="text" id="edit-nome" class="edit-input"
                        value="<?php echo htmlspecialchars($usuario['nome']); ?>"
                        maxlength="100" placeholder="Seu nome">
                </div>

                <!-- Bio -->
                <div class="edit-field">
                    <label>Bio <span style="color:rgba(255,255,255,0.3);">(opcional)</span></label>
                    <textarea id="edit-bio" class="edit-input" rows="3"
                        maxlength="300"
                        placeholder="Fale um pouco sobre você..."><?php echo htmlspecialchars($usuario['bio'] ?? ''); ?></textarea>
                </div>

            </div>
            <div class="modal-footer gap-2">
                <button class="btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn-salvar" onclick="salvarPerfil()">
                    <i class="fas fa-check me-2"></i>Salvar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast feedback -->
<div class="toast-perfil" id="toast-perfil">
    <i class="fas fa-check-circle"></i>
    <span id="toast-msg">Perfil atualizado!</span>
</div>

<footer class="player">
    <img id="slideshow-img" src="https://img.freepik.com/vetores-premium/um-cartaz-de-publicidade-musical-com-um-disco-de-vinil-e-notas-musicais-em-uma-ilustracao-vetorial-isolada_606304-808.jpg" alt="banner">
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // ── Preview da foto antes de salvar ──
    let fotoSelecionada = null;
    document.getElementById('input-foto').addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        fotoSelecionada = file;
        const reader = new FileReader();
        reader.onload = e => {
            const wrap = document.querySelector('.edit-avatar-preview');
            // Remove inicial se existir
            const inicial = document.getElementById('edit-avatar-inicial');
            if (inicial) inicial.remove();
            // Atualiza ou cria a tag img
            let img = document.getElementById('edit-avatar-img');
            if (!img) {
                img = document.createElement('img');
                img.id = 'edit-avatar-img';
                wrap.insertBefore(img, wrap.querySelector('.edit-avatar-overlay'));
            }
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });

    // ── Salvar perfil via FormData (suporta arquivo) ──
    async function salvarPerfil() {
        const nome = document.getElementById('edit-nome').value.trim();
        const bio  = document.getElementById('edit-bio').value.trim();

        if (!nome) { mostrarToast('O nome não pode ser vazio.', true); return; }

        const btn = document.querySelector('.btn-salvar');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Salvando...';

        const fd = new FormData();
        fd.append('nome', nome);
        fd.append('bio',  bio);
        if (fotoSelecionada) fd.append('foto', fotoSelecionada);

        try {
            const res  = await fetch('api/perfil/editar.php', { method: 'POST', body: fd });
            const data = await res.json();

            if (data.ok) {
                // Atualiza nome na página
                document.querySelector('.profile-info h1').textContent = data.nome;
                // Atualiza bio
                const bioEl = document.querySelector('.bio-text');
                if (data.bio) {
                    if (bioEl) bioEl.textContent = '"' + data.bio + '"';
                    else {
                        const p = document.createElement('p');
                        p.className = 'bio-text';
                        p.textContent = '"' + data.bio + '"';
                        document.querySelector('.profile-sub').insertAdjacentElement('afterend', p);
                    }
                } else if (bioEl) bioEl.remove();

                // Atualiza avatar na página principal
                if (data.foto) {
                    atualizarAvatar(data.foto);
                }

                bootstrap.Modal.getInstance(document.getElementById('modalEditarPerfil')).hide();
                mostrarToast('Perfil atualizado com sucesso!');
                fotoSelecionada = null;
            } else {
                mostrarToast(data.erro || 'Erro ao salvar.', true);
            }
        } catch(e) {
            mostrarToast('Erro de conexão.', true);
        }

        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check me-2"></i>Salvar';
    }

    function atualizarAvatar(src) {
        // Avatar no header do perfil
        const avatarWrap = document.querySelector('.profile-avatar');
        const inicial    = document.getElementById('avatar-inicial');
        if (inicial) inicial.remove();
        let imgMain = document.getElementById('avatar-img');
        if (!imgMain) {
            imgMain    = document.createElement('img');
            imgMain.id = 'avatar-img';
            avatarWrap.insertBefore(imgMain, avatarWrap.querySelector('.avatar-overlay'));
        }
        imgMain.src = src;
    }

    function mostrarToast(msg, erro = false) {
        const t = document.getElementById('toast-perfil');
        document.getElementById('toast-msg').textContent = msg;
        t.classList.toggle('erro', erro);
        t.querySelector('i').className = erro ? 'fas fa-times-circle' : 'fas fa-check-circle';
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 3200);
    }

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
        setTimeout(() => { current = (current + 1) % slides.length; img.src = slides[current]; img.style.opacity = '1'; }, 1000);
    }, 30000);

    const observer = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
</script>
</body>
</html>

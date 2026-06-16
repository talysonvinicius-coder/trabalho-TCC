<?php
session_start();

if (!isset($_SESSION['plano_id']) || $_SESSION['plano_id'] != 2 || empty($_SESSION['id'])) {
    header("Location: biblioteca.php");
    exit;
}

$id_lista = $_GET['id'] ?? null;
if (!$id_lista) {
    header("Location: biblioteca.php");
    exit;
}

require_once 'model/dao/ListasDAO.php';
$listaDAO = new ListasDAO();

$lista_atual = $listaDAO->buscarLista($id_lista, $_SESSION['id']);
if (!$lista_atual) {
    header("Location: biblioteca.php");
    exit;
}

$musicasLista = $listaDAO->listarMusicasDaLista($id_lista);

// Fetch all musics for the add modal
require_once 'model/dao/Conexao.php';
$pdo = Conexao::getConexao();
$stmt_all = $pdo->query("SELECT m.id, m.titulo, a.nome as artista_nome FROM musicas m LEFT JOIN artista a ON m.artista_id = a.id ORDER BY m.titulo ASC");
$todasMusicas = $stmt_all->fetchAll(PDO::FETCH_ASSOC);

$isPremium = true;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($lista_atual['nome']); ?> - SoundScore</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/navbarPag.css">
    <link rel="stylesheet" href="assets/css/teste.css">
    <link rel="stylesheet" href="assets/css/verLista.css">
</head>
<body>

<?php include __DIR__ . '/navbarPag.php'; ?>

    <main class="content">
        <div class="playlist-header">
            <img src="https://picsum.photos/seed/<?php echo $lista_atual['id']; ?>/300" alt="Capa da Playlist" class="playlist-cover">
            <div class="playlist-info">
                <span style="font-weight:700; font-size:0.85rem; letter-spacing: 1px;">PLAYLIST</span>
                <h1><?php echo htmlspecialchars($lista_atual['nome']); ?></h1>
                <?php if (!empty($lista_atual['descricao'])): ?>
                    <p class="playlist-desc"><?php echo htmlspecialchars($lista_atual['descricao']); ?></p>
                <?php endif; ?>
                <div class="playlist-meta">
                    SoundScore <span>• <?php echo count($musicasLista); ?> músicas</span>
                </div>
            </div>
        </div>

        <div class="action-bar">
            <button class="btn-adicionar-principal" data-bs-toggle="modal" data-bs-target="#modal-add-todas">
                <i class="fas fa-plus me-2"></i> Adicionar Músicas
            </button>
        </div>

        <div class="tracklist-container mt-4">
            <?php if (empty($musicasLista)): ?>
                <div class="text-center mt-5" style="padding: 60px 0;">
                    <i class="fas fa-music mb-3" style="font-size: 3rem; color: rgba(255,255,255,0.2);"></i>
                    <h4 class="text-white">Esta playlist está vazia</h4>
                    <p class="text-muted">Adicione algumas músicas para começar a ouvir.</p>
                </div>
            <?php else: ?>
                <table class="track-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Título</th>
                            <th style="width: 80px; text-align: center;"><i class="far fa-clock"></i></th>
                            <th style="width: 60px; text-align: center;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($musicasLista as $index => $m): ?>
                            <tr class="track-row">
                                <td style="color:#b3b3b3;"><?php echo $index + 1; ?></td>
                                <td>
                                    <div class="track-title-col">
                                        <img src="https://picsum.photos/seed/music<?php echo $m['id']; ?>/100" alt="Capa">
                                        <div>
                                            <div class="track-name"><?php echo htmlspecialchars($m['titulo']); ?></div>
                                            <div class="track-artist"><?php echo htmlspecialchars($m['artista_nome']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td style="color:#b3b3b3; text-align: center;">--:--</td>
                                <td style="text-align: center;">
                                    <form action="controller/RemoverMusListas.php" method="POST" style="margin:0;" onsubmit="return confirm('Tem certeza que deseja remover esta música da playlist?');">
                                        <input type="hidden" name="lista_id" value="<?php echo $lista_atual['id']; ?>">
                                        <input type="hidden" name="musica_id" value="<?php echo $m['id']; ?>">
                                        <button type="submit" class="btn-trash" title="Remover da playlist"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- Modal Adicionar Músicas -->
        <div id="modal-add-todas" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content" style="background: rgba(30,30,40,0.95); border: 1px solid rgba(255,255,255,0.15); color: #fff;">
                    <div class="modal-header border-0">
                        <h5 class="modal-title">Todas as Músicas</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" style="padding: 0;">
                        <div class="list-group list-group-flush" style="border-radius: 0;">
                            <?php foreach($todasMusicas as $tm): ?>
                                <?php 
                                    // Verify if the music is already in the list to hide the button or show differently
                                    $jaAdicionada = false;
                                    foreach ($musicasLista as $mLista) {
                                        if ($mLista['id'] == $tm['id']) {
                                            $jaAdicionada = true;
                                            break;
                                        }
                                    }
                                ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center" style="background: transparent; border-bottom: 1px solid rgba(255,255,255,0.05); color: #fff; padding: 12px 20px;">
                                    <div class="d-flex align-items-center">
                                        <img src="https://picsum.photos/seed/music<?php echo $tm['id']; ?>/100" style="width: 40px; height: 40px; border-radius: 4px; margin-right: 15px;">
                                        <div>
                                            <div style="font-weight: 500; font-size: 1rem;"><?php echo htmlspecialchars($tm['titulo']); ?></div>
                                            <div style="font-size: 0.85rem; color: #b3b3b3;"><?php echo htmlspecialchars($tm['artista_nome']); ?></div>
                                        </div>
                                    </div>
                                    <?php if ($jaAdicionada): ?>
                                        <button class="btn btn-sm btn-secondary rounded-pill" disabled><i class="fas fa-check"></i></button>
                                    <?php else: ?>
                                        <form action="controller/AdicionarMusLista.php" method="POST" style="margin:0;">
                                            <input type="hidden" name="lista_id" value="<?php echo $lista_atual['id']; ?>">
                                            <input type="hidden" name="musica_id" value="<?php echo $tm['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-light rounded-pill px-3">Adicionar</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

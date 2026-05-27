<?php
require_once __DIR__ . '/model/dao/CategoriaDAO.php';

$categoriaDAO = new CategoriaDAO();
$categorias = $categoriaDAO->listarCategorias();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundScore - Categorias</title>
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

        /* Botão cadastrar */
        .btn-cadastrar {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 20px;
            border-radius: 25px;
            background: linear-gradient(135deg, #7c4dff, #4fc3f7);
            border: none;
            color: #fff;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: filter 0.2s, transform 0.2s;
        }
        .btn-cadastrar:hover { filter: brightness(1.12); transform: scale(1.03); }

        /* Tabela */
        .table-wrapper { background: #181818; border-radius: 14px; overflow: hidden; border: 1px solid rgba(255,255,255,0.06); }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: linear-gradient(135deg, rgba(124,77,255,0.15), rgba(79,195,247,0.08)); border-bottom: 1px solid rgba(255,255,255,0.08); }
        thead th { padding: 14px 18px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #b3b3b3; }
        tbody tr { border-bottom: 1px solid rgba(255,255,255,0.04); transition: background 0.2s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: rgba(255,255,255,0.04); }
        tbody td { padding: 13px 18px; font-size: 0.88rem; color: #fff; vertical-align: middle; }

        .desc-cell { color: #b3b3b3; font-size: 0.82rem; max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* Status badge */
        .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .status-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
        .status-badge.ativo   { background: rgba(29,185,84,0.15); color: #1db954; border: 1px solid rgba(29,185,84,0.3); }
        .status-badge.inativo { background: rgba(255,255,255,0.06); color: #b3b3b3; border: 1px solid rgba(255,255,255,0.1); }

        /* Botões ação */
        .acoes { display: flex; gap: 6px; }
        .btn-editar, .btn-excluir { border: none; border-radius: 20px; padding: 5px 12px; font-size: 0.75rem; font-weight: 600; cursor: pointer; transition: filter 0.2s, transform 0.15s; }
        .btn-editar  { background: linear-gradient(135deg, #7c4dff, #4fc3f7); color: #fff; }
        .btn-excluir { background: rgba(229,57,53,0.12); color: #ef5350; border: 1px solid rgba(229,57,53,0.3); }
        .btn-editar:hover, .btn-excluir:hover { filter: brightness(1.2); transform: scale(1.04); }

        /* Modal */
        .modal-overlay { display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(0,0,0,0.6); backdrop-filter: blur(8px); justify-content: center; align-items: center; }
        .modal-overlay.aberto { display: flex; }
        .modal-box { background: rgba(22,22,34,0.97); border: 1px solid rgba(124,77,255,0.2); border-radius: 16px; padding: 32px; width: 100%; max-width: 460px; margin: 0 16px; }
        .modal-box h2 { font-size: 1.2rem; font-weight: 700; margin-bottom: 20px; color: #fff; }
        .modal-box label { font-size: 0.8rem; color: #b3b3b3; margin-bottom: 6px; display: block; }
        .modal-box input, .modal-box textarea, .modal-box select { width: 100%; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); border-radius: 8px; color: #fff; padding: 10px 14px; font-size: 0.88rem; outline: none; margin-bottom: 14px; transition: border-color 0.2s; }
        .modal-box input:focus, .modal-box textarea:focus, .modal-box select:focus { border-color: rgba(124,77,255,0.5); }
        .modal-box textarea { resize: vertical; min-height: 80px; }
        .modal-box select option { background: #1a1a2e; }
        .modal-acoes { display: flex; gap: 10px; margin-top: 6px; }
        .btn-salvar { flex: 1; background: linear-gradient(135deg, #7c4dff, #4fc3f7); border: none; color: #fff; padding: 10px; border-radius: 20px; font-weight: 600; font-size: 0.88rem; cursor: pointer; transition: filter 0.2s; }
        .btn-salvar:hover { filter: brightness(1.12); }
        .btn-cancelar-modal { flex: 1; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); color: #b3b3b3; padding: 10px; border-radius: 20px; font-size: 0.88rem; cursor: pointer; transition: background 0.2s; }
        .btn-cancelar-modal:hover { background: rgba(255,255,255,0.1); color: #fff; }

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
            <input type="text" id="search-input" placeholder="Buscar categoria..." oninput="filtrarTabela()">
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
            <h2><i class="fas fa-folder-open me-2" style="color:#7c4dff;"></i>Administração de Categorias</h2>
            <div class="d-flex align-items-center gap-3">
                <span style="font-size:0.82rem; color:#b3b3b3;"><span id="cat-count"><?php echo count($categorias); ?></span> categoria<?php echo count($categorias) !== 1 ? 's' : ''; ?></span>
                <button class="btn-cadastrar" onclick="abrirModalCadastro()">
                    <i class="fas fa-plus"></i> Nova Categoria
                </button>
            </div>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="tabela-categorias">
                    <?php if (empty($categorias)): ?>
                        <tr><td colspan="5" style="text-align:center; color:#b3b3b3; padding:40px;">Nenhuma categoria encontrada.</td></tr>
                    <?php else: ?>
                        <?php foreach ($categorias as $c): ?>
                        <tr id="linha-<?php echo $c['id']; ?>">
                            <td style="color:#b3b3b3;"><?php echo htmlspecialchars($c['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($c['nome'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="desc-cell"><?php echo htmlspecialchars($c['descricao'] ?? '—', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <span class="status-badge <?php echo $c['status'] == 1 ? 'ativo' : 'inativo'; ?>">
                                    <?php echo $c['status'] == 1 ? 'Ativa' : 'Inativa'; ?>
                                </span>
                            </td>
                            <td class="acoes">
                                <button class="btn-editar" onclick="abrirModal(<?php echo $c['id']; ?>)">
                                    <i class="fas fa-pen me-1"></i>Editar
                                </button>
                                <button class="btn-excluir" onclick="excluirCategoria(<?php echo $c['id']; ?>)">
                                    <i class="fas fa-trash me-1"></i>Excluir
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Modal Editar -->
    <div id="modal-editar" class="modal-overlay">
        <div class="modal-box">
            <h2><i class="fas fa-pen me-2" style="color:#7c4dff;"></i>Editar Categoria</h2>
            <form id="form-editar">
                <input type="hidden" name="id" id="edit-id">
                <label>Nome</label>
                <input type="text" name="nome" id="edit-nome" required placeholder="Nome da categoria">
                <label>Descrição</label>
                <textarea name="descricao" id="edit-descricao" placeholder="Descrição da categoria"></textarea>
                <label>Status</label>
                <select name="status" id="edit-status">
                    <option value="1">Ativa</option>
                    <option value="0">Inativa</option>
                </select>
                <div class="modal-acoes">
                    <button type="submit" class="btn-salvar"><i class="fas fa-save me-2"></i>Salvar</button>
                    <button type="button" class="btn-cancelar-modal" onclick="fecharModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Cadastrar -->
    <div id="modal-cadastrar" class="modal-overlay">
        <div class="modal-box">
            <h2><i class="fas fa-plus me-2" style="color:#7c4dff;"></i>Nova Categoria</h2>
            <form id="form-cadastrar">
                <label>Nome</label>
                <input type="text" name="nome" id="cad-nome" required placeholder="Nome da categoria">
                <label>Descrição</label>
                <textarea name="descricao" id="cad-descricao" placeholder="Descrição da categoria"></textarea>
                <label>Status</label>
                <select name="status" id="cad-status">
                    <option value="1">Ativa</option>
                    <option value="0">Inativa</option>
                </select>
                <div class="modal-acoes">
                    <button type="submit" class="btn-salvar"><i class="fas fa-plus me-2"></i>Cadastrar</button>
                    <button type="button" class="btn-cancelar-modal" onclick="fecharModalCadastro()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</main>

<footer class="player">
    <img id="slideshow-img" src="https://img.freepik.com/vetores-premium/um-cartaz-de-publicidade-musical-com-um-disco-de-vinil_606304-808.jpg" alt="banner">
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>
<script src="assets/js/categoria.js"></script>
<script>
    function filtrarTabela() {
        const input = document.getElementById('search-input').value.toLowerCase();
        const linhas = document.querySelectorAll('#tabela-categorias tr');
        let visiveis = 0;
        linhas.forEach(tr => {
            const visivel = tr.textContent.toLowerCase().includes(input);
            tr.style.display = visivel ? '' : 'none';
            if (visivel) visiveis++;
        });
        document.getElementById('cat-count').textContent = visiveis;
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

<?php
session_start();

if (empty($_SESSION['logado']) || $_SESSION['perfil'] !== 'admin') {
    header('Location: index.html');
    exit();
}

require_once __DIR__ . '/model/dao/UsuarioDAO.php';

$usuarioDAO = new UsuarioDAO();
$usuarios = $usuarioDAO->listarUsuario();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundScore - Usuários</title>
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
        .see-all { color: #b3b3b3; font-size: 0.78rem; text-decoration: none; text-transform: uppercase; letter-spacing: 1px; transition: color 0.2s; }
        .see-all:hover { color: #fff; }

        /* Tabela */
        .table-wrapper {
            background: #181818;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.06);
        }

        table { width: 100%; border-collapse: collapse; }

        thead tr {
            background: linear-gradient(135deg, rgba(124,77,255,0.15), rgba(79,195,247,0.08));
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        thead th {
            padding: 14px 18px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #b3b3b3;
        }

        tbody tr {
            border-bottom: 1px solid rgba(255,255,255,0.04);
            transition: background 0.2s;
        }

        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: rgba(255,255,255,0.04); }

        tbody td {
            padding: 13px 18px;
            font-size: 0.88rem;
            color: #fff;
            vertical-align: middle;
        }

        /* Avatar inline */
        .user-cell { display: flex; align-items: center; gap: 10px; }
        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #7c4dff, #4fc3f7);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        /* Status badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-badge.ativo   { background: rgba(29,185,84,0.15); color: #1db954; border: 1px solid rgba(29,185,84,0.3); }
        .status-badge.inativo { background: rgba(255,255,255,0.06); color: #b3b3b3; border: 1px solid rgba(255,255,255,0.1); }
        .status-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

        /* Botões de ação */
        .acoes { display: flex; gap: 6px; flex-wrap: wrap; }

        .btn-editar, .btn-ativar, .btn-desativar, .btn-excluir {
            border: none;
            border-radius: 20px;
            padding: 5px 12px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: filter 0.2s, transform 0.15s;
        }
        .btn-editar   { background: linear-gradient(135deg, #7c4dff, #4fc3f7); color: #fff; }
        .btn-ativar   { background: rgba(29,185,84,0.15); color: #1db954; border: 1px solid rgba(29,185,84,0.3); }
        .btn-desativar{ background: rgba(255,193,7,0.12); color: #ffc107; border: 1px solid rgba(255,193,7,0.3); }
        .btn-excluir  { background: rgba(229,57,53,0.12); color: #ef5350; border: 1px solid rgba(229,57,53,0.3); }

        .btn-editar:hover, .btn-ativar:hover, .btn-desativar:hover, .btn-excluir:hover {
            filter: brightness(1.2);
            transform: scale(1.04);
        }

        /* Botão voltar */
        .btn-voltar {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            padding: 9px 20px;
            border-radius: 25px;
            background: rgba(124,77,255,0.12);
            border: 1px solid rgba(124,77,255,0.35);
            color: #c4a8ff;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s, border-color 0.2s, transform 0.2s;
        }
        .btn-voltar:hover { background: rgba(124,77,255,0.25); border-color: #7c4dff; color: #fff; transform: translateX(-3px); }
        .btn-voltar i { transition: transform 0.2s; }
        .btn-voltar:hover i { transform: translateX(-3px); }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(8px);
            justify-content: center;
            align-items: center;
        }
        .modal-overlay.aberto { display: flex; }

        .modal-box {
            background: rgba(22,22,34,0.97);
            border: 1px solid rgba(124,77,255,0.2);
            border-radius: 16px;
            padding: 32px;
            width: 100%;
            max-width: 460px;
            margin: 0 16px;
        }
        .modal-box h2 { font-size: 1.2rem; font-weight: 700; margin-bottom: 20px; color: #fff; }

        .modal-box label { font-size: 0.8rem; color: #b3b3b3; margin-bottom: 6px; display: block; }

        .modal-box input {
            width: 100%;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 8px;
            color: #fff;
            padding: 10px 14px;
            font-size: 0.88rem;
            outline: none;
            margin-bottom: 14px;
            transition: border-color 0.2s;
        }
        .modal-box input:focus { border-color: rgba(124,77,255,0.5); }

        .modal-acoes { display: flex; gap: 10px; margin-top: 6px; }

        .btn-salvar {
            flex: 1;
            background: linear-gradient(135deg, #7c4dff, #4fc3f7);
            border: none;
            color: #fff;
            padding: 10px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.88rem;
            cursor: pointer;
            transition: filter 0.2s;
        }
        .btn-salvar:hover { filter: brightness(1.12); }

        .btn-cancelar-modal {
            flex: 1;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            color: #b3b3b3;
            padding: 10px;
            border-radius: 20px;
            font-size: 0.88rem;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-cancelar-modal:hover { background: rgba(255,255,255,0.1); color: #fff; }

        /* Fade-in */
        .fade-in { opacity: 0; transform: translateY(20px); transition: opacity 0.5s ease, transform 0.5s ease; }
        .fade-in.visible { opacity: 1; transform: translateY(0); }

        /* Footer slideshow */
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
            <input type="text" id="search-input" placeholder="Buscar usuário..." oninput="filtrarTabela()">
        </div>
        <div class="user-controls d-flex align-items-center gap-3">
            <span class="badge-upgrade"><i class="fas fa-shield-alt me-1"></i>Admin</span>
            <span id="nome-usuario" style="font-size:0.85rem; color:#b3b3b3;"></span>
        </div>
    </header>

    <a href="homeAdmin.php" class="btn-voltar fade-in">
        <i class="fas fa-arrow-left"></i> Voltar
    </a>

    <section class="fade-in">
        <div class="section-header">
            <h2><i class="fas fa-users-cog me-2" style="color:#7c4dff;"></i>Administração de Usuários</h2>
            <span style="font-size:0.82rem; color:#b3b3b3;">
                <span id="user-count"><?php echo count($usuarios); ?></span> usuário<?php echo count($usuarios) !== 1 ? 's' : ''; ?>
            </span>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Usuário</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="tabela-usuarios">
                    <?php if (empty($usuarios)): ?>
                        <tr><td colspan="5" style="text-align:center; color:#b3b3b3; padding:40px;">Nenhum usuário encontrado.</td></tr>
                    <?php else: ?>
                        <?php foreach ($usuarios as $u): ?>
                            <tr id="linha-<?php echo $u['id']; ?>">
                                <td style="color:#b3b3b3;"><?php echo htmlspecialchars($u['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar"><?php echo strtoupper(mb_substr($u['nome'], 0, 1)); ?></div>
                                        <?php echo htmlspecialchars($u['nome'], ENT_QUOTES, 'UTF-8'); ?>
                                    </div>
                                </td>
                                <td style="color:#b3b3b3;"><?php echo htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <span class="status-badge <?php echo $u['status'] == 1 ? 'ativo' : 'inativo'; ?>">
                                        <?php echo $u['status'] == 1 ? 'Ativo' : 'Inativo'; ?>
                                    </span>
                                </td>
                                <td class="acoes">
                                    <button class="btn-editar" onclick="abrirModal(<?php echo $u['id']; ?>)">
                                        <i class="fas fa-pen me-1"></i>Editar
                                    </button>
                                    <?php if ($u['status'] == 1): ?>
                                        <button class="btn-desativar" onclick="alterarStatus(<?php echo $u['id']; ?>, 0)">
                                            <i class="fas fa-ban me-1"></i>Desativar
                                        </button>
                                    <?php else: ?>
                                        <button class="btn-ativar" onclick="alterarStatus(<?php echo $u['id']; ?>, 1)">
                                            <i class="fas fa-check me-1"></i>Ativar
                                        </button>
                                    <?php endif; ?>
                                    <button class="btn-excluir" onclick="excluirUsuario(<?php echo $u['id']; ?>)">
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

    <!-- Modal editar -->
    <div id="modal-editar" class="modal-overlay">
        <div class="modal-box">
            <h2><i class="fas fa-pen me-2" style="color:#7c4dff;"></i>Editar Usuário</h2>
            <form id="form-editar">
                <input type="hidden" name="id" id="edit-id">
                <label for="edit-nome">Nome</label>
                <input type="text" name="nome" id="edit-nome" required placeholder="Nome do usuário">
                <label for="edit-email">Email</label>
                <input type="email" name="email" id="edit-email" required placeholder="email@exemplo.com">
                <label for="edit-senha">Nova Senha <small style="color:#555;">(deixe vazio para manter)</small></label>
                <input type="password" name="senha" id="edit-senha" placeholder="••••••••">
                <div class="modal-acoes">
                    <button type="submit" class="btn-salvar"><i class="fas fa-save me-2"></i>Salvar</button>
                    <button type="button" class="btn-cancelar-modal" onclick="fecharModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</main>

<!-- Footer Slideshow -->
<footer class="player">
    <img id="slideshow-img" src="https://img.freepik.com/vetores-premium/um-cartaz-de-publicidade-musical-com-um-disco-de-vinil_606304-808.jpg" alt="banner">
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>
<script src="assets/js/admin.js"></script>
<script>
    // Filtro de busca na tabela
    function filtrarTabela() {
        const input = document.getElementById('search-input').value.toLowerCase();
        const linhas = document.querySelectorAll('#tabela-usuarios tr');
        let visiveis = 0;
        linhas.forEach(tr => {
            const texto = tr.textContent.toLowerCase();
            const visivel = texto.includes(input);
            tr.style.display = visivel ? '' : 'none';
            if (visivel) visiveis++;
        });
        document.getElementById('user-count').textContent = visiveis;
    }

    // Slideshow footer
    const slides = [
        'https://img.freepik.com/vetores-premium/um-cartaz-de-publicidade-musical-com-um-disco-de-vinil_606304-808.jpg',
        'https://png.pngtree.com/thumb_back/fh260/background/20221224/pngtree-blue-musical-notes-background-image_1530362.jpg',
        'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT_5qs5i8zvZ2TQptBz3UOxKhFS-Te9heBoDA&s',
        'https://thumbs.dreamstime.com/b/grande-nota-musical-%C3%A9-uma-trepada-sobre-fundo-abstrato-para-publicidade-em-lojas-e-est%C3%BAdios-de-m%C3%BAdios-de-m%C3%BAsica-gerada-por-ai-334831956.jpg'
    ];
    let current = 0;
    const img = document.getElementById('slideshow-img');
    setInterval(() => {
        img.style.opacity = '0';
        setTimeout(() => { current = (current + 1) % slides.length; img.src = slides[current]; img.style.opacity = '1'; }, 1000);
    }, 30000);

    // Fade-in
    const observer = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
</script>
</body>
</html>

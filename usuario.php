<?php
require_once __DIR__ . '/model/dao/UsuarioDAO.php';

$usuarioDAO = new UsuarioDAO();
$usuarios = $usuarioDAO->listarUsuario();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundScore | Administração de Usuários</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>

    <?php include __DIR__ . '/navbar.php'; ?>

    <main class="content">
        <h1>Administração de Usuários</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="tabela-usuarios">
                <?php if (empty($usuarios)): ?>
                    <tr><td colspan="5">Nenhum usuário encontrado.</td></tr>
                <?php else: ?>
                    <?php foreach ($usuarios as $u): ?>
                        <tr id="linha-<?php echo $u['id']; ?>">
                            <td><?php echo htmlspecialchars($u['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($u['nome'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo ($u['status'] == 1 ? 'Ativo' : 'Inativo'); ?></td>
                            <td class="acoes">
                                <button class="btn-editar" type="button" onclick="abrirModal(<?php echo $u['id']; ?>)">Editar</button>
                                <?php if ($u['status'] == 1): ?>
                                    <button class="btn-desativar" type="button" onclick="alterarStatus(<?php echo $u['id']; ?>, 0)">Desativar</button>
                                <?php else: ?>
                                    <button class="btn-ativar" type="button" onclick="alterarStatus(<?php echo $u['id']; ?>, 1)">Ativar</button>
                                <?php endif; ?>
                                <button class="btn-excluir" type="button" onclick="excluirUsuario(<?php echo $u['id']; ?>)">Excluir</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="homeAdmin.php" class="btn-voltar">Voltar</a>

        <div id="modal-editar" class="modal-overlay">
            <div class="modal-box">
                <h2>Editar Usuário</h2>
                <form id="form-editar">
                    <input type="hidden" name="id" id="edit-id">

                    <label for="edit-nome">Nome</label>
                    <input type="text" name="nome" id="edit-nome" required>

                    <label for="edit-email">Email</label>
                    <input type="email" name="email" id="edit-email" required>

                    <label for="edit-senha">Nova Senha <small style="color:#777;">(deixe vazio para manter)</small></label>
                    <input type="password" name="senha" id="edit-senha">

                    <div class="modal-acoes">
                        <button type="submit" class="btn-salvar">Salvar</button>
                        <button type="button" class="btn-cancelar-modal" onclick="fecharModal()">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="assets/js/app.js"></script>
    <script src="assets/js/admin.js"></script>
</body>
</html>

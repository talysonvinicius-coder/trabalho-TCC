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
    <title>SoundScore | Administração de Categorias</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>

    <?php include __DIR__ . '/navbar.php'; ?>

    <main class="content">
        <h1>Administração de Categorias</h1>

        <button class="btn-cadastrar" onclick="abrirModalCadastro()">Cadastrar Nova Categoria</button>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="tabela-categorias">
                <?php if (empty($categorias)): ?>
                    <tr><td colspan="5">Nenhuma categoria encontrada.</td></tr>
                <?php else: ?>
                    <?php foreach ($categorias as $c): ?>
                        <tr id="linha-<?php echo $c['id']; ?>">
                            <td><?php echo htmlspecialchars($c['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($c['nome'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($c['descricao'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo ($c['status'] == 1 ? 'Ativa' : 'Inativa'); ?></td>
                            <td class="acoes">
                                <button class="btn-editar" type="button" onclick="abrirModal(<?php echo $c['id']; ?>)">Editar</button>
                                <button class="btn-excluir" type="button" onclick="excluirCategoria(<?php echo $c['id']; ?>)">Excluir</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="homeAdmin.php" class="btn-voltar">Voltar</a>

        <div id="modal-editar" class="modal-overlay">
            <div class="modal-box">
                <h2>Editar Categoria</h2>
                <form id="form-editar">
                    <input type="hidden" name="id" id="edit-id">

                    <label for="edit-nome">Nome</label>
                    <input type="text" name="nome" id="edit-nome" required>

                    <label for="edit-descricao">Descrição</label>
                    <textarea name="descricao" id="edit-descricao" rows="3"></textarea>

                    <label for="edit-status">Status</label>
                    <select name="status" id="edit-status" required>
                        <option value="1">Ativa</option>
                        <option value="0">Inativa</option>
                    </select>

                    <div class="modal-acoes">
                        <button type="submit" class="btn-salvar">Salvar</button>
                        <button type="button" class="btn-cancelar-modal" onclick="fecharModal()">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="modal-cadastrar" class="modal-overlay">
            <div class="modal-box">
                <h2>Cadastrar Categoria</h2>
                <form id="form-cadastrar">
                    <label for="cad-nome">Nome</label>
                    <input type="text" name="nome" id="cad-nome" required>

                    <label for="cad-descricao">Descrição</label>
                    <textarea name="descricao" id="cad-descricao" rows="3"></textarea>

                    <label for="cad-status">Status</label>
                    <select name="status" id="cad-status" required>
                        <option value="1">Ativa</option>
                        <option value="0">Inativa</option>
                    </select>

                    <div class="modal-acoes">
                        <button type="submit" class="btn-salvar">Cadastrar</button>
                        <button type="button" class="btn-cancelar-modal" onclick="fecharModalCadastro()">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="assets/js/app.js"></script>
    <script src="assets/js/categoria.js"></script>
</body>
</html>
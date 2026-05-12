(async () => {
    const sessao = await verificarSessao(true);
    if (!sessao) return;
    carregarCategorias();
})();

async function carregarCategorias() {
    try {
        const res = await fetch('api/categorias/listar.php');
        const dados = await res.json();
        if (!dados.ok) {
            alert('Erro: ' + dados.erro);
            return;
        }
        renderizarTabela(dados.categorias);
    } catch {
        alert('Erro ao carregar categorias.');
    }
}

function renderizarTabela(categorias) {
    const tbody = document.getElementById('tabela-categorias');
    if (!categorias.length) {
        tbody.innerHTML = '<tr><td colspan="5">Nenhuma categoria encontrada.</td></tr>';
        return;
    }
    tbody.innerHTML = categorias.map(c => `
        <tr id="linha-${c.id}">
            <td>${c.id}</td>
            <td>${escHtml(c.nome)}</td>
            <td>${escHtml(c.descricao || '')}</td>
            <td>${c.status == 1 ? 'Ativa' : 'Inativa'}</td>
            <td class="acoes">
                <button class="btn-editar" onclick="abrirModal(${c.id})">Editar</button>
                <button class="btn-excluir" onclick="excluirCategoria(${c.id})">Excluir</button>
            </td>
        </tr>
    `).join('');
}

async function excluirCategoria(id) {
    if (!confirm('ATENÇÃO: Deseja excluir permanentemente esta categoria?')) return;

    const body = new URLSearchParams({ id });
    const res = await fetch('api/categorias/excluir.php', { method: 'POST', body });
    const dados = await res.json();

    if (dados.ok) {
        document.getElementById('linha-' + id)?.remove();
    } else {
        alert('Erro: ' + dados.erro);
    }
}

async function abrirModal(id) {
    const res = await fetch('api/categorias/editar.php?id=' + id);
    const dados = await res.json();
    if (!dados.ok) { alert('Categoria não encontrada'); return; }

    const c = dados.categoria;
    document.getElementById('edit-id').value = c.id;
    document.getElementById('edit-nome').value = c.nome;
    document.getElementById('edit-descricao').value = c.descricao || '';
    document.getElementById('edit-status').value = c.status;

    document.getElementById('modal-editar').style.display = 'flex';
}

function fecharModal() {
    document.getElementById('modal-editar').style.display = 'none';
}

function abrirModalCadastro() {
    document.getElementById('cad-nome').value = '';
    document.getElementById('cad-descricao').value = '';
    document.getElementById('cad-status').value = '1';
    document.getElementById('modal-cadastrar').style.display = 'flex';
}

function fecharModalCadastro() {
    document.getElementById('modal-cadastrar').style.display = 'none';
}

document.getElementById('form-editar').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const body = new URLSearchParams({
        id: form.id.value,
        nome: form.nome.value,
        descricao: form.descricao.value,
        status: form.status.value
    });

    const res = await fetch('api/categorias/editar.php', { method: 'POST', body });
    const dados = await res.json();

    if (dados.ok) {
        fecharModal();
        carregarCategorias();
    } else {
        alert('Erro: ' + dados.erro);
    }
});

document.getElementById('form-cadastrar').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const body = new URLSearchParams({
        nome: form.nome.value,
        descricao: form.descricao.value,
        status: form.status.value
    });

    const res = await fetch('api/categorias/cadastrar.php', { method: 'POST', body });
    const dados = await res.json();

    if (dados.ok) {
        fecharModalCadastro();
        carregarCategorias();
    } else {
        alert('Erro: ' + dados.erro);
    }
});

function escHtml(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str || ''));
    return div.innerHTML;
}

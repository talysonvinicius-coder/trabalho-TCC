(async () => {
    const sessao = await verificarSessao(true);
    if (!sessao) return;
    carregarUsuarios();
})();

async function carregarUsuarios() {
    try {
        const res = await fetch('api/usuarios/listar.php');
        const dados = await res.json();
        if (!dados.ok) {
            alert('Erro: ' + dados.erro);
            return;
        }
        renderizarTabela(dados.usuarios);
    } catch {
        alert('Erro ao carregar usuários.');
    }
}

function renderizarTabela(usuarios) {
    const tbody = document.getElementById('tabela-usuarios');
    if (!usuarios.length) {
        tbody.innerHTML = '<tr><td colspan="5">Nenhum usuário encontrado.</td></tr>';
        return;
    }
    tbody.innerHTML = usuarios.map(u => `
        <tr id="linha-${u.id}">
            <td>${u.id}</td>
            <td>${escHtml(u.nome)}</td>
            <td>${escHtml(u.email)}</td>
            <td>${u.status == 1 ? 'Ativo' : 'Inativo'}</td>
            <td class="acoes">
                <button class="btn-editar" onclick="abrirModal(${u.id})">Editar</button>
                ${u.status == 1
                    ? `<button class="btn-desativar" onclick="alterarStatus(${u.id}, 0)">Desativar</button>`
                    : `<button class="btn-ativar"    onclick="alterarStatus(${u.id}, 1)">Ativar</button>`
                }
                <button class="btn-excluir" onclick="excluirUsuario(${u.id})">Excluir</button>
            </td>
        </tr>
    `).join('');
}

async function alterarStatus(id, status) {
    const acao = status === 1 ? 'ativar' : 'desativar';
    if (!confirm(`Deseja ${acao} este usuário?`)) return;

    const body = new URLSearchParams({ id, status });
    const res  = await fetch('api/usuarios/status.php', { method: 'POST', body });
    const dados = await res.json();

    if (dados.ok) {
        carregarUsuarios();
    } else {
        alert('Erro: ' + dados.erro);
    }
}

async function excluirUsuario(id) {
    if (!confirm('ATENÇÃO: Deseja excluir permanentemente este usuário?')) return;

    const body = new URLSearchParams({ id });
    const res  = await fetch('api/usuarios/excluir.php', { method: 'POST', body });
    const dados = await res.json();

    if (dados.ok) {
        document.getElementById('linha-' + id)?.remove();
    } else {
        alert('Erro: ' + dados.erro);
    }
}

async function abrirModal(id) {
    const res = await fetch('api/usuarios/editar.php?id=' + id);
    const dados = await res.json();
    if (!dados.ok) { alert('Usuário não encontrado'); return; }

    const u = dados.usuario;
    document.getElementById('edit-id').value    = u.id;
    document.getElementById('edit-nome').value  = u.nome;
    document.getElementById('edit-email').value = u.email;
    document.getElementById('edit-senha').value = '';

    document.getElementById('modal-editar').style.display = 'flex';
}

function fecharModal() {
    document.getElementById('modal-editar').style.display = 'none';
}

document.getElementById('form-editar').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const body = new URLSearchParams({
        id:    form.id.value,
        nome:  form.nome.value,
        email: form.email.value,
        senha: form.senha.value
    });

    const res  = await fetch('api/usuarios/editar.php', { method: 'POST', body });
    const dados = await res.json();

    if (dados.ok) {
        fecharModal();
        carregarUsuarios();
    } else {
        alert('Erro: ' + dados.erro);
    }
});

function escHtml(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str || ''));
    return div.innerHTML;
}

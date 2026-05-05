// Utilitários compartilhados entre todas as páginas

async function verificarSessao(exigeAdmin = false) {
    try {
        const res = await fetch('api/auth/session.php');
        const dados = await res.json();

        if (!dados.logado) {
            window.location.href = 'index.html';
            return null;
        }

        if (exigeAdmin && dados.perfil !== 'admin') {
            window.location.href = 'paginicial.html';
            return null;
        }

        return dados;
    } catch {
        window.location.href = 'index.html';
        return null;
    }
}

async function logout() {
    await fetch('api/auth/logout.php', { method: 'POST' });
    window.location.href = 'index.html';
}

function mostrarErro(elementId, mensagem) {
    const el = document.getElementById(elementId);
    if (el) {
        el.textContent = mensagem;
        el.style.display = 'block';
    }
}

function ocultarErro(elementId) {
    const el = document.getElementById(elementId);
    if (el) el.style.display = 'none';
}

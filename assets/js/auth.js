// Redireciona para home se já estiver logado
(async () => {
    try {
        const res = await fetch('api/auth/session.php');
        const dados = await res.json();
        if (dados.logado) window.location.href = 'paginicial.php';
    } catch { /* sem sessão ativa */ }
})();

document.getElementById('form-login').addEventListener('submit', async (e) => {
    e.preventDefault();
    ocultarErro('msg-erro');

    const form = e.target;
    const body = new URLSearchParams({
        email: form.email.value,
        senha: form.senha.value
    });

    try {
        const res  = await fetch('api/auth/login.php', { method: 'POST', body });
        const dados = await res.json();

        if (dados.ok) {
            window.location.href = dados.perfil === 'admin' ? 'homeAdmin.php' : 'paginicial.php';
        } else {
            mostrarErro('msg-erro', dados.erro);
        }
    } catch {
        mostrarErro('msg-erro', 'Erro de conexão. Tente novamente.');
    }
});

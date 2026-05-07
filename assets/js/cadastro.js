document.getElementById('form-cadastro').addEventListener('submit', async (e) => {
    e.preventDefault();
    ocultarErro('msg-erro');

    const form = e.target;
    const senha = form.senha.value;
    const confirma = form.confirma.value;

    if (senha !== confirma) {
        mostrarErro('msg-erro', 'As senhas não coincidem');
        return;
    }

    const body = new URLSearchParams({
        nome:      form.nome.value,
        email:     form.email.value,
        senha:     senha,
        perfil_id: form.perfil_id.value,
        plano_id:  form.plano_id.value
    });

    try {
        const res  = await fetch('api/auth/cadastro.php', { method: 'POST', body });
        const dados = await res.json();

        if (dados.ok) {
            window.location.href = 'index.html?cadastro=1';
        } else {
            mostrarErro('msg-erro', dados.erro);
        }
    } catch {
        mostrarErro('msg-erro', 'Erro de conexão. Tente novamente.');
    }
});

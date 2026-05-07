(async () => {
    const sessao = await verificarSessao(true);
    if (sessao) {
        const nomeEl = document.getElementById('nome-usuario');
        if (nomeEl) nomeEl.textContent = sessao.nome;
    }
})();

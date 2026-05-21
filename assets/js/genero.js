(async () => {
    const sessao = await verificarSessao();
    if (!sessao) return;
    document.getElementById('nome-usuario').textContent = sessao.nome;
})();

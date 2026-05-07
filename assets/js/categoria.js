document.getElementById('form-categoria').addEventListener('submit', (e) => {
    e.preventDefault();
    ocultarErro('msg-erro');
    document.getElementById('msg-sucesso').style.display = 'none';

    const form = e.target;
    const nome = form.nome.value.trim();
    const descricao = form.descricao.value.trim();

    if (!nome) {
        mostrarErro('msg-erro', 'Informe o nome da categoria.');
        return;
    }

    document.getElementById('msg-sucesso').textContent = `Categoria "${nome}" criada com sucesso.`;
    document.getElementById('msg-sucesso').style.display = 'block';
    form.reset();
});

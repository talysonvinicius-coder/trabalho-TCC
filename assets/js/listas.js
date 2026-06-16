function abrirModalNovaLista() {
    document.getElementById('modal-titulo').innerText = 'Nova Lista';
    document.getElementById('lista-id').value = '';
    document.getElementById('lista-nome').value = '';
    document.getElementById('lista-descricao').value = '';
    var modal = new bootstrap.Modal(document.getElementById('modal-lista'));
    modal.show();
}

function abrirModalEditar(id, nome, descricao) {
    document.getElementById('modal-titulo').innerText = 'Editar Lista';
    document.getElementById('lista-id').value = id;
    document.getElementById('lista-nome').value = nome;
    document.getElementById('lista-descricao').value = descricao;
    var modal = new bootstrap.Modal(document.getElementById('modal-lista'));
    modal.show();
}

function fecharModal() {
    var modalEl = document.getElementById('modal-lista');
    var modal = bootstrap.Modal.getInstance(modalEl);
    if(modal) {
        modal.hide();
    }
}

function abrirModalAddLista(musicaId) {
    document.getElementById('add-musica-id').value = musicaId;
    var modal = new bootstrap.Modal(document.getElementById('modal-add-lista'));
    modal.show();
}

function fecharModalAddLista() {
    var modalEl = document.getElementById('modal-add-lista');
    var modal = bootstrap.Modal.getInstance(modalEl);
    if(modal) {
        modal.hide();
    }
}
const GENEROS_BUSCA = [
    { nome: 'Hip-Hop',           classe: 'hiphop',    link: 'hiphop.html' },
    { nome: 'Jazz',              classe: 'jazz',       link: 'jazz.html' },
    { nome: 'POP',               classe: 'pop',        link: 'pop.html' },
    { nome: 'Música Eletrônica', classe: 'eletronica', link: 'musicaeletro.html' },
    { nome: 'Rock',              classe: 'rock',       link: 'rock.html' },
    { nome: 'MPB',               classe: 'mpb',        link: 'mpb.html' },
    { nome: 'Sertanejo',         classe: 'sertanejo',  link: '#' },
    { nome: 'Funk',              classe: 'funk',       link: '#' },
    { nome: 'Reggae',            classe: 'reggae',     link: '#' },
    { nome: 'Clássica',          classe: 'classica',   link: '#' },
    { nome: 'Lo-fi',             classe: 'lofi',       link: '#' },
    { nome: 'Country',           classe: 'country',    link: '#' },
    { nome: 'Forró & Brega',     classe: 'forro',      link: '#' },
    { nome: 'Cristã',            classe: 'crista',     link: '#' },
    { nome: 'K-pop',             classe: 'kpop',       link: '#' },
    { nome: 'Trap/Rap',          classe: 'rap-trap',   link: '#' },
];

(async () => {
    const sessao = await verificarSessao();
    if (!sessao) return;
    renderizarCards(GENEROS_BUSCA);
    gerarVisualizador();
})();

function renderizarCards(lista) {
    const grade = document.getElementById('grade-generos');
    grade.innerHTML = lista.map(g => `
        <div class="card ${g.classe}" data-nome="${g.nome.toLowerCase()}">
            <h2>${g.nome}</h2>
            <a href="${g.link}" class="btn-visualizar">Visualizar Músicas</a>
        </div>
    `).join('');
}

document.getElementById('inputBusca').addEventListener('keyup', function () {
    const termo = this.value.toLowerCase();
    document.querySelectorAll('#grade-generos .card').forEach(card => {
        const match = card.dataset.nome.includes(termo);
        card.style.display = match ? 'block' : 'none';
    });
});

function gerarVisualizador() {
    const cores = ['#7b4fb6', '#00d4ff', '#9b59b6', '#e74c3c', '#f1c40f', '#2ecc71'];
    const container = document.getElementById('audio-visualizer');
    container.innerHTML = Array.from({ length: 60 }, (_, i) => {
        const altura = Math.floor(Math.random() * 36) + 10;
        const cor = cores[i % cores.length];
        return `<div class="bar" style="height:${altura}px; background:${cor};"></div>`;
    }).join('');
}

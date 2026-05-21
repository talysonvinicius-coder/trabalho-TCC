const MUSICAS = [
    { titulo: 'Midnight City',   artista: 'M83',           genero: 'Eletrônica', nota: 5, capa: 'https://picsum.photos/seed/music1/200' },
    { titulo: 'Blinding Lights', artista: 'The Weeknd',    genero: 'Pop',        nota: 4, capa: 'https://picsum.photos/seed/music2/200' },
    { titulo: 'Breathe Deeper',  artista: 'Tame Impala',   genero: 'Rock',       nota: 5, capa: 'https://picsum.photos/seed/music3/200' },
    { titulo: 'Levitating',      artista: 'Dua Lipa',      genero: 'Pop',        nota: 3, capa: 'https://picsum.photos/seed/music4/200' },
    { titulo: 'Starboy',         artista: 'The Weeknd',    genero: 'R&B',        nota: 4, capa: 'https://picsum.photos/seed/music5/200' },
    { titulo: 'Heat Waves',      artista: 'Glass Animals', genero: 'Indie',      nota: 2, capa: 'https://picsum.photos/seed/music6/200' },
];

(async () => {
    const sessao = await verificarSessao();
    if (!sessao) return;
    document.getElementById('nome-usuario').textContent = sessao.nome;
    renderizarEstatisticas();
    renderizarMusicas();
})();

function renderizarEstatisticas() {
    const total = MUSICAS.length;
    const media = total > 0 ? (MUSICAS.reduce((s, m) => s + m.nota, 0) / total).toFixed(1) : 0;

    document.getElementById('avg-score').textContent = media;
    document.getElementById('total-avaliacoes').textContent = total + ' avaliações';

    const estrelas = document.getElementById('avg-stars');
    estrelas.innerHTML = Array.from({ length: 5 }, (_, i) =>
        `<i class="${i < Math.round(media) ? 'fas' : 'far'} fa-star"></i>`
    ).join('');

    const contagem = [1, 2, 3, 4, 5].reduce((acc, n) => {
        acc[n] = MUSICAS.filter(m => m.nota === n).length;
        return acc;
    }, {});

    const barras = document.getElementById('barras-nota');
    barras.innerHTML = [5, 4, 3, 2, 1].map(i => {
        const pct = total > 0 ? (contagem[i] / total * 100).toFixed(0) : 0;
        return `
            <div class="bar-row">
                <span>${i}</span>
                <i class="fas fa-star" style="color:#ffc107; font-size:0.75rem;"></i>
                <div class="bar-track"><div class="bar-fill" style="width:${pct}%"></div></div>
                <span class="bar-count">${contagem[i]}</span>
            </div>`;
    }).join('');
}

function renderizarMusicas() {
    const grid = document.getElementById('grid-musicas');
    grid.innerHTML = MUSICAS.map(m => `
        <div class="col">
            <div class="music-card">
                <img src="${m.capa}" alt="${m.titulo}">
                <div class="info">
                    <h6>${m.titulo}</h6>
                    <p>${m.artista} &bull; ${m.genero}</p>
                    <div class="stars">
                        ${Array.from({ length: 5 }, (_, i) =>
                            `<i class="${i < m.nota ? 'fas' : 'far'} fa-star ${i >= m.nota ? 'empty' : ''}"></i>`
                        ).join('')}
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

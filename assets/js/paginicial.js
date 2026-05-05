const GENEROS = [
    { nome: 'Hip-Hop',           classe: 'hiphop',    link: 'hiphop.html',       color: '#7b4fb6' },
    { nome: 'Jazz',              classe: 'jazz',       link: 'jazz.html',         color: '#3b5998' },
    { nome: 'POP',               classe: 'pop',        link: 'pop.html',          color: '#3498db' },
    { nome: 'Música Eletrônica', classe: 'eletronica', link: 'musicaeletro.html', color: '#5d5d6a' },
    { nome: 'Rock',              classe: 'rock',       link: 'rock.html',         color: '#e74c3c' },
    { nome: 'MPB',               classe: 'mpb',        link: 'mpb.html',          color: '#5c6bc0' },
    { nome: 'Sertanejo',         classe: 'sertanejo',  link: '#',                 color: '#f1c40f' },
    { nome: 'Funk',              classe: 'funk',       link: '#',                 color: '#e67e22' },
    { nome: 'Reggae',            classe: 'reggae',     link: '#',                 color: '#7e57c2' },
    { nome: 'Clássica',          classe: 'classica',   link: '#',                 color: '#ecf0f1' },
    { nome: 'Lo-fi',             classe: 'lofi',       link: '#',                 color: '#9b59b6' },
    { nome: 'Country',           classe: 'country',    link: '#',                 color: '#a0522d' },
    { nome: 'Forró & Brega',     classe: 'forro',      link: '#',                 color: '#ff5733' },
    { nome: 'Cristã',            classe: 'crista',     link: '#',                 color: '#ffffff' },
    { nome: 'K-pop',             classe: 'kpop',       link: '#',                 color: '#ff69b4' },
    { nome: 'Trap/Rap',          classe: 'rap-trap',   link: '#',                 color: '#616161' },
];

(async () => {
    const sessao = await verificarSessao();
    if (!sessao) return;

    document.getElementById('nome-usuario').textContent = sessao.nome;

    if (sessao.perfil === 'admin') {
        document.getElementById('link-admin').style.display = 'flex';
    }

    renderizarGeneros();
    iniciarSlideshow();
})();

function renderizarGeneros() {
    const grid = document.getElementById('grade-generos');
    grid.innerHTML = GENEROS.map(g => `
        <div class="col">
            <div class="genre-card ${g.classe}" style="border-bottom-color: ${g.color}">
                <h2>${g.nome}</h2>
                <a href="${g.link}" class="btn-genero">Visualizar Músicas</a>
            </div>
        </div>
    `).join('');
}

function iniciarSlideshow() {
    const slides = [
        'https://img.freepik.com/vetores-premium/um-cartaz-de-publicidade-musical-com-um-disco-de-vinil-e-notas-musicais-em-uma-ilustracao-vetorial-isolada_606304-808.jpg',
        'https://png.pngtree.com/thumb_back/fh260/background/20221224/pngtree-blue-musical-notes-background-image_1530362.jpg',
        'https://thumbs.dreamstime.com/b/grande-nota-musical-%C3%A9-uma-trepada-sobre-fundo-abstrato-334831956.jpg'
    ];
    let current = 0;
    const img = document.getElementById('slideshow-img');

    setInterval(() => {
        img.style.opacity = '0';
        setTimeout(() => {
            current = (current + 1) % slides.length;
            img.src = slides[current];
            img.style.opacity = '1';
        }, 1000);
    }, 30000);
}

function toggleCurtir(btn) {
    const icon = btn.querySelector('i');
    const curtido = icon.classList.contains('fas');
    icon.classList.toggle('fas', !curtido);
    icon.classList.toggle('far', curtido);
    btn.style.color = curtido ? '' : '#e53935';
    btn.style.borderColor = curtido ? '' : '#e53935';
    btn.innerHTML = curtido
        ? '<i class="far fa-heart me-2"></i>Curtir'
        : '<i class="fas fa-heart me-2"></i>Curtido';
}

function abrirComentario() {
    document.getElementById('modal-comentario').style.display = 'flex';
}

function fecharComentario() {
    document.getElementById('modal-comentario').style.display = 'none';
    document.getElementById('texto-comentario').value = '';
}

function enviarComentario() {
    const texto = document.getElementById('texto-comentario').value.trim();
    if (!texto) return;
    fecharComentario();
}

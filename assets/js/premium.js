(async () => {
    const sessao = await verificarSessao();
    if (!sessao) return;
})();

function showPage(pageId) {
    document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
    const alvo = document.getElementById(pageId);
    if (alvo) alvo.classList.add('active');
}

function createPlaylist() {
    const name = prompt('Qual o nome da nova playlist?');
    if (!name || !name.trim()) return;

    const container = document.getElementById('playlist-list');
    const item = document.createElement('div');
    item.className = 'playlist-item';
    item.innerHTML = `
        <div>
            <span>${name.trim()}</span>
            <span class="tag-premium">PREMIUM</span>
        </div>
        <button class="btn-delete" onclick="this.parentElement.remove()">&times;</button>
    `;
    container.prepend(item);
}

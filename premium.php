<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Music Experience</title>
    <link rel="stylesheet" href="assets/css/premium.css">
</head>
<body>

    <div class="app-container">
        <nav class="sidebar">
            <h2><a href="paginicial.php">SoundScore 👑</a></h2>
            <ul>
                <li onclick="showPage('home')">🏠 Início</li>
                <li onclick="showPage('playlists')">📚 Minhas Listas</li>
                <li onclick="showPage('premium-info')">⭐ Benefícios</li>
            </ul>
        </nav>

        <main class="content">
            
            <div id="home" class="page active">
                <h1>Olá, Usuário Premium</h1>
                <p>O que pretende ouvir hoje?</p>
                <div style="background: #282828; padding: 20px; border-radius: 10px; margin-top: 20px;">
                    <h3>Mix Diário 1</h3>
                    <p style="color: #b3b3b3;">Artistas recomendados para você.</p>
                </div>
            </div>

            <div id="playlists" class="page">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h1>Minhas Playlists</h1>
                    <button class="premium-btn" onclick="createPlaylist()">+ Nova Playlist</button>
                </div>
                <div id="playlist-list">
                    <div class="playlist-item">
                        <div>
                            <span>Favoritas de 2024</span>
                            <span class="tag-premium">PREMIUM</span>
                        </div>
                        <button class="btn-delete" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                </div>
            </div>

            <div id="premium-info" class="page">
                <h1>O Teu Plano Premium</h1>
                <ul>
                    <li>✅ Áudio de Alta Qualidade (320kbps)</li>
                    <li>✅ Sem anúncios</li>
                    <li>✅ Downloads ilimitados</li>
                    <li>✅ Criação de listas ilimitadas</li>
                </ul>
            </div>

        </main>
    </div>

    <script>
        // JAVASCRIPT INTEGRADO
        
        // Função para trocar de página
        function showPage(pageId) {
            document.querySelectorAll('.page').forEach(page => {
                page.classList.remove('active');
            });

            const targetPage = document.getElementById(pageId);
            if (targetPage) {
                targetPage.classList.add('active');
            }
        }

        // Função para criar playlist dinâmica com botão de excluir
        function createPlaylist() {
            const name = prompt("Qual o nome da tua nova playlist?");
            
            if (name && name.trim() !== "") {
                const listContainer = document.getElementById('playlist-list');
                
                const newItem = document.createElement('div');
                newItem.className = 'playlist-item';
                
                // Estrutura atualizada: Texto à esquerda e Botão de Excluir à direita
                newItem.innerHTML = `
                    <div>
                        <span>${name}</span>
                        <span class="tag-premium">PREMIUM</span>
                    </div>
                    <button class="btn-delete" onclick="this.parentElement.remove()">&times;</button>
                `;
                
                listContainer.prepend(newItem); 
            }
        }
    </script>

</body>
</html>
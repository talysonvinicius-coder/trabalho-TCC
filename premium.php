<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Music Experience</title>
    <style>
        /* CSS INTEGRADO */
        * { box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            background-color: #121212; 
            color: #ffffff; 
        }

        .app-container { 
            display: flex; 
            height: 100vh; 
        }

        /* Menu Lateral */
        .sidebar { 
            width: 240px; 
            background-color: #000000; 
            padding: 20px; 
            display: flex;
            flex-direction: column;
        }

        .sidebar h2 { color: #1DB954; font-size: 1.5rem; margin-bottom: 30px; }

        .sidebar ul { list-style: none; padding: 0; }

        .sidebar li { 
            padding: 12px; 
            cursor: pointer; 
            color: #b3b3b3; 
            transition: 0.3s;
            border-radius: 4px;
        }

        .sidebar li:hover { color: white; background-color: #282828; }

        /* Área de Conteúdo */
        .content { 
            flex: 1; 
            padding: 40px; 
            overflow-y: auto;
            background: linear-gradient(to bottom, #1e1e1e, #121212);
        }

        .page { display: none; }
        .page.active { display: block; animation: fadeIn 0.4s; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Estilo Playlists */
        .premium-btn { 
            padding: 12px 24px; 
            background-color: #1DB954; 
            border: none; 
            border-radius: 50px; 
            color: black; 
            font-weight: bold;
            cursor: pointer; 
            margin-bottom: 20px;
            transition: transform 0.2s;
        }

        .premium-btn:hover { transform: scale(1.05); background-color: #1ed760; }

        #playlist-list { 
            list-style: none; 
            padding: 0; 
            margin-top: 20px;
        }

        .playlist-item { 
            padding: 15px; 
            background-color: #181818; 
            margin-bottom: 10px; 
            border-radius: 8px;
            border: 1px solid #282828;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .playlist-item span { font-weight: 500; }
        .tag-premium { font-size: 0.7rem; background: #fbbf24; color: black; padding: 2px 6px; border-radius: 4px; margin-left: 8px; }

        /* Estilo do botão de excluir */
        .btn-delete {
            background: none;
            border: none;
            color: #b3b3b3;
            cursor: pointer;
            font-size: 1.2rem;
            transition: 0.2s;
        }

        .btn-delete:hover {
            color: #ff4d4d;
            transform: scale(1.2);
        }

    </style>
</head>
<body>

    <div class="app-container">
        <nav class="sidebar">
            <h2>SoundScore 👑</h2>
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
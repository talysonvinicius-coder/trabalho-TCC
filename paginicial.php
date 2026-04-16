<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundScore - Player</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/teste.css">
    <style>
        /* Ajuste extra para o link não perder o estilo da sidebar */
        .sidebar nav ul li a {
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: center;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="logo">🎶 SoundScore</div>
        <nav>
            <ul>
                <li class="active">
                    <i class="fas fa-home"></i> Início
                </li>
                <li>
                    <a href="buscar.php">
                        <i class="fas fa-search"></i> Buscar
                    </a>
                </li>
               <li>
                <a href="biblioteca.php">
                    <i class="fas fa-book"></i> Sua Biblioteca
                </a>
               </li>
                <li>
                    <a href="premium.php">
                        <i class="fas fa-lock"></i> Adquirir Premium 
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <main class="content">
        <header class="top-bar">
            <div class="user-controls">
                <span>Upgrade</span>
                <div class="profile-circle"><i class="fas fa-user"></i></div>
            </div>
        </header>

        <section>
            <h2>Avaliadas recentemente</h2>
            <div class="grid">
                <div class="card">
                    <img src="https://picsum.photos/seed/music1/200" alt="Capa">
                    <h4>Midnight City</h4>
                    <p>M83 • <span class="score-badge">9.8</span></p>
                </div>
                <div class="card">
                    <img src="https://picsum.photos/seed/music2/200" alt="Capa">
                    <h4>Blinding Lights</h4>
                    <p>The Weeknd • <span class="score-badge">9.5</span></p>
                </div>
                <div class="card">
                    <img src="https://picsum.photos/seed/music3/200" alt="Capa">
                    <h4>Breathe Deeper</h4>
                    <p>Tame Impala • <span class="score-badge">9.2</span></p>
                </div>
            </div>
        </section>

        <section>
            <h2>Artistas Recomendados</h2>
            <div class="grid">
                <div class="card artist">
                    <img src="https://picsum.photos/seed/art1/200" alt="Artista">
                    <h4>The Weeknd</h4>
                    <p>Artista</p>
                </div>
                <div class="card artist">
                    <img src="https://picsum.photos/seed/art2/200" alt="Artista">
                    <h4>Tame Impala</h4>
                    <p>Artista</p>
                </div>
            </div>
        </section>

        <section>
            <h2>Suas Categorias</h2>
            <div class="grid">
                <div class="card artist">
                    <img src="https://picsum.photos/seed/genre1/200" alt="Categoria">
                    <h4>Rock Clássico</h4>
                    <p>Playlist</p>
                </div>
                <div class="card artist">
                    <img src="https://picsum.photos/seed/genre2/200" alt="Categoria">
                    <h4>Lofi Hip Hop</h4>
                    <p>Playlist</p>
                </div>
            </div>
        </section>

    </main>

    <footer class="player">
        <div class="now-playing">
            <img src="https://picsum.photos/seed/music1/200" alt="Atual">
            <div>
                <div class="song-title">Midnight City</div>
                <div class="song-artist">M83</div>
            </div>
        </div>

        <div class="player-controls">
            <div class="buttons">
                <i class="fas fa-random secondary"></i>
                <i class="fas fa-step-backward"></i>
                <i class="fas fa-play-circle main"></i>
                <i class="fas fa-step-forward"></i>
                <i class="fas fa-redo secondary"></i>
            </div>
            <div class="progress-container">
                <span class="time">1:20</span>
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
                <span class="time">3:45</span>
            </div>
        </div>

        <div class="volume-controls">
            <i class="fas fa-volume-up"></i>
            <div class="volume-bar">
                <div class="volume-fill"></div>
            </div>
        </div>
    </footer>

</body>
</html>
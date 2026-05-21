<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=bdmusica;charset=utf8mb4', 'root', '');
    $stmt = $pdo->query("SELECT id, nome FROM genero WHERE status = 1");
    $generos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $generos = [];
    error_log($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoundScore - Player</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/global.css">
    <style>
        .sidebar nav ul li a {
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: center;
            width: 100%;
            height: 100%;
        }
        .card-img-overlay-play {
            position: absolute;
            bottom: 70px;
            right: 12px;
            opacity: 0;
            transition: opacity 0.3s ease, transform 0.3s ease;
            transform: translateY(8px);
        }
        .card:hover .card-img-overlay-play {
            opacity: 1;
            transform: translateY(0);
        }
        .card { position: relative; }
        .genre-card {
            background: rgba(255,255,255,0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            padding: 25px 15px;
            text-align: center;
            transition: 0.3s;
        }
        .genre-card:hover {
            transform: translateY(-5px);
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.2);
        }
        .genre-card h2 { font-size: 1.1rem; margin-bottom: 12px; font-weight: 400; color: #fff; }
        .genre-card button {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.7);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            cursor: pointer;
            transition: 0.3s;
        }
        .genre-card button:hover { background: white; color: black; }
        .genre-card.hiphop    { border-bottom: 3px solid #7b4fb6; }
        .genre-card.jazz      { border-bottom: 3px solid #3b5998; }
        .genre-card.pop       { border-bottom: 3px solid #3498db; }
        .genre-card.eletronica{ border-bottom: 3px solid #5d5d6a; }
        .genre-card.rock      { border-bottom: 3px solid #e74c3c; }
        .genre-card.mpb       { border-bottom: 3px solid #5c6bc0; }
        .genre-card.sertanejo { border-bottom: 3px solid #f1c40f; }
        .genre-card.funk      { border-bottom: 3px solid #e67e22; }
        .genre-card.reggae    { border-bottom: 3px solid #7e57c2; }
        .genre-card.classica  { border-bottom: 3px solid #ecf0f1; }
        .genre-card.lofi      { border-bottom: 3px solid #9b59b6; }
        .genre-card.country   { border-bottom: 3px solid #a0522d; }
        .genre-card.forro     { border-bottom: 3px solid #ff5733; }
        .genre-card.crista    { border-bottom: 3px solid #ffffff; }
        .genre-card.kpop      { border-bottom: 3px solid #ff69b4; }
        .genre-card.rap-trap  { border-bottom: 3px solid #616161; }
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
                <a href="biblioteca.php">
                    <i class="fas fa-book"></i> Sua Biblioteca
                </a>
               </li>
                <li>
                    <a href="perfil.php">
                        <i class="fas fa-user"></i> Perfil
                </li>
              
                  
            </ul>
        </nav>
        <div class="sidebar-premium-banner" style="margin-top:10px;">
            <i class="fas fa-star mb-2" style="color:#ffc107;font-size:1.4rem"></i>
            <p>Ouça sem limites com o <strong>Premium</strong></p>
            <a href="premium.php" class="btn-premium-sidebar">Experimente grátis</a>
        </div>
    </aside>

    <main class="content">
        <header class="top-bar">
            <div class="search-bar-top">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="O que você quer ouvir?">
            </div>
            <div class="user-controls">
                <a href="premium.php" class="badge-upgrade"><i class="fas fa-bolt me-1"></i>Upgrade</a>
            </div>
        </header>

        <!-- Banner destaque -->
        <div class="featured-banner mb-4">
            <div class="featured-info">
                <span class="featured-tag">🔥Avaliações em alta</span>
                <h1>Midnight City</h1>
                <p>M83 • Álbum: Hurry Up, We're Dreaming</p>
                <div class="d-flex gap-2 mt-3">
                    <button class="btn-play-featured" onclick="abrirComentario()"><i class="fas fa-comment me-2"></i>Comentar</button>
                    <button class="btn-like-featured" id="btn-curtir" onclick="toggleCurtir(this)"><i class="far fa-heart me-2"></i>Curtir</button>
                </div>
            </div>
            <img src="https://picsum.photos/seed/music1/400/220" alt="Destaque" class="featured-img">
        </div>

        <section class="mb-4">
            <div class="section-header">
                <h2>Categorias</h2>
                <a href="buscar.php" class="see-all">Ver tudo</a>
            </div>
            <div class="generos-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px;">
                <?php if (count($generos) > 0): ?>
                    <?php foreach ($generos as $genero): ?>
                        <a href="genero.php?id=<?php echo htmlspecialchars($genero['id']); ?>" class="btn-genero" style="padding: 15px; text-align: center; border-radius: 8px; text-decoration: none; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); transition: 0.3s; display: block;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'">
                            <?php echo htmlspecialchars($genero['nome']); ?>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="grid-column: 1 / -1; color: rgba(255,255,255,0.5);">Nenhum gênero disponível.</p>
                <?php endif; ?>
            </div>
        </section>

        <section class="mb-4">
            <div class="section-header">
                <h2>Avaliadas recentemente</h2>
                <a href="#" class="see-all">Ver tudo</a>
            </div>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                <div class="col"><div class="card">
                    <img src="https://picsum.photos/seed/music1/200" alt="Capa">
                    <button class="card-img-overlay-play"><i class="fas fa-play"></i></button>
                    <h4>Midnight City</h4>
                    <p>M83 • <span class="score-badge">9.8</span></p>
                </div></div>
                <div class="col"><div class="card">
                    <img src="https://picsum.photos/seed/music2/200" alt="Capa">
                    <button class="card-img-overlay-play"><i class="fas fa-play"></i></button>
                    <h4>Blinding Lights</h4>
                    <p>The Weeknd • <span class="score-badge">9.5</span></p>
                </div></div>
                <div class="col"><div class="card">
                    <img src="https://picsum.photos/seed/music3/200" alt="Capa">
                    <button class="card-img-overlay-play"><i class="fas fa-play"></i></button>
                    <h4>Breathe Deeper</h4>
                    <p>Tame Impala • <span class="score-badge">9.2</span></p>
                </div></div>
                <div class="col"><div class="card">
                    <img src="https://picsum.photos/seed/music4/200" alt="Capa">
                    <button class="card-img-overlay-play"><i class="fas fa-play"></i></button>
                    <h4>Levitating</h4>
                    <p>Dua Lipa • <span class="score-badge">9.0</span></p>
                </div></div>
            </div>
        </section>

        <section class="mb-4">
            <div class="section-header">
                <h2>Artistas Recomendados</h2>
                <a href="#" class="see-all">Ver tudo</a>
            </div>
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                <div class="col"><div class="card artist">
                    <img src="https://picsum.photos/seed/art1/200" alt="Artista">
                    <h4>The Weeknd</h4>
                    <p><i class="fas fa-circle-check text-success me-1" style="font-size:.7rem"></i>Artista Verificado</p>
                </div></div>
                <div class="col"><div class="card artist">
                    <img src="https://picsum.photos/seed/art2/200" alt="Artista">
                    <h4>Tame Impala</h4>
                    <p><i class="fas fa-circle-check text-success me-1" style="font-size:.7rem"></i>Artista Verificado</p>
                </div></div>
                <div class="col"><div class="card artist">
                    <img src="https://picsum.photos/seed/art3/200" alt="Artista">
                    <h4>Dua Lipa</h4>
                    <p><i class="fas fa-circle-check text-success me-1" style="font-size:.7rem"></i>Artista Verificado</p>
                </div></div>
            </div>
        </section>



    </main>

    <!-- Modal de Comentário -->
    <div id="modal-comentario" style="display:none; position:fixed; inset:0; z-index:9999;
        background:rgba(0,0,0,0.55); backdrop-filter:blur(8px); justify-content:center; align-items:center;">
        <div style="background:rgba(30,30,40,0.92); border:1px solid rgba(255,255,255,0.15);
            border-radius:16px; padding:32px; width:100%; max-width:480px; margin:0 16px;">
            <h5 style="color:#fff; margin-bottom:6px;"><i class="fas fa-comment me-2"></i>Comentar</h5>
            <p style="color:rgba(255,255,255,0.5); font-size:0.85rem; margin-bottom:16px;">Midnight City — M83</p>
            <textarea id="texto-comentario" rows="5" placeholder="Escreva seu comentário..."
                style="width:100%; background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.15);
                border-radius:10px; color:#fff; padding:12px; resize:none; outline:none; font-size:0.9rem;"></textarea>
            <div style="display:flex; gap:10px; margin-top:14px;">
                <button onclick="enviarComentario()" style="flex:1; background:#e53935; border:none;
                    color:#fff; padding:10px; border-radius:20px; cursor:pointer; font-size:0.9rem; transition:0.3s;">
                    <i class="fas fa-paper-plane me-2"></i>Enviar
                </button>
                <button onclick="fecharComentario()" style="flex:1; background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.15);
                    color:rgba(255,255,255,0.7); padding:10px; border-radius:20px; cursor:pointer; font-size:0.9rem; transition:0.3s;">
                    Cancelar
                </button>
            </div>
        </div>
    </div>

   <!-- Rodapé do Player -->
<footer class="player" style="padding:0; overflow:hidden; height:50px; position: fixed; bottom: 0; width: 100%;">
    <div class="now-playing" style="width:100%; height:100%; background: #000;">
        <img id="slideshow-img" 
             src="https://statig.com.br"
             style="width:100%; height:100%; object-fit:cover; display:block; transition: opacity 1s ease;">
    </div>
</footer>

<script>
    const slides = [
        'https://img.freepik.com/vetores-premium/um-cartaz-de-publicidade-musical-com-um-disco-de-vinil-e-notas-musicais-em-uma-ilustracao-vetorial-isolada_606304-808.jpg',
        'https://png.pngtree.com/thumb_back/fh260/background/20221224/pngtree-blue-musical-notes-background-image_1530362.jpg',
        'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT_5qs5i8zvZ2TQptBz3UOxKhFS-Te9heBoDA&s',
        'https://thumbs.dreamstime.com/b/grande-nota-musical-%C3%A9-uma-trepada-sobre-fundo-abstrato-para-publicidade-em-lojas-e-est%C3%BAdios-de-m%C3%BAsica-gerada-por-ai-334831956.jpg'
    ];

    let current = 0;
    const img = document.getElementById('slideshow-img');

    // Função para trocar a imagem
    function mudarSlide() {
        // 1. Começa o fade out (fica transparente)
        img.style.opacity = '0';

        // 2. Espera o fade out terminar (1 segundo) para trocar a fonte
        setTimeout(() => {
            current = (current + 1) % slides.length;
            img.src = slides[current];
            
            // 3. Faz o fade in (volta a aparecer)
            img.style.opacity = '1';
        }, 1000); 
    }

    // Executa a função a cada 30 segundos (300000 milissegundos)
    setInterval(mudarSlide, 30000);

    // Curtir no banner destaque
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

    // Modal de comentário
    function abrirComentario() {
        const modal = document.getElementById('modal-comentario');
        modal.style.display = 'flex';
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
</script>

</body>
</html>
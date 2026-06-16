<?php
$isPremium = isset($_SESSION['plano_id']) && $_SESSION['plano_id'] == 2;
?>
<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar-pag">
    <div class="sidebar-pag-logo">🎶 SoundScore</div>
    <nav>
        <ul>
            <li class="<?php echo ($currentPage == 'paginicial.php') ? 'active' : ''; ?>">
                <a href="paginicial.php"><i class="fas fa-home"></i> Início</a>
            </li>
            <li class="<?php echo ($currentPage == 'biblioteca.php') ? 'active' : ''; ?>">
                <a href="biblioteca.php"><i class="fas fa-book"></i> Sua Biblioteca</a>
            </li>
            <li class="<?php echo ($currentPage == 'perfil.php') ? 'active' : ''; ?>">
                <a href="perfil.php"><i class="fas fa-user"></i> Perfil</a>
            </li>
            <li class="<?php echo ($currentPage == 'buscar.php') ? 'active' : ''; ?>">
                <a href="buscar.php"><i class="fas fa-search"></i> Buscar</a>
            </li>
        </ul>
    </nav>

    <?php if (!$isPremium): ?>
        <div class="sidebar-pag-premium">
            <i class="fas fa-star" style="color:#ffc107; font-size:1.2rem;"></i>
            <p>Ouça sem limites com o <strong>Premium</strong></p>
            <a href="premium.php" class="btn-premium-sidebar">Experimente grátis</a>
        </div>
    <?php endif; ?>

    <button class="sidebar-pag-logout" onclick="logout()">
        <i class="fas fa-sign-out-alt"></i> Sair da conta
    </button>
</aside>
<script>
    (function() {
        const page = window.location.pathname.split('/').pop();
        document.querySelectorAll('.sidebar-pag nav li a').forEach(a => {
            if (a.getAttribute('href') === page) {
                a.parentElement.classList.add('active');
            }
        });
    })();
</script>
<script src="assets/js/app.js"></script>

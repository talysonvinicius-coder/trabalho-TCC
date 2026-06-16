<?php
session_start();

if (empty($_SESSION['logado'])) {
    header("Location: login.html");
    exit;
}

$isPremium = isset($_SESSION['plano_id']) && $_SESSION['plano_id'] == 2;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SoundScore - Player</title>
    <link rel="stylesheet" href="assets/css/global.css">
</head>
<body>

    <div class="player-container">
        <?php if ($isPremium): ?>
            <audio controls autoplay>
                <source src="assets/audio/arquivo_da_musica.mp3" type="audio/mpeg">
            </audio>
        <?php else: ?>
            <div class="upgrade-banner">
                <i class="fas fa-lock"></i>
                <p>Ouvir músicas é uma funcionalidade exclusiva Premium.</p>
                <a href="premium.php" class="btn-upgrade">Fazer Upgrade Agora</a>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
<?php
$id_genero = $_GET['id'] ?? null;

if (!$id_genero) {
    header("Location: buscar.php");
    exit;
}

$pdo = new PDO('mysql:host=localhost;dbname=bdmusica;charset=utf8mb4', 'root', '');

$stmt_genero = $pdo->prepare("SELECT nome, descricao FROM genero WHERE id = :id");
$stmt_genero->execute(['id' => $id_genero]);
$genero_atual = $stmt_genero->fetch(PDO::FETCH_ASSOC);

if (!$genero_atual) {
    header("Location: buscar.php");
    exit;
}

$stmt_musicas = $pdo->prepare("SELECT titulo, artista, album FROM musicas WHERE genero_id = :id");
$stmt_musicas->execute(['id' => $id_genero]);
$musicas = $stmt_musicas->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SoundScore - <?php echo htmlspecialchars($genero_atual['nome']); ?></title>
    <link rel="stylesheet" href="assets/css/global.css">
</head>
<body>
    <main class="content">
        <div class="container">
            <h2>Explore as melhores faixas de <?php echo htmlspecialchars($genero_atual['nome']); ?></h2>
            <p><?php echo htmlspecialchars($genero_atual['descricao']); ?></p>

            <div class="musicas-grid">
                <?php if (count($musicas) > 0): ?>
                    <?php foreach ($musicas as $musica): ?>
                        <div class="musica-card">
                            <div class="musica-info">
                                <h3><?php echo htmlspecialchars($musica['artista']); ?></h3>
                                <p><?php echo htmlspecialchars($musica['titulo']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhuma música encontrada para este gênero.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>
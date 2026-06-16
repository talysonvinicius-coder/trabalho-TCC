<?php
$pdo = new PDO('mysql:host=localhost;dbname=bdmusica;charset=utf8mb4', 'root', '');
$rows = $pdo->query("SELECT id, titulo, spotify_link FROM musicas ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
    echo $r['id'] . ' | ' . $r['titulo'] . ' | ' . ($r['spotify_link'] ?? 'NULL') . "\n";
}

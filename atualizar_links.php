<?php
$pdo = new PDO('mysql:host=localhost;dbname=bdmusica;charset=utf8mb4', 'root', '');

$links = [
    1  => 'https://www.youtube.com/watch?v=O0bMbVDXIA4', // The Emptiness Machine - Linkin Park
    2  => 'https://www.youtube.com/watch?v=Sl7XCxTgfMU', // The Kids Aren't Alright - The Offspring
    4  => 'https://www.youtube.com/watch?v=XmSdTa9kaiQ', // New Year's Day - U2
    5  => 'https://www.youtube.com/watch?v=6Ejga4kJUts', // Zombie - The Cranberries
    6  => 'https://www.youtube.com/watch?v=mvxSPasOqkU', // Por Onde Andei - Pitty e Nando Reis
    10 => 'https://www.youtube.com/watch?v=O2qS7ValDUs', // All Star Azul - Nando Reis
];

$stmt = $pdo->prepare("UPDATE musicas SET spotify_link = :link WHERE id = :id");
foreach ($links as $id => $link) {
    $stmt->execute(['link' => $link, 'id' => $id]);
    echo "Atualizado ID $id\n";
}
echo "Concluído!";

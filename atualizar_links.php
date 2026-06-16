<?php
$pdo = new PDO('mysql:host=localhost;dbname=bdmusica;charset=utf8mb4', 'root', '');

$links = [
    1  => 'https://youtu.be/SRXH9AbT280?si=UcU1Kl0RTiaXdpax', // The Emptiness Machine - Linkin Park
    2  => 'https://youtu.be/7iNbnineUCI?si=37E8RB5q_ILZCF46', // The Kids Aren't Alright - The Offspring
    4  => 'https://youtu.be/jeYCyCaK_5k?si=qh8BOkt4GVBANBP0', // New Year's Day - U2
    5  => 'https://youtu.be/6Ejga4kJUts?si=Qy26PTajpOClUqYo', // Zombie - The Cranberries
    6  => 'https://youtu.be/K_MG6cbGhkA?si=5bbctt-ezgi3Ujie', // Por Onde Andei - Pitty e Nando Reis
    10 => 'https://music.youtube.com/watch?v=pkNLaPVsw58&si=2xo8fz4SS2xKbsmp', // All Star Azul - Nando Reis
];

$stmt = $pdo->prepare("UPDATE musicas SET spotify_link = :link WHERE id = :id");
foreach ($links as $id => $link) {
    $stmt->execute(['link' => $link, 'id' => $id]);
    echo "Atualizado ID $id\n";
}
echo "Concluído!";

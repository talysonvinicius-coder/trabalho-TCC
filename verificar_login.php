<?php
$pdo = new PDO('mysql:host=localhost;dbname=bdmusica;charset=utf8mb4', 'root', '');

// Verificar logins
$stmt = $pdo->prepare("SELECT usuario_id, email FROM login WHERE usuario_id IN (17, 18, 19) ORDER BY usuario_id DESC");
$stmt->execute();
$logins = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Logins existentes:<br>";
echo "<pre>";
print_r($logins);
echo "</pre>";

// Tentar login com João Silva
$stmt = $pdo->prepare("SELECT u.id, u.nome, l.email FROM usuario u LEFT JOIN login l ON u.id = l.usuario_id WHERE u.nome = 'João Silva' LIMIT 1");
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
echo "João Silva:<br>";
echo "<pre>";
print_r($user);
echo "</pre>";

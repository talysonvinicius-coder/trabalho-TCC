<?php
$pdo = new PDO('mysql:host=localhost;dbname=bdmusica;charset=utf8mb4', 'root', '');

// Verificar primeiras músicas que existem
$stmt = $pdo->prepare("SELECT id FROM musicas LIMIT 4");
$stmt->execute();
$musicas = $stmt->fetchAll(PDO::FETCH_COLUMN);

if (count($musicas) < 2) {
    echo "Não há músicas suficientes no banco de dados!";
    exit;
}

// Limpar avaliações antigas do admin
$pdo->prepare("DELETE FROM avaliacoes WHERE usuario_id = 3")->execute();

// Limpar listas antigas do admin
$pdo->prepare("DELETE FROM listas WHERE usuario_id = 3")->execute();

// Inserir uma avaliação com comentário para o admin (usuário 3)
$stmt = $pdo->prepare("
    INSERT INTO avaliacoes (usuario_id, musica_id, nota, comentario, data_avaliacao)
    VALUES (3, ?, 5, 'Essa música é simplesmente fantástica! Adorei a melodia e a letra. Definitivamente uma das melhores do artista.', NOW())
");
$stmt->execute([$musicas[0]]);
echo "Avaliação 1 criada<br>";

$stmt = $pdo->prepare("
    INSERT INTO avaliacoes (usuario_id, musica_id, nota, comentario, data_avaliacao)
    VALUES (3, ?, 4, 'Muito bom! A produção está impecável e o artista entrega uma performance excelente.', NOW())
");
$stmt->execute([$musicas[1]]);
echo "Avaliação 2 criada<br>";

// Criar uma lista para o admin
$stmt = $pdo->prepare("
    INSERT INTO listas (usuario_id, nome, descricao, data_criacao)
    VALUES (3, 'Minhas Favoritas', 'Coleção das minhas músicas favoritas de todos os tempos', NOW())
");
$stmt->execute();
$lista_id = $pdo->lastInsertId();
echo "Lista criada com ID: $lista_id<br>";

// Adicionar músicas à lista
$stmt = $pdo->prepare("INSERT INTO lista_musicas (lista_id, musica_id) VALUES (?, ?)");
$stmt->execute([$lista_id, $musicas[0]]);
$stmt->execute([$lista_id, $musicas[1]]);
echo "Músicas adicionadas à lista<br>";

// Criar uma segunda lista
$stmt = $pdo->prepare("
    INSERT INTO listas (usuario_id, nome, descricao, data_criacao)
    VALUES (3, 'Para Trabalhar', 'Música de fundo para focar no trabalho', NOW())
");
$stmt->execute();
$lista_id2 = $pdo->lastInsertId();

if (count($musicas) > 2) {
    $stmt = $pdo->prepare("INSERT INTO lista_musicas (lista_id, musica_id) VALUES (?, ?)");
    if (isset($musicas[2])) $stmt->execute([$lista_id2, $musicas[2]]);
    if (isset($musicas[3])) $stmt->execute([$lista_id2, $musicas[3]]);
}
echo "Segunda lista criada com ID: $lista_id2<br>";

echo "<br>Dados de teste criados com sucesso!";

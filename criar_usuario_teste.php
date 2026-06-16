<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=bdmusica;charset=utf8mb4', 'root', '');
    
    // Inserir segundo usuário de teste
    $stmt = $pdo->prepare("
        INSERT INTO usuario (nome, bio, foto, data_criacao, perfil_id, plano_id, status) 
        VALUES (?, ?, ?, NOW(), 1, 1, 1)
    ");
    $stmt->execute(['Maria Santos', 'Fã de rock e indie', '']);
    
    // Pegar o ID do usuário inserido
    $usuario_id = $pdo->lastInsertId();
    echo "Usuário criado com ID: " . $usuario_id . "<br>";
    
    // Inserir login para esse usuário
    $stmt = $pdo->prepare("
        INSERT INTO login (usuario_id, email, senha)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$usuario_id, 'maria@example.com', password_hash('123456', PASSWORD_BCRYPT)]);
    
    echo "Login criado com sucesso!<br>";
    echo "Email: maria@example.com<br>";
    echo "Senha: 123456<br>";
    
    // Listar todos os usuários
    echo "<br><br>Usuários no banco:<br>";
    $stmt = $pdo->prepare("SELECT id, nome, status FROM usuario ORDER BY id DESC LIMIT 10");
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($usuarios);
    echo "</pre>";
    
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

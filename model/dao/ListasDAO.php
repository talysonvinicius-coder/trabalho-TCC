<?php
require_once 'Conexao.php';

class ListasDAO {
    public function criarLista($usuarioId, $nome, $descricao) {
        $pdo = Conexao::getConexao();
        $stmt = $pdo->prepare("INSERT INTO listas (usuario_id, nome, descricao) VALUES (?, ?, ?)");
        return $stmt->execute([$usuarioId, $nome, $descricao]);
    }

    public function buscarLista($id, $usuarioId) {
        $pdo = Conexao::getConexao();
        $stmt = $pdo->prepare("SELECT * FROM listas WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $usuarioId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listarPorUsuario($usuarioId) {
        $pdo = Conexao::getConexao();
        $stmt = $pdo->prepare("
            SELECT l.*, 
                   (SELECT COUNT(*) FROM lista_musicas lm WHERE lm.lista_id = l.id) as total_musicas
            FROM listas l 
            WHERE l.usuario_id = ? 
            ORDER BY l.data_criacao DESC
        ");
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizarLista($id, $nome, $descricao, $usuarioId) {
        $pdo = Conexao::getConexao();
        $stmt = $pdo->prepare("UPDATE listas SET nome = ?, descricao = ? WHERE id = ? AND usuario_id = ?");
        return $stmt->execute([$nome, $descricao, $id, $usuarioId]);
    }

    public function deletarLista($id, $usuarioId) {
        $pdo = Conexao::getConexao();
        $stmt = $pdo->prepare("DELETE FROM listas WHERE id = ? AND usuario_id = ?");
        return $stmt->execute([$id, $usuarioId]);
    }

    public function adicionarMusica($listaId, $musicaId) {
        $pdo = Conexao::getConexao();
        $stmt = $pdo->prepare("INSERT IGNORE INTO lista_musicas (lista_id, musica_id) VALUES (?, ?)");
        return $stmt->execute([$listaId, $musicaId]);
    }
    public function listarMusicasDaLista($listaId) {
        $pdo = Conexao::getConexao();
        $stmt = $pdo->prepare("
            SELECT m.*, m.artista as artista_nome 
            FROM musicas m 
            JOIN lista_musicas lm ON m.id = lm.musica_id 
            WHERE lm.lista_id = ?
        ");
        $stmt->execute([$listaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public function removerMusica($listaId, $musicaId) {
    $pdo = Conexao::getConexao();
    $stmt = $pdo->prepare("DELETE FROM lista_musicas WHERE lista_id = ? AND musica_id = ?");
    return $stmt->execute([$listaId, $musicaId]);
}
}
?>
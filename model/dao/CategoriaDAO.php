<?php
require_once 'Conexao.php';
require_once __DIR__ . '/../dto/CategoriaDTO.php';

class CategoriaDAO {
    public function cadastrarCategoria(CategoriaDTO $categoriaDTO) {
        try {
            $pdo = Conexao::getConexao();
            $sql = "INSERT INTO categorias (nome, descricao, status) VALUES (?, ?, ?);";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(1, $categoriaDTO->getNome());
            $stmt->bindValue(2, $categoriaDTO->getDescricao());
            $stmt->bindValue(3, $categoriaDTO->getStatus() ?? 1);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function listarCategorias() {
        try {
            $pdo = Conexao::getConexao();
            $sql = "SELECT id, nome, descricao, status, data_criacao FROM categorias ORDER BY nome";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function buscarPorId($id) {
        try {
            $pdo = Conexao::getConexao();
            $sql = "SELECT id, nome, descricao, status, data_criacao FROM categorias WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }
}
?>
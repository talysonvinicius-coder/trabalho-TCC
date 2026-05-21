<?php
require_once 'Conexao.php';
require_once __DIR__ . '/../dto/UsuarioDTO.php';

class UsuarioDAO {

    public function cadastrarUsuario(UsuarioDTO $usuarioDTO) {
        try {
            $pdo = Conexao::getConexao();
            $pdo->beginTransaction();

            $sqlUsuario = "INSERT INTO usuario (nome, perfil_id, plano_id, status) VALUES (?, ?, ?, 1)";
            $stmtUsuario = $pdo->prepare($sqlUsuario);
            $stmtUsuario->bindValue(1, $usuarioDTO->getNome());
            $stmtUsuario->bindValue(2, $usuarioDTO->getPerfilId() ?? 1);
            $stmtUsuario->bindValue(3, $usuarioDTO->getPlanoId() ?? 1);
            $stmtUsuario->execute();

            $usuarioId = $pdo->lastInsertId();

            $sqlLogin = "INSERT INTO login (email, senha, usuario_id, ativo) VALUES (?, ?, ?, 1)";
            $stmtLogin = $pdo->prepare($sqlLogin);
            $stmtLogin->bindValue(1, $usuarioDTO->getEmail());
            $stmtLogin->bindValue(2, md5($usuarioDTO->getSenha()));
            $stmtLogin->bindValue(3, $usuarioId);
            $stmtLogin->execute();

            $pdo->commit();
            return true;
        } catch (PDOException $e) {
            $pdo->rollBack();
            return false;
        }
    }

    public function listarUsuario() {
        try {
            $pdo = Conexao::getConexao();
            $sql = "SELECT u.id, u.nome, l.email, u.status
                    FROM usuario u
                    LEFT JOIN login l ON u.id = l.usuario_id";
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
            $sql = "SELECT u.id, u.nome, l.email, u.status
                    FROM usuario u
                    LEFT JOIN login l ON u.id = l.usuario_id
                    WHERE u.id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function editarUsuario($id, UsuarioDTO $usuarioDTO) {
        try {
            $pdo = Conexao::getConexao();
            $pdo->beginTransaction();

            $sqlUsuario = "UPDATE usuario SET nome = ? WHERE id = ?";
            $stmtUsuario = $pdo->prepare($sqlUsuario);
            $stmtUsuario->bindValue(1, $usuarioDTO->getNome());
            $stmtUsuario->bindValue(2, $id);
            $stmtUsuario->execute();

            $sqlLogin = "UPDATE login SET email = ? WHERE usuario_id = ?";
            $stmtLogin = $pdo->prepare($sqlLogin);
            $stmtLogin->bindValue(1, $usuarioDTO->getEmail());
            $stmtLogin->bindValue(2, $id);
            $stmtLogin->execute();

            if ($usuarioDTO->getSenha()) {
                $sqlSenha = "UPDATE login SET senha = ? WHERE usuario_id = ?";
                $stmtSenha = $pdo->prepare($sqlSenha);
                $stmtSenha->bindValue(1, md5($usuarioDTO->getSenha()));
                $stmtSenha->bindValue(2, $id);
                $stmtSenha->execute();
            }

            $pdo->commit();
            return true;
        } catch (PDOException $e) {
            $pdo->rollBack();
            return false;
        }
    }

    public function alterarStatus($id, $novoStatus) {
        try {
            $pdo = Conexao::getConexao();
            $sql = "UPDATE usuario SET status = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(1, $novoStatus);
            $stmt->bindValue(2, $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function excluirUsuario($id) {
        try {
            $pdo = Conexao::getConexao();
            $sql = "DELETE FROM usuario WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(1, $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function buscarParaLogin($email) {
        try {
            $pdo = Conexao::getConexao();
            $sql = "SELECT u.id, u.nome, u.status, l.senha, l.ativo, u.perfil_id
                    FROM usuario u
                    JOIN login l ON u.id = l.usuario_id
                    WHERE l.email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(1, $email);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $result['perfil'] = $result['perfil_id'] == 2 ? 'admin' : 'cliente';
                unset($result['perfil_id']);
            }
            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>

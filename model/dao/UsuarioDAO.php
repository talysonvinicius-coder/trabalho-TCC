<?php
require_once 'Conexao.php';
require_once __DIR__ . '/../dto/UsuarioDTO.php';
class UsuarioDAO {

 // Método para cadastrar um novo usuário no banco de dados
 public function cadastrarUsuario(UsuarioDTO $usuarioDTO) {
 try {
 $pdo = Conexao::getConexao();
 
 // Inicia transação para garantir integridade dos dados
 $pdo->beginTransaction();
 
 // Insere na tabela usuario
 $sqlUsuario = "INSERT INTO usuario (nome, perfil_id, plano_id, status) VALUES (?, ?, ?, 1)";
 $stmtUsuario = $pdo->prepare($sqlUsuario);
 $stmtUsuario->bindValue(1, $usuarioDTO->getNome());
 $stmtUsuario->bindValue(2, $usuarioDTO->getPerfilId() ?? 1); // Padrão: perfil 'usuario'
 $stmtUsuario->bindValue(3, $usuarioDTO->getPlanoId() ?? 1); // Padrão: plano 'free'
 $stmtUsuario->execute();
 
 // Obtém o ID do usuário inserido
 $usuarioId = $pdo->lastInsertId();
 
 // Insere na tabela login
 $sqlLogin = "INSERT INTO login (email, senha, usuario_id, ativo) VALUES (?, ?, ?, 1)";
 $stmtLogin = $pdo->prepare($sqlLogin);
 $stmtLogin->bindValue(1, $usuarioDTO->getEmail());
 $stmtLogin->bindValue(2, md5($usuarioDTO->getSenha()));
 $stmtLogin->bindValue(3, $usuarioId);
 $stmtLogin->execute();
 
 // Confirma a transação
 $pdo->commit();
 return true;
 } catch (PDOException $e) {
 // Desfaz a transação em caso de erro
 $pdo->rollBack();
 echo "Erro ao cadastrar: " . $e->getMessage();
 return false;
 }
 }

 // Método para listar todos os usuários (ativos e inativos)
 public function listarUsuario() {
 try {
 $pdo = Conexao::getConexao();
 // Busca usuários e seus emails da tabela login
 $sql = "SELECT u.id, u.nome, l.email, u.status 
         FROM usuario u
         LEFT JOIN login l ON u.id = l.usuario_id";
 $stmt = $pdo->prepare($sql);
 $stmt->execute();
 return $stmt->fetchAll(PDO::FETCH_ASSOC);
 } catch (PDOException $e) {
 echo "Erro ao listar: " . $e->getMessage();
 return [];
 }
 }
 // Método único para Ativar (1) ou Desativar (0) - Exclusão Lógica
 public function alterarStatus($id, $novoStatus) {
 try {
 $pdo = Conexao::getConexao();
 $sql = "UPDATE usuario SET status = ? WHERE id = ?";
 $stmt = $pdo->prepare($sql);
 $stmt->bindValue(1, $novoStatus);
 $stmt->bindValue(2, $id);
 return $stmt->execute();
 } catch (PDOException $e) {
 echo "Erro ao alterar status: " . $e->getMessage();
 return false;
 }
 }
 // Método para excluir um usuário permanentemente (Exclusão Física)
 public function excluirUsuario($id) {
 try {
 $pdo = Conexao::getConexao();
 $sql = "DELETE FROM usuario WHERE id = ?";
 $stmt = $pdo->prepare($sql);
 $stmt->bindValue(1, $id);
 return $stmt->execute();
 } catch (PDOException $e) {
 echo "Erro ao excluir: " . $e->getMessage();
 return false;
 }
 }

 // Método para buscar usuário para login
 public function buscarParaLogin($email) {
 try {
 $pdo = Conexao::getConexao();
 $sql = "SELECT u.nome, l.senha, l.ativo, u.perfil_id FROM usuario u JOIN login l ON u.id = l.usuario_id WHERE l.email = ?";
 $stmt = $pdo->prepare($sql);
 $stmt->bindValue(1, $email);
 $stmt->execute();
 $result = $stmt->fetch(PDO::FETCH_ASSOC);
 if ($result) {
 $result['perfil'] = $result['perfil_id'] == 1 ? 'cliente' : 'admin';
 unset($result['perfil_id']);
 }
 return $result;
 } catch (PDOException $e) {
 echo "Erro ao buscar: " . $e->getMessage();
 return false;
 }
 }
} // Fim da classe UsuarioDAO
?>
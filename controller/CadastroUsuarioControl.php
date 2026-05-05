<?php
//Controller responsável por processar o cadastro de usuário
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../Model/dto/UsuarioDTO.php';
require_once '../Model/dao/UsuarioDAO.php';
// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

 // Recebe os dados do formulário
 $nome = $_POST['nome'];
 $email = $_POST['email'];
 $senha = $_POST['senha'];

 // Instancia o DTO e preenche com os dados
 $usuarioDTO = new UsuarioDTO();
 $usuarioDTO->setNome($nome);
 $usuarioDTO->setEmail($email);
 $usuarioDTO->setSenha($senha);

 // Instancia o DAO e chama o método de cadastro
 $usuarioDAO = new UsuarioDAO();
 $resultado = $usuarioDAO->cadastrarUsuario($usuarioDTO);

echo $resultado;

 // Exibe alerta JavaScript com o resultado
 if ($resultado) {
 echo "<script>
 alert('Usuário cadastrado com sucesso!');
 window.location.href = '../index.php';
 </script>";
 } else {
 echo "$resultado <script>
 alert('Erro ao cadastrar usuário!');
 window.location.href = '../cadastro.php';
 </script>";
 }
}
?>
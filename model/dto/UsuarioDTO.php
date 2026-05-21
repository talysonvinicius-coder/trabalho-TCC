<?php
// Classe UsuarioDTO - Data Transfer Object
//Responsável por transportar os dados do usuário entre as camadas
class UsuarioDTO {

 // Atributos privados
 private $id;
 private $nome;
 private $email;
 private $senha;
 private $perfil_id;
 private $plano_id;

 // Getters
 public function getId() {
 return $this->id;
 }

 public function getNome() {
 return $this->nome;
 }

 public function getEmail() {
 return $this->email;
 }

 public function getSenha() {
 return $this->senha;
 }

 public function getPerfilId() {
 return $this->perfil_id;
 }

 public function getPlanoId() {
 return $this->plano_id;
 }

 // Setters
 public function setId($id) {
 $this->id = $id;
 }

 public function setNome($nome) {
 $this->nome = $nome;
 }

 public function setEmail($email) {
 $this->email = $email;
 }

 public function setSenha($senha) {
 $this->senha = $senha;
 }

 public function setPerfilId($perfil_id) {
 $this->perfil_id = $perfil_id;
 }

 public function setPlanoId($plano_id) {
 $this->plano_id = $plano_id;
 }
}
?>
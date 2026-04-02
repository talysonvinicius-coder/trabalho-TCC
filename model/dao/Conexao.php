<?php
class Conexao {
    public static function getConexao() {
        $host = "localhost";
        $dbname = "bdmusica"; // <--- ALTERADO DE 'usuario' PARA 'bdmusica'
        $usuario = "root"; 
        $senha = ""; 

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $senha);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            echo "Erro na conexão: " . $e->getMessage();
            return null;
        }
    }
}
?>
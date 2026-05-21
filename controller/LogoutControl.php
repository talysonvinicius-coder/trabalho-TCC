<?php
session_start();

// Destrói a sessão e redireciona para o login
session_destroy();
header('Location: ../index.php');
exit();
?>

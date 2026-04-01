<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/cadastrarmusic.css">
    <title>cadastro</title>
</head>
<body>
     <fieldset>
 <legend>Administração</legend>
 <form action="controller/TestarConexaoControl.php"
method="post">
 <button type="submit">Testar Conexão com o Banco</button>
 </form>
 </fieldset>

    <div class="container">
        <div class="title">
            <h1>Vem avaliar com a gente!!</h1>
        </div>

        <div class="register-form">
            <form action="./controller/CadastroUsuarioControl.php" method="post" autocomplete="on">    
        <a>
    <h2>Dados pessoais</h2>
   <p> <label for="inome">Nome:</br></label><input type="text" name="Nome Completo" id="inome" placeholder="Nome Completo" minlength="5" 
    maxlength="15" required></p> 
   <p> <label for="iemail">Email:</label></br><input type="text" name="seuemail" id="iemail" placeholder="seuemail@exemplo.com" minlength="5" 
    maxlength="50" required></p> 
   <p> <label for="itel">telefone(formato(xx)xxxx-xxxx):</label></br><input type="text" name="(12)2526-3577" id="itel" placeholder="(12)2526-3577" minlength="5" 
    maxlength="15" required></p> 
    <p> <label for="inome">Gênero musical favorito:</br></label><input type="text" name="Gênero" id="igenero" placeholder="ex:HipHop" minlength="5" 
    maxlength="15" required></p> 

   <p> <label for="isenha">Crie uma senha:</label></br><input type="password" name="Digite sua senha" id="isenha" placeholder="Digite sua senha" minlength="5" 
    maxlength="15" required></p> 

 <a href="concluido.php"><input type="button" value="Enviar"></input>
    
    
   </fieldset>
    </form>
        </div>
    </div>   
    
</body>
</html>
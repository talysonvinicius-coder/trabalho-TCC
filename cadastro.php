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
    <form action="../trabalho-html/Controller/CadastroUsuarioControl.php" method="post" autocomplete="on">    
        <h2>Dados pessoais</h2>
        
        <p> 
            <label for="inome">Nome:<br></label>
            <input type="text" name="nome" id="inome" placeholder="Nome Completo" minlength="5" maxlength="50" required>
        </p> 
        
        <p> 
            <label for="iemail">Email:</label><br>
            <input type="email" name="email" id="iemail" placeholder="seuemail@exemplo.com" required>
        </p> 

        <p> 
            <label for="itel">Telefone:</label><br>
            <input type="text" name="telefone" id="itel" placeholder="(12)2526-3577">
        </p> 

        <p> 
            <label for="igenero">Gênero musical favorito:<br></label>
            <input type="text" name="genero" id="igenero" placeholder="ex: HipHop">
        </p> 

        <p> 
            <label for="isenha">Crie uma senha:</label><br>
            <input type="password" name="senha" id="isenha" placeholder="Digite sua senha" minlength="5" required>
        </p> 

        <button type="submit">Cadastrar</button>
    </form>
</div>
    
   </fieldset>
    </form>
        
    </div>   
    
</body>
</html>
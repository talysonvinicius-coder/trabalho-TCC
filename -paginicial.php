<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <link rel="stylesheet" href="./assets/css/estilo.css">

    <style>
          body {
            font-family: Arial, Helvetica, sans-serif;
            width: 100vw;
            height: 100vh;
            margin: 0;
            padding: 0;
        }
        table {
            width: 700px;
            border-collapse: collapse ;
            /* Obrigatório para ter o título da tabela fixo */
            position: relative;
        }
        td, th {
            border: 1px solid black;
            padding: 20px;
            background-color: #294C60;
        }
        thead, tfoot {
            background-color: gray;
            color: rgb(0, 0, 0);
        }

        thead > tr > th {
            /* No título da linha do cabeçalho vou grudar "sticky" */
            position: sticky; /* grudar */
            top: 0; /* na posição 0 */
            background-color: gray;

        }
        td.num {
            text-align: right;
        
        }
        caption {
            /* Tamanho da fonte */
            font-size: 1.5em;
            /* Para colocar em negrito */
            font-weight: bold;
            /* Para deixar mais espaçado */
            padding: 15px;
            /* Colocar um fundo no meu caption */
            background-color: lightgray;

        }
        
       input {
  font-size: large;
  color: #fff;
  padding: 15px 15px;
  background-color: #001B2E;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

    </style>
    
</head>
<body>
    <?php 
        include("./partials/navbar.php");
    ?>
    <h1>Escolha o que avaliar hoje!!</h1>
 <table>
    <tbody>
        <tr>
        <label><td>Hip-Hop</td> 
            <td><a href="hiphop.php"><button id="visualizarmusicas">Visualizar Músicas</button></td>
        <td>Jazz</td>
         <td><a href="Jazz.php"><button id="visualizarmusicas">Visualizar Músicas</button></td>
        </tr>
        <tr>
        <td>POP</td>
         <td><a href="POP.php"><button id="visualizarmusicas">Visualizar Músicas</button></td>
        <td>Música Eletrônica</td>
         <td><a href="musicaeletro.php"><button id="visualizarmusicas">Visualizar Músicas</button></td>
        </tr>
        <tr>
            <td>Rock</td>
             <td><a href="rock.php"><button id="visualizarmusicas">Visualizar Músicas</button></td>
            <td>MPB</td>
             <td><a href="mpb.php"><button id="visualizarmusicas">Visualizar Músicas</button></td>
        </tr>
         
    
        <link rel="stylesheet" href="paginicial.php">
    </tbody>
 </table>

 <a href="./index.php"><input type="button" value="Voltar para Login" ></a> 
</body>
</html>
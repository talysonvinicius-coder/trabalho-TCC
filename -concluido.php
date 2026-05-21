<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
          body {
            background-color: #FFEFD3;
            font-family: Arial, Helvetica, sans-serif;
        }
        table {
            width: 700px;
            border-collapse: collapse ;
            /* Obrigatório para ter o título da tabela fixo */
            position: relative;
        }
        td, th {
            border: 1px solid #294C60;
            padding: 20px;
        }
        thead, tfoot {
            background-color: #001B2E;
            color: rgb(0, 0, 0);
        }

        thead > tr > th {
            /* No título da linha do cabeçalho vou grudar "sticky" */
            position: sticky; /* grudar */
            top: 0; /* na posição 0 */
            background-color: #001B2E;
            color: #fff;

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
        
        tbody > tr:nth-child(2n) {
            background-color: #294C60;

        }

    </style>
</head>
<body>
    <h1>Agora você faz parte do nosso grupo
        de avaliadores!!</h1>
       
<table>
    
    <table>
        <caption> Nossos Avaliadores</caption>
        
        <thead>
            <tr>
                <th scope="col">Nome</th>
                <th scope="col">Quantidade de avaliações</th>
                <th scope="col">Gênero musical preferido </th>
            </tr>
        </thead>
        <tbody>
           <tr>
                <td>Lucas</td>
                <td >25</td>
                <td>Rock</td>
            </tr>
            <tr>
                <td>Sophia</td>
                <td >18</td>
                <td>Jazz</td>
            </tr>
            <tr>
                <td>Raphael Costa</td>
                <td >32</td>
                <td>Música Eletrônica</td>
            </tr>
            <tr>
                <td>Mariana Oliveira</td>
                <td >22</td>
                <td>MPB</td>
            </tr>
            <tr>
                <td>Pedro Souza</td>
                <td >15</td>
                <td>POP</td>
            </tr>
            </tbody>

          
            <link rel="stylesheet"  href="concluido.php">

</table>
<p><a href="musica.php">Voltar para página inicial</a></p>

        

        
</body>
</html>
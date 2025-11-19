<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        body {
            box-sizing: border-box;
  margin: 0;
  padding: 0;
  width: 100vw;
  height: 80vh;
  background-image: url('../img/Copilot_20251116_001125.png');
  background-size: cover;
  background-position: center;
  color: #fff;
  font-size: x-large;
}

.container {
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.register-form{
  margin: 0 auto;
  background-color: #C8A03B;
  padding: 25px;
  border-radius: 15px;
}

    </style>
</head>
<body>
        <?php 
        include("./partials/navbar.php");
    ?>
    <div class="container">
        <div class="register-form">
            <h1>Avaliação registrada com sucesso!</h1>
        </div>
    </div>
</body>
</html>
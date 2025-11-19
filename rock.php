<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./assets/css/rock.css">
</head>

<body>
    <?php 
        include("./partials/navbar.php");
    ?>
    <div class="container">
        <div class="register-form">
            <fieldset>
                <div class="musica">
                  <h2>  Smells Like Teen Spirit-Nirvana</h2>
                    <div class="star-rating">
                        <input type="radio" id="rating-star-1" name="rating" value="1">
                        <label for="rating-star-1">★</label><br>
                        <input type="radio" id="rating-star-2" name="rating" value="2">
                        <label for="rating-star-2">★</label><br>
                        <input type="radio" id="rating-star-3" name="rating" value="3">
                        <label for="rating-star-3">★</label>
                        <input type="radio" id="rating-star-4" name="rating" value="4">
                        <label for="rating-star-4">★</label>
                        <input type="radio" id="rating-star-5" name="rating" value="5">
                        <label for="rating-star-5">★</label>
                    </div>
                </div>
                <div class="musica">
                  <h2>  Master Of Puppets-Metálica</h2>
                    <div class="star-rating">
                        <input type="radio" id="rating2-star-1" name="rating-2" value="1">
                        <label for="rating2-star-1">★</label><br>
                        <input type="radio" id="rating2-star-2" name="rating-2" value="2">
                        <label for="rating2-star-2">★</label><br>
                        <input type="radio" id="rating2-star-3" name="rating-2" value="3">
                        <label for="rating2-star-3">★</label>
                        <input type="radio" id="rating2-star-4" name="rating-2" value="4">
                        <label for="rating2-star-4">★</label>
                        <input type="radio" id="rating2-star-5" name="rating-2" value="5">
                        <label for="rating2-star-5">★</label>
                    </div>
                </div>
                <div class="musica">
                   <h2> Cherry Waves-Deftones</h2>
                    <div class="star-rating">
                        <input type="radio" id="rating3-star-1" name="rating-3" value="1">
                        <label for="rating3-star-1">★</label><br>
                        <input type="radio" id="rating3-star-2" name="rating-3" value="2">
                        <label for="rating3-star-2">★</label><br>
                        <input type="radio" id="rating3-star-3" name="rating-3" value="3">
                        <label for="rating3-star-3">★</label>
                        <input type="radio" id="rating3-star-4" name="rating-3" value="4">
                        <label for="rating3-star-4">★</label>
                        <input type="radio" id="rating3-star-5" name="rating-3" value="5">
                        <label for="rating3-star-5">★</label>
                    </div>
                </div>
                <div class="musica">
                   <h2>  Freak On a Leash-Korn</h2>
                    <div class="star-rating">
                        <input type="radio" id="rating4-star-1" name="rating-4" value="1">
                        <label for="rating4-star-1">★</label><br>
                        <input type="radio" id="rating4-star-2" name="rating-4" value="2">
                        <label for="rating4-star-2">★</label><br>
                        <input type="radio" id="rating4-star-3" name="rating-4" value="3">
                        <label for="rating4-star-3">★</label>
                        <input type="radio" id="rating4-star-4" name="rating-4" value="4">
                        <label for="rating4-star-4">★</label>
                        <input type="radio" id="rating4-star-5" name="rating-4" value="5">
                        <label for="rating4-star-5">★</label>
                    </div>
                </div>
            </fieldset>
            <a href="paginicial.php"> <input type="button" value="Voltar para página inicial"> 
            <a href="rock2.php"> <input type="button" value="Avançar"> 
        </div>
    </div>
</body>

</html>
<?php
require('../controller/loginController.php');
?>


<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/login.css" rel="stylesheet">
    <title>Bejelentkezés</title>
</head>
<body>
    
    <div class="container">
        <form action="" class="form" method="post">

            <h2>Bejelentkezés</h2>
                
                    <label for="email">E-mail</label>
                    <input type="email" name="email" class="box" id="email" required>
                
                
                    <label for="password">Jelszó</label>
                    <input type="password" name="password" class="box" id="password" required>
                
                
                    <input type="submit" class="btn" id="submit" name="submit" value="Bejelentkezés" required>

                    <a href="../view/register.php">Nincs még fiókod?</a>

                    <?php if (!empty($message)) : ?>
                        <div class="error-message"><?php echo $message; ?></div>
                    <?php endif; ?>
        </form>

        <div class="side">
            <img src="../pictures/schoolCanteen.jpg" alt="school canteen">
        </div>
    </div>
    
</body>
</html>


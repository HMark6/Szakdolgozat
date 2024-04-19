<?php
require('../controller/registrationController.php');
?>



<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/register.css" rel="stylesheet">
    <title>Regisztráció</title>
</head>
<body>

<div class="container">
        <form action="" class="form" method="post">

            <h2>Regisztráció</h2>
                
                    <label for="vezetekNev">Vezetéknév</label>
                    <input type="text" name="vezetekNev" id="vezetekNev" class="box"  pattern="[A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű\s]+" title="Csak betűket tartalmazhat" required>

                    <label for="keresztNev">Keresztnév</label>
                    <input type="text" name="keresztNev" id="keresztNev" class="box" pattern="[A-Za-zÁÉÍÓÖŐÚÜŰáéíóöőúüű\s]+" title="Csak betűket tartalmazhat" required>
                
                
                    <label for="telepules">Település</label>
                    <select name="telepules" id="telepules" class="box" required>
                        <option value="">Válasszon települést...</option>
                        <?php
                        $sql = "SELECT * FROM `irányítószámok` ORDER BY `irányítószámok`.`telepulesek` ASC";
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['telepulesek'] . "'>" . $row['telepulesek'] . "</option>";
                            }
                        }
                        ?>
                    </select>

                    <label for="telefonSzam">Telefonszám</label>
                    <input type="tel" name="telefonSzam" id="telefonSzam" class="box" placeholder="204159720" pattern="[0-9]{2}[0-9]{3}[0-9]{4}" required>

                    <label for="email">E-mail cím</label>
                    <input type="email" name="email" id="email" class="box" required>
                    <div id="emailError" class="error-message"></div>

                    <label for="password">Jelszó</label>
                    <input type="password" name="password" id="password" class="box" required>
                
                
                    <input type="submit" class="btn" id="submit" name="submit" value="Regsiztrálás" required>

                    <a href="../view/login.php">Van már fiókod?</a>

                    <?php if (!empty($message)) : ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>

        </form>

        <div class="side">
            <img src="../pictures/schoolCanteen.jpg" alt="school canteen">
        </div>
    </div>

</body>
</html>
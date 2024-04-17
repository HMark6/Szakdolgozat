<?php
require('../controller/userController.php');
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="../css/dashboard.css" rel="stylesheet">
</head>
<body>

<?php
$logged_in = isset($_SESSION['user_id']); // Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
?>




<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="../index.php">Menü</a>
                
            </li>
            <li class="nav-item">
                <a class="nav-link" href="view/menu.php">Étlap</a>
            </li>
            
        </ul>
    </div>
</nav>


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                    <p class="card-text">Üdvözöllek, <?php echo $lastname . ' ' . $firstname; ?>!</p>
                    </div>
                    <div class="card-body">
                        

                        <p>Szerkezthető adatok:</p>
                        
                        <form action="" id="profileForm" class="row g-3" method="post">
                            <div class="col-12">
                                <label for="phone" class="form-label">Telefonszám:</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $phone; ?>" pattern="[0-9]{2}[0-9]{3}[0-9]{4}" required>
                            </div>
                            <div class="col-12">
                                <label for="email" class="form-label">E-mail cím:</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="save">Mentés</button>
                            <input type="submit" name="logout" class="btn btn-danger" value="Kijelentkezés">
                            <?php if ($has_subscription && !$subscription_cancelled) : ?>
                                <button type="submit" class="btn btn-danger" name="cancel_subscription">Előfizetés lemondása</button>
                                <p>*Az előfizetést csak a hét kezdése előtt egy nappal lehet lemondani!</p>
                            <?php endif; ?>

                        </form>
                        <?php if (!empty($message)) : ?>
                            <div class="alert alert-success mt-3" role="alert">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include '../view/footer.php'; ?>
    <script>
        // Ellenőrzés a mentés előtt
        document.getElementById('profileForm').addEventListener('submit', function(event) {
            var phoneInput = document.getElementById('phone');
            var emailInput = document.getElementById('email');

            if (!phoneInput.checkValidity()) {
                phoneInput.classList.add('is-invalid');
                event.preventDefault();
            } else {
                phoneInput.classList.remove('is-invalid');
            }

            if (!emailInput.checkValidity()) {
                emailInput.classList.add('is-invalid');
                event.preventDefault();
            } else {
                emailInput.classList.remove('is-invalid');
            }
        });

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    </script>
</body>
</html>

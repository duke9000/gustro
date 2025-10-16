<?php
session_start();

include("connection.php");
include("function.php");

$errore = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    if (!empty($user_name) && !empty($password) && !is_numeric($user_name)) {
        $query = "SELECT * FROM users WHERE user_name = '$user_name' LIMIT 1";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);

            if ($user_data['password'] === $password) {
                $_SESSION["user_id"] = $user_data['user_id'];
                $_SESSION['user_name'] = $user_data['user_name'];
                $_SESSION["mansione"] = $user_data['mansione'];

                switch ($user_data['mansione']) {
                    case 'magazzino':
                        header("Location: public/magazzino.php");
                        break;
                    case 'cucina':
                        header("Location: public/dashboard_cucina.php");
                        break;
                    case 'ufficio':
                        header("Location: public/ufficio.php");
                        break;
                    case 'admin':
                        header("Location: public/admin.php");
                        break;
                    default:
                        header("Location: index.php");
                }
                die;
            }
        }

        $errore = "⚠️ Username o password errati!";
    } else {
        $errore = "⚠️ Inserisci credenziali valide!";
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Gustrò</title>
  <link rel="stylesheet" href="style/login.css" />
  <link rel="icon" href="img/favicon.ico" type="image/x-icon">


</head>
<body>
  <div class="login-container">
    <img src="img/gustroLogo.jpg" alt="Logo Gustrò" style="width: 250px; height: 140px;">

    <?php if (!empty($errore)): ?>
      <div class="errore-box">
        <?php echo $errore; ?>
      </div>
    <?php endif; ?>

    <form class="login-form" method="post">
      <h2>Benvenuto</h2>
      <input type="text" name="user_name" placeholder="Username" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit" class="accedi">Accedi</button>
      <div class="footer-links">
        <a href="#">Password dimenticata?</a>
        <a href="signup.php">Registrati</a>
      </div>
    </form>
  </div>
</body>
</html>
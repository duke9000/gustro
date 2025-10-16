<?php
session_start();

include("connection.php");
include("function.php");

$errore = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];
    $mansione = $_POST['mansione'];

    if (!empty($user_name) && !empty($password) && !empty($mansione) && !is_numeric($user_name) && strpos($user_name, ' ') === false) {
        
        // Verifica se l'username esiste già
        $check_query = "SELECT * FROM users WHERE user_name = '$user_name' LIMIT 1";
        $result = mysqli_query($con, $check_query);

        if (mysqli_num_rows($result) > 0) {
            $errore = "⚠️ Questo username esiste già. Scegline uno diverso!";
        } else {
            // Salva nel database
            $user_id = random_num(20);
            $query = "INSERT INTO users (user_id, user_name, password, mansione) VALUES ('$user_id', '$user_name', '$password', '$mansione')";
            mysqli_query($con, $query);
            header("Location: login.php");
            die;
        }
    } else {
        $errore = "🚫 L'username non può contenere spazi e la mansione va selezionata!";
    }
}
?>


<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registrazione</title>
  <link rel="stylesheet" href="style/login.css" />
  <link rel="icon" href="img/favicon.ico" type="image/x-icon">
</head>
<body>
  <div class="login-container" style="width: 250px;">
    <img src="img/gustroLogo.jpg" alt="Logo Gustrò" style="width: 250px; height: 140px;">
    
    <?php if (!empty($errore)): ?>
      <div class="errore-box">
        <?php echo $errore; ?>
      </div>
    <?php endif; ?>

    <form class="login-form" method="post">
      <h2>Crea il tuo account</h2>
      <input type="text" name="user_name" placeholder="Username" required />
      <input type="password" name="password" placeholder="Password" required />
      
      <select name="mansione" required>
        <option value="">Seleziona la mansione</option>
        <option value="magazzino">Magazzino</option>
        <option value="cucina">Cucina</option>
        <option value="ufficio">Ufficio</option>
      </select>

      <button type="submit" class="accedi">Registrati</button>

      <div class="footer-links">
        <a href="#">Password dimenticata?</a>
        <a href="login.php">Login</a>
      </div>
    </form>
  </div>
</body>
</html>

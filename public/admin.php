<?php
session_start();

// 🔐 Controllo accesso
if($_SESSION['mansione'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// ✅ Recupera lo username dalla sessione
$user_name = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magazzino</title>
    <link rel="stylesheet" href="stile.css"> <!-- opzionale -->
    <link rel="icon" href="../img/favicon.ico" type="image/x-icon">
</head>
<body>
    <h1>📦 Benvenuto, <?php echo htmlspecialchars($user_name); ?>!</h1>
    <p>Questa è la tua pagina dedicata al <strong>tutto</strong>.</p>
    <a href="../logout.php">Logout<a>
</body>
</html>
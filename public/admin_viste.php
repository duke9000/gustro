<?php
session_start();

// Controllo accesso: solo utenti loggati
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Utente';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dati inseriti</title>
    <link rel="icon" href="../img/favicon.ico" type="image/x-icon">
</head>
<body>
    <h1>Dati inseriti</h1>
    <p>Ciao <?php echo htmlspecialchars($user_name); ?>, qui vedrai i dati inseriti (pagina segnaposto).</p>
    <p><a href="admin.php">Torna alla dashboard</a></p>
</body>
</html>
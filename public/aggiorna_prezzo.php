<?php
session_start();
require_once('../connection.php');

if ($_SESSION['mansione'] !== 'ufficio') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id_prodotto']);
    $codice = trim($_POST['codice_articolo']);
    $prezzo = floatval(str_replace(',', '.', $_POST['prezzo'])); // gestisce virgole

    $query = "UPDATE prodotti SET codice_articolo = ?, prezzo = ? WHERE id_prodotto = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "sdi", $codice, $prezzo, $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ufficio.php");
        exit;
    } else {
        echo "❌ Errore nell'aggiornamento: " . mysqli_error($conn);
    }
}
?>

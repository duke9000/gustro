<?php
session_start();
require_once('../connection.php');

if (!isset($_SESSION['mansione']) || !in_array($_SESSION['mansione'], ['ufficio', 'admin'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id_prodotto']);
    $codice = trim($_POST['codice_articolo']);
    $prezzo = floatval(str_replace(',', '.', $_POST['prezzo']));
    $responsabile = $_SESSION['user_name'];

    $query = "UPDATE prodotti SET codice_articolo = ?, prezzo = ?, responsabile_ufficio = ? WHERE id_prodotto = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "sdsi", $codice, $prezzo, $responsabile, $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: ufficio.php");
        exit;
    } else {
        echo "❌ Errore nell'aggiornamento: " . mysqli_error($con);
    }
}
?>

<?php
session_start();
include("../connection.php");
include("../function.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $responsabile = $_SESSION['user_name'];

    // Dati generali
    $materia_prima = $_POST["materia_prima"] ?? '';
    $codice_articolo = strtoupper($_POST['codice_articolo'] ?? '');
    $fornitore = $_POST["fornitore"] ?? '';
    $provenienza = $_POST["provenienza"] ?? '';
    $dimensione_collo = $_POST["dimensione_collo"] ?? '';
    $pezzatura = $_POST["pezzatura"] ?? '';
    $data_di_arrivo = $_POST["data_di_arrivo"] ?? '';
    $data_di_scadenza = $_POST["data_di_scadenza"] ?? '';
    $prezzo  = $_POST["prezzo"] ?? '';
    $feedback = $_POST["feedback"] ?? '';

    // Tipologia e posizione
$tipologia = '';
$posizione = '';

if (isset($_POST['tradizionale'])) {
    $tipologia = 'Tradizionale';
    if (!empty($_POST['posizione_tradizionale_congelato'])) {
        $posizione = 'Congelatore ' . $_POST['posizione_tradizionale_congelato'];
    } elseif (!empty($_POST['posizione_tradizionale_frigorifero'])) {
        $posizione = 'Frigorifero ' . $_POST['posizione_tradizionale_frigorifero'];
    } elseif (!empty($_POST['posizione_tradizionale_magazzino'])) {
        $posizione = 'Magazzino ' . $_POST['posizione_tradizionale_magazzino'];
    }
} elseif (isset($_POST['bio'])) {
    $tipologia = 'BIO';
    if (!empty($_POST['posizione_bio_congelato'])) {
        $posizione = 'Congelatore ' . $_POST['posizione_bio_congelato'];
    } elseif (!empty($_POST['posizione_bio_frigorifero'])) {
        $posizione = 'Frigorifero ' . $_POST['posizione_bio_frigorifero'];
    } elseif (!empty($_POST['posizione_bio_magazzino'])) {
        $posizione = 'Magazzino ' . $_POST['posizione_bio_magazzino'];
    }
} elseif (isset($_POST['gluten_free'])) {
    $tipologia = 'Senza Glutine';
    if (!empty($_POST['posizione_gluten_free_congelato'])) {
        $posizione = 'Congelatore ' . $_POST['posizione_gluten_free_congelato'];
    } elseif (!empty($_POST['posizione_gluten_free_frigorifero'])) {
        $posizione = 'Frigorifero ' . $_POST['posizione_gluten_free_frigorifero'];
    } elseif (!empty($_POST['posizione_gluten_free_magazzino'])) {
        $posizione = 'Magazzino ' . $_POST['posizione_gluten_free_magazzino'];
    }
}


    // Gestione foto
    $foto_preview = null;
    if (isset($_FILES['foto']) && is_array($_FILES['foto']['tmp_name'])) {
        $uploadDir = __DIR__ . '/foto/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $nome_base = preg_replace('/[^a-zA-Z0-9]/', '_', $data_di_arrivo . '-' . $materia_prima . '-' . $fornitore);

        foreach ($_FILES['foto']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['foto']['error'][$key] === UPLOAD_ERR_OK) {
                $originalName = basename($_FILES['foto']['name'][$key]);
                $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                $safeName = $nome_base . '-' . $key . '.' . $extension;
                $targetPath = $uploadDir . $safeName;
                move_uploaded_file($tmpName, $targetPath);

                if ($key === 0) {
                    $foto_preview = $safeName;
                }
            }
        }
    }

    // Inserimento nel database
    $query_sql = "INSERT INTO prodotti (
        materia_prima, codice_articolo, fornitore, provenienza, dimensione_collo, pezzatura,
        data_di_arrivo, data_di_scadenza, prezzo, feedback,
        tipologia, posizione,
        responsabile_magazzino, foto_preview
    ) VALUES (
        '$materia_prima', '$codice_articolo', '$fornitore', '$provenienza', '$dimensione_collo', '$pezzatura',
        '$data_di_arrivo', '$data_di_scadenza', '$prezzo', '$feedback',
        '$tipologia', '$posizione',
        '$responsabile', '$foto_preview'
    )";

    $risultato = $con->query($query_sql);

    if ($risultato == TRUE) {
        echo '<div class="container" style="text-align: center; padding: 30px;">
            <h1 style="color: #4b3a2d; font-size: 24px;">✅ Inserimento completato</h1>
            <p style="color: #3e2f1c; font-size: 18px; margin-bottom: 30px;">
                Il prodotto è stato registrato correttamente nel magazzino.
            </p>
            <div class="button-group" style="display: flex; justify-content: center; gap: 20px;">
                <a href="magazzino.php" class="custom-button" style="background-color:#4b3a2d; color:white;">
                    🔁 Inserisci un altro
                </a>
                <a href="../logout.php" class="custom-button" style="background-color:#4b3a2d; color:white;">
                    🔒 Logout
                </a>
            </div>
        </div>';
    } else {
        echo "Query fallita: $query_sql";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserimento Completato ✅ </title>
    <link rel="stylesheet" href="../style/magazzino.css">
    <link rel="icon" href="../img/favicon.ico" type="image/x-icon">

</head>
<body>
    
</body>
</html>
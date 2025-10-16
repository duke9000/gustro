<?php
session_start();
require_once '../connection.php';

// 🔐 Accesso solo per cucina o admin
if (!isset($_SESSION['mansione']) || !in_array($_SESSION['mansione'], ['cucina', 'admin'])) {
    header("Location: login.php");
    exit;
}

$user_name = $_SESSION['user_name'];
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "ID prodotto mancante.";
    exit;
}

// 🔍 Recupera dati prodotto
$stmt = $con->prepare("SELECT * FROM prodotti WHERE id_prodotto = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$prodotto = $stmt->get_result()->fetch_assoc();

if (!$prodotto) {
    echo "Prodotto non trovato.";
    exit;
}

// ✅ Salvataggio dati
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $peso_congelato = $_POST['peso_congelato'] ?? null;
    $peso_scongelato = $_POST['peso_scongelato'] ?? null;
    $feedback = $_POST['feedback_cuoco'] ?? '';
    $foto_nome = '';
    $responsabile = $user_name;

    // 📸 Gestione foto
    if (!empty($_FILES['foto_cucina']['name'])) {
        $foto_nome = date('Ymd_His') . '--' . basename($_FILES['foto_cucina']['name']);
        move_uploaded_file($_FILES['foto_cucina']['tmp_name'], "foto/" . $foto_nome);
    }

    // 🧠 Aggiorna database con responsabile_cucina
    $stmt = $con->prepare("UPDATE prodotti 
        SET peso_congelato = ?, peso_scongelato = ?, feedback_cuoco = ?, foto_cucina = ?, lavorazione_completata = 1, responsabile_cucina = ? 
        WHERE id_prodotto = ?");
    $stmt->bind_param("ddsssi", $peso_congelato, $peso_scongelato, $feedback, $foto_nome, $responsabile, $id);
    $stmt->execute();

    header("Location: cucina.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Inserimento Cucina</title>
  <link rel="stylesheet" href="../style/cucina.css">
  <link rel="icon" href="../img/favicon.ico" type="image/x-icon">
</head>
<body>
  <div class="container">
    <h2>🍳 Inserisci dati per: <?php echo htmlspecialchars($prodotto['materia_prima']); ?></h2>

    <form method="post" enctype="multipart/form-data">
      <?php if (strtolower(trim($prodotto['conservazione'])) === 'congelato'): ?>
        <label for="peso_congelato">Peso congelato (kg):</label>
        <input type="text" name="peso_congelato" id="peso_congelato" required>

        <label for="peso_scongelato">Peso scongelato (kg):</label>
        <input type="text" name="peso_scongelato" id="peso_scongelato" required>
      <?php endif; ?>

      <label for="feedback_cuoco">Feedback cuoco:</label>
      <textarea name="feedback_cuoco" id="feedback_cuoco" required></textarea>

      <label for="foto_cucina">Foto prodotto lavorato:</label>
      <input type="file" name="foto_cucina" id="foto_cucina" accept="image/*">

      <input type="submit" value="✅ Salva e completa">
    </form>

    <br>
    <a href="cucina.php">⬅️ Torna alla dashboard</a>
  </div>
</body>
</html>

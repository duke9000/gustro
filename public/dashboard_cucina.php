<?php
session_start();
require_once '../connection.php';

// 🔐 Controllo accesso
if ($_SESSION['mansione'] !== 'cucina') {
    header("Location: login.php");
    exit;
}

$user_name = $_SESSION['user_name'];


$conn = new mysqli("localhost", "root", "", "db_gustro");

/*
CONNESSIONE A GUSTRO
$conn = new mysqli("localhost", "root", "", "db_gustro");
*/



// 🔍 Query prodotti non ancora lavorati
$query = "SELECT materia_prima, data_di_arrivo, foto_preview, fornitore FROM prodotti WHERE lavorazione_completata = 0 ORDER BY data_di_arrivo DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Cucina</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style/cucina.css">
  <link rel="icon" href="img/favicon.ico" type="image/x-icon">
  <style>
    @media screen and (max-width: 600px) {
      table, thead, tbody, th, td, tr {
        display: block;
        width: 100%;
      }

      thead {
        display: none;
      }

      tr {
  border: 1px solid #d3b48d;
  border-radius: 12px;
  overflow: hidden;
  margin-bottom: 3px;
}

td {
  border: none; /* rimuove il bordo inferiore */
}

      td::before {
        content: attr(data-label);
        font-weight: bold;
        color: #4b3a2d;
        display: block;
        margin-bottom: 4px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>🍽️ Ciao, <?php echo htmlspecialchars($user_name); ?>!</h1>
    <p>Questi sono i prodotti <strong>non ancora lavorati</strong>.</p>
    <a href="../logout.php">🔒 Logout</a>

    <table>
  <thead>
    <tr>
      <th>Prodotto</th>
      <th>Fornitore</th>
      <th>Data Arrivo</th>
      <th>Foto</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td data-label="Prodotto"><?php echo htmlspecialchars($row['materia_prima']); ?></td>
        <td data-label="Fornitore"><?php echo htmlspecialchars($row['fornitore']); ?></td>
        <td data-label="Data Arrivo"><?php echo htmlspecialchars($row['data_di_arrivo']); ?></td>
        <td data-label="Foto">
          <?php if (!empty($row['foto_preview'])): ?>
            <div class="foto-wrapper">
              <img src="foto/<?php echo htmlspecialchars($row['foto_preview']); ?>" alt="Foto" style="width:60px; border-radius:8px;">
              <div class="mobile-action">
                <a href="cucina.php" class="action-button">🍳 Vai alla cucina</a>
              </div>
            </div>
          <?php else: ?>
            <span style="color:#999;">Nessuna foto</span>
          <?php endif; ?>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
  </div>
</body>
</html>


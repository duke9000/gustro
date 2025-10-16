<?php
session_start();
require_once('../connection.php');

// 🔐 Controllo accesso
if ($_SESSION['mansione'] !== 'ufficio') {
    header("Location: login.php");
    exit;
}

$user_name = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Ufficio - Materie da Completare</title>
  <link rel="stylesheet" href="../style/ufficio.css">
  <link rel="icon" href="../img/favicon.ico" type="image/x-icon">
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      font-size: 0.95em;
    }
    th, td {
      padding: 10px;
      border-bottom: 1px solid #ddd;
      text-align: left;
      vertical-align: middle;
    }
    th {
      background-color: #d3b48d;
      color: #fff;
    }
    img.preview {
      max-width: 100px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    input[type="text"] {
      width: 100%;
      padding: 6px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    input[type="submit"] {
      background-color: #d3b48d;
      color: #fff;
      border: none;
      padding: 8px 12px;
      border-radius: 8px;
      cursor: pointer;
    }
    input[type="submit"]:hover {
      background-color: #b89b74;
    }
  </style>
</head>
<body>
  <div class="container">
  <h1>📦 Ciao, <?php echo htmlspecialchars($user_name);?>!</h1>  
  <h4>📋 Immetti i campi mancanti delle Materie prime</h4>
    <table>
      <thead>
        <tr>
          <th>Codice Articolo</th>
          <th>Foto</th>
          <th>Materia Prima</th>
          <th>Fornitore</th>
          <th>Data Arrivo</th>
          <th>Responsabile Magazzino</th>
          <th>Prezzo</th>
          <th>Azione</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $query = "SELECT * FROM vista_materie_da_completare";
        $result = mysqli_query($con, $query);

        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr>";
          echo "<form method='post' action='aggiorna_prezzo.php'>";
          
          // Codice Articolo (modificabile)
          echo "<td><input type='text' name='codice_articolo' value='" . htmlspecialchars($row['codice_articolo']) . "'></td>";
          
          // Foto Preview
          echo "<td>";
          if (!empty($row['foto_preview'])) {
            echo "<a href='../public/foto/" . htmlspecialchars($row['foto_preview']) . "' target='_blank'>";
echo "<img src='../public/foto/" . htmlspecialchars($row['foto_preview']) . "' alt='Preview' class='preview'>";
echo "</a>";

          } else {
            echo "—";
          }
          echo "</td>";
          
          // Altri campi
          echo "<td>" . htmlspecialchars($row['materia_prima']) . "</td>";
          echo "<td>" . htmlspecialchars($row['fornitore']) . "</td>";
          echo "<td>" . htmlspecialchars($row['data_di_arrivo']) . "</td>";
          echo "<td>" . htmlspecialchars($row['responsabile_magazzino']) . "</td>";
          
          // Prezzo (modificabile)
          echo "<td><input type='text' name='prezzo' value='" . htmlspecialchars($row['prezzo']) . "'></td>";
          
          // Azione
          echo "<td>
                  <input type='hidden' name='id_prodotto' value='" . $row['id_prodotto'] . "'>
                  <input type='submit' value='💾 Salva'>
                </td>";
          
          echo "</form>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>


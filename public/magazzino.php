<?php
session_start();

// 🔐 Controllo accesso: solo utenti con mansione "magazzino" possono accedere
if($_SESSION['mansione'] !== 'magazzino') {
    header("Location: login.php");
    exit;
}

// ✅ Recupera lo username dalla sessione per salutarlo nella pagina
$user_name = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inserimento Materie Prime</title>
  <!-- Collegamento al foglio di stile personalizzato -->
  <link rel="stylesheet" href="../style/magazzino.css">
  <link rel="icon" href="../img/favicon.ico" type="image/x-icon">
</head>
<body>

  <div class="container">
    <!-- Saluto personalizzato -->
    <h1>📦 Ciao, <?php echo htmlspecialchars($user_name); ?>!</h1>
    <p>Questa è la tua pagina dedicata al <strong>Magazzino</strong>.</p>
    <a href="../logout.php">🔒 Logout</a>

    <h1> Inserimento Materie Prime</h1>

    <!-- Form di inserimento -->
    <form method="post" action="inserimento_materie_prime.php" enctype="multipart/form-data" onsubmit="showSuccessMessage()">

      <!-- Sezione foto -->
      <h2>📷 Foto Ingredienti</h2>
      <div class="button-group">
        <!-- Pulsante per scattare foto -->
        <label for="cameraInput" class="custom-button" style="background-color:  #d3b48d; color: aliceblue;">
          <img src="https://img.icons8.com/ios-filled/50/000000/camera.png" alt="Fotocamera">
          Scatta Foto
        </label>
        <input type="file" id="cameraInput" name="foto[]" accept="image/*" capture="environment" multiple onchange="previewImages(event)">

        <!-- Pulsante per aprire galleria -->
        <label for="galleryInput" class="custom-button" style="background-color:  #d3b48d; color: aliceblue;">
          <img src="https://img.icons8.com/ios-filled/50/000000/image-gallery.png" alt="Galleria">
          Apri Galleria
        </label>
        <input type="file" id="galleryInput" name="foto[]" accept="image/*" multiple onchange="previewImages(event)">
      </div>

      <!-- Anteprima immagini -->
      <div class="preview" id="preview"></div>

      <!-- Sezione info generali -->
      <h2>🏷️ Info Generali</h2>
      <input type="text" name="materia_prima" placeholder="Nome Materia Prima" required>
      <input type="text" name="codice_articolo" placeholder="Codice Articolo" style="text-transform: uppercase;">
      <input type="text" name="fornitore" placeholder="Fornitore">
      <input type="text" name="provenienza" placeholder="Provenienza Prodotto">
      <input type="text" name="dimensione_collo" placeholder="kg Collo">
      <input type="text" name="pezzatura" placeholder="Pezzatura">

      <!-- Date di arrivo e scadenza -->
      <h2>📅 Data di Arrivo</h2>
      <input type="date" name="data_di_arrivo" required>
      <h2>📅 Data di Scadenza</h2>
      <input type="date" name="data_di_scadenza" required>

      <!-- Prezzo -->
      <h2>💰 Prezzo ad U.M.</h2>
      <input type="text" name="prezzo" placeholder="Prezzo al kg, al litro">

      <!-- Sezione Conservazione e Caratteristiche -->
      <h2>📦 Conservazione e Caratteristiche</h2>

      <div class="checkbox-group">
        <!-- Tipologia: Tradizionale -->
        <label><input type="checkbox" name="tradizionale" onchange="toggleGroup(this, 'tradizionale')"> 🛒 Tradizionale</label>
        <div id="group-tradizionale" class="subgroup" style="display:none;">
          <label><input type="checkbox" onchange="toggleInput(this, 'posizione_tradizionale_congelato')"> ❄️ Congelato</label>
          <input type="text" id="posizione_tradizionale_congelato" name="posizione_tradizionale_congelato" placeholder="Posizione" style="display:none;">

          <label><input type="checkbox" onchange="toggleInput(this, 'posizione_tradizionale_frigorifero')"> 🧊 Frigorifero</label>
          <input type="text" id="posizione_tradizionale_frigorifero" name="posizione_tradizionale_frigorifero" placeholder="Posizione" style="display:none;">

          <label><input type="checkbox" onchange="toggleInput(this, 'posizione_tradizionale_magazzino')"> 🏚️ Magazzino</label>
          <input type="text" id="posizione_tradizionale_magazzino" name="posizione_tradizionale_magazzino" placeholder="Posizione" style="display:none;">
        </div>

        <!-- Tipologia: BIO -->
        <label><input type="checkbox" name="bio" onchange="toggleGroup(this, 'bio')"> 🌱 BIO</label>
        <div id="group-bio" class="subgroup" style="display:none;">
          <label><input type="checkbox" onchange="toggleInput(this, 'posizione_bio_congelato')"> ❄️ Congelato</label>
          <input type="text" id="posizione_bio_congelato" name="posizione_bio_congelato" placeholder="Posizione" style="display:none;">

          <label><input type="checkbox" onchange="toggleInput(this, 'posizione_bio_frigorifero')"> 🧊 Frigorifero</label>
          <input type="text" id="posizione_bio_frigorifero" name="posizione_bio_frigorifero" placeholder="Posizione" style="display:none;">

          <label><input type="checkbox" onchange="toggleInput(this, 'posizione_bio_magazzino')"> 🏚️ Magazzino</label>
          <input type="text" id="posizione_bio_magazzino" name="posizione_bio_magazzino" placeholder="Posizione" style="display:none;">
        </div>

        <!-- Tipologia: Senza Glutine -->
        <label><input type="checkbox" name="gluten_free" onchange="toggleGroup(this, 'gluten_free')"> 🚫🌾 Senza Glutine</label>
        <div id="group-gluten_free" class="subgroup" style="display:none;">
          <label><input type="checkbox" onchange="toggleInput(this, 'posizione_gluten_free_congelato')"> ❄️ Congelato</label>
          <input type="text" id="posizione_gluten_free_congelato" name="posizione_gluten_free_congelato" placeholder="Posizione" style="display:none;">

          <label><input type="checkbox" onchange="toggleInput(this, 'posizione_gluten_free_frigorifero')"> 🧊 Frigorifero</label>
          <input type="text" id="posizione_gluten_free_frigorifero" name="posizione_gluten_free_frigorifero" placeholder="Posizione" style="display:none;">

          <label><input type="checkbox" onchange="toggleInput(this, 'posizione_gluten_free_magazzino')"> 🏚️ Magazzino</label>
          <input type="text" id="posizione_gluten_free_magazzino" name="posizione_gluten_free_magazzino" placeholder="Posizione" style="display:none;">
        </div>
      </div>

      <!-- Script per mostrare/nascondere gruppi e input -->
      <script>
        function toggleGroup(checkbox, groupId) {
          const group = document.getElementById('group-' + groupId);
          group.style.display = checkbox.checked ? 'block' : 'none';
        }

        function toggleInput(checkbox, inputId) {
          const input = document.getElementById(inputId);
          input.style.display = checkbox.checked ? 'block' : 'none';
        }
      </script>

      <!-- Sezione feedback -->
      <h2>💬 Feedback</h2>
      <textarea name="feedback" placeholder="Note o commenti..."></textarea>

      <!-- Pulsante di invio -->
      <input type="submit" name="invia" value="Invia">
    </form>

    <!-- Messaggio di successo -->
    <div class="success-message" id="successMessage">
      ✅ Inserimento inviato con successo!
    </div>

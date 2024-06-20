<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>SAE 24</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles/style.css">
</head>
<body>
  <header>
      <h1>Consultation son</h1>
      <nav>
          <ul>
              <li><a href="index.html" class="first">Accueil</a></li>
              <li><a href="#">Consultation son</a></li>
              <li><a href="consultation_ultrason.php">Consultation ultrason</a></li>
              <li><a href="admin.php">Administration</a></li>
              <li><a href="gestion_de_projet.html">Gestion de projet</a></li>
          </ul>
      </nav>
  </header>


  <h2>Tableau de consultation des positions</h2>


  <?php
  // Connection
  $conn = mysqli_connect("localhost", "sae24", "passroot", "sae24");
  if (!$conn) {
      die("Connexion échouée: " . mysqli_connect_error());
  }


  // Retrieve room IDs for the selection form
  $sql_salles = "SELECT IDSalle FROM salles";
  $result_salles = mysqli_query($conn, $sql_salles);
  $salles = [];
  if ($result_salles) {
      while ($row = mysqli_fetch_assoc($result_salles)) {
          $salles[] = $row['IDSalle'];
      }
  } else {
      die("Erreur lors de l'exécution de la requête des salles: " . mysqli_error($conn));
  }


  // Set table size
  $rows = 16;
  $cols = 16;


  // Initialize the color table
  $colors = array_fill(1, $rows, array_fill(1, $cols, ''));


  // Color the sensors blue
  $colors[1][1] = 'blue'; // (1, 1)
  $colors[16][1] = 'blue'; // (16, 1)
  $colors[16][16] = 'blue'; // (16, 16)


  // Gérer les données POST pour ajouter une couleur spécifique
  if ($_SERVER["REQUEST_METHOD"] == "POST") { // Checks if the request method used is POST
      if (isset($_POST['salle'])) { // Check if key 'room' exists in POST data
          $salle_selectionnee = $_POST['salle']; //Get the value of the selected room from the form
          $_SESSION['salle'] = $salle_selectionnee; // Store selected room in session
      }


      // Traiter les autres données POST pour ajouter une couleur spécifique
      if (isset($_POST['row'], $_POST['col'], $_POST['color'])) {
          $row = intval($_POST['row']);
          $col = intval($_POST['col']);
          $color = $_POST['color'];


          // Vérifier si les valeurs de ligne et de colonne sont valides
          if ($row >= 1 && $row <= $rows && $col >= 1 && $col <= $cols) {
              $colors[$rows - $row + 1][$col] = $color; // Inverser l'axe des y
          }
      }
  }


  // Utiliser la salle stockée dans la session si disponible
  $salle_selectionnee = isset($_SESSION['salle']) ? $_SESSION['salle'] : $salles[0];


  // Récupérer les données de la table SQL pour les positions des sons en fonction de la salle sélectionnée
  $sql_positions = "SELECT ValeurX, ValeurY, DateHeure FROM positions_son WHERE IDSalle = '$salle_selectionnee' ORDER BY DateHeure";
  $result_positions = mysqli_query($conn, $sql_positions);


  if (!$result_positions) {
      die("Erreur lors de l'exécution de la requête: " . mysqli_error($conn));
  } elseif (mysqli_num_rows($result_positions) > 0) {
      // Récupérer toutes les positions dans un tableau
      $positions = mysqli_fetch_all($result_positions, MYSQLI_ASSOC);


      // Parcourir toutes les positions
      for ($i = 0; $i < count($positions); $i++) {
          $x = intval($positions[$i]["ValeurX"]);
          $y = intval($positions[$i]["ValeurY"]);


          if ($x >= 1 && $x <= $rows && $y >= 1 && $y <= $cols) {
              if ($i == count($positions) - 1) {
                  // Dernière position en rouge
                  $colors[$y][$x] = 'red'; // Inverser l'axe des y
              } else {
                  // Positions précédentes en orange
                  $colors[$y][$x] = 'orange'; // Inverser l'axe des y
              }
          }
      }
  }


  mysqli_close($conn);
  ?>


  <!-- Formulaire de sélection de salle -->
  <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
  <label for="salle" class="salle-label">Sélectionnez une salle :</label>
      <select id="salle" name="salle">
          <?php foreach ($salles as $salle): ?>
              <option value="<?php echo $salle; ?>" <?php echo ($salle == $salle_selectionnee) ? 'selected' : ''; ?>>
                  <?php echo $salle; ?>
              </option>
          <?php endforeach; ?>
      </select>
      <button type="submit">Soumettre</button>
  </form>


  <table>
      <?php
      for ($i = $rows; $i >= 1; $i--) { // Inverser l'ordre des lignes pour avoir (1,1) en bas
          echo "<tr>";
          for ($j = 1; $j <= $cols; $j++) {
              $class = "class='tab'";
              $style = $colors[$i][$j] ? "style='background-color: {$colors[$i][$j]};'" : "";
              echo "<td $class $style></td>";
          }
          echo "</tr>";
      }
      ?>
  </table>

  <script>
    const tbody = document.querySelector("tbody")

    setInterval(function() {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);

                let i = 0
                const tableau = Object.values(JSON.parse(this.responseText))
                while (i != tableau.length) {
                    const ligne = Object.values(tableau[i])
                    console.log(ligne)
                    let y = 0

                    while (y != tableau.length) {
                        const cas = Object.values(ligne[y])
                        tbody.children[tbody.children.length - i - 1].children[y].style.backgroundColor = cas
                        y++
                    }

                    i++
                }
            }
        };
        xmlhttp.open("GET", "./consultation_son_refresh.php?salle=<?php echo $salle_selectionnee; ?>", true);
        xmlhttp.send();
    }, 1000)
  </script>


  <!-- Bouton Reset -->
  <section class="reset-container">
      <button onclick="location.href='./supprimer.php'">Reset</button>
  </section>


  <footer>
      <ul class="IUT">
          <li>IUT de Blagnac</li>
          <li>Département Réseaux et Télécommunications</li>
          <li><a href="mentions-légales.html">Mentions légales</a></li>
      </ul>
  </footer>
</body>
</html>


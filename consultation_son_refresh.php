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

  $salle_selectionnee = $_GET['salle'];

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

  echo json_encode($colors);


  mysqli_close($conn);
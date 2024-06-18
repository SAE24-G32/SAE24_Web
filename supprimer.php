<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "sae24");
if (!$conn) {
    die("Connexion échouée: " . mysqli_connect_error());
}

$sql_reset = "DELETE FROM positions_son";
if (!mysqli_query($conn, $sql_reset)) {
    echo "Erreur lors de la suppression des données: " . mysqli_error($conn) . "<br>";
}

mysqli_close($conn);

header("Location: ./consultation_son.php");
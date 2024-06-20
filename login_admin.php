<?php
	session_start();
	$_SESSION["mdp"]=$_REQUEST["mdp"];  // Get the password
	$motdep=$_SESSION["mdp"];
	$_SESSION["auth"]=FALSE;

	// Script for verifying the administration password, using the Connexion table

	if(empty($motdep))
		header("Location:login_admin_error.php");
	else
     {
		/* Access to the database */
		include ("mysql.php");

		$requete = "SELECT `mdp` FROM `administration`";
		$resultat = mysqli_query($id_bd, $requete)
			or die("Execution de la requete impossible : $requete");

		$ligne = mysqli_fetch_row($resultat);
		if ($motdep==$ligne[0])
		 {
			$_SESSION["auth"]=TRUE;		
            mysqli_close($id_bd);
			echo "<script type='text/javascript'>document.location.replace('modification_bdd.php');</script>";
		 }
		else
		 {
			$_SESSION = array(); // Reset of the session array
			session_destroy();   // Session destruction
			unset($_SESSION);    // Array destruction
            mysqli_close($id_bd);
            echo "<script type='text/javascript'>document.location.replace('login_admin_error.php');</script>";
		 }
     } 
 ?>
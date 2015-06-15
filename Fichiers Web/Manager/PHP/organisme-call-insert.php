<html>
<head>
	<title>Appel à projet</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>

<?php
	//Récupération des variables 
		$vNom=$_POST['Nom'];
		$vDateC=$_POST['DatedeCréation'];
		$vDateF=$_POST['Datedefin'];
	
		
	// Connexion à la BDD  --
		include "../../php/connect.php";
		$vConn=fConnect();
	
	if (empty($vDateC) || empty($vDateF)) 
		echo "Vous devez renseigner une date de creation et de fin!";

	else {

		$vSql = "SELECT dateCreation,dateFin FROM OrganismeProjet WHERE dateCreation = '$vDateC' AND dateFin = '$vDateF';";
		$vQuery=pg_query($vConn,$vSql);

		if(!(pg_num_rows($vQuery) == 0))
			echo "Erreur : Un organisme avec les mêmes dates de début et de fin existe deja, veuillez verifier vos donnes!<br>";
		
		else {
	
			//<!-- Insertion de l appel dans la BDD
			$vSql = "INSERT INTO OrganismeProjet (nom, dateCreation, DateFin)
		 		VALUES ('$vNom', '$vDateC', '$vDateF')";
			$vQuery=pg_query($vConn,$vSql);
	
	
			//<!-- Test sur le retour de la requête
			if(!$vQuery)
				echo"Ajout de l'appel impossible";
			else
				echo"<p>Appel à projet ajouté avec succès</p>";

			};
	};
	
?>

	<br/>
	<p><a href="../HTML/OrganismeProjet.html">Inserer un nouvel organisme projet</a></p>
	<p><a href="../HTML/AccueilManager.html">Retour à l'accueil</a></p>
</body>
</html>

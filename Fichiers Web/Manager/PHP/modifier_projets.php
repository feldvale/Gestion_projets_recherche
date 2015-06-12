

<html>
<head>
	<title>Projets</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>

<?php
	//Récupération des variables
	$vNomActuel=$_POST['nomActuel'];
	$vDatedebutActuelle=$_POST['dateDebutActuelle'];
	$vNom=$_POST['nomNouveau'];
	$vdateDebut=$_POST['dateD'];
	$vdateFin=$_POST['dateF'];
	$vproposition=$_POST['prop'];
		
	//Connexion à la BDD
	include "../../php/connect.php";
	$vConn=fConnect();

	if (empty($vNomActuel)|| empty($vDatedebutActuelle))
		echo "Vous devez renseigner le nom et la date de debut du projet à modifier!";
	else {

	$vSql = "SELECT nom, dateDebut FROM Projet WHERE nom = '$vNomActuel' AND dateDebut='$vDatedebutActuelle';";
	$vQuery=pg_query($vConn,$vSql);
		
	if(pg_num_rows($vQuery) == 0)
			echo "Erreur : veuillez vérifier vos données concernant le projet à modifier.<br>";
	
	else {

		if (empty($vNom)|| empty($vdateDebut) || empty($vdateFin) || empty($vproposition)) 
			echo "Vous devez rentrer toutes les informations concernant les modifications du projet (si vous ne la changez pas, reecrivez celle de depart!";
		
		else {
			//Insertion de l appel dans la BDD
			$vSql = "UPDATE Projet 
				SET nom='$vNom', dateDebut='$vdateDebut', dateFin='$vdateFin', proposition='$vproposition'
				WHERE nom='$vNomActuel' AND dateDebut='$vDatedebutActuelle'; ";
			$vQuery=pg_query($vConn,$vSql);
	
	
			//Test sur le retour de la requête
			if(!$vQuery)
				echo"Modification projet impossible";
			else
				echo"<p>Modification projet réalisée avec succès</p>";
			};
		};
	};
	
?>

	<hr/>
	<p><a href="../HTML/modifier_projet.html">Modifier un autre projet</a></p>
	<p><a href="../HTML/AccueilManager.html">Retour à l'accueil</a></p>
</body>
</html>

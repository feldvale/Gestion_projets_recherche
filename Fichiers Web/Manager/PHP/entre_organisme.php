<html>
<head>
	<title>Organismes</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>

<?php
	//Récupération des variables
	$vNom=$_POST['nom']; //Utilisation d'une séquence
	$vDateCreation=$_POST['dateCreation'];
	$vDateFin=$_POST['dateFin'];

	//Connexion à la BDD
	include "../../php/connect.php";
	$vConn=fConnect();
	
	//Insertion de l appel dans la BDD
	$vSql = "INSERT INTO OrganismeProjet VALUES($vNom,$vDateCreation,$vDateFin)";
	$vQuery=pg_query($vConn,$vSql);
	
	
	Test sur le retour de la requête
	if(!$vQuery)
		echo"Ajout de l'organisme impossible";
	else
		echo"<p>Organisme ajouté avec succès</p>";
	
?>

	<hr/>
	<p><a href="../HTML/entre_organisme.html">Ajouter un nouvel organisme</a></p>
	<p><a href="../HTML/AccueilManager.html">Retour à l'accueil</a></p>
	
</body>
</html>

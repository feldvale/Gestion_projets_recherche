<html>
<head>
	<title>Employe Contact</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>

<?php
	//Récupération des variables
	$vMail=$_POST['mail'];
	$vTitre=$_POST['titre'];
	$vTelephone=$_POST['telephone'];
		
	//Connexion à la BDD
	include "../../php/connect.php";
	$vConn=fConnect();

	if (empty($vMail)) 
		echo "Vous devez rentrer une adresse mail!";

	else {

		$vSql = "SELECT mail FROM EmployeContact WHERE mail = '$vMail'";
		$vQuery=pg_query($vConn,$vSql);

		if(!(pg_num_rows($vQuery) == 0))
			echo "Erreur : Verifiez votre adresse mail.<br>";
	
		else {
	
			//Insertion de l appel dans la BDD
			$vSql = "INSERT INTO EmployeContact VALUES('$vMail','$vTitre','$vTelephone')";
			$vQuery=pg_query($vConn,$vSql);
	
	
			//Test sur le retour de la requête
			if(!$vQuery)
				echo"Ajout de l'employe contact impossible";
			else
				echo"<p>Ajout de l'employe contact avec succès</p>";
	
		};
	};
	
?>

	<hr/>
	<p><a href="../HTML/entre_employe_contact.html">Ajouter un nouvel employé contact</a></p>
	<p><a href="../HTML/AccueilManager.html">Retour à l'accueil</a></p>
	
</body>
</html>

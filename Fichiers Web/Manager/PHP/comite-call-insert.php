<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>

<?php
	//Récupération des variables 
		$vId=$_POST['id'];
		$vNom=$_POST['nom'];
		$vNomOrga=$_POST['nomOrga'];
		$vDateC=$_POST['dateOrga'];
	
		
	// Connexion à la BDD  --
		include "../../php/connect.php";
		$vConn=fConnect();
	
	if (empty($vId) || empty($vNomOrga) || empty($vDateC) ) 
		echo "Vous devez renseigner l'identifiant du comite, le nom de l'organisme projet ainsi que sa date de creation!";

	else {

		$vSql = "SELECT * FROM Comite WHERE id = '$vId';";
		$vQuery=pg_query($vConn,$vSql);

		if(!(pg_num_rows($vQuery) == 0))
			echo "Erreur : Un comite avec le même identifiant existe deja, veuillez verifier vos donnes!<br>";

		else {
			$vSql = "SELECT * FROM Comite WHERE nomOrga = '$vNomOrga' AND dateOrga = '$vDateC';";
			$vQuery=pg_query($vConn,$vSql);

			if(pg_num_rows($vQuery) == 0)
				echo "Erreur : L'organisme projet auquel vous faites reference n'existe pas, veuillez verifier vos donnees!<br>";

			else {
	
				//<!-- Insertion de l appel dans la BDD
				$vSql = "INSERT INTO Comite (id,nom,nomOrga,dateOrga)
		 			VALUES ('$vId', '$vNom', '$vNomOrga','$vDateC')";
				$vQuery=pg_query($vConn,$vSql);
	
	
				//<!-- Test sur le retour de la requête
				if(!$vQuery)
					echo"Ajout du comite impossible";
				else
					echo"<p> Comite ajouté avec succès</p>";

			};
		};
	};
	
?>

	<br/>
	<p><a href="../HTML/OrganismeProjet.html">Retour aux entites juridiques</a></p>
	<p><a href="../HTML/AccueilManager.html">Retour à l'accueil</a></p>
</body>
</html>

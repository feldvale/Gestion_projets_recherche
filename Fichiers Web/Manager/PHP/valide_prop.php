
<html>
<head>
	<title>Valider Proposition</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>

<?php
	//Récupération des variables
	$vNumero=$_POST['numero'];
		
	//Connexion à la BDD
	include "../../php/connect.php";
	$vConn=fConnect();

	if (empty($vNumero))
		echo "Vous devez renseigner le numero de la proposition à valider!";
	else {

		$vSql = "SELECT numero FROM Proposition WHERE numero = '$vNumero' and etatReponse=false ;";
		$vQuery=pg_query($vConn,$vSql);
		
		if(pg_num_rows($vQuery) == 0)
			echo "Erreur : veuillez vérifier que le numero de la proposition existe!<br>";
	
		else {

			//Insertion de l appel dans la BDD
			$vSql = "UPDATE Proposition SET etatReponse='true' WHERE numero=$vNumero;";
			$vQuery=pg_query($vConn,$vSql);
	
	
			//Test sur le retour de la requête
			if(!$vQuery)
				echo"Changement statut impossible";
			else
				echo"<p>Changement statut réalisé avec succès</p>";
		};
	};
	
?>

	<hr/>
	<p><a href="../HTML/valide_prop.html">Valider d'autres propositions</a></p>
	<p><a href="../HTML/AccueilManager.html">Retour à l'accueil</a></p>
</body>
</html>

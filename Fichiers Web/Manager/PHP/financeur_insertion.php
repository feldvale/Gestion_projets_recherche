<html>
<head>
	<title>Financeurs</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>

<?php
	//Récupération des variables 
		$vNom=$_POST['nom'];
		$vDateC=$_POST['dateDebut'];
		$vDateF=$_POST['dateFin'];
		$vEmpC=$_POST['employeContact'];
		$vType=$_POST['type'];
	

	// Connexion à la BDD  --
		include "../../php/connect.php";
		$vConn=fConnect();
	
	if (empty($vNom) || empty($vDateC)) 
		echo "Vous devez renseigner un nom et une date de début ";

	else {

		$vSql = "SELECT dateFin FROM OrganismeProjet WHERE dateDebut = '$vDateC' AND nom= '$vNom';";
		$vQuery=pg_query($vConn,$vSql);

		if(!(pg_num_rows($vQuery) == 0))
			echo "Erreur : Un financeur avec la même date de début et et le même nom existe deja, veuillez verifier vos donnes!<br>";
		
		else {
	
			//<!-- Insertion de l appel dans la BDD
			$vSql = "INSERT INTO Financeur (id, nom, dateDebut, dateFin,employeContact,type)
		 		VALUES ('nextval('seqFinanceur')','$vNom', '$vDateC', '$vDateF','$vEmpC', '$vType')";
			$vQuery=pg_query($vConn,$vSql);
	
	
			//<!-- Test sur le retour de la requête
			if(!$vQuery)
				echo"Ajout de l'appel impossible";
			else
				echo"<p> Financeur ajouté avec succès</p>";

			};
	};
	
?>

	<br/>
	<p><a href="../HTML/financeur.html">Inserer un nouveau financeur</a></p>
	<p><a href="../HTML/AccueilManager.html">Retour à l'accueil</a></p>
</body>
</html>

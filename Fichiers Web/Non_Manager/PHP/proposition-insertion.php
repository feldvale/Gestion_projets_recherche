<html>
<head>
	<meta charset "UTF-8">
<head>
<body>
	<?php
		include "../../php/commonfunctions.php";
		include "../../php/global.php";
		include "../../php/connect.php";

		$connect = fConnect();

		$sujet = $_POST['sujet'];
		$description = $_POST['description'];
		$comite = $_POST['comite'];
		
		//Recherche du comité dans la bdd
		$vSql = "SELECT id FROM Comite WHERE nom = '$comite'";
		$vQuery=pg_query($connect,$vSql);

		if(pg_num_rows($vQuery) == 0)
			echo "Erreur : veuillez vérifier le nom du comité.<br>";
		else{
			$vResult=pg_fetch_array($vQuery);
			//Insertion de l appel dans la BDD
			$vSql = "INSERT INTO Proposition (numero, description, comite, dateEnvoi, etatReponse) 
				VALUES (nextval('seqProposition'),'$description', $vResult[id], current_date, false)";
			$vQuery=pg_query($connect,$vSql);
			
			//Test sur le retour de la requête
			if(!$vQuery)
				echo"Ajout de la proposition impossible";
			else
				echo"<p>Appel à projet ajouté avec succès</p>";
		}		
		
	?>
	<p><a href="../HTML/accueil.html">Retour à l'accueil</a></p>
	
</body>
</html>

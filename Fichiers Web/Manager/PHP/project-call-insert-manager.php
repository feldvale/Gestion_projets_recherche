<html>
<head>
	<title>Appel à projet</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>

<?php

	//Récupération des variables
	$vTheme=$_POST['theme'];
	$vFin=$_POST['dateFin'];
	$vDesc=$_POST['desc'];
	$vComite=$_POST['comite'];
	
				
		//Connexion à la BDD
		include "../../php/connect.php";
		$vConn=fConnect();
		
		//Recherche du comité dans la bdd
		$vSql = "SELECT id FROM Comite WHERE nom = '$vComite'";
		$vQuery=pg_query($vConn,$vSql);
		
		
		if(pg_num_rows($vQuery) == 0)
			echo "Erreur : veuillez vérifier le nom du comité.<br>";
		else{
			$vResult=pg_fetch_array($vQuery);
			//Insertion de l appel dans la BDD
			$vSql = "INSERT INTO AppelProjet VALUES(nextval('seqAppelProjet'),current_date,'$vFin','$vTheme','$vDesc',$vResult[id])";
			$vQuery=pg_query($vConn,$vSql);
			
			//Test sur le retour de la requête
			if(!$vQuery)
				echo"Ajout de l'appel impossible";
			else
				echo"<p>Appel à projet ajouté avec succès</p>";
		}
	
	//}
	
?>

	<hr/>
	<p><a href="../HTML/project-call-manager.html">Retour à l'appel de projet</a></p>
	<p><a href="../HTML/AccueilManager.html">Retour à l'accueil</a></p>
</body>
</html>

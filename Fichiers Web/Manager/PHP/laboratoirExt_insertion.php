<html>
<head>
	<title>Laboratoires Externes</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>

<?php
	//Récupération des variables 
		$vNom=$_POST['nom'];
		$vDateC=$_POST['dateDebut'];
		$vDateF=$_POST['dateFin'];
	

	// Connexion à la BDD  --
		include "../../php/connect.php";
		$vConn=fConnect();
	
	if (empty($vNom) || empty($vDateC)) 
		echo "Vous devez renseigner un nom et une date de début ";

	else {

		$vSql = "SELECT dateFin FROM LaboratoireExterne WHERE dateDebut = to_date('$vDateC','dd-mm-yyyy') AND nom= '$vNom';";
		$vQuery=pg_query($vConn,$vSql);

		if(!(pg_num_rows($vQuery) == 0))
			echo "Erreur : Un Laboratoire avec la même date de début et et le même nom existe deja, veuillez verifier vos donnees!<br>";
		
		else {
	
			//<!-- Insertion de l appel dans la BDD
			$vSql = "INSERT INTO LaboratoireExterne (id, nom, dateDebut, dateFin)
		 		VALUES (nextval('seqLaboExterne'),'$vNom', to_date('$vDateC','dd-mm-yyyy'), to_date('$vDateF','dd-mm-yyyy'))";
			$vQuery=pg_query($vConn,$vSql);
	
	
			//<!-- Test sur le retour de la requête
			if(!$vQuery)
				echo"Ajout de l'appel impossible";
			else
				echo"<p> Laboratoire ajouté avec succès</p>";

			};
	};
	
?>

	<br/>
	<p><a href="../HTML/OrganismeProjet.html">Inserer un nouvel organisme</a></p>
	<p><a href="../HTML/AccueilManager.html">Retour à l'accueil</a></p>
</body>
</html>

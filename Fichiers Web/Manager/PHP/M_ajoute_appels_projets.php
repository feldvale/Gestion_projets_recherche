<html>
<head>
	<title>Appel à projet</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>

<?php
	//Récupération des variables
	//$vId=$_POST['id']; Utilisation d'une séquence
	$vTheme=$_POST['theme'];
	$vFin=$_POST['fin'];
	$vDesc=$_POST['desc'];
	$vComite=$_POST['comite'];
		
	//Connexion à la BDD
	include "connect.php";
	$vConn=fConnect();
	
	//Insertion de l appel dans la BDD
	$vSql = "INSERT INTO AppelProjet VALUES(SeqAppelProjet.NEXTVAL,current_date,$vFin,$vTheme,$vDesc,$vComite)";
	$vQuery=pg_query($vConn,$vSql1);
	
	
	Test sur le retour de la requête
	if(!$vQuery)
		echo"Ajout de l'appel impossible";
	else
		echo"<p>Appel à projet ajouté avec succès</p>";
	
?>

	<hr/>
	<p><a href="../HTML/M_ajoute_appels_projets.html">Ajouter un nouveau projet</a></p>
	<p><a href="../HTML/AccueilManager.html">Retour à l'accueil</a></p>
	
</body>
</html>

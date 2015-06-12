<!--12/06 -->
<html>
<head>
	<meta charset="UTF-8">
<head>
<body>
	<?php
		include "../../php/commonfunctions.php";
		include "../../php/global.php";
		include "../../php/connect.php";

		$connect = fConnect();	

		$recherche = $_POST['recherche'];
		$type = $_POST['type_recherche'];
		
		switch ($type)
		{
			case "nom_prop":	
				$query = "SELECT ap.theme, p.description, p.dateEnvoi, p.dateReponse, p.BudgetTot, c.nom 
					FROM Proposition p, AppelProjet ap, Comite c
					WHERE p.comite = c.id AND c.id=ap.comiteresp AND ap.theme = '$recherche'";
				$result = pg_query($connect, $query);
				if (!result)
					echo "Erreur ! </br>";
			break;

			/*case "periode":	//gestion des dates à revoir
				$query = "SELECT * FROM proposition WHERE sujet = '$recherche'";
				$result = pg_query($connect, $query);
				if (!result)
					echo "Erreur ! </br>";
			break;*/

			case "organisme":	
				$query = "SELECT p.description, p.dateEnvoi, p.dateReponse, p.BudgetTot, c.nom 
					FROM Proposition p, Comite c FROM proposition p, comite c 
					WHERE p.comite = c.id AND c.nom = '$recherche'";
				$result = pg_query($connect, $query);
				if (!result)
					echo "Erreur ! </br>";
			break;
		}
	?>
	<p><a href="../HTML/accueil.html">Retour à l'accueil</a></p>
</body>
</html>

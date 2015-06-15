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
				$query = "SELECT p.numero, ap.theme, p.description, p.dateEnvoi, p.dateReponse, p.BudgetTot, c.nom 
					FROM Proposition p, AppelProjet ap, Comite c
					WHERE p.comite = c.id AND c.id=ap.comiteresp AND ap.theme = '$recherche'";
				$result = pg_query($connect, $query);
				if (!$result)
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
			default:
				$query = "SELECT ap.theme, p.description, p.dateEnvoi, p.dateReponse, p.BudgetTot, c.nom 
					FROM Proposition p, AppelProjet ap, Comite c
					WHERE p.comite = c.id AND c.id=ap.comiteresp";
				$result = pg_query($connect, $query);
		}		

		echo "Consulter les informations concernant une proposition:";		
		echo "<FORM METHOD='POST' ACTION='proposition-view.php'>";			
		echo "Liste des propositions:";
		echo "<select name='menuChoixProposition' size='1'>";				
		while ($row = pg_fetch_array($result, NULL, PGSQL_NUM)) 				
		{
			echo "<option value='$row[0]'>$row[1]</option>";				
		}
		echo "</select>";
		echo "</br>";
		echo "<input type='submit' value='Consulter'>";
		echo "</br>";		
		echo "</FORM>";

		echo "<br/>";
		echo "<br/>";				

		pg_result_seek($result, 0);
		
		echo "Modifier une proposition:";
		echo "<FORM METHOD='POST' ACTION='proposition-update.php'>";			
		echo "Liste des propositions:";
		echo "<select name='menuChoixProposition' size='1'>";
		while ($row = pg_fetch_array($result, NULL, PGSQL_NUM)) 				
		{
			echo "<option value='$row[0]'>$row[1]</option>";				
		}
		echo "</select>";			
		echo "</br>";
		echo "<input type='submit' value='Modifier'>";
		echo "</br>";
		echo "</FORM>";

		echo "<br/>";
		echo "<br/>";
		
		pg_result_seek($result, 0);
		
		echo "Supprimer une proposition:";
		echo "<FORM METHOD='POST' ACTION='proposition-delete.php'>";
		echo "Liste des propositions:";
		echo "<select name='menuChoixProposition' size='1'>";
		while ($row = pg_fetch_array($result, NULL, PGSQL_NUM)) 				
		{
			echo "<option value='$row[0]'>$row[1]</option>";
		}
		echo "</select>";
		echo "</br>";
		echo "<input type='submit' value='Supprimer'>";
		echo "</br>";
		echo "</FORM>";	
	?>
	<p><a href="../HTML/accueil.html">Retour à l'accueil</a></p>
</body>
</html>

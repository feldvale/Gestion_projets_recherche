<html>
<head>
	<meta charset "UTF-8">
</head>
<body>
	<?php
		include "../../php/commonfunctions.php";
		include "../../php/global.php";
		include "../../php/connect.php";

		$connect = fConnect();
	
		$sujet=$_POST["new_sujet"];
		$dateEnvoi=$_POST["new_DateEnvoi"];
		$dateRep=$_POST["new_DateRep"];
		$budget=$_POST["new_Budget"];
		
		if (strtotime($dateEnvoi) > strtotime($dateRep))
		{
			echo '<body onLoad="alert(\'La date d\'envoi ne peut pas être supérieure à la date de réponse. Veuillez vérifier les données entrées\')">';
			echo '<meta http-equiv="refresh" content="0; URL=proposition-update.php">';
		}
		else
		{
			$query = "UPDATE proposition SET sujet ='$sujet', dateEnvoi = '$dateEnvoi', dateReponse='$dateReponse',budgetTot='$budget'
						WHERE $_POST['new_num'] = $proposition";
			
			$update = pg_query($connect, $query);
			if (!$update)
				echo"Erreur lors de la mise à jour des données !";
			echo"<br/>";
			echo"<a href='../HTML/accueil.html'>Retour à l'accueil</a>";
		}
	
	?>
</body>
</head>

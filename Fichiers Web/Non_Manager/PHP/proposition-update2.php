<html>
<head>	
	<meta charset="UTF-8">
</head>
<body>
	<p>test</p>	
	<?php	
		echo"test2";
		include "../../php/commonfunctions.php";		
		include "../../php/global.php";		
		include "../../php/connect.php";		
		
		$connect = fConnect();			
		$sujet=$_POST["new_sujet"];		
		$dateEnvoi=$_POST["new_DateEnvoi"];		
		$dateRep=$_POST["new_DateRep"];		
		$budget=$_POST["new_Budget"];	
		$new_num=$_POST["new_num"];
		/*if (strtotime($dateEnvoi) > strtotime($dateRep))		
		{			echo '<body onLoad="alert(\'La date d\'envoi ne peut pas être supérieure à la date de réponse. Veuillez vérifier les données entrées\')">';
		echo '<meta http-equiv="refresh" content="0; URL=proposition-update.php">';		}
		else
		{*/
			$query = "UPDATE proposition SET dateEnvoi = '$dateEnvoi', dateReponse='$dateReponse',budgetTot='$budget'
						WHERE '$new_num' = numero";
			$query2 = "UPDATE appelprojet SET theme = '$sujet'
						WHERE '$new_num' = proposition.numero AND proposition.comite = appelprojet.comiteresp";
			
			$update = pg_query($connect, $query);
			$update2 = pg_query($connect, $query2);
			if (!$update || !$update2)
				echo"Erreur lors de la mise à jour des données !";
			echo"<br/>";
	//	}
	?>	
	<p><a href="../HTML/accueil.html">Retour à l'accueil</a></p>
</body>
</html>

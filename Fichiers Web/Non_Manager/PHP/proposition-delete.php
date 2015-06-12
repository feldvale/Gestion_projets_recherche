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

		$proposition = $_POST["menuChoixProposition"];
		
		$query = "DELETE * FROM proposition WHERE numero = $proposition";
		$result = pg_query($connect, $query);
		if (!result)
			echo"Erreur lors de la suppression";
		echo"<br/>";
		echo"<a href='../HTML/accueil.html'>Retour à l'accueil</a>";
	
	?>
</body>
</head>

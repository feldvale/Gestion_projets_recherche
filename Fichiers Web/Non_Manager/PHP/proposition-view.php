<html>
<head>
	<meta charset="UTF-8">
</head>
<body>
	<?php
		include "../../php/commonfunctions.php";
		include "../../php/global.php";
		include "../../php/connect.php";

		$connect = fConnect();

		
		
		if(isset($_POST["menuChoixProposition"]))
		{
			$proposition = $_POST["menuChoixProposition"];

			$query = "SELECT * FROM proposition WHERE numero = $proposition";
			$result = pg_query($connect, $query);
	
	
			echo"<TABLE border='1'>";
			echo"<TR>";
			echo"<TD>Sujet</TD>	<TD>Date d'envoi</TD>	<TD>Date de reponse</TD>	<TD>Budget</TD>";
			echo"</TR>";
			while($row = pg_fetch_array($result, NULL, PGSQL_NUM))
			{
				echo"<TR>";
				echo"<TD>$row[1]</TD>	<TD>$row[3]</TD>	<TD>$row[4]</TD>	<TD>$row[6]</TD>";
				echo"</TR>";
			};
			echo"</TABLE>";
			echo"<br/>";
			echo"<br/>";
		}
		else
		{
			echo '<body onLoad="alert(\'Erreur. Retour au menu Proposition\')">';
			echo '<meta http-equiv="refresh" content="0; URL=../HTML/proposition.html">';
		}
		echo"<a href='../HTML/accueil.html'>Retour à l'accueil</a>";
	
	
	?>
</body>
</html>

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

		$proposition = $_POST["menuChoixProposition"];
		
		$query = "SELECT * FROM proposition WHERE numero = $proposition";
		$result = pg_query($connect, $query);
	
		
		echo"<TABLE border='1'>";
		echo"<TR>";
		echo"<TD>Sujet</TD>	<TD>Date d'envoi</TD>	<TD>Date de reponse</TD>	<TD>Budget</TD>";
		echo"</TR>";
		while($row = pg_fetch_array($result, PGSQL_NUM))
		{
			echo"<TR>";
			echo"<TD>$row[1]</TD>	<TD>$row[4]</TD>	<TD>$row[5]</TD>	<TD>$row[7]</TD>";
			echo"</TR>";
		};
		echo"</TABLE>";
		echo"<br/>";
		echo"<br/>";
		echo"<a href='../HTML/accueil.html'>Retour à l'accueil</a>";
	
	
	?>
</body>
</head>

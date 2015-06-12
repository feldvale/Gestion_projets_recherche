<html>
<head>
	<title>Laboratoires Externes</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>
	<h1>Liste des laboratoire externes</h1><br><br>
		
	<p><table border="1">
		<tr>
			<th width="100pt">Nom</th>
			<th width="100pt">Date de début</th>
			<th width="100pt">Date de fin</th>
		</tr>
<?php
	//Connexion à la BDD
	include "../../php/connect.php";	
	$vConn=fConnect();
	
	$vNom=$_POST['filtreNom'];
	$vDateC=$_POST['filtreDateD'];
	$vDateF=$_POST['filtredateF'];

	
	if(!empty($vNom))	
		$vCondLancement = "nom= '$vNom' AND";
	else
		$vCondLancement = "";
	
	
	if(!empty($vDateC))	
		$vCondFin = "dateDebut = '$vDateC' AND ";
	else
		$vCondFin = "";

	if(!empty($vDateF))	
		$vCondOrga = "dateFin = '$vDateF' AND ";
	else
		$vCondOrga = "";


	$vSql = "SELECT * FROM Financeur f WHERE $vCondTheme $vCondLancement $vCondFin 1=1";
	
	$vQuery=pg_query($vConn, $vSql);


	while($vResult=pg_fetch_array($vQuery)){
		echo"<tr>";
		echo"<td>$vResult[nom]</td>";
		echo"<td>$vResult[dateDebut]</td>";
		echo"<td>$vResult[dateFin]</td>";
		echo"</tr>";
	}
?>
	</table></p>
	<hr/>
	<a href="../HTML/LaboratoireExt.html">Retour à la section laboratoire externe</a>
	<p><a href="../HTML/AccueilManager.html">Retour à l'accueil</a></p>
</body>
</html>

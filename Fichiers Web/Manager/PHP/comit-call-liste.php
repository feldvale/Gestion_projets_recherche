<html>
<head>
	<title>Liste des comites</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>
	<h1>Liste des comites</h1><br><br>
		
	<p><table border="1">
		<tr>
			<th width="100pt">Identifiant</th>
			<th width="100pt">Nom</th>
			<th width="100pt">Nom organisme projet</th>
			<th width="100pt">Date creation organisme projet</th>
		</tr>
<?php
	//Connexion à la BDD
	include "../../php/connect.php";	
	$vConn=fConnect();
	
	$vId = $_POST['filtreId'];
	$vNom = $_POST['filtreNom'];
	$vNomOrga = $_POST['filtreNomOrga'];
	$vDateOrga = $_POST['filtreDateOrga'];

	if(!empty($vId))	
		$vCondTheme = "id= '$vId' AND ";
	else
		$vCondTheme = "";
	
	if(!empty($vNom))	
		$vCondLancement = "nom= '$vNom' AND";
	else
		$vCondLancement = "";
	
	
	if(!empty($vNomOrga))	
		$vCondFin = "nomOrga = '$vNomOrga' AND ";
	else
		$vCondFin = "";

	if(!empty($vDateOrga))	
		$vCondOrga = "dateOrga = to_date('$vDateOrga','DD/MM/YYYY') AND ";
	else
		$vCondOrga = "";
	

	$vSql = "SELECT * FROM Comite c WHERE $vCondTheme $vCondLancement $vCondFin $vCondOrga 1=1";
	
	$vQuery=pg_query($vConn, $vSql);


	while($vResult=pg_fetch_array($vQuery)){
		echo"<tr>";
		echo"<td>$vResult[id]</td>";
		echo"<td>$vResult[nom]</td>";
		echo"<td>$vResult[nomorga]</td>";
		echo"<td>$vResult[dateorga]</td>";
		echo"</tr>";
	}
?>
	</table></p>
	<hr/>
	<a href="../HTML/OrganismeProjet.html">Retour aux Entites juridiques </a>
	<p><a href="../HTML/accueil.html">Retour à l'accueil</a></p>
</body>
</html>

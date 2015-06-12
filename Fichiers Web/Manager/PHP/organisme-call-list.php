<html>
<head>
	<title>Liste des organismes</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>
	<h1>Liste des organismes</h1><br><br>
		
	<p><table border="1">
		<tr>
			<th width="100pt">Nom</th>
			<th width="100pt">Date de Lancement</th>
			<th width="100pt">Date de Fin</th>
		</tr>
<?php
	//Connexion à la BDD
	include "../../php/connect.php";	
	$vConn=fConnect();
	
	$vTheme = $_POST['filtreNom'];
	$vStart = $_POST['filtreDateC'];
	$vEnd = $_POST['filtreDateF'];

	if(!empty($vTheme))	
		$vCondTheme = "nom = '$vTheme' AND ";
	else
		$vCondTheme = "";
	
	if(!empty($vStart))	
		$vCondLancement = "filtreDateC = to_date('$vStart','DD/MM/YYYY') AND";
	else
		$vCondLancement = "";
	
	
	if(!empty($vEnd))	
		$vCondFin = "filtreDateF = to_date('$vEnd','DD/MM/YYYY') AND ";
	else
		$vCondFin = "";
	

	$vSql = "SELECT nom, dateCreation, dateFin FROM OrganismeProjet WHERE $vCondLancement $vCondFin $vCondTheme 1=1";
	$vQuery=pg_query($vConn, $vSql);
	
	

	while($vResult=pg_fetch_array($vQuery)){
		echo"<tr>";
		echo"<td>$vResult[nom]</td>";
		echo"<td>$vResult[datecreation]</td>";
		echo"<td>$vResult[datefin]</td>";
		echo"</tr>";
	}
?>
	</table></p>
	<hr/>
	<a href="../HTML/OrganismeProjet.html">Retour aux Entites juridiques </a>
	<p><a href="../HTML/accueil.html">Retour à l'accueil</a></p>
</body>
</html>

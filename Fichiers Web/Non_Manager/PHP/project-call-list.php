<html>
<head>
	<title>Appel à projet</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>
	<h1>Appels à Projet</h1><br><br>
		
	<p><table border="1">
		<tr>
			<th width="100pt">Identifiant</th>
			<th width="100pt">Date de Lancement</th>
			<th width="100pt">Date de Fin</th>
			<th width="150pt">Thème</th>
			<th width="500pt">Description</th>
		</tr>
<?php
	//Connexion à la BDD
	include "../../php/connect.php";	
	$vConn=fConnect();
	
	$vTheme = $_POST['filtreTheme'];
	$vStart = $_POST['dateLancement'];
	$vEnd = $_POST['dateFin'];
	
	if(!empty($vStart))	
		$vCondLancement = "datelancement = to_date('$vStart','DD/MM/YYYY') AND";
	else
		$vCondLancement = "";
	
	if(!empty($vTheme))	
		$vCondTheme = "theme = '$vTheme' AND ";
	else
		$vCondTheme = "";
	
	if(!empty($vEnd))	
		$vCondFin = "datefin = to_date('$vEnd','DD/MM/YYYY') AND ";
	else
		$vCondFin = "";
	
	//Recuperation de la table AppelProjet : id, deb, fin, theme, desc
	$vSql = "SELECT id, datelancement, datefin, theme, description	FROM AppelProjet WHERE $vCondLancement $vCondFin $vCondTheme 1=1";
	$vQuery=pg_query($vConn, $vSql);
	
	
	// Affichage des champs de la table
	while($vResult=pg_fetch_array($vQuery)){
		echo"<tr>";
		echo"<td>$vResult[id]</td>";
		echo"<td>$vResult[datelancement]</td>";
		echo"<td>$vResult[datefin]</td>";
		echo"<td>$vResult[theme]</td>";
		echo"<td>$vResult[description]</tr>";
	}
?>
	</table></p>
	<hr/>
	<p><a href="../HTML/project-call.html">Retour à l'appel de projet</a></p>
	<p><a href="../HTML/accueil.html">Retour à l'accueil</a></p>
</body>
</html>

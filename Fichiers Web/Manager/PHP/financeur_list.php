<html>
<head>
	<title>Financeurs</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>
	<h1>Liste des financeurs</h1><br><br>
		
	<p><table border="1">
		<tr>
			<th width="100pt">Nom</th>
			<th width="100pt">Date de début</th>
			<th width="100pt">Date de fin</th>
			<th width="100pt">Employe contacte</th>
			<th width="100pt">Type de financeur</th>
		</tr>
<?php
	//Connexion à la BDD
	include "../../php/connect.php";	
	$vConn=fConnect();
	
	$vNom=$_POST['filtreNom'];
	$vDateC=$_POST['filtreDateD'];
	$vDateF=$_POST['filtredateF'];
	$vEmpC=$_POST['filtreemployeC'];
	$vType=$_POST['filtretype'];


	
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
	
	if(!empty($vEmpC))	
		$vCondTheme = "employeContact= '$vEmpC' AND ";
	else
		$vCondTheme = "";

	if(!empty($vType))	
		$vCondEmp = "type= '$vType' AND ";
	else
		$vCondEmp = "";
	

	$vSql = "SELECT * FROM Financeur f WHERE $vCondTheme $vCondLancement $vCondFin $vCondOrga $vCondEmp 1=1";
	
	$vQuery=pg_query($vConn, $vSql);


	while($vResult=pg_fetch_array($vQuery)){
		echo"<tr>";
		echo"<td>$vResult[nom]</td>";
		echo"<td>$vResult[dateDebut]</td>";
		echo"<td>$vResult[dateFin]</td>";
		echo"<td>$vResult[employeContact]</td>";
		echo"<td>$vResult[type]</td>";
		echo"</tr>";
	}
?>
	</table></p>
	<hr/>
	<a href="../HTML/financeur.html">Retour à la section financeurs</a>
	<p><a href="../HTML/AccueilManager.html">Retour à l'accueil</a></p>
</body>
</html>

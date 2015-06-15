<html>
<head>
	<title>Dépenses des établissements</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>
	<h1>Moyenne des dépenses par etablissement et par proposition </h1><br>
	
	<p><table border="1">
		<tr>
			<th width="150pt">Etablissement</th>
			<th width="200pt">Numéro de la proposition</th>
			<th width="100pt">Moyenne des dépenses</th>
		</tr>
<?php
	//Connexion à la BDD
	include "../../php/connect.php";	
	$vConn=fConnect();
		
	
	$vSql = "SELECT EC.nomEtablissement AS J, P.numero AS G, avg(Sq.somme) AS H
FROM Proposition P, MembreInterne MI, RedactionProposition RP, EnseignantChercheur EC, (SELECT Sum(L.montant) AS somme FROM LigneBudgetaire L GROUP BY L.numeroB) AS Sq
WHERE RP.numero = P.numero AND RP.mail = MI.mailMembre AND EC.mailMembre = MI.mailMembre
GROUP BY EC.nomEtablissement, P.numero ;";	
	$vQuery=pg_query($vConn, $vSql);
	
	// Affichage des champs de la table
	while($vResult=pg_fetch_array($vQuery)){
		echo"<tr>";
		echo"<td>$vResult[j]</td>";
		echo"<td>$vResult[g]</td>";
		echo"<td>$vResult[h]</td>";
	}
?>
	</table></p>
	
	
	<hr/>
	<p><a href="../HTML/AccueilManager.html">Retour à l'accueil</a></p>
</body>
</html>
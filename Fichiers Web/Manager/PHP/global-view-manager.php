<html>
<head>
	<title>Vue globale</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>
	<h1>Vue globale</h1><br>
	
	<h2>Financeurs et Organismes</h2>
	<p><table border="1">
		<tr>
			<th width="150pt">Nom du financeur</th>
			<th width="200pt">Employé Contact : mail</th>
			<th width="100pt">Titre</th>
			<th width="150pt">Telephone</th>
			<th width="200pt">Nom de l'organisme projet</th>
		</tr>
<?php
	//Connexion à la BDD
	include "../../php/connect.php";	
	$vConn=fConnect();
		
	
	$vSql = "SELECT F.nom as nom_financeur, EC.mail as employe_mail, EC.titre as employe_titre, EC.telephone as employe_tel, OP.nom as nom_org
FROM OrganismeProjet OP, Financeur F, CreationOrganismeProjet COP, EmployeContact EC
WHERE F.employeContact = EC.mail AND COP.nomOrganisme = OP.nom AND COP.dateCreation = OP.dateCreation
	AND COP.idFinanceur = F.id
ORDER BY F.nom;";	
	$vQuery=pg_query($vConn, $vSql);
	
	// Affichage des champs de la table
	while($vResult=pg_fetch_array($vQuery)){
		echo"<tr>";
		echo"<td>$vResult[nom_financeur]</td>";
		echo"<td>$vResult[employe_mail]</td>";
		echo"<td>$vResult[employe_titre]</td>";
		echo"<td>$vResult[employe_tel]</td>";
		echo"<td>$vResult[nom_org]</tr>";
	}
?>
	</table></p>
	
	<br><h2>Organismes et appels à projet</h2>
	<p><table border="1">
		<tr>
			<th width="100pt">Organisme de projet</th>
			<th width="100pt">Comité responsable</th>
			<th width="150pt">Thème de l'appel à projet</th>
			<th width="150pt">Date de lancement</th>
			<th width="100pt">Date de fin</th>
			<th width="500pt">Description</th>
		</tr>
<?php
	//Connexion à la BDD
	
	$vSql = "SELECT OP.nom as nom_org, C.nom as comite, AP.theme as theme, AP.dateLancement as lancement, AP.dateFin as fin, AP.description AS desc
			FROM OrganismeProjet OP, Comite C, AppelProjet AP
			WHERE OP.nom = C.nomOrga AND OP.dateCreation = C.dateOrga AND AP.comiteResp = C.id
			ORDER BY OP.nom, OP.dateCreation, C.nom;";
	$vQuery=pg_query($vConn, $vSql);
	
	
	// Affichage des champs de la table
	while($vResult=pg_fetch_array($vQuery)){
		echo"<tr>";
		echo"<td>$vResult[nom_org]</td>";
		echo"<td>$vResult[comite]</td>";
		echo"<td>$vResult[theme]</td>";
		echo"<td>$vResult[lancement]</td>";
		echo"<td>$vResult[fin]</td>";
		echo"<td>$vResult[desc]</td>";
	}
?>
	</table></p>
	
	
	<br><h2>Propositions, Projets, Dépenses</h2>
	<p><table border="1">
		<tr>
			<th width="150pt">Theme de l'appel à projet</th>
			<th width="400pt">Description de l'appel</th>
			<th width="100pt">Date d'envoie de la proposition</th>
			<th width="150pt">Date de réponse</th>
			<th width="100pt">Réponse</th>
			<th width="400pt">Description de la proposition</th>
			<th width="100pt">Date de début du projet</th>
			<th width="100pt">Date de fin</th>
			<th width="100pt">Nombre de dépenses éffectuées</th>
		</tr>
<?php
	//Connexion à la BDD
	
	$vSql = "SELECT SR3.theme as theme, SR3.app_descr as app_descr, SR3.envoie as envoie, SR3.date_reponse as date_reponse, SR3.etat as etat,
				SR3.prop_descr AS prop_descr, SR3.debut as proj_debut, SR3.fin as proj_fin, SR4.nb_depense as nb_depense
			FROM
			(
				SELECT SR2.theme as theme, SR2.app_descr as app_descr, SR2.envoie as envoie, SR2.date_reponse as date_reponse, SR2.etat as etat, SR2.prop_descr AS prop_descr,
				Proj.nom as nom, Proj.dateDebut as debut, Proj.dateFin as fin
				FROM
				(	
					SELECT SR1.theme as theme, SR1.descr as app_descr, Prop.dateEnvoi as envoie, Prop.dateReponse as date_reponse, Prop.etatReponse as etat,
					Prop.description AS prop_descr, Prop.numero as numero
					FROM
					(
						SELECT AP.theme as theme, AP.description as descr, C.id as id
						FROM Comite C, AppelProjet AP
						WHERE AP.comiteResp = C.id
					) SR1 LEFT OUTER JOIN Proposition Prop ON SR1.id = Prop.comite
				) SR2
					LEFT OUTER JOIN Projet Proj ON Proj.proposition = SR2.numero
			) SR3 LEFT OUTER JOIN
			(
				SELECT Proj.nom as nom, Proj.dateDebut as debut, count(D.id) as nb_depense
				FROM Projet Proj, Depense D 
				WHERE Proj.nom = D.nomP AND Proj.dateDebut = D.dateP
				GROUP BY Proj.nom, Proj.dateDebut
			) SR4 ON SR3.nom = SR4.nom AND SR3.debut = SR4.debut
			ORDER BY SR3.theme;";
	$vQuery=pg_query($vConn, $vSql);
	
	
	// Affichage des champs de la table
	while($vResult=pg_fetch_array($vQuery)){
		echo"<tr>";
		echo"<td>$vResult[theme]</td>";
		echo"<td>$vResult[app_descr]</td>";
		echo"<td>$vResult[envoie]</td>";
		echo"<td>$vResult[date_reponse]</td>";
		if($vResult['etat'])
			echo"<td>Validé</td>";
		else
			echo"<td>Rejeté</td>";
		echo"<td>$vResult[prop_descr]</td>";
		echo"<td>$vResult[proj_debut]</td>";
		echo"<td>$vResult[proj_fin]</td>";
		echo"<td>$vResult[nb_depense]</td>";
	}
?>
	</table></p>
	

	<br><h2>Moyenne du total des dépenses des projets pour chaque organisme</h2>
	<p><table border="1">
		<tr>
			<th width="100pt">Nom de l'organisme</th>
			<th width="100pt">Date de création de l'organisme</th>
			<th width="200pt">Moyenne de ses dépenses total de chaque projet</th>
		</tr>
<?php
	//Connexion à la BDD
	
	$vSql = "SELECT OP.nom as nom, OP.dateCreation as creation, avg(SR.somme) AS moyenne
			FROM OrganismeProjet OP, Comite C, Proposition Prop, Projet proj,
				(SELECT sum(D.montant) as somme
					FROM Projet P, Depense D 
					WHERE P.nom = D.nomP AND P.dateDebut = D.dateP
					GROUP BY P.nom, P.dateDebut) SR
			WHERE OP.nom = C.nomOrga AND OP.dateCreation = C.dateOrga AND C.id = Prop.comite AND
				Proj.proposition = Prop.numero AND OP.dateCreation >= to_date('01/01/1990','DD/MM/YYYY')
			GROUP BY  OP.nom, OP.dateCreation;";
	$vQuery=pg_query($vConn, $vSql);
	
	
	// Affichage des champs de la table
	while($vResult=pg_fetch_array($vQuery)){
		echo"<tr>";
		echo"<td>$vResult[nom]</td>";
		echo"<td>$vResult[creation]</td>";
		echo"<td>$vResult[moyenne]</td>";
	}
?>
	</table></p>
	
	
	
	<hr/>
	<p><a href="../HTML/AccueilManager.html">Retour à l'accueil</a></p>
</body>
</html>

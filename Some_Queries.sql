
/* Affichage des différentes entités : financeurs, organismes projet ... */
SELECT F.nom as nom_financeur, EC.mail as employe_mail, EC.titre as employe_titre, EC.telephone as employe_tel, OP.nom as nom_org
FROM OrganismeProjet OP, Financeur F, CreationOrganismeProjet COP, EmployeContact EC
WHERE F.employeContact = EC.mail AND COP.nomOrganisme = OP.nom AND COP.dateCreation = OP.dateCreation
	AND COP.idFinanceur = F.id
ORDER BY F.nom;


/* Affichage des appels à projet liés aux organismes */
SELECT OP.nom as nom_org, C.nom as comite, AP.theme as theme, AP.dateLancement as lancement, AP.dateFin as fin, AP.description AS desc
FROM OrganismeProjet OP, Comite C, AppelProjet AP
WHERE OP.nom = C.nomOrga AND OP.dateCreation = C.dateOrga AND AP.comiteResp = C.id
ORDER BY OP.nom, OP.dateCreation, C.nom;


/* Affiche l'ensemble des appels à projet, avec les propositions, les projets et le nombre de dépenses effectuées au cours du projet */
SELECT SR3.theme as theme, SR3.app_descr as app_descr, SR3.envoie as envoie, SR3.date_reponse as date_reponse, SR3.etat as etat,
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
ORDER BY SR3.theme;


/* Requete complexe qui revnoie la moyenne du total des dépenses de chaque projet lié aux organismes */
SELECT OP.nom as nom, OP.dateCreation as creation, avg(SR.somme) AS moyenne
FROM OrganismeProjet OP, Comite C, Proposition Prop, Projet proj,
	(SELECT sum(D.montant) as somme
		FROM Projet P, Depense D 
		WHERE P.nom = D.nomP AND P.dateDebut = D.dateP
		GROUP BY P.nom, P.dateDebut) SR
WHERE OP.nom = C.nomOrga AND OP.dateCreation = C.dateOrga AND C.id = Prop.comite AND
	Proj.proposition = Prop.numero AND OP.dateCreation >= to_date('01/01/1990','DD/MM/YYYY')
GROUP BY  OP.nom, OP.dateCreation;

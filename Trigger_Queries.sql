			/***********************/
			/* REQUETES COMPLEXES */
			/***********************/

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


SELECT EC.nomEtablissement AS J, P.numero AS G, avg(Sq.somme) AS H
FROM Proposition P, MembreInterne MI, RedactionProposition RP, EnseignantChercheur EC, (SELECT Sum(L.montant) AS somme FROM LigneBudgetaire L GROUP BY L.numeroB) AS Sq
WHERE RP.numero = P.numero AND RP.mail = MI.mailMembre AND EC.mailMembre = MI.mailMembre
GROUP BY EC.nomEtablissement, P.numero ;


				/***********/
				/* TRIGGER */
				/***********/


/* TRIGGER BUDGET TOTAL*/


CREATE OR REPLACE FUNCTION modifier_budget_total() RETURNS TRIGGER AS $budgetTotal$
	DECLARE budget NUMERIC;
	DECLARE numProp INTEGER;
	BEGIN
		IF(TG_OP = 'DELETE') THEN
			numProp = OLD.numerob;
		ELSE
			numProp = NEW.numerob;
		END IF;
		
		SELECT sum(LB.montant) INTO budget
		FROM LigneBudgetaire LB
		WHERE LB.numerob = numProp;
		
		UPDATE Proposition SET budgettot = budget
		WHERE numero = numProp;
		
		RETURN NULL;
	END;
$budgetTotal$ LANGUAGE plpgsql;
	
DROP TRIGGER IF EXISTS budgetTotal ON lignebudgetaire;

CREATE TRIGGER budgetTotal
	AFTER INSERT OR DELETE OR UPDATE
	ON lignebudgetaire
	FOR EACH ROW
	EXECUTE PROCEDURE modifier_budget_total();



/* TRIGGER BUDGET RESTANT */

CREATE OR REPLACE FUNCTION modifier_budget_restant() RETURNS TRIGGER AS $budgetRestant$
	DECLARE budgetRest NUMERIC;
	DECLARE nomProj VARCHAR(30);
	DECLARE dateProj DATE;
	DECLARE proj RECORD;
	DECLARE trigg_rec RECORD;
	BEGIN
		IF(TG_OP = 'DELETE') THEN
				trigg_rec := OLD;
		ELSE
				trigg_rec := NEW;
		END IF;
		IF(TG_NAME = 'newbudgetrestantdep') THEN
			nomProj = trigg_rec.nomP;
			dateProj = trigg_rec.dateP;
		ELSE
			SELECT nom, dateDebut INTO proj
			FROM PROJET
			WHERE proposition = trigg_rec.numero;
			nomProj = proj.nom;
			dateProj = proj.dateDebut;
		END IF;
		
		SELECT Prop.BudgetTot - sum(D.montant) INTO budgetRest
			FROM Proposition Prop, Projet TProj, Depense D
			WHERE TProj.nom = D.nomP AND TProj.dateDebut = d.dateP AND
			TProj.nom = nomProj AND TProj.dateDebut = dateProj AND
			TProj.proposition = Prop.numero
			GROUP BY Prop.BudgetTot;
		
		UPDATE Projet SET budgetRestant = budgetRest
		WHERE nom = nomProj AND dateDebut = dateProj;
		
		RETURN NULL;
		
	END;
$budgetRestant$ LANGUAGE plpgsql;
	
DROP TRIGGER IF EXISTS NewBudgetRestantProp ON Proposition;
CREATE TRIGGER NewBudgetRestantProp 
	AFTER INSERT OR DELETE OR UPDATE
	ON Proposition
	FOR EACH ROW
	EXECUTE PROCEDURE modifier_budget_restant();


DROP TRIGGER IF EXISTS NewBudgetRestantDep ON Depense;
CREATE TRIGGER NewBudgetRestantDep 
	AFTER INSERT OR DELETE OR UPDATE
	ON Depense
	FOR EACH ROW
	EXECUTE PROCEDURE modifier_budget_restant();



/* TRIGGER CREATIION DE PROJET*/

CREATE OR REPLACE FUNCTION creer_projet() RETURNS TRIGGER AS $$
DECLARE nomProj VARCHAR(30);
	BEGIN
		SELECT theme INTO nomProj
			FROM AppelProjet AP
			WHERE NEW.numero = AP.id;
		IF(TG_OP = 'INSERT' AND NEW.etatReponse = true) THEN
			INSERT INTO Projet (nom, dateDebut, dateFin, proposition)
			VALUES (nomProj, current_date, current_date, NEW.numero);
			
		ELSIF(NEW.etatReponse = true AND OLD.etatReponse = false) THEN		
			INSERT INTO Projet (nom, dateDebut, dateFin, proposition)
			VALUES (nomProj, current_date, current_date, NEW.numero);
		END IF;
		
		RETURN NULL;
		
	END;
$$ LANGUAGE plpgsql;
	
DROP TRIGGER IF EXISTS NewProject ON Proposition;

CREATE TRIGGER NewProject
	AFTER INSERT OR UPDATE ON Proposition
	FOR EACH ROW
	EXECUTE PROCEDURE creer_projet();
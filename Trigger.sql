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
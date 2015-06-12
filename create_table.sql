DROP TABLE RedactionProposition;
DROP TABLE CreationOrganismeProjet;
DROP TABLE Depense;
DROP TABLE Doctorant;
DROP TABLE IngenieurRecherche;
DROP TABLE EnseignantChercheur;
DROP TABLE MembreInterne;
DROP TABLE MembreExterne;
DROP TABLE Membre;
DROP TABLE Projet;
DROP TABLE Financeur;
DROP TABLE EmployeContact;
DROP TABLE LaboratoireExterne;
DROP TABLE Lignebudgetaire;
DROP TABLE Proposition;
DROP TABLE AppelProjet;
DROP TABLE Comite;
DROP TABLE OrganismeProjet;


create table OrganismeProjet (
nom varchar(30),
dateCreation date,
dateFin date,
Primary key(nom,dateCreation),
check (dateCreation <= dateFin)
);

create table Comite (
id integer primary key,
nom varchar(30),
nomOrga varchar(30) not null,
dateOrga date not null,
foreign key (dateOrga,nomOrga) references OrganismeProjet(dateCreation,nom)
);

create table AppelProjet (
id integer primary key,
dateLancement date,
dateFin date,
theme varchar(30),
description varchar(70),
comiteResp integer NOT NULL,
foreign key (comiteResp) references Comite(id),
check (dateLancement <= dateFin)
);

create table Proposition (
numero integer primary key,
description varchar(70),
comite integer not null,
dateEnvoi date,
dateReponse date,
etatReponse boolean,
BudgetTot float,
foreign key (comite) references Comite(id),
check (dateEnvoi <= dateReponse)
);


create table Lignebudgetaire (
numeroB integer,
objet varchar(30),
montant float not null,
type varchar(30),
primary key (objet, numeroB),
foreign key (numeroB) references Proposition(numero),
check (type in ('fonctionnement','materiel'))
);

create table LaboratoireExterne (
id integer primary key,
nom varchar(30) not null,
dateDebut date not null,
dateFin date,
Unique (nom, dateDebut),
check (dateDebut <= dateFin)
);


create table EmployeContact (
mail varchar(30) primary key,
titre varchar(30),
telephone varchar(10)
);

create table Financeur (
id integer primary key,
nom varchar(30) not null,
dateDebut date not null,
dateFin date,
employeContact varchar(30) Unique not null,
type char(1),
check (type in ('E','P','R','V','O')),
foreign key (employeContact) references EmployeContact(mail),
Unique (nom, dateDebut),
check (dateDebut <= dateFin)
);

create table Projet (
nom varchar(30),
dateDebut date,
dateFin date not null, 
proposition integer,
budgetRestant float, 
primary key (nom, dateDebut),
termine boolean,
foreign key (proposition) references Proposition(numero),
check (dateDebut <= dateFin)
);

create table Membre (
mail varchar(30) primary key,
nom varchar(30) not null,
prenom varchar(30)not null,
fonction varchar(30),
validateur boolean not null,
projet varchar(30),
dateProjet date,
foreign key (projet,dateProjet) references Projet(nom,dateDebut)
);

create table MembreExterne (
mailMembre varchar(30) primary key,
idLaboratoire integer,
idFinanceur integer,
foreign key (idLaboratoire) references LaboratoireExterne(id),
foreign key (idFinanceur) references Financeur(id),
foreign key (mailMembre) references Membre(mail),
check (( idLaboratoire is null) != (idFinanceur is null))
);

create table MembreInterne (
mailMembre varchar(30) primary key,
foreign key (mailMembre) references Membre(mail)
);

create table EnseignantChercheur (
mailmembre varchar(30) primary key,
quotite float,
nomEtablissement varchar(30),
foreign key (mailmembre) references MembreInterne(mailMembre)
);

create table IngenieurRecherche (
mailmembre varchar(30) primary key,
domaine varchar(30),
foreign key (mailmembre) references MembreInterne(mailMembre)
);

create table Doctorant (
mailmembre varchar(30) primary key,
sujetThese varchar(30),
dateDebutThese date not null,
dateFinThese date, 
foreign key (mailMembre) references MembreInterne(mailMembre),
check (dateDebutThese <= dateFinThese)
);


create table Depense (
id integer primary key, 
dateDepense date, 
montant float, 
type varchar(30)not null,
membreDemandeur varchar(30)not null,
membreValidateur varchar(30)not null,
nomP Varchar (20) not null,
dateP date not null, 
foreign key (membreDemandeur) references Membre(mail),
foreign key (membreValidateur) references Membre(mail),
foreign key (nomP,dateP) references Projet(nom,dateDebut),
check (type in ('fonctionnement','materiel')),
check (membreDemandeur <> membreValidateur),
Check ((nomP is not null) or (dateP is not null))
);

create table CreationOrganismeProjet (
nomOrganisme varchar(30),
dateCreation date, 
idFinanceur integer,
primary key(nomOrganisme, dateCreation, idFinanceur),
foreign key (nomOrganisme,dateCreation) references OrganismeProjet(nom,dateCreation),
foreign key (idFinanceur) references Financeur(id)
);


create table RedactionProposition (
mail varchar(30),
numero integer,
primary key(mail, numero),
foreign key (mail) references MembreInterne(mailMembre),
foreign key (numero) references Proposition(numero)
);




CREATE VIEW MembreInterne(mailMembre, nom, prenom, fonction, validateur) AS
        SELECT MI.mailMembre, M.nom, M.prenom, M.fonction, M.validateur, M.projet, M.dateProjet
        FROM MembreInterne MI, Membre M
        WHERE MI.mailMembre = M.mail;
        
CREATE VIEW MembreExterne(mail, idLaboratoire, idFinanceur, nom, prenom, fonction, validateur) AS
        SELECT ME.mailMembre, ME.idLaboratoire, ME.idFinanceur, M.nom, M.prenom, M.fonction, M.validateur
        FROM MembreExterne ME, Membre M
        WHERE ME.mailMembre = M.mail;
        
CREATE VIEW EnseignantChercheur (mailmembre, quotite, projet, dateprojet, nomEtablissement, fonction, validateur, nom, prenom) AS
        SELECT EC.mailmembre, EC.quotite, M.projet, M.dateprojet, EC.nomEtablissement, M.fonction, M.validateur, M.nom, M.prenom
        FROM EnseignantChercheur EC, Membre M, MembreInterne MI
        WHERE EC.mailmembre = MI.mailMembre AND MI.mailMembre = M.mail;
        
CREATE VIEW Doctorant (mailmembre, sujetThese, dateDebutThese, dateFinThese, projet, dateprojet, fonction, validateur, nom, prenom) AS
        SELECT D.mailmembre, D.sujetThese,D.dateDebutThese,D.dateFinThese, M.projet, M.dateprojet, M.fonction, M.validateur, M.nom, M.prenom
        FROM Doctorant D, Membre M, MembreInterne MI
        WHERE D.mailmembre = MI.mailMembre AND MI.mailMembre = M.mail;
        
CREATE VIEW IngenieurRecherche (mailmembre, domaine, projet, dateprojet, fonction, validateur, nom, prenom) AS
        SELECT IR.mailMembre, IR.domaine, M.projet, M.dateprojet, M.fonction, M.validateur, M.nom, M.prenom
        FROM IngenieurRecherche IR, Membre M, MembreInterne MI
        WHERE IR.mailmembre = MI.mailMembre AND MI.mailMembre = M.mail;
        
        
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

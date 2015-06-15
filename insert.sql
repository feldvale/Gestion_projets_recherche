/* Pour éviter la violation des contraintes de clé primaires on supprimer toutes les données en premier */
DELETE  FROM RedactionProposition;
DELETE  FROM CreationOrganismeProjet;
DELETE  FROM Depense;
DELETE  FROM Doctorant;
DELETE  FROM IngenieurRecherche;
DELETE  FROM EnseignantChercheur;
DELETE  FROM MembreExterne;
DELETE  FROM MembreInterne;
DELETE  FROM Membre;
DELETE  FROM Projet;
DELETE  FROM Financeur;
DELETE  FROM EmployeContact;
DELETE  FROM LaboratoireExterne;
DELETE  FROM Lignebudgetaire;
DELETE  FROM Proposition;
DELETE  FROM AppelProjet;
DELETE  FROM Comite;
DELETE  FROM OrganismeProjet;

DROP SEQUENCE IF EXISTS seqComite;
DROP SEQUENCE IF EXISTS seqAppelProjet;
DROP SEQUENCE IF EXISTS seqProposition;
DROP SEQUENCE IF EXISTS seqLaboExterne;
DROP SEQUENCE IF EXISTS seqFinanceur;
DROP SEQUENCE IF EXISTS seqDepense;

CREATE SEQUENCE seqComite START WITH 1;
CREATE SEQUENCE seqAppelProjet START WITH 1;
CREATE SEQUENCE seqProposition START WITH 1;
CREATE SEQUENCE seqLaboExterne START WITH 1;
CREATE SEQUENCE seqFinanceur START WITH 1;
CREATE SEQUENCE seqDepense START WITH 1;


INSERT INTO OrganismeProjet (nom, dateCreation, DateFin)
VALUES ('organisme1', '1998-04-12', '12-05-2020') ;

INSERT INTO OrganismeProjet (nom, dateCreation, DateFin)
VALUES ('organisme2', '2002-04-10', '10-05-2017') ;


INSERT INTO EmployeContact (mail, titre, telephone)
VALUES ('jerome.lerzec@gmail.com', 'Directeur Partenariat', '0645454545');

INSERT INTO EmployeContact (mail, titre, telephone)
VALUES ('martine.challe@gmail.com', 'Assistante', '0606060606');

/* Ajout d'un appel à projet avec son comité responsable. Et insertion d'une proposition avec le projet réalisé par le trigger et ensuite les membres du projet*/

INSERT INTO Comite (id, nom, nomOrga, dateOrga)
VALUES (nextval('seqComite'),'Com1','organisme1','1998-04-12');


INSERT INTO AppelProjet (id, dateLancement, dateFin, theme, description, comiteResp)
VALUES (nextval('seqAppelProjet'), '10-04-1998', '2002-05-10', 'cheval', 'nouveau fer', currval('seqComite')); 

INSERT INTO Proposition (numero, description, comite, dateEnvoi, dateReponse, etatReponse, BudgetTot)
VALUES (nextval('seqProposition'),'Estampage', currval('seqAppelProjet'), '1998-05-10', '11-06-1998', true, NULL);


INSERT INTO Membre (mail, nom, prenom, fonction, validateur, projet, dateProjet)
VALUES ('tre@gmail.com', 'Sallit', 'Jean', 'incubateur', true,'cheval',current_date);

INSERT INTO Membre (mail, nom, prenom, fonction, validateur, projet, dateProjet)
VALUES ('tre1@gmail.com', 'Machou', 'Marguerite', 'incubateur', false,'cheval',current_date);

INSERT INTO Membre (mail, nom, prenom, fonction, validateur, projet, dateProjet)
VALUES ('tre2@gmail.com', 'Leclerc', 'Vivien', 'incubateur', false,'cheval',current_date);

INSERT INTO Membre (mail, nom, prenom, fonction, validateur, projet, dateProjet)
VALUES ('tre3@gmail.com', 'Ducornu', 'Fabrice', 'incubateur', true,'cheval',current_date);

INSERT INTO MembreInterne (mailMembre)
VALUES ('tre2@gmail.com');

INSERT INTO MembreInterne (mailMembre)
VALUES ('tre3@gmail.com');

INSERT INTO RedactionProposition (mail, numero)
VALUES ('tre3@gmail.com',currval('seqProposition'));

INSERT INTO Lignebudgetaire (numeroB, objet, montant, type)
VALUES (currval('seqProposition'),'Test d_échantillionage', 429.69, 'fonctionnement');


/*Deuxième série de comité....projet*/


INSERT INTO Comite (id, nom, nomOrga, dateOrga)
VALUES (nextval('seqComite'),'Com2','organisme1','1998-04-12');


INSERT INTO AppelProjet (id, dateLancement, dateFin, theme, description, comiteResp)
VALUES (nextval('seqAppelProjet'), '25-04-1998', '2004-05-10', 'Domotique', 'Création d un systeme de climatisation automatise', currval('seqComite'));

INSERT INTO Proposition (numero, description, comite, dateEnvoi, dateReponse, etatReponse, BudgetTot)
VALUES (nextval('seqProposition'),'Moulage', currval('seqAppelProjet'),'1998-05-01', '11-05-1998', true, NULL);

INSERT INTO Membre (mail, nom, prenom, fonction, validateur, projet, dateProjet)
VALUES ('tre4@gmail.com', 'pif', 'pufsss', 'incubateur', false,'Domotique', current_date);

INSERT INTO MembreInterne (mailMembre)
VALUES ('tre4@gmail.com');

INSERT INTO Membre (mail, nom, prenom, fonction, validateur, projet, dateProjet)
VALUES ('tre5@gmail.com', 'pif', 'pufssss', 'incubateur', true,'Domotique', current_date);

INSERT INTO MembreInterne (mailMembre)
VALUES ('tre5@gmail.com');

INSERT INTO Membre (mail, nom, prenom, fonction, validateur, projet, dateProjet)
VALUES ('tre6@gmail.com', 'pif', 'pufssps', 'incubateur', true,'Domotique', current_date);

INSERT INTO Membre (mail, nom, prenom, fonction, validateur, projet, dateProjet)
VALUES ('tre7@gmail.com', 'pif', 'pufsspp', 'incubateur', true,'Domotique', current_date);

INSERT INTO RedactionProposition (mail, numero)
VALUES ('tre5@gmail.com',currval('seqProposition'));

INSERT INTO Lignebudgetaire (numeroB, objet, montant, type)
VALUES (currval('seqProposition'),'Commande de boitier', 400, 'materiel');


/* Insertion de différentes entités et membres*/


INSERT INTO Comite (id, nom, nomOrga, dateOrga)
VALUES (nextval('seqComite'),'Com3','organisme2','2002-04-10');

INSERT INTO LaboratoireExterne (id, nom, dateDebut, dateFin)
VALUES (nextval('seqLaboExterne'), 'labo1', '1998-05-01', '01-05-1999');

INSERT INTO Financeur (id, nom, dateDebut, dateFin, employeContact, type)
VALUES (nextval('seqFinanceur'), 'potol', '1998-05-01', '1999-05-01', 'martine.challe@gmail.com', 'E');

INSERT INTO CreationOrganismeProjet (nomOrganisme, dateCreation, idFinanceur)
VALUES ('organisme2',' 2002-04-10',currval('seqFinanceur'));

INSERT INTO MembreExterne (mailMembre, idLaboratoire, idFinanceur)
VALUES ('tre@gmail.com', NULL, currval('seqFinanceur'));

INSERT INTO LaboratoireExterne (id, nom, dateDebut, dateFin)
VALUES (nextval('seqLaboExterne'), 'labo2', '1998-05-02', '01-05-1999');

INSERT INTO Financeur (id, nom, dateDebut, dateFin, employeContact, type)
VALUES (nextval('seqFinanceur'), 'potole', '1998-05-01', '1999-05-01', 'jerome.lerzec@gmail.com', 'R');

INSERT INTO CreationOrganismeProjet (nomOrganisme, dateCreation, idFinanceur)
VALUES ('organisme1', '1998-04-12',currval('seqFinanceur'));

INSERT INTO MembreExterne (mailMembre, idLaboratoire, idFinanceur)
VALUES ('tre1@gmail.com', currval('seqLaboExterne'), NULL);


INSERT INTO EnseignantChercheur (mailMembre, quotite, nomEtablissement)
VALUES ('tre2@gmail.com', 24, 'utc');

INSERT INTO EnseignantChercheur (mailMembre, quotite, nomEtablissement)
VALUES ('tre3@gmail.com', 21, 'utc');

INSERT INTO IngenieurRecherche (mailMembre, domaine )
VALUES ('tre4@gmail.com', 'archéologie');

INSERT INTO Doctorant (mailMembre, sujetThese,dateDebutThese,dateFinThese)
VALUES ('tre5@gmail.com', 'sinusite', '1998-06-01', '1999-05-01');

INSERT INTO Depense ( id, dateDepense, montant, type, membreDemandeur, membreValidateur, nomP, dateP)
VALUES (nextval('seqDepense'), '1998-06-01', 286, 'fonctionnement', 'tre3@gmail.com', 'tre@gmail.com','Domotique', current_date);

INSERT INTO Depense ( id, dateDepense, montant, type, membreDemandeur, membreValidateur, nomP, dateP)
VALUES (nextval('seqDepense'), '1998-06-01', 170, 'fonctionnement', 'tre2@gmail.com', 'tre@gmail.com','cheval', current_date);



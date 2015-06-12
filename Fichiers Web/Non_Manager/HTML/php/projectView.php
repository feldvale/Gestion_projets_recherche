<?php

	include "connect.php";
	$vConn = fConnect();
	
	function getMemberName($vConn,$mail)
	{
		$query = "SELECT nom,prenom ".
						 "FROM membre ".
						 "WHERE mail = '".$mail."'";
						 
		$vQuery=pg_query($vConn, $query);
		
		while($vResult=pg_fetch_array($vQuery,null,PGSQL_ASSOC))
		{
			$name = $vResult["prenom"]." ".$vResult["nom"];
		}
		return $name;
	}
	
	if($_GET['proj'] != '')
	{
		$tab = explode('/',$_GET['proj']);
		$projName = $tab[0];
		$projDate = $tab[1];
	
		
		//Members	
		$query_member = 	"SELECT m.nom AS membrenom, m.prenom AS membreprenom, m.mail AS mail, m.fonction AS fonction ". 
											"FROM projet p, membre m ".
											"WHERE p.nom = m.projet AND p.datedebut = m.dateprojet AND p.nom = '".$projName."' AND p.datedebut = '".$projDate."'";
		#echo "query is ".$query."<br>";
		
		$vQuery=pg_query($vConn, $query_member);
			
		echo "Projet Membre: <br>";
		while($vResult=pg_fetch_array($vQuery,null,PGSQL_ASSOC))
		{
			echo "Nom: ".$vResult["membrenom"]."<br>";
			echo "Prenom: ".$vResult["membreprenom"]."<br>";
			echo "Mail: ".$vResult["mail"]."<br>";
			echo "Fonction: ".$vResult["fonction"]."<br>";
			echo "<br>";
		}
		echo "<br>";
		
		//Depense
	
		$query_depense = "SELECT d.datedepense AS date,d.montant AS montant, d.type AS type, d.membredemandeur AS demandeur, d.membrevalidateur AS valid ".
										 "FROM projet p, depense d ".
										 "WHERE p.nom = d.nomp AND p.datedebut = d.datep AND p.nom = '".$projName."' AND p.datedebut = '".$projDate."'";
		
		$vQuery=pg_query($vConn, $query_depense);
		echo "Depense du Projet: <br>";
		
		while($vResult=pg_fetch_array($vQuery,null,PGSQL_ASSOC))
		{
			echo "Depense Date: ".$vResult["date"]."<br>";
			echo "Montant: ".$vResult["montant"]."Euros<br>";
			if($vResult["type"] != NULL)
			{
				echo "Type: ".$vResult["type"]."<br>";
			}
			else{
				echo "Type: Unknown<br>";
			}
			
			echo "Demandeur: ".getMemberName($vConn,$vResult["demandeur"])."<br>";
			echo "Validateur: ".getMemberName($vConn,$vResult["valid"])."<br>";
			echo "<br>";
		}
		
		//Budget du projet
		echo "Budget du Projet:<br>";
		echo "<br>";
		
		//Depense en Total
	
		$query_depense_total = "SELECT SUM(d.montant) AS total ".
													 "FROM projet p, depense d ".
													 "WHERE p.nom = d.nomp AND p.datedebut = d.datep AND p.nom = '".$projName."' AND p.datedebut = '".$projDate."'";
		
		$vQuery=pg_query($vConn, $query_depense_total);
		while($vResult=pg_fetch_array($vQuery,null,PGSQL_ASSOC))
		{
				echo "Depense En Total: ".$vResult["total"]."euros<br>";
		}
		echo "<br>";
		
		echo "L'argent reste:<br>";
		echo "<br>";
	}
	else
	{
		echo "ERROR:Not happens";
	}
?>
<?php
	include "connect.php";
	$vConn = fConnect();
	
	if($_GET["depense"])
	{
			$tab = explode("/",$_GET["depense"]);
			
			$id = $tab[0];
			$date = $tab[1];
			$montant = $tab[2];
			$type = $tab[3];
			$demandeur = $tab[4];
			$validateur = $tab[5];
			$proj_name = $tab[6];
			$proj_datedebut = $tab[7];
			
			if($type == 'null')
			{
				$type = 'NULL';
			}
			
			if($id != '' && $demandeur != '' && $validateur != '' && $proj_name != '' && $proj_datedebut != '')
			{
				$query_depense = "INSERT INTO depense ".
												 "VALUES(".$id.",'".$date."',".$montant.
												 ",".$type.",'".$demandeur."','".$validateur.
												 "','".$proj_name."','".$proj_datedebut."')";
												 		
				//echo $query_depense;
				
				$result = pg_query($vConn, $query_depense);
				
				if($result != FALSE)
				{
					echo "Insere reussi";
				}
				else
				{
					echo "Insere echoue";
				}
			}
			else
			{
				echo "ERROR: Quequel que champs sont null";	
			}
				
			
	}
?>
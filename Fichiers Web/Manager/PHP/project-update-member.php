<?php
	include "connect.php";
	$vConn = fConnect();
	
	if($_GET["member"] != '')
	{
		$tab = 	explode("/",$_GET["member"]);
		
		$nom = $tab[0];
		$prenom = $tab[1];
		$mail = $tab[2];
		$fonction = $tab[3];
		$proj_name = $tab[4];
		$proj_datedebut = $tab[5];
		
		if($fonction == 'null')
		{
			$fonction = NULL;	
		}
		
		$validateur = TRUE;
		
		if($nom != "null" && $prenom != "null" && $mail != "null")
		{
			$query_member = "INSERT INTO membre ".
										"VALUES('".$mail."','".$nom."','".$prenom.
										"','".$fonction."','".$validateur."','".$proj_name."','".$proj_datedebut.
										"')";
										
			//echo "Query is ".$query_member;		
			
			$result = pg_query($vConn, $query_member);
			
			if($result != FALSE)
			{
				echo "Insertion reussie";
			}
			else
			{
				echo "Insertion echoue";
			}
			
		}
		else{
			echo "ERROR: Les champs sont null";
		}
													
	}
?>

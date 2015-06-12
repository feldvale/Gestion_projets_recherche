<?php
	include "connect.php";
	include "global.php";
	$vConn = fConnect();

	if($_GET["proj"] != '')
	{
		$tab = explode("/",$_GET["proj"]);
		
		$projName = $tab[0];
		$projDatedebut = $tab[1];
		
		#Firstly, we should drop off the foregin keys
		$query_dropkey_member = "Update membre ".
														"SET projet = NULL,dateprojet = NULL ".
														"WHERE projet = '".$projName."' AND dateprojet = '".$projDatedebut."'";
														
		$vQuery=pg_query($vConn, $query_dropkey_member);
		
		if($vQuery != False)
		{
			#Then, invisble the project
			$query_projet =  "Update projet ".
											 "SET fini = TRUE ".
											 "WHERE nom = '".$projName."' AND datedebut = '".$projDatedebut."'";
			
			$vQuery=pg_query($vConn, $query_projet);
			
			if($vQuery != FALSE )
			{
					echo "Success";
			}
			else{
					echo "Failed";
			}
			
			
		}
		else
		{
			echo "ERROR: The members foregin keys can not be deleted";		
		}
			
	
	}
	

?>
<?php
	include "commonfunctions.php";
	include "global.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kube Web Framework</title>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="stylesheet" href="css/kube.min.css" />

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.js"></script>
    <script src="js/kube.min.js"></script>

</head>

<body>
	<?php
		#Reasrch Form Datas
		if(!empty($_POST["project_name"]) && $_POST["project_name"] != "")
		{
			$Data["projectName"] = $_POST["project_name"];
		}
		else
		{
			$Data["projectName"] = NULL;	
		}
			
		if(!empty($_POST["proposition"]) && $_POST["proposition"] != "")
		{
			$Data["projectProposition"] = $_POST["proposition"];
		}
		else{
			$Data["projectProposition"] = NULL;	
		}
		
		if(!empty($_POST["begin_day"]) && $_POST["begin_day"] != "" && $_POST["begin_day"] != "--")
		{
			$beginday = $_POST["begin_day"];
		}
		else
		{
			$beginday = NULL;	
		}
		
		if(!empty($_POST["begin_month"]) && $_POST["begin_month"] != ""&& $_POST["begin_month"] != "--")
		{
			$beginmonth = $_POST["begin_month"];
		}
		else
		{
			$beginmonth = NULL;	
		}
		
		if(!empty($_POST["begin_year"]) && $_POST["begin_year"] != ""&& $_POST["begin_year"] != "----")
		{
			$beginyear = $_POST["begin_year"];
		}
		else
		{
			$beginyear = NULL;	
		}
		
		if(!empty($_POST["end_day"]) && $_POST["end_day"] != ""&& $_POST["end_day"] != "--")
		{
			$endday = $_POST["end_day"];
		}
		else
		{
			$endday = NULL;	
		}
		
		if(!empty($_POST["end_month"]) && $_POST["end_month"] != ""&& $_POST["end_month"] != "--")
		{
			$endmonth = $_POST["end_month"];
		}
		else
		{
			$endmonth = NULL;	
		}
		
		if(!empty($_POST["end_year"]) && $_POST["end_year"] != ""&& $_POST["end_year"] != "----")
		{
			$endyear = $_POST["end_year"];
		}
		else
		{
			$endday = NULL;	
		}
		
		
		if($beginday != NULL && $beginmonth != NULL && $beginyear != NULL)
		{
			$Data["begindate"] = commonfunctions::dateFormer($beginday,$beginmonth,$beginyear,"-");	
		}
		else
		{
			$Data["begindate"] = NULL;	
		}
		
		if($endday != NULL && $endmonth != NULL && $endyear != NULL)
		{
			$Data["enddate"] = commonfunctions::dateFormer($endday,$endmonth,$endyear,"-");	
		}
		else
		{
			$Data["enddate"] = NULL;	
		}
		
		#Link the data
		include "connect.php";
		$vConn = fConnect();
		
		$query = "";
		$query = $query."SELECT * FROM projet WHERE ";
		$count = 0;
		$cond  = FALSE;
		
		if($Data["projectName"] != NULL)
		{
				if($count != 0)
				{
					$query = $query." AND ";
				}
				$query = $query."nom = '".$Data["projectName"]."'";
				$cond  = TRUE;
				$count++;
		}
		
		if($Data["projectProposition"] != NULL)
		{
				if($count != 0)
				{
					$query = $query." AND ";
				}
				
				$ProId = commonfunctions::getPropositionIdBySujet($vConn,$Data["projectProposition"]);
				
				$query = $query."proposition = ".$ProId;
				$cond  = TRUE;
				$count++;
		}
		
		if($Data["begindate"] != NULL)
		{
				if($count != 0)
				{
					$query = $query." AND ";
				}
				$query = $query."datedebut = '".$Data["begindate"]."'";
				$count++;
				$cond  = TRUE;
		}
		
		if($Data["enddate"] != NULL)
		{
				if($count != 0)
				{
					$query = $query." AND ";
				}
				$query = $query."datefin = '".$Data["enddate"]."'";
				$count++;
				$cond  = TRUE;
		}
		
		$query = $query.";";
		//echo "query is ".$query."<br>";
	
	  if($cond == TRUE)
	  {
			$vQuery=pg_query($vConn, $query);
		
		
		$count = 1;
		commonfunctions::projectHeader();
		while($vResult=pg_fetch_array($vQuery))
		{
			
			commonfunctions::projectLister($vResult,$count,$vConn);
			$count ++;
			
		}
		if($count == 1)
	  {
	 	 ?>
	 	    <tr>Non resultats</tr>
	 	  </tbody>
	 	</table>
	 	 <?php
	  }
			commonfunctions::projectFooter();
		}
		else
		{
		   echo "Aucune critere chosis";	
		}
		
		
		
		
		
	?>
</body>
</html>
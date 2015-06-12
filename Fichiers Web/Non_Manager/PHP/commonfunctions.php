<?php
include "global.php";

Class commonfunctions
{
	
	public static function dateFormer($day,$month,$year,$separator)
	{
		$date = $year.$separator.$_SESSION["MonthMap"][$month].$separator.$day;
		return $date;	
	}
	
	public static function getPropositionIdBySujet($vConn,$sujet)
	{
		$query = "SELECT numero ".
						 "FROM proposition ".
						 "WHERE description LIKE '%".$sujet."%'";
		
		#echo $query;				 
		$vQuery=pg_query($vConn, $query);
		
		while($vResult=pg_fetch_array($vQuery))
		{
			return $vResult[0];	
		}
		return NULL;
	}
	
	public static function getPropositionSujetById($vConn,$numero)
	{
		$query = "SELECT description ".
						 "FROM proposition ".
						 "WHERE numero = ".$numero;
						 
		$vQuery=pg_query($vConn, $query);
		
		while($vResult=pg_fetch_array($vQuery))
		{
			return $vResult[0];	
		}
		return NULL;
	}
	
	public static function getPropositionBujet($vConn,$numero)
	{
		if($numero != NULL && $numero != '')
		{
			$query = "SELECT SUM(lb.montant) AS somme ".
						 "FROM proposition p, lignebudgetaire lb ".
						 "WHERE p.numero = lb.numerob AND p.numero = ".$numero;
						 
			$vQuery=pg_query($vConn, $query);	
			while($vResult=pg_fetch_array($vQuery))
			{
				return $vResult[0];	
			}
		}
		else
		{
			return "Non Budget";		 	
		}
	}
	
	public static function getProjetDepense($vConn,$projet,$datedebut)
	{
		if($projet != NULL && $projet != '')
		{
			$query = "SELECT SUM(d.montant) AS somme ".
						 "FROM projet p, depense d ".
						 "WHERE p.nom = d.nomp AND p.datedebut = d.datep ".
						 "AND p.nom = '".$projet."' AND p.datedebut = '".$datedebut."'";
						 
			$vQuery=pg_query($vConn, $query);	
			while($vResult=pg_fetch_array($vQuery))
			{
				return $vResult[0];	
			}
		}
		else
		{
			return "Non Depense";		 	
		}
	}
	
	
	
	public static function HTML_header()
	{
		?>
			<head>
   		 <title>Labo de recherche</title>

    	<meta charset="utf-8" />
    	<meta name="viewport" content="width=device-width, initial-scale=1" />

    	<link rel="stylesheet" href="../HTML/css/kube.min.css" />

			<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>-->
   		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.js"></script>
    	<script src="../HTML/js/kube.min.js"></script>
			
			</head>
		<?php	
	}
	
	public static function projectHeader()
	{
		commonfunctions::HTML_header();
		?>
			<style>
				.panel{
					display:none;
				}
				.panel1{
					display:none;
				}
				.panel2{
					display:none;
				}
			</style>
			<script type="text/javascript">
						$(document).ready(function(){
							$('.view').click(function(){
								$(this).children("div.panel").slideToggle("slow");
								var obj = $(this).children('div.panel').find('p'); 
								
								$.ajax({
									type: 'GET',
									url: 'projectView.php',
									data: 'proj='+$(this).parentsUntil('tbody').children("td.name").find('p').html()+'/'+$(this).parentsUntil('tbody').children("td.datedebut").html(),
									//dataType: 'json',
									success: function(msg){
										obj.html(msg);
									}
								});
							});
							
							$('.update').click(function(){
									$(this).siblings("div").slideToggle("slow"); 
							});
							
							$('.update_depense').click(function(){
									$(this).siblings("div.panel1").slideToggle("slow");
							});
							
							$('.update_member').click(function(){
									$(this).siblings("div.panel2").slideToggle("slow");
							});
							
							
							$('.valid_member').click(function(){
									var nom = $(this).siblings("input.member_nom");
									var prenom = $(this).siblings("input.member_prenom");
									var mail = $(this).siblings("input.member_mail");
									var fonction = $(this).siblings("input.member_fonction");
									var proj_name = $(this).parentsUntil('tbody').children('td.name').find('p'); 
									var proj_datedebut = $(this).parentsUntil('tbody').children('td.datedebut');
									
									if(nom.val() == '')
									{
										nom.val("null");
									}
									
									if(prenom.val() == '')
									{
										prenom.val("null");
									}
									
									if(mail.val() == '')
									{
										mail.val("null");
									}
									
									if(fonction.val() == '')
									{
										fonction.val("null");
									}
									
									
									$.ajax({
										type: 'GET',
										url: 'project-update-member.php',
										data: 'member='+nom.val()+"/"+prenom.val()+"/"+mail.val()+"/"+fonction.val()+"/"+proj_name.html()+"/"+proj_datedebut.html(),
										success: function(msg){
											window.alert(msg);
										}
									});	
							});
							
							$('.valid_depense').click(function(){
									var depense_id = $(this).siblings("input.depense_id");
									var depense_date = $(this).siblings("input.depense_date");
									var montant = $(this).siblings("input.depense_montant");
									var depense_type = $(this).siblings("input.depense_type");
									var demandeur = $(this).siblings("input.demandeur");
									var validateur = $(this).siblings("input.valideur");
									var proj_name = $(this).parentsUntil('tbody').children('td.name').find('p'); 
									var proj_datedebut = $(this).parentsUntil('tbody').children('td.datedebut');
									
									if(depense_type.val() == '')
									{
										depense_type.val("null");
									}
								
									
									$.ajax({
										type: 'GET',
										url: 'project-update-depense.php',
										data: 'depense='+depense_id.val()+"/"+depense_date.val()+"/"+montant.val()+"/"+depense_type.val()+"/"+demandeur.val()+"/"+validateur.val()+"/"+proj_name.html()+"/"+proj_datedebut.html(),
										success: function(msg){
											window.alert(msg);
										}
									});	
									
							});
							
							$('.delete').click(function()
							{
								
								var proj_name = $(this).parentsUntil('tbody').children('td.name').find('p');
								var proj_datedebut = $(this).parentsUntil('tbody').children('td.datedebut'); 
								
								$.ajax({
										type: 'GET',
										url: 'project-delete.php',
										data: 'proj='+proj_name.html()+'/'+proj_datedebut.html(),
										success: function(msg){
											window.alert(msg);
										}
									});	
							});
						});
			</script>
			
			<h1>La liste des projets correspondant à votre recherche</h1>
			<table class="table-stripped">
    			<thead>
        		<tr>
           	 <th>Nom de projet</th>
           	 <th>Date de debut</th>
           	 <th>Date de fin</th>
           	 <th>Proposition</th>
           	 <th>L'argent Restant</th>
           	 <th>Terminer</th>
           	 <th>Options</th>
        		</tr>
    			</thead>
    			<tbody>
    <?php
	}
	
	public static function projectLister($data,$count,$vConn)
	{ 
    		?>
        	<tr id="<?php echo $count;?>">
            <td class="name"><p id="<?php echo "proj_name".$count; ?>"><?php echo $data[0];?></p></td>
            <td class="datedebut"><?php echo $data[1];?></td>
            <td><?php echo $data[2];?></td>
            <td><?php 
            	if($data[3]!=NULL)
            	{
            		echo commonfunctions::getPropositionSujetById($vConn,$data[3]);
            	}
            	else
            	{
            		echo "Non Propostion Associe";	
            	}
            ?></td>
            <td><?php
            		$budjet = commonfunctions::getPropositionBujet($vConn,$data[3]);
            		$depense = commonfunctions::getProjetDepense($vConn,$data[0],$data[1]);
            		$montant = $budjet - $depense;
            		if($budjet == null)
            		{
            			echo "0 EUROS";
            		}
            		else
            		{
            			echo $montant." EUROS";
            		}
            		?></td>
            <td><?php 
            	if($data[5] == TRUE)
            	{
            			echo "Termine";
            	}
            	else
            	{
            		  echo "En Cours";	
            	}
            ?></td>
            <td>
            	<ul class="blocks-3">
    						<li>
    							<div class="view">
    								<div id="<?php echo "title".$count;?>" class="btn-blue" >   View</div>
    								<div id="<?php echo "panel".$count;?>" class="panel">
    									<p id="<?php echo "result".$count;?>"></p>
    								</div>
									</div>
								</li>
								<?php
								if($data[5] != TRUE)
								{
								?>
    						<li>
    							<div class="update">
    								<div id="<?php echo "update1".$count;?>" class="btn-green">   Update</div>
    							</div>
    							<div id="<?php echo "update_panel".$count;?>" class="panel">
										<!-- <div class="update_depense">
	  										<label class="btn">Ajoute_Membre</label>
									   </div>
									   <div class="panel1">
	  											<input name="member_nom" class="member_nom" placeholder="membre nom" width="50px"/>
	  											<input name="member_prenom" class="member_prenom" placeholder="membre prenom" width="50px"/>
	  											<input name="member_mail" class="member_mail" placeholder="mail" width="50px"/>
	  											<input name="member_fonction" class="member_fonction" placeholder="fonction" width="50px"/>
	  											<button class="valid_member">Valider</button>
	  								 </div>-->
									  <div class="update_member">
	  										<label class="btn">Ajoute_Depense</label> 
										 </div>
										 <div class="panel2">
	  											<input name="depense_id" class="depense_id" placeholder="depense_id" />
	  											<input name="depense_date" class="depense_date" placeholder="depense_date" />
	  											<input name="depense_montant" class="depense_montant" placeholder="depense_montant" />
	  											<input name="depense_type" class="depense_type" placeholder="type" />
	  											<input name="demandeur" class="demandeur" placeholder="demandeur" />
	  											<input name="valideur" class="valideur" placeholder="valideur" />
	  											<button class="valid_depense">Valider</button>
	  								 </div>
    							</div>
    						</li>
   			 				<!--<li>
   			 					<div class="delete">
   			 						<div id="<?php echo "delete-title".$count;?>" class="btn-red" >Terminer</div>
   			 					</div>
   			 				</li>-->
   			 				<?php
   			 			}
   			 				?>
							</ul>
            </td>
        	</tr>     			
<?php	
	}

	public static function projectFooter()
	{
?>
				</tbody>
			</table>
<?php
	}
}

?>

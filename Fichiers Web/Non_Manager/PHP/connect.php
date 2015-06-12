<?php
	function fConnect(){
		$vHost="tuxa.sme.utc";
		$vPort="5432";
		$vDbname="dbnf17p025";
		$vUser="nf17p025";
		$vPassword="cZMSg0wc";
		$vConn = pg_connect("host=$vHost port=$vPort dbname=$vDbname user=$vUser password=$vPassword");
		if($vConn != FALSE)
		{
			return $vConn;
		}
		else
		{
			echo "ERROR:La base de donnnee ne peut pas etre connecte<br>";	
		}
	}
?>
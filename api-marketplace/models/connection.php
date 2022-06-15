<?php 

class Connection{

	static public function connect(){

		try{

			$link = new PDO("mysql:host=database-partsologydb-instance-1.cwxi1wtucpyv.us-east-2.rds.amazonaws.com;dbname=marketplace","admin", "DNJ2021Partsology");

			$link->exec("set names utf8");

		}catch(PDOException $e){

			die("Error: ".$e->getMessage());

		}

		return $link;
		
	}

}


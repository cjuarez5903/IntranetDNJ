<?php

/*=============================================
Conexión a la BD
=============================================*/

class Connection{

	static public function connect(){

		try{

			$link = new PDO("mysql:host=localhost;dbname=marketplace","root", "");

			$link->exec("set names utf8");

		}catch(PDOException $e){

			die("Error: ".$e->getMessage());

		}

		return $link;
		
	}

}

/*=============================================
Modelo
=============================================*/

class Model{

	static public function getData($table, $select, $linkTo, $equalTo){

		$stmt = Connection::connect()->prepare("SELECT $select FROM $table WHERE $linkTo = :$linkTo");

		$stmt -> bindParam(":".$linkTo, $equalTo, PDO::PARAM_STR);

		$stmt -> execute();

		return $stmt -> fetchAll();

	}

	static public function deleteData($table, $id, $nameId){

		$stmt = Connection::connect()->prepare("DELETE FROM $table WHERE $nameId = :$nameId");

		$stmt -> bindParam(":".$nameId, $id, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "The process was successful";
		
		}else{

			return Connection::connect()->errorInfo();

		}

	}


}

/*=============================================
Controlador
=============================================*/

class Controller{

	static public function getData($table, $select, $linkTo, $equalTo){

		$response = Model::getData($table, $select, $linkTo, $equalTo);

		return $response;

	}

	static public function deleteData($table, $id, $nameId){

		$response = Model::deleteData($table, $id, $nameId);

		return $response;

	}


}

/*=============================================
Vista
=============================================*/

$tables = array("order","sale");

foreach ($tables as $key => $value) {

	$response = Controller::getData($value."s", "id_".$value, "status_".$value, "test");

	if(isset($response[0]["id_".$value])){
	
		$delete = Controller::deleteData($value."s", $response[0]["id_".$value], "id_".$value);
		echo '<pre>'; print_r($delete); echo '</pre>';

	}

}
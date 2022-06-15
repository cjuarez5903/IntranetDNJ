<?php 

require_once "connection.php";

class GetModel{

	/*=============================================
	Peticiones GET sin filtro
	=============================================*/

	static public function getData($table, $orderBy, $orderMode, $startAt, $endAt, $select){
	
		if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){

			$stmt = Connection::connect()->prepare("SELECT $select FROM $table ORDER BY $orderBy $orderMode");

		}else if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){

			$stmt = Connection::connect()->prepare("SELECT $select FROM $table ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");
		
		}else{

			$stmt = Connection::connect()->prepare("SELECT $select FROM $table");

		}

		$stmt -> execute();

		return $stmt -> fetchAll(PDO::FETCH_CLASS);

	}

	/*=============================================
	Peticiones GET con filtro
	=============================================*/

	static public function getFilterData($table, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt, $select){

		/*=============================================
		Seleccionar varios filtros
		=============================================*/

		$linkToArray = explode(",", $linkTo);
		$equalToArray = explode(",", $equalTo);
		$linkToText = "";

		if(count($linkToArray) > 1){

			foreach ($linkToArray as $key => $value) {
				
				if($key > 0){

					$linkToText .= "AND ".$value." = :".$value." ";
				}

			}

		}

		if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){

			$stmt = Connection::connect()->prepare("SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode");
		
		}else if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){

			$stmt = Connection::connect()->prepare("SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");
		
		}else{

			$stmt = Connection::connect()->prepare("SELECT $select FROM $table WHERE $linkToArray[0] = :$linkToArray[0] $linkToText");

		}

		foreach ($linkToArray as $key => $value) {
			
			$stmt->bindParam(":".$value, $equalToArray[$key], PDO::PARAM_STR);
			
		}

		$stmt -> execute();

		return $stmt -> fetchAll(PDO::FETCH_CLASS);

	}

	/*=============================================
	Peticiones GET tablas relacionadas sin filtro
	=============================================*/

	static public function getRelData($rel, $type, $orderBy, $orderMode, $startAt, $endAt, $select){

		$relArray = explode(",", $rel);
		$typeArray = explode(",", $type);

		/*=============================================
		Relacionar 2 tablas
		=============================================*/

		if(count($relArray) == 2 && count($typeArray) == 2){

			$on1 = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0]; 
			$on2 = $relArray[1].".id_".$typeArray[1];

			if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1 = $on2 ORDER BY $orderBy $orderMode");

			}else if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){

			$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1 = $on2 ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");
		
			}else{

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1 = $on2");

			}

		}

		/*=============================================
		Relacionar 3 tablas
		=============================================*/

		if(count($relArray) == 3 && count($typeArray) == 3){

			$on1a = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0]; 
			$on1b = $relArray[1].".id_".$typeArray[1];

			$on2a = $relArray[0].".id_".$typeArray[2]."_".$typeArray[0]; 
			$on2b = $relArray[2].".id_".$typeArray[2];

			if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1a = $on1b INNER JOIN $relArray[2] ON $on2a = $on2b ORDER BY $orderBy $orderMode");

			}else if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){

			$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1a = $on1b INNER JOIN $relArray[2] ON $on2a = $on2b ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");
		
			}else{

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1a = $on1b INNER JOIN $relArray[2] ON $on2a = $on2b");

			}

		}

		/*=============================================
		Relacionar 4 tablas
		=============================================*/

		if(count($relArray) == 4 && count($typeArray) == 4){

			$on1a = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0]; 
			$on1b = $relArray[1].".id_".$typeArray[1];

			$on2a = $relArray[0].".id_".$typeArray[2]."_".$typeArray[0]; 
			$on2b = $relArray[2].".id_".$typeArray[2];

			$on3a = $relArray[0].".id_".$typeArray[3]."_".$typeArray[0]; 
			$on3b = $relArray[3].".id_".$typeArray[3];

			if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1a = $on1b INNER JOIN $relArray[2] ON $on2a = $on2b INNER JOIN $relArray[3] ON $on3a = $on3b ORDER BY $orderBy $orderMode");

			}else if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){

			$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1a = $on1b INNER JOIN $relArray[2] ON $on2a = $on2b INNER JOIN $relArray[3] ON $on3a = $on3b ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");
		
			}else{

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1a = $on1b INNER JOIN $relArray[2] ON $on2a = $on2b INNER JOIN $relArray[3] ON $on3a = $on3b");

			}

		}

		$stmt -> execute();

		return $stmt -> fetchAll(PDO::FETCH_CLASS);
		
	}

	/*=============================================
	Peticiones GET tablas relacionadas con filtro
	=============================================*/

	static public function getRelFilterData($rel, $type, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt, $select){

		/*=============================================
		Seleccionar varios filtros
		=============================================*/

		$linkToArray = explode(",", $linkTo);
		$equalToArray = explode(",", $equalTo);
		$linkToText = "";

		if(count($linkToArray) > 1){

			foreach ($linkToArray as $key => $value) {
				
				if($key > 0){

					$linkToText .= "AND ".$value." = :".$value." ";
				}

			}

		}

		$relArray = explode(",", $rel);
		$typeArray = explode(",", $type);

		/*=============================================
		Relacionar 2 tablas
		=============================================*/

		if(count($relArray) == 2 && count($typeArray) == 2){

			$on1 = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0]; 
			$on2 = $relArray[1].".id_".$typeArray[1];

			if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1 = $on2 WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode");

			}else if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){

			$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1 = $on2 WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");
		
			}else{

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1 = $on2 WHERE $linkToArray[0] = :$linkToArray[0] $linkToText");

			}			

		}

		/*=============================================
		Relacionar 3 tablas
		=============================================*/

		if(count($relArray) == 3 && count($typeArray) == 3){

			$on1a = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0]; 
			$on1b = $relArray[1].".id_".$typeArray[1];

			$on2a = $relArray[0].".id_".$typeArray[2]."_".$typeArray[0]; 
			$on2b = $relArray[2].".id_".$typeArray[2];

			if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1a = $on1b INNER JOIN $relArray[2] ON $on2a = $on2b WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode");

			}else if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){

			$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1a = $on1b INNER JOIN $relArray[2] ON $on2a = $on2b WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");
		
			}else{

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1a = $on1b INNER JOIN $relArray[2] ON $on2a = $on2b WHERE $linkToArray[0] = :$linkToArray[0] $linkToText");

			}

		}

		/*=============================================
		Relacionar 4 tablas
		=============================================*/

		if(count($relArray) == 4 && count($typeArray) == 4){

			$on1a = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0]; 
			$on1b = $relArray[1].".id_".$typeArray[1];

			$on2a = $relArray[0].".id_".$typeArray[2]."_".$typeArray[0]; 
			$on2b = $relArray[2].".id_".$typeArray[2];

			$on3a = $relArray[0].".id_".$typeArray[3]."_".$typeArray[0]; 
			$on3b = $relArray[3].".id_".$typeArray[3];

			if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1a = $on1b INNER JOIN $relArray[2] ON $on2a = $on2b INNER JOIN $relArray[3] ON $on3a = $on3b WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode");

			}else if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1a = $on1b INNER JOIN $relArray[2] ON $on2a = $on2b INNER JOIN $relArray[3] ON $on3a = $on3b WHERE $linkToArray[0] = :$linkToArray[0] $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");
		
			}else{

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1a = $on1b INNER JOIN $relArray[2] ON $on2a = $on2b INNER JOIN $relArray[3] ON $on3a = $on3b WHERE $linkToArray[0] = :$linkToArray[0] $linkToText");

			}

		}

		foreach ($linkToArray as $key => $value) {
			
			$stmt->bindParam(":".$value, $equalToArray[$key], PDO::PARAM_STR);
			
		}

		$stmt -> execute();

		return $stmt -> fetchAll(PDO::FETCH_CLASS);
		
	}

	/*=============================================
	Peticiones GET para el buscador
	=============================================*/

	static public function getSearchData($table, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt, $select){

		/*=============================================
		Seleccionar varios filtros
		=============================================*/

		$linkToArray = explode(",", $linkTo);
		$searchArray = explode(",", $search);
		$linkToText = "";

		if(count($linkToArray) > 1){

			foreach ($linkToArray as $key => $value) {

				if($key > 0){

					$linkToText .=  "AND ".$value." = :".$value." ";

				}

			}


		}


		if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){

			$stmt = Connection::connect()->prepare("SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText ORDER BY $orderBy $orderMode");

		}else if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){

			$stmt = Connection::connect()->prepare("SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");
		
		}else{

			$stmt = Connection::connect()->prepare("SELECT $select FROM $table WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText");

		}

		if(count($linkToArray) > 1){

			unset($linkToArray[0]);

			foreach ($linkToArray as $key => $value) {
				
				$stmt->bindParam(":".$value, $searchArray[$key], PDO::PARAM_STR);
			}

		}	

		$stmt -> execute();

		return $stmt -> fetchAll(PDO::FETCH_CLASS);

	}

	/*=============================================
	Peticiones GET  para el buscador entre tablas relacionadas
	=============================================*/

	static public function getSearchRelData($rel, $type, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt, $select){

		/*=============================================
		Seleccionar varios filtros
		=============================================*/

		$linkToArray = explode(",", $linkTo);
		$searchArray = explode(",", $search);
		$linkToText = "";

		if(count($linkToArray) > 1){

			foreach ($linkToArray as $key => $value) {

				if($key > 0){

					$linkToText .=  "AND ".$value." = :".$value." ";

				}

			}

		}

		$relArray = explode(",", $rel);
		$typeArray = explode(",", $type);

		/*=============================================
		Relacionar 2 tablas
		=============================================*/

		if(count($relArray) == 2 && count($typeArray) == 2){

			$on1 = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0]; 
			$on2 = $relArray[1].".id_".$typeArray[1];

			if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1 = $on2 WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText ORDER BY $orderBy $orderMode");

			}else if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){

			$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1 = $on2 WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");
		
			}else{

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1 = $on2 WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText");

			}			

		}

		/*=============================================
		Relacionar 3 tablas
		=============================================*/

		if(count($relArray) == 3 && count($typeArray) == 3){

			$on1a = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0]; 
			$on1b = $relArray[1].".id_".$typeArray[1];

			$on2a = $relArray[0].".id_".$typeArray[2]."_".$typeArray[0]; 
			$on2b = $relArray[2].".id_".$typeArray[2];

			if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1a = $on1b INNER JOIN $relArray[2] ON $on2a = $on2b WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText ORDER BY $orderBy $orderMode");

			}else if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){

			$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1a = $on1b INNER JOIN $relArray[2] ON $on2a = $on2b WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");
		
			}else{

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1a = $on1b INNER JOIN $relArray[2] ON $on2a = $on2b WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText");

			}

		}

		/*=============================================
		Relacionar 4 tablas
		=============================================*/

		if(count($relArray) == 4 && count($typeArray) == 4){

			$on1a = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0]; 
			$on1b = $relArray[1].".id_".$typeArray[1];

			$on2a = $relArray[0].".id_".$typeArray[2]."_".$typeArray[0]; 
			$on2b = $relArray[2].".id_".$typeArray[2];

			$on3a = $relArray[0].".id_".$typeArray[3]."_".$typeArray[0]; 
			$on3b = $relArray[3].".id_".$typeArray[3];

			if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1a = $on1b INNER JOIN $relArray[2] ON $on2a = $on2b INNER JOIN $relArray[3] ON $on3a = $on3b WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText ORDER BY $orderBy $orderMode");

			}else if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1a = $on1b INNER JOIN $relArray[2] ON $on2a = $on2b INNER JOIN $relArray[3] ON $on3a = $on3b WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");
		
			}else{

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1a = $on1b INNER JOIN $relArray[2] ON $on2a = $on2b INNER JOIN $relArray[3] ON $on3a = $on3b WHERE $linkToArray[0] LIKE '%$searchArray[0]%' $linkToText");

			}

		}

		if(count($linkToArray) > 1){

			unset($linkToArray[0]);

			foreach ($linkToArray as $key => $value) {
				
				$stmt->bindParam(":".$value, $searchArray[$key], PDO::PARAM_STR);
			}

		}	

		$stmt -> execute();

		return $stmt -> fetchAll(PDO::FETCH_CLASS);
		
	}

	/*=============================================
	Peticiones GET de rangos
	=============================================*/

	static public function getBetweenData($table, $linkTo, $between1, $between2, $filterTo, $inTo, $orderBy, $orderMode, $startAt, $endAt, $select){

		if($inTo != 0){

			$inTo = "IN (".$inTo.")";

		}else{

			$inTo = "NOT IN (".$inTo.")";

		}

		/*=============================================
		Seleccionar varios filtros
		=============================================*/

		if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){

			$stmt = Connection::connect()->prepare("SELECT $select FROM $table WHERE $linkTo BETWEEN '$between1' AND '$between2' AND $filterTo $inTo ORDER BY $orderBy $orderMode");

		}else if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){

			$stmt = Connection::connect()->prepare("SELECT $select FROM $table WHERE $linkTo BETWEEN '$between1' AND '$between2' AND $filterTo $inTo ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");
		
		}else{

			$stmt = Connection::connect()->prepare("SELECT $select FROM $table WHERE $linkTo BETWEEN '$between1' AND '$between2' AND $filterTo $inTo");
			

		}

		$stmt -> execute();

		return $stmt -> fetchAll(PDO::FETCH_CLASS);

	}

	/*=============================================
	Peticiones GET para rangos entre tablas relacionadas
	=============================================*/

	static public function getBetweenRelData($rel, $type, $linkTo, $between1, $between2, $filterTo, $inTo, $orderBy, $orderMode, $startAt, $endAt, $select){

		if($inTo != 0){

			$inTo = "IN (".$inTo.")";

		}else{

			$inTo = "NOT IN (".$inTo.")";

		}


		$relArray = explode(",", $rel);
		$typeArray = explode(",", $type);

		/*=============================================
		Relacionar 2 tablas
		=============================================*/

		if(count($relArray) == 2 && count($typeArray) == 2){

			$on1 = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0]; 
			$on2 = $relArray[1].".id_".$typeArray[1];

			if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1 = $on2 WHERE $linkTo BETWEEN '$between1' AND '$between2' AND $filterTo $inTo  ORDER BY $orderBy $orderMode");

			}else if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){

			$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1 = $on2 WHERE $linkTo BETWEEN '$between1' AND '$between2'  AND $filterTo $inTo  ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");
		
			}else{

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1 = $on2 WHERE $linkTo BETWEEN '$between1' AND '$between2' AND $filterTo $inTo ");

			}			

		}

		/*=============================================
		Relacionar 3 tablas
		=============================================*/

		if(count($relArray) == 3 && count($typeArray) == 3){

			$on1a = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0]; 
			$on1b = $relArray[1].".id_".$typeArray[1];

			$on2a = $relArray[0].".id_".$typeArray[2]."_".$typeArray[0]; 
			$on2b = $relArray[2].".id_".$typeArray[2];

			if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1a = $on1b INNER JOIN $relArray[2] ON $on2a = $on2b WHERE $linkTo BETWEEN '$between1' AND '$between2' AND $filterTo $inTo  ORDER BY $orderBy $orderMode");

			}else if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){

			$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1a = $on1b INNER JOIN $relArray[2] ON $on2a = $on2b WHERE $linkTo BETWEEN '$between1' AND '$between2' AND $filterTo $inTo ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");
		
			}else{

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1a = $on1b INNER JOIN $relArray[2] ON $on2a = $on2b WHERE $linkTo BETWEEN '$between1' AND '$between2' AND $filterTo $inTo ");

			}

		}

		/*=============================================
		Relacionar 4 tablas
		=============================================*/

		if(count($relArray) == 4 && count($typeArray) == 4){

			$on1a = $relArray[0].".id_".$typeArray[1]."_".$typeArray[0]; 
			$on1b = $relArray[1].".id_".$typeArray[1];

			$on2a = $relArray[0].".id_".$typeArray[2]."_".$typeArray[0]; 
			$on2b = $relArray[2].".id_".$typeArray[2];

			$on3a = $relArray[0].".id_".$typeArray[3]."_".$typeArray[0]; 
			$on3b = $relArray[3].".id_".$typeArray[3];

			if($orderBy != null && $orderMode != null && $startAt == null && $endAt == null){

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1a = $on1b INNER JOIN $relArray[2] ON $on2a = $on2b INNER JOIN $relArray[3] ON $on3a = $on3b WHERE $linkTo BETWEEN '$between1' AND '$between2' AND $filterTo $inTo  ORDER BY $orderBy $orderMode");

			}else if($orderBy != null && $orderMode != null && $startAt != null && $endAt != null){

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1a = $on1b INNER JOIN $relArray[2] ON $on2a = $on2b INNER JOIN $relArray[3] ON $on3a = $on3b WHERE $linkTo BETWEEN '$between1' AND '$between2' AND $filterTo $inTo  ORDER BY $orderBy $orderMode LIMIT $startAt, $endAt");
		
			}else{

				$stmt = Connection::connect()->prepare("SELECT $select FROM $relArray[0] INNER JOIN $relArray[1] ON $on1a = $on1b INNER JOIN $relArray[2] ON $on2a = $on2b INNER JOIN $relArray[3] ON $on3a = $on3b WHERE $linkTo BETWEEN '$between1' AND '$between2' AND $filterTo $inTo ");

			}

		}


		$stmt -> execute();

		return $stmt -> fetchAll(PDO::FETCH_CLASS);
		
	}

}



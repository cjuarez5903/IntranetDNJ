<?php 

class GetController{

	/*=============================================
	Peticiones GET sin filtro
	=============================================*/

	public function getData($table, $orderBy, $orderMode, $startAt, $endAt, $select){

		$response = GetModel::getData($table, $orderBy, $orderMode, $startAt, $endAt, $select);


		$return = new GetController();
		$return -> fncResponse($response, "getData");

	}

	/*=============================================
	Peticiones GET con filtro
	=============================================*/

	public function getFilterData($table, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt, $select){

		$response = GetModel::getFilterData($table, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt, $select);

		$return = new GetController();
		$return -> fncResponse($response, "getFilterData");

	}

	/*=============================================
	Peticiones GET tablas relacionadas sin filtro
	=============================================*/

	public function getRelData($rel, $type, $orderBy, $orderMode, $startAt, $endAt, $select){

		$response = GetModel::getRelData($rel, $type, $orderBy, $orderMode, $startAt, $endAt, $select);

		$return = new GetController();
		$return -> fncResponse($response, "getRelData");

	}

	/*=============================================
	Peticiones GET tablas relacionadas con filtro
	=============================================*/

	public function getRelFilterData($rel, $type, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt, $select){

		$response = GetModel::getRelFilterData($rel, $type, $linkTo, $equalTo, $orderBy, $orderMode, $startAt, $endAt, $select);

		$return = new GetController();
		$return -> fncResponse($response, "getRelFilterData");

	}

	/*=============================================
	Peticiones GET para el buscador
	=============================================*/

	public function getSearchData($table, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt, $select){

		$response = GetModel::getSearchData($table, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt, $select);

		$return = new GetController();
		$return -> fncResponse($response, "getSearchData");

	}

	/*=============================================
	Peticiones GET  para el buscador entre tablas relacionadas
	=============================================*/

	public function getSearchRelData($rel, $type, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt, $select){

		$response = GetModel::getSearchRelData($rel, $type, $linkTo, $search, $orderBy, $orderMode, $startAt, $endAt, $select);

		$return = new GetController();
		$return -> fncResponse($response, "getSearchRelData");

	}

	/*=============================================
	Peticiones GET para rangos
	=============================================*/

	public function getBetweenData($table, $linkTo, $between1, $between2, $filterTo, $inTo, $orderBy, $orderMode, $startAt, $endAt, $select){

		$response = GetModel::getBetweenData($table, $linkTo, $between1, $between2, $filterTo, $inTo, $orderBy, $orderMode, $startAt, $endAt, $select);

		$return = new GetController();
		$return -> fncResponse($response, "getBetweenData");

	}

	/*=============================================
	Peticiones GET  para rangos entre tablas relacionadas
	=============================================*/

	public function getBetweenRelData($rel, $type, $linkTo, $between1, $between2, $filterTo, $inTo, $orderBy, $orderMode, $startAt, $endAt, $select){

		$response = GetModel::getBetweenRelData($rel, $type, $linkTo, $between1, $between2, $filterTo, $inTo, $orderBy, $orderMode, $startAt, $endAt, $select);

		$return = new GetController();
		$return -> fncResponse($response, "getBetweenRelData");

	}

	/*=============================================
	Respuestas del controlador
	=============================================*/

	public function fncResponse($response, $method){

		if(!empty($response)){

			$json = array(
				'status' => 200,
				'total' => count($response),
				"results" => $response
			);

		}else{

			$json = array(
				'status' => 404,
				"results" => "Not Found",
				'method' => $method
			);

		}

		echo json_encode($json, http_response_code($json["status"]));

		return;

	}






}

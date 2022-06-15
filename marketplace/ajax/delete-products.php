<?php

require_once "../controllers/curl.controller.php";

class DeleteController{

	public $id;

	public function ajaxDeleteProduct(){

		$select = "url_category,image_product,gallery_product,top_banner_product,default_banner_product,horizontal_slider_product,vertical_slider_product";
		$url = CurlController::api()."relations?rel=products,categories,subcategories,stores&type=product,category,subcategory,store&linkTo=id_product&equalTo=".$this->id."&select=".$select;
		$method = "GET";
		$fields = array();
		$header = array();

		$dataProduct = CurlController::request($url, $method, $fields, $header)->results[0];
		
		/*=============================================
		Borrar Imagen
		=============================================*/
		
		unlink("../views/img/products/".$dataProduct->url_category."/".$dataProduct->image_product);

		/*=============================================
		Borrar Galeria de producto
		=============================================*/

		foreach (json_decode($dataProduct->gallery_product,true) as $key => $value) {
		
			unlink("../views/img/products/".$dataProduct->url_category."/gallery/".$value);

		}

		/*=============================================
		Borrar Top Banner
		=============================================*/
		
		unlink("../views/img/products/".$dataProduct->url_category."/top/".json_decode($dataProduct->top_banner_product,true)["IMG tag"]);

		/*=============================================
		Borrar Default Banner
		=============================================*/
		
		unlink("../views/img/products/".$dataProduct->url_category."/default/".$dataProduct->default_banner_product);

		/*=============================================
		Borrar Horizontal Slider
		=============================================*/
		
		unlink("../views/img/products/".$dataProduct->url_category."/horizontal/".json_decode($dataProduct->horizontal_slider_product,true)["IMG tag"]);

		/*=============================================
		Borrar Vertical Slider
		=============================================*/
		
		unlink("../views/img/products/".$dataProduct->url_category."/vertical/".$dataProduct->vertical_slider_product);
	}


}

if(isset($_POST["idProduct"])){


	$idProduct = new DeleteController();
	$idProduct -> id = $_POST["idProduct"];
	$idProduct -> ajaxDeleteProduct();

}



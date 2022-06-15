<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class TemplateController{

	/*=============================================
	Traemos la Vista Principal de la plantilla
	=============================================*/

	public function index(){

		include "views/template.php";
	}

	/*=============================================
	Ruta Principal o Dominio del sitio
	=============================================*/

	static public function path(){

		if(!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])){

			//return "https://localhost/DNJTOOL/marketplace/"; // Pruebas Facebook	
			return "https://localhost/marketplace/";
		}else{

			return "http://marketplace.com/";
		}

	}

	/*=============================================
	Ahorro en oferta
	=============================================*/

	static public function savingValue($price, $offer, $type){

		// Cuando la oferta es con descuento

		if($type == "Discount"){

			$save = $offer*$price/100;
			return number_format($save,2);

		}

		// Cuando la oferta es con precio fijo

		if($type == "Fixed"){

			$save = $price - $offer;
			return number_format($save,2);

		}

	}

	/*=============================================
	Precio final de oferta
	=============================================*/

	static public function offerPrice($price, $offer, $type){

		// Cuando la oferta es con descuento

		if($type == "Discount"){

			$offerPrice = $price - ($offer*$price/100);
			return number_format($offerPrice,2);

		}

		// Cuando la oferta es con precio fijo

		if($type == "Fixed"){

			return number_format($offer,2);

		}

	}

	/*=============================================
	Promediar reseñas
	=============================================*/

	static public function averageReviews($reviews){

		$totalReviews = 0;

		if($reviews != null){

			foreach ($reviews as $key => $value) {
			
				$totalReviews += $value["review"];
			}

			return round($totalReviews/count($reviews));

		}else{

			return 0;
		}

	}

	/*=============================================
	Descuento de la oferta
	=============================================*/

	static public function offerDiscount($price, $offer, $type){

		// Cuando la oferta es con descuento

		if($type == "Discount"){

			return $offer;

		}

		// Cuando la oferta es con precio fijo

		if($type == "Fixed"){

			$offerDiscount = $offer*100/$price;
			return round($offerDiscount);

		}

	}

	/*=============================================
	Función para mayúscula inicial
	=============================================*/

	static public function capitalize($value){

		$text = str_replace("_", " ", $value);

		return ucwords($text);
	}

	/*=============================================
	Función para enviar correos electrónicos
	=============================================*/

	static public function sendEmail($name, $subject, $email, $message, $url){

		date_default_timezone_set("America/Bogota");

		$mail = new PHPMailer;

		$mail->Charset = "UTF-8";

		$mail->isMail();

		$mail->setFrom("support@marketplace.com", "Marketplace Support");

		$mail->Subject = "Hi ".$name." - ".$subject;

		$mail->addAddress($email);

		$mail->msgHTML(' 

			<div>

				Hi, '.$name.':

				<p>'.$message.'</p>

				<a href="'.$url.'">Click this link for more information</a>

				If you didn’t ask to verify this address, you can ignore this email.

				Thanks,

				Your Marketplace Team

			</div>

		');

		$send = $mail->Send();

		if(!$send){

			return $mail->ErrorInfo;	

		}else{

			return "ok";

		}

	}

	/*=============================================
	Función para almacenar imágenes
	=============================================*/

	static public function saveImage($image, $folder, $path, $width, $height, $name){

		if(isset($image["tmp_name"]) && !empty($image["tmp_name"])){ 

			/*=============================================
			Configuramos la ruta del directorio donde se guardará la imagen
			=============================================*/

			$directory = strtolower("views/".$folder."/".$path);

			/*=============================================
			Preguntamos primero si no existe el directorio, para crearlo
			=============================================*/

			if(!file_exists($directory)){

				mkdir($directory, 0755);

			}

			/*=============================================
			Eliminar todos los archivos que existan en ese directorio
			=============================================*/

			if($folder != "img/products" && $folder != "img/stores"){

				$files = glob($directory."/*");

				foreach ($files as $file) {
					
					unlink($file);
				}

			}
			
			/*=============================================
			Capturar ancho y alto original de la imagen
			=============================================*/

			list($lastWidth, $lastHeight) = getimagesize($image["tmp_name"]);

			/*=============================================
			De acuerdo al tipo de imagen aplicamos las funciones por defecto
			=============================================*/

			if($image["type"] == "image/jpeg"){

				//definimos nombre del archivo
				$newName  = $name.'.jpg';

				//definimos el destino donde queremos guardar el archivo
				$folderPath = $directory.'/'.$newName;

				if(isset($image["mode"]) && $image["mode"] == "base64"){

					file_put_contents($folderPath, file_get_contents($image["tmp_name"]));

				}else{

					//Crear una copia de la imagen
					$start = imagecreatefromjpeg($image["tmp_name"]);

					//Instrucciones para aplicar a la imagen definitiva
					$end = imagecreatetruecolor($width, $height);

					imagecopyresized($end, $start, 0, 0, 0, 0, $width, $height, $lastWidth, $lastHeight);

					imagejpeg($end, $folderPath);

				}

			}

			if($image["type"] == "image/png"){

				//definimos nombre del archivo
				$newName  = $name.'.png';

				//definimos el destino donde queremos guardar el archivo
				$folderPath = $directory.'/'.$newName;

				if(isset($image["mode"]) && $image["mode"] == "base64"){

					file_put_contents($folderPath, file_get_contents($image["tmp_name"]));

				}else{

					//Crear una copia de la imagen
					$start = imagecreatefrompng($image["tmp_name"]);

					//Instrucciones para aplicar a la imagen definitiva
					$end = imagecreatetruecolor($width, $height);

					imagealphablending($end, FALSE);
					
					imagesavealpha($end, TRUE);	

					imagecopyresampled($end, $start, 0, 0, 0, 0, $width, $height, $lastWidth, $lastHeight);

					imagepng($end, $folderPath);

				}

			}

			return $newName;

		}else{

			return "error";

		}

	}

	/*=============================================
	Función Limpiar HTML
	=============================================*/	

	static public function htmlClean($code){

		$search = array('/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s');

		$replace = array('>','<','\\1');

		$code = preg_replace($search, $replace, $code);

		$code = str_replace("> <", "><", $code);

		return $code;

	}

}


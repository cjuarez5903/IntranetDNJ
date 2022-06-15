<?php

/*=============================================
CORS
=============================================*/

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('content-type: application/json; charset=utf-8');

if(isset(getallheaders()["Authorization"]) || getallheaders()["Authorization"] == 'c5LTA6WPbMwHhEabYu77nN9cn4VcMj'){

	/*=============================================
	Crear archivo en el servidor
	=============================================*/

	if(isset($_POST["file"]) && !empty($_POST["file"])){ 

		/*=============================================
		Configuramos la ruta del directorio donde se guardará la imagen
		=============================================*/

		$directory = strtolower($_POST["folder"]);

		/*=============================================
		Preguntamos primero si no existe el directorio, para crearlo
		=============================================*/

		if(!file_exists($directory)){

			mkdir($directory, 0755);

		}
		
		/*=============================================
		Capturar ancho y alto original de la imagen
		=============================================*/

		list($lastWidth, $lastHeight) = getimagesize($_POST["file"]);

		/*=============================================
		De acuerdo al tipo de imagen aplicamos las funciones por defecto
		=============================================*/

		if($_POST["type"] == "image/jpeg"){

			//definimos nombre del archivo
			$newName  = $_POST["name"].'.jpg';

			//definimos el destino donde queremos guardar el archivo
			$folderPath = $directory.'/'.$newName;

			if(isset($_POST["mode"]) && $_POST["mode"] == "base64"){

				file_put_contents($folderPath, file_get_contents($_POST["file"]));

			}else{

				//Crear una copia de la imagen
				$start = imagecreatefromjpeg($_POST["file"]);

				//Instrucciones para aplicar a la imagen definitiva
				$end = imagecreatetruecolor($_POST["width"], $_POST["height"]);

				imagecopyresized($end, $start, 0, 0, 0, 0, $_POST["width"], $_POST["height"], $lastWidth, $lastHeight);

				imagejpeg($end, $folderPath);

			}

		}

		if($_POST["type"] == "image/png"){

			//definimos nombre del archivo
			$newName  = $_POST["name"].'.png';

			//definimos el destino donde queremos guardar el archivo
			$folderPath = $directory.'/'.$newName;

			if(isset($_POST["mode"]) && $_POST["mode"] == "base64"){

				file_put_contents($folderPath, file_get_contents($_POST["file"]));

			}else{

				//Crear una copia de la imagen
				$start = imagecreatefrompng($_POST["file"]);

				//Instrucciones para aplicar a la imagen definitiva
				$end = imagecreatetruecolor($_POST["width"], $_POST["height"]);

				imagealphablending($end, FALSE);
				
				imagesavealpha($end, TRUE);	

				imagecopyresampled($end, $start, 0, 0, 0, 0, $_POST["width"], $_POST["height"], $lastWidth, $lastHeight);

				imagepng($end, $folderPath);

			}

		}

		if($_POST["type"] == "image/gif"){

			//definimos nombre del archivo
			$newName  = $_POST["name"].'.gif';

			//definimos el destino donde queremos guardar el archivo
			$folderPath = $directory.'/'.$newName;

			move_uploaded_file($_POST["file"],$folderPath);
		}

		echo $newName;

	}

	/*=============================================
	Borrar archivo en el servidor
	=============================================*/

	else if(isset($_POST["deleteFile"])){
 		
 		/*=============================================
		Borramos el archivo
		=============================================*/	
		unlink($_SERVER['DOCUMENT_ROOT']."/views/img/".$_POST["deleteFile"]);

	
		$arrayDelete = explode("/",$_POST["deleteFile"]);
		array_pop($arrayDelete);
		$arrayDelete = implode("/",$arrayDelete);

		/*=============================================
		Borramos todos los posibles archivos del directorio
		=============================================*/

		$files = glob($_SERVER['DOCUMENT_ROOT']."/views/img/".$arrayDelete."/*");

		foreach ($files as $file) {
			unlink($file);
		}	

		/*=============================================
		Borramos el directorio
		=============================================*/	
		rmdir($_SERVER['DOCUMENT_ROOT']."/views/img/".$arrayDelete);

		echo "ok";

	}else{

		echo "error";

	}

}


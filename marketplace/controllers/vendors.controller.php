<?php 

class VendorsController{

	/*=============================================
	Registro de nueva tienda y producto
	=============================================*/	

	public function newVendor(){

		/*=============================================
		Validar que si vengan variables Post
		=============================================*/	

		if( isset($_POST["nameStore"]) ){

			/*=============================================
		 	Validar sintaxis lado servidor
			=============================================*/
			
			if(preg_match('/^[A-Za-z0-9ñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["nameStore"]) &&
		       preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,1000}$/', $_POST["aboutStore"]) &&
		       preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["cityStore"]) && 
		       preg_match('/^[-\\(\\)\\0-9 ]{1,}$/', $_POST["phoneStore"]) &&
		       preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["addressStore"])){

		       	/*=============================================
		 		Validar imágenes
				=============================================*/

				if( isset($_FILES['logoStore']["tmp_name"]) && 
					!empty($_FILES['logoStore']["tmp_name"]) &&
				    isset($_FILES['coverStore']["tmp_name"]) && 
				    !empty($_FILES['coverStore']["tmp_name"])){

					/*=============================================
		 			Guardar imagen del logo
					=============================================*/

					$imageLogo = $_FILES['logoStore'];
			  		$folderLogo = "img/stores";
			  		$pathLogo = $_POST["urlStore"];
			  		$widthLogo = 270;
			  		$heightLogo = 270;
			  		$nameLogo = "logo";

			  		$saveImageLogo = TemplateController::saveImage($imageLogo, $folderLogo, $pathLogo, $widthLogo, $heightLogo, $nameLogo);

			  		if($saveImageLogo != "error"){

			  			/*=============================================
			 			Guardar imagen del logo
						=============================================*/

						$imageCover = $_FILES['coverStore'];
				  		$folderCover = "img/stores";
				  		$pathCover = $_POST["urlStore"];
				  		$widthCover = 1424;
				  		$heightCover = 768;
				  		$nameCover = "cover";

				  		$saveImageCover = TemplateController::saveImage($imageCover, $folderCover, $pathCover, $widthCover, $heightCover, $nameCover);

				  		if($saveImageCover != "error"){

				  			/*=============================================
			 				Agrupar redes sociales
							=============================================*/
							$socialNetwork = array();

							if(isset($_POST["facebookStore"]) && !empty($_POST["facebookStore"])){

								array_push($socialNetwork, ["facebook"=>"https://facebook.com/".$_POST["facebookStore"]]);

							}

							if(isset($_POST["instagramStore"]) && !empty($_POST["instagramStore"])){

								array_push($socialNetwork, ["instagram"=>"https://instagram.com/".$_POST["instagramStore"]]);

							}

							if(isset($_POST["twitterStore"]) && !empty($_POST["twitterStore"])){

								array_push($socialNetwork, ["twitter"=>"https://twitter.com/".$_POST["twitterStore"]]);

							}

							if(isset($_POST["linkedinStore"]) && !empty($_POST["linkedinStore"])){

								array_push($socialNetwork, ["linkedin"=>"https://linkedin.com/".$_POST["linkedinStore"]]);

							}

							if(isset($_POST["youtubeStore"]) && !empty($_POST["youtubeStore"])){

								array_push($socialNetwork, ["youtube"=>"https://youtube.com/".$_POST["youtubeStore"]]);

							}

							if(count($socialNetwork) > 0){

								$socialNetwork = json_encode($socialNetwork);
							
							}else{

								$socialNetwork = null;
							}

				  			/*=============================================
			 				Organizar los datos que se subiran a BD de la tienda
							=============================================*/
							$dataStore = array(					

								"id_user_store" => $_SESSION["user"]->id_user,
								"name_store" => TemplateController::capitalize($_POST["nameStore"]),
								"url_store" => $_POST["urlStore"],
								"logo_store" => $saveImageLogo,
								"cover_store" => $saveImageCover,
								"about_store" => $_POST["aboutStore"],
								"abstract_store" =>  substr($_POST["aboutStore"], 0, 100)."...",
								"email_store" => $_POST["emailStore"],
								"country_store" => explode("_", $_POST["countryStore"])[0],
								"city_store" => $_POST["cityStore"],
								"phone_store" => explode("_", $_POST["countryStore"])[1]."_".$_POST["phoneStore"],
								"address_store" => $_POST["addressStore"],
								"socialnetwork_store" => $socialNetwork,
								"products_store" => 1,
								"date_created_store" => date("Y-m-d")
							);

							$url = CurlController::api()."stores?token=".$_SESSION["user"]->token_user;
							$method = "POST";
							$fields = $dataStore;
							$header = array(

							   "Content-Type" =>"application/x-www-form-urlencoded"

							);

							$saveStore = CurlController::request($url, $method, $fields, $header);

							if($saveStore->status == 200){

								$saveProduct = 	VendorsController::newProduct($saveStore->results->lastId);
								
								return $saveProduct;

							}else{

							echo '<script>

									fncFormatInputs();

									fncNotie(3, "Error saving store");

								</script>';

								return;
						}


				  		}else{

							echo '<script>

								fncFormatInputs();

								fncNotie(3, "Error saving cover image");

							</script>';

						}

			  		}else{

						echo '<script>

							fncFormatInputs();

							fncNotie(3, "Error saving logo image");

						</script>';

					}

				}else{

					echo '<script>

						fncFormatInputs();

						fncNotie(3, "Error: There are no images of the store");

					</script>';


				}

			}else{

				echo '<script>

					fncFormatInputs();

					fncNotie(3, "Error in the syntax of the fields");

				</script>';

			}

		}

	}

	/*=============================================
	Registro de nuevo producto
	=============================================*/	

	static public function newProduct($idStore){
		
		if( isset($_POST["nameProduct"]) ){
			
			/*=============================================
	 		Validar sintaxis lado servidor
			=============================================*/

			if(preg_match('/^[A-Za-z0-9ñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["nameProduct"])){

				/*=============================================
	 			Agrupar Resumen del Producto
				=============================================*/

				if(isset($_POST["inputSummary"])){	

					$summaryProduct = array();

					for($i = 0; $i < $_POST["inputSummary"]; $i++){

						array_push($summaryProduct, $_POST["summaryProduct_".$i]);

					}

				}

				/*=============================================
	 			Agrupar Detalles del Producto
				=============================================*/

				if(isset($_POST["inputDetails"])){

					$detailsProduct = array();

					for($i = 0; $i < $_POST["inputDetails"]; $i++){

						$detailsProduct[$i] = (object)["title"=>$_POST["detailsTitleProduct_".$i],"value"=>$_POST["detailsValueProduct_".$i]];
					}

				}

				/*=============================================
	 			Agrupar Especificaciones del Producto
				=============================================*/

				if(isset($_POST["inputSpecifications"])){

					$specificationsProduct = array();

					for($i = 0; $i < $_POST["inputSpecifications"]; $i++){

						$specificationsProduct[$i] = (object)[$_POST["specificationsTypeProduct_".$i]=>explode(",",$_POST["specificationsValuesProduct_".$i])];
					}

					$specificationsProduct = json_encode($specificationsProduct);

					if($specificationsProduct == '[{"":[""]}]'){

						$specificationsProduct = null;
					}

				}else{

					$specificationsProduct = null;

				}

				/*=============================================
	 			Validar y guardar imagen del logo
				=============================================*/			

				if(isset($_FILES['imageProduct']["tmp_name"]) && !empty($_FILES['imageProduct']["tmp_name"])){

					$image = $_FILES['imageProduct'];
					$folder = "img/products";
					$path =  explode("_",$_POST["categoryProduct"])[1];
					$width = 300;
					$height = 300;
					$name = $_POST["urlProduct"];

					$saveImageProduct = TemplateController::saveImage($image, $folder, $path, $width, $height, $name);

					if($saveImageProduct == "error"){

						echo '<script>

						fncFormatInputs();

						fncNotie(3, "Failed to save product image");

						</script>';

						return;

					}

				}else{

				echo '<script>

					fncFormatInputs();

					fncNotie(3, "Failed to save product image");

					</script>';

					return;

				}

				/*=============================================
	 			Guardar imágenes galería
				=============================================*/

				$galleryProduct = array();
				$count = 0;

				foreach (json_decode($_POST['galleryProduct'],true) as $key => $value) {

					$count ++;

					$image["tmp_name"] = $value["file"];
					$image["type"] = $value["type"];
					$image["mode"] = "base64";
					
					$folder = "img/products";
					$path =  explode("_",$_POST["categoryProduct"])[1]."/gallery";
					$width = $value["width"];
					$height = $value["height"];
					$name = mt_rand(10000, 99999);

					$saveImageGallery  = TemplateController::saveImage($image, $folder, $path, $width, $height, $name);

					array_push($galleryProduct, $saveImageGallery);


				}

				if(count($galleryProduct) == $count){

					/*=============================================
		 			Agrupar información para Top Banner
					=============================================*/

					if(isset($_FILES['topBanner']["tmp_name"]) && !empty($_FILES['topBanner']["tmp_name"])){

						$image = $_FILES['topBanner'];
						$folder = "img/products";
						$path =  explode("_",$_POST["categoryProduct"])[1]."/top";
						$width = 1920;
						$height = 80;
						$name = mt_rand(10000, 99999);

						$saveImageTopBanner  = TemplateController::saveImage($image, $folder, $path, $width, $height, $name);

						if($saveImageTopBanner != "error"){

							if(isset($_POST['topBannerH3Tag']) && 
				  			   preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['topBannerH3Tag']) &&
				  				isset($_POST['topBannerP1Tag']) && 
				  				preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['topBannerP1Tag']) &&
				  				isset($_POST['topBannerH4Tag']) &&
				  				preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['topBannerH4Tag']) &&
				  			    isset($_POST['topBannerP2Tag']) &&
				  			    preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['topBannerP2Tag']) &&
				  			    isset($_POST['topBannerSpanTag']) &&
				  			    preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['topBannerSpanTag']) &&
				  			    isset($_POST['topBannerButtonTag']) &&
				  			    preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['topBannerButtonTag'])
				  			){

								$topBanner = (object)[

									"H3 tag"=>TemplateController::capitalize($_POST['topBannerH3Tag']),
				  					"P1 tag"=>TemplateController::capitalize($_POST['topBannerP1Tag']),
				  					"H4 tag"=>TemplateController::capitalize($_POST['topBannerH4Tag']),
				  					"P2 tag"=>TemplateController::capitalize($_POST['topBannerP2Tag']),
				  					"Span tag"=>TemplateController::capitalize($_POST['topBannerSpanTag']),
				  					"Button tag"=>TemplateController::capitalize($_POST['topBannerButtonTag']),
				  					"IMG tag"=>$saveImageTopBanner

								];

				  			}else{

								echo '<script>

									fncFormatInputs();

									fncNotie(3, "Error in the syntax of the fields of Top Banner");

								</script>';

								return;

							}


						}


					}else{

						echo '<script>

						fncFormatInputs();

						fncNotie(3, "Failed to save product top banner");

						</script>';

						return;

					}

					/*=============================================
		 			Agrupar información para Default Banner
					=============================================*/

					if(isset($_FILES['defaultBanner']["tmp_name"]) && !empty($_FILES['defaultBanner']["tmp_name"])){


						$image = $_FILES['defaultBanner'];
						$folder = "img/products";
						$path =  explode("_",$_POST["categoryProduct"])[1]."/default";
						$width = 570;
						$height = 210;
						$name = mt_rand(10000, 99999);

						$saveImageDefaultBanner  = TemplateController::saveImage($image, $folder, $path, $width, $height, $name);

						if($saveImageDefaultBanner == "error"){

				  			echo '<script>

								fncFormatInputs();

								fncNotie(3, "Error saving default banner image");

							</script>';

							return;
				  		}

					}else{

						echo '<script>

						fncFormatInputs();

						fncNotie(3, "Failed to save product default banner");

						</script>';

						return;

					}

					/*=============================================
		 			Agrupar información para Horizontal Slider
					=============================================*/

					if(isset($_FILES['hSlider']["tmp_name"]) && !empty($_FILES['hSlider']["tmp_name"])){

						$image = $_FILES['hSlider'];
						$folder = "img/products";
						$path =  explode("_",$_POST["categoryProduct"])[1]."/horizontal";
						$width = 1920;
						$height = 358;
						$name = mt_rand(10000, 99999);

						$saveImageHSlider  = TemplateController::saveImage($image, $folder, $path, $width, $height, $name);

						if($saveImageHSlider != "error"){

								if(isset($_POST['hSliderH4Tag']) && 
					  			   preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['hSliderH4Tag']) &&
					  				isset($_POST['hSliderH3_1Tag']) && 
					  				preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['hSliderH3_1Tag']) &&
					  				isset($_POST['hSliderH3_2Tag']) &&
					  				preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['hSliderH3_2Tag']) &&
					  			    isset($_POST['hSliderH3_3Tag']) &&
					  			    preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['hSliderH3_3Tag']) &&
					  			    isset($_POST['hSliderH3_4sTag']) &&
					  			    preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['hSliderH3_4sTag']) &&
					  			    isset($_POST['hSliderButtonTag']) &&
					  			    preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['hSliderButtonTag'])
					  			){

								$hSlider = (object)[

				  					"H4 tag"=>TemplateController::capitalize($_POST['hSliderH4Tag']),
				  					"H3-1 tag"=>TemplateController::capitalize($_POST['hSliderH3_1Tag']),
				  					"H3-2 tag"=>TemplateController::capitalize($_POST['hSliderH3_2Tag']),
				  					"H3-3 tag"=>TemplateController::capitalize($_POST['hSliderH3_3Tag']),
				  					"H3-4s tag"=>TemplateController::capitalize($_POST['hSliderH3_4sTag']),
				  					"Button tag"=>TemplateController::capitalize($_POST['hSliderButtonTag']),
				  					"IMG tag"=>$saveImageHSlider

				  				];

				  			}else{

								echo '<script>

									fncFormatInputs();

									fncNotie(3, "Error in the syntax of the fields of Top Banner");

								</script>';

								return;

							}


						}


					}else{

						echo '<script>

						fncFormatInputs();

						fncNotie(3, "Failed to save product horizontal slider");

						</script>';

						return;

					}

					/*=============================================
		 			Agrupar información para Vertical Slider
					=============================================*/

					if(isset($_FILES['vSlider']["tmp_name"]) && !empty($_FILES['vSlider']["tmp_name"])){

						$image = $_FILES['vSlider'];
						$folder = "img/products";
						$path =  explode("_",$_POST["categoryProduct"])[1]."/vertical";
						$width = 263;
						$height = 629;
						$name = mt_rand(10000, 99999);

						$saveImageVSlider  = TemplateController::saveImage($image, $folder, $path, $width, $height, $name);

						if($saveImageVSlider == "error"){

				  			echo '<script>

								fncFormatInputs();

								fncNotie(3, "Error saving vertical slider image");

							</script>';

							return;
				  		}


					}else{

						echo '<script>

						fncFormatInputs();

						fncNotie(3, "Failed to save product vertical slider");

						</script>';

						return;

					}

					/*=============================================
					Agrupar información para el video
					=============================================*/

					if(!empty($_POST['type_video']) && 
						!empty($_POST['id_video']) 
					){

						$video_product = array();

					if(preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,100}$/', $_POST['id_video'])){

							array_push($video_product, $_POST['type_video']);
							array_push($video_product, $_POST['id_video']);

							$video_product = json_encode($video_product);

						}else{

							echo '<script>

							fncFormatInputs();

							fncNotie(3, "Error in the syntax of the fields of Video");

							</script>';

							return;

						}

					}else{

						$video_product = null;
					}

					/*=============================================
					Agrupar información de oferta
					=============================================*/

					if(!empty($_POST["type_offer"]) && 
						!empty($_POST["value_offer"]) &&
						!empty($_POST["date_offer"])
					){

						if(preg_match('/^[.\\,\\0-9]{1,}$/', $_POST['value_offer'])){

							$offer_product = array($_POST["type_offer"], $_POST["value_offer"], $_POST["date_offer"] );

							$offer_product = json_encode($offer_product);

						}else{

							echo '<script>

							fncFormatInputs();

							fncNotie(3, "Error in the syntax of the fields of Offer");

							</script>';

							return;

						}

					}else{

						$offer_product = null;

					}

					/*=============================================
					Validar información de precio venta, precio envío, dias de entrega y stock
					=============================================*/

					if(isset($_POST["price"]) &&
					preg_match('/^[.\\,\\0-9]{1,}$/', $_POST["price"]) &&
					isset($_POST["shipping"]) &&
					preg_match('/^[.\\,\\0-9]{1,}$/', $_POST["shipping"]) &&
					isset($_POST["delivery_time"]) &&
					preg_match('/^[0-9]{1,}$/', $_POST["delivery_time"]) &&
					isset($_POST["stock"]) &&
					preg_match('/^[0-9]{1,}$/', $_POST["stock"])
					){


						/*=============================================
			 			Data del producto
						=============================================*/			
						
						$dataProduct = array(
							
							"approval_product" => "review",
							"feedback_product" => "Your product is under review",
							"state_product" => "show",
							"id_store_product" => $idStore,
							"name_product" => TemplateController::capitalize($_POST["nameProduct"]),
							"url_product" => $_POST["urlProduct"],
							"id_category_product" => explode("_",$_POST["categoryProduct"])[0],
							"id_subcategory_product" => explode("_",$_POST["subCategoryProduct"])[0],
							"title_list_product" => explode("_",$_POST["subCategoryProduct"])[1],
							"description_product" => $_POST["descriptionProduct"],
							"summary_product" => json_encode($summaryProduct),
							"details_product" => json_encode($detailsProduct),
							"specifications_product" => $specificationsProduct,
							"tags_product" => json_encode(explode(",",$_POST["tagsProduct"])),
							"image_product" => $saveImageProduct,
							"gallery_product" => json_encode($galleryProduct),
							"top_banner_product" => json_encode($topBanner),
							"default_banner_product" => $saveImageDefaultBanner,
							"horizontal_slider_product" => json_encode($hSlider),
							"vertical_slider_product" => $saveImageVSlider,
							"video_product" => $video_product,
							"offer_product" => $offer_product,
							"price_product" => $_POST["price"],
							"shipping_product" => $_POST["shipping"],
							"delivery_time_product" => $_POST["delivery_time"],
							"stock_product" => $_POST["stock"],
							"date_created_product" => date("Y-m-d")

						);

						$url = CurlController::api()."products?token=".$_SESSION["user"]->token_user;
						$method = "POST";
						$fields = $dataProduct;
						$header = array(

						   "Content-Type" =>"application/x-www-form-urlencoded"

						);

						$saveProduct = CurlController::request($url, $method, $fields, $header);

						if($saveProduct->status == 200){

							echo '<script>

								fncFormatInputs();

								fncSweetAlert(
									"success",
									"Your records were created successfully",
									"'.TemplateController::path().'account&my-store"
								);

							</script>';



						}else{

							echo '<script>

							fncFormatInputs();

							fncNotie(3, "Error saving product");

						</script>';


						}


					}else{

		  				echo '<script>

							fncFormatInputs();

							fncNotie(3, "Error in the syntax of the fields of Price");

						</script>';

						return;

		  			}

				}


			}else{

				echo '<script>

					fncFormatInputs();

					fncNotie(3, "Error in the syntax of the fields of Name Product");

				</script>';

			}


		}

	}

	/*=============================================
	Registro de nueva tienda y producto
	=============================================*/	

	public function editStore(){

		/*=============================================
		Validar que si vengan variables Post
		=============================================*/	

		if( isset($_POST["idStore"]) ){

			/*=============================================
		 	Validar sintaxis lado servidor
			=============================================*/
			
			if(preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,1000}$/', $_POST["aboutStore"]) &&
		       preg_match('/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["cityStore"]) && 
		       preg_match('/^[-\\(\\)\\0-9 ]{1,}$/', $_POST["phoneStore"]) &&
		       preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["addressStore"])){

		       	/*=============================================
		 		Guardar imagen del logo
				=============================================*/

				if(isset($_FILES['logoStore']["tmp_name"]) && 
				   !empty($_FILES['logoStore']["tmp_name"])){

					$imageLogo = $_FILES['logoStore'];
			  		$folderLogo = "img/stores";
			  		$pathLogo = $_POST["urlStore"];
			  		$widthLogo = 270;
			  		$heightLogo = 270;
			  		$nameLogo = "logo";

			  		$saveImageLogo = TemplateController::saveImage($imageLogo, $folderLogo, $pathLogo, $widthLogo, $heightLogo, $nameLogo);

			  		if($saveImageLogo == "error"){

			  			echo '<script>

							fncFormatInputs();

							fncNotie(3, "Error saving default logo image");

						</script>';

						return;

			  		}

				}else{

					$saveImageLogo = $_POST["logoStoreOld"];

				}

				/*=============================================
		 		Guardar imagen de la portada
				=============================================*/

				if(isset($_FILES['coverStore']["tmp_name"]) && 
				    !empty($_FILES['coverStore']["tmp_name"])){

					/*=============================================
		 			Guardar imagen de la portada
					=============================================*/

					$imageCover = $_FILES['coverStore'];
			  		$folderCover = "img/stores";
			  		$pathCover = $_POST["urlStore"];
			  		$widthCover = 1424;
			  		$heightCover = 768;
			  		$nameCover = "cover";

			  		$saveImageCover = TemplateController::saveImage($imageCover, $folderCover, $pathCover, $widthCover, $heightCover, $nameCover);

			  		if($saveImageCover == "error"){

			  			echo '<script>

							fncFormatInputs();

							fncNotie(3, "Error saving default cover image");

						</script>';

						return;

			  		}


				}else{

					$saveImageCover = $_POST["coverStoreOld"];

				}

	  			/*=============================================
 				Agrupar redes sociales
				=============================================*/
				$socialNetwork = array();

				if(isset($_POST["facebookStore"]) && !empty($_POST["facebookStore"])){

					array_push($socialNetwork, ["facebook"=>"https://facebook.com/".$_POST["facebookStore"]]);

				}

				if(isset($_POST["instagramStore"]) && !empty($_POST["instagramStore"])){

					array_push($socialNetwork, ["instagram"=>"https://instagram.com/".$_POST["instagramStore"]]);

				}

				if(isset($_POST["twitterStore"]) && !empty($_POST["twitterStore"])){

					array_push($socialNetwork, ["twitter"=>"https://twitter.com/".$_POST["twitterStore"]]);

				}

				if(isset($_POST["linkedinStore"]) && !empty($_POST["linkedinStore"])){

					array_push($socialNetwork, ["linkedin"=>"https://linkedin.com/".$_POST["linkedinStore"]]);

				}

				if(isset($_POST["youtubeStore"]) && !empty($_POST["youtubeStore"])){

					array_push($socialNetwork, ["youtube"=>"https://youtube.com/".$_POST["youtubeStore"]]);

				}

				if(count($socialNetwork) > 0){

					$socialNetwork = json_encode($socialNetwork);
				
				}else{

					$socialNetwork = null;
				}

	  			/*=============================================
 				Organizar los datos que se subiran a BD de la tienda
				=============================================*/
				$dataStore = "logo_store=".$saveImageLogo."&cover_store=".$saveImageCover."&about_store=".$_POST["aboutStore"]."&abstract_store=". substr($_POST["aboutStore"], 0, 100)."..."."&email_store=".$_POST["emailStore"]."&country_store=".explode("_", $_POST["countryStore"])[0]."&city_store=".$_POST["cityStore"]."&phone_store=".explode("_", $_POST["countryStore"])[1]."_".$_POST["phoneStore"]."&address_store=".$_POST["addressStore"]."&socialnetwork_store=".$socialNetwork;

				$url = CurlController::api()."stores?id=".$_POST["idStore"]."&nameId=id_store&token=".$_SESSION["user"]->token_user;
				$method = "PUT";
				$fields = $dataStore;
				$header = array(

				   "Content-Type" =>"application/x-www-form-urlencoded"

				);

				$editStore = CurlController::request($url, $method, $fields, $header);

				if($editStore->status == 200){

					echo '<script>

						fncFormatInputs();

						fncSweetAlert(
							"success",
							"Your store were edited successfully",
							"'.TemplateController::path().'account&my-store"
						);

					</script>';

				}else{

					echo '<script>

							fncFormatInputs();

							fncNotie(3, "Error saving store");

						</script>';

						return;
				}

			}else{

				echo '<script>

					fncFormatInputs();

					fncNotie(3, "Error in the syntax of the fields");

				</script>';

			}

		}

	}


	/*=============================================
	Editar producto
	=============================================*/	

	static public function editProduct(){

		if(isset($_POST["id_product"])){

			/*=============================================
 			Agrupar Resumen del Producto
			=============================================*/

			if(isset($_POST["inputSummary"])){				

				$summaryProduct = array();

				for($i = 0; $i < $_POST["inputSummary"]; $i++){

					array_push($summaryProduct, $_POST["summaryProduct_".$i]);

				}

			}

			/*=============================================
 			Agrupar Detalles del Producto
			=============================================*/

			if(isset($_POST["inputDetails"])){

				$detailsProduct = array();

				for($i = 0; $i < $_POST["inputDetails"]; $i++){

					$detailsProduct[$i] = (object)["title"=>$_POST["detailsTitleProduct_".$i],"value"=>$_POST["detailsValueProduct_".$i]];

				}

			}

			/*=============================================
 			Agrupar Especificaciones del Producto
			=============================================*/

			if(isset($_POST["inputSpecifications"])){

				$specificationsProduct = array();

				for($i = 0; $i < $_POST["inputSpecifications"]; $i++){

					$specificationsProduct[$i] = (object)[$_POST["specificationsTypeProduct_".$i]=>explode(",",$_POST["specificationsValuesProduct_".$i])];
				}

				$specificationsProduct = json_encode($specificationsProduct);

				if($specificationsProduct == '[{"":[""]}]'){

					$specificationsProduct = null;
				}

			}else{

				$specificationsProduct = null;

			}

			/*=============================================
	 		Guardar imagen del logo
			=============================================*/

			if(isset($_FILES['imageProduct']["tmp_name"]) && !empty($_FILES['imageProduct']["tmp_name"])){

				$imageProduct = $_FILES['imageProduct'];
		  		$folderProduct = "img/products";
		  		$pathProduct = explode("_",$_POST["categoryProduct"])[1];
		  		$widthProduct = 300;
		  		$heightProduct = 300;
		  		$nameProduct = $_POST["urlProduct"];

		  		$saveImageProduct = TemplateController::saveImage($imageProduct, $folderProduct, $pathProduct, $widthProduct, $heightProduct, $nameProduct);

		  		if($saveImageProduct == "error"){

		  			echo '<script>

						fncFormatInputs();

						fncNotie(3, "Error saving product image");

					</script>';

					return;

		  		}

			}else{

				$saveImageProduct = $_POST['imageProductOld'];

			}

			/*=============================================
 			Guardar imágenes galería
			=============================================*/

			$galleryProduct = array();
			$count = 0;
			$count2 = 0;
			$continueEdit = false;

			if(!empty($_POST['galleryProduct'])){	

				foreach (json_decode($_POST['galleryProduct'],true) as $key => $value) {

					$count ++;

					$image["tmp_name"] = $value["file"];
					$image["type"] = $value["type"];
					$image["mode"] = "base64";
					
					$folder = "img/products";
					$path =  explode("_",$_POST["categoryProduct"])[1]."/gallery";
					$width = $value["width"];
					$height = $value["height"];
					$name = mt_rand(10000, 99999);

					$saveImageGallery  = TemplateController::saveImage($image, $folder, $path, $width, $height, $name);

					array_push($galleryProduct, $saveImageGallery);

					if(count($galleryProduct) == $count){

						if(!empty($_POST['galleryProductOld'])){

							foreach (json_decode($_POST['galleryProductOld'],true) as $key => $value) {

								$count2++;
								array_push($galleryProduct, $value);
							}

							if(count(json_decode($_POST['galleryProductOld'],true)) == $count2){

			  					$continueEdit = true;

			  				}

						}else{

							$continueEdit = true;
						}

					}

				}

			}else{

				if(!empty($_POST['galleryProductOld'])){

					$count2 = 0;

					foreach (json_decode($_POST['galleryProductOld'],true) as $key => $value) {

						$count2++;
						array_push($galleryProduct, $value);
					}

					if(count(json_decode($_POST['galleryProductOld'],true)) == $count2){

	  					$continueEdit = true;

	  				}

				}

			}

			/*=============================================
 			Eliminamos archivos basura del servidor
			=============================================*/

			if(!empty($_POST['deleteGalleryProduct'])){

				foreach (json_decode($_POST['deleteGalleryProduct'],true) as $key => $value) {

					unlink("views/img/products/".explode("_",$_POST["categoryProduct"])[1]."/gallery/".$value);

				}

			}

			/*=============================================
 			Validamos que no venga la galería vacía
			=============================================*/

			if(count($galleryProduct) == 0){

  				echo '<script>

					fncFormatInputs();

					fncNotie(3, "The gallery cannot be empty");

				</script>';

				return;

  			}

			if($continueEdit){

				/*=============================================
	 			Agrupar información para Top Banner
				=============================================*/

				if(isset($_FILES['topBanner']["tmp_name"]) && !empty($_FILES['topBanner']["tmp_name"])){

					$imageTopBanner = $_FILES['topBanner'];
			  		$folderTopBanner = "img/products";
			  		$pathTopBanner = explode("_",$_POST["categoryProduct"])[1]."/top";
			  		$widthTopBanner = 1920;
			  		$heightTopBanner = 80;
			  		$nameTopBanner = mt_rand(10000, 99999);

			  		$saveImageTopBanner = TemplateController::saveImage($imageTopBanner, $folderTopBanner, $pathTopBanner, $widthTopBanner, $heightTopBanner, $nameTopBanner);

			  		if($saveImageTopBanner == "error"){

			  			echo '<script>

							fncFormatInputs();

							fncNotie(3, "Error saving top banner image");

						</script>';

						return;
			  		
			  		}else{

			  			unlink("views/".$folderTopBanner."/".$pathTopBanner."/".$_POST["topBannerOld"]);
			  		}

			  	}else{

			  		$saveImageTopBanner = $_POST["topBannerOld"];
			  	}

			  	if(isset($_POST['topBannerH3Tag']) && 
	  			   preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['topBannerH3Tag']) &&
	  				isset($_POST['topBannerP1Tag']) && 
	  				preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['topBannerP1Tag']) &&
	  				isset($_POST['topBannerH4Tag']) &&
	  				preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['topBannerH4Tag']) &&
	  			    isset($_POST['topBannerP2Tag']) &&
	  			    preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['topBannerP2Tag']) &&
	  			    isset($_POST['topBannerSpanTag']) &&
	  			    preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['topBannerSpanTag']) &&
	  			    isset($_POST['topBannerButtonTag']) &&
	  			    preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['topBannerButtonTag'])
	  			){

	  				$topBanner = (object)[

	  					"H3 tag"=>TemplateController::capitalize($_POST['topBannerH3Tag']),
	  					"P1 tag"=>TemplateController::capitalize($_POST['topBannerP1Tag']),
	  					"H4 tag"=>TemplateController::capitalize($_POST['topBannerH4Tag']),
	  					"P2 tag"=>TemplateController::capitalize($_POST['topBannerP2Tag']),
	  					"Span tag"=>TemplateController::capitalize($_POST['topBannerSpanTag']),
	  					"Button tag"=>TemplateController::capitalize($_POST['topBannerButtonTag']),
	  					"IMG tag"=>$saveImageTopBanner

	  				];

	  			}else{

					echo '<script>

						fncFormatInputs();

						fncNotie(3, "Error in the syntax of the fields of Top Banner");

					</script>';

					return;

				}

				/*=============================================
	 			Agrupar información para Default Banner
				=============================================*/

				if(isset($_FILES['defaultBanner']["tmp_name"]) && !empty($_FILES['defaultBanner']["tmp_name"])){

					$imageDefaultBanner = $_FILES['defaultBanner'];
			  		$folderDefaultBanner = "img/products";
			  		$pathDefaultBanner = explode("_",$_POST["categoryProduct"])[1]."/default";
			  		$widthDefaultBanner = 570;
			  		$heightDefaultBanner = 210;
			  		$nameDefaultBanner = mt_rand(10000, 99999);

			  		$saveImageDefaultBanner = TemplateController::saveImage($imageDefaultBanner, $folderDefaultBanner, $pathDefaultBanner, $widthDefaultBanner, $heightDefaultBanner, $nameDefaultBanner);

			  		if($saveImageDefaultBanner == "error"){

			  			echo '<script>

							fncFormatInputs();

							fncNotie(3, "Error saving default banner image");

						</script>';

						return;

			  		}else{

			  			unlink("views/".$folderDefaultBanner."/".$pathDefaultBanner."/".$_POST["defaultBannerOld"]);
			  		}

			  	}else{

			  		$saveImageDefaultBanner = $_POST["defaultBannerOld"];
			  	}

			  	/*=============================================
	 			Agrupar información para Horizontal Slider
				=============================================*/

				if(isset($_FILES['hSlider']["tmp_name"]) && !empty($_FILES['hSlider']["tmp_name"])){

					$imageHSlider = $_FILES['hSlider'];
			  		$folderHSlider = "img/products";
			  		$pathHSlider = explode("_",$_POST["categoryProduct"])[1]."/horizontal";
			  		$widthHSlider = 1920;
			  		$heightHSlider = 358;
			  		$nameHSlider = mt_rand(10000, 99999);

			  		$saveImageHSlider = TemplateController::saveImage($imageHSlider, $folderHSlider, $pathHSlider, $widthHSlider, $heightHSlider, $nameHSlider);

			  		if($saveImageHSlider == "error"){

						echo '<script>

							fncFormatInputs();

							fncNotie(3, "Error saving horizontal slider image");

						</script>';

						return;

			  		}else{

						unlink("views/".$folderHSlider."/".$pathHSlider."/".$_POST["hSliderOld"]);

					}

				}else{

					$saveImageHSlider  = $_POST["hSliderOld"];
				
				}

				if(isset($_POST['hSliderH4Tag']) && 
	  			   preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['hSliderH4Tag']) &&
	  				isset($_POST['hSliderH3_1Tag']) && 
	  				preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['hSliderH3_1Tag']) &&
	  				isset($_POST['hSliderH3_2Tag']) &&
	  				preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['hSliderH3_2Tag']) &&
	  			    isset($_POST['hSliderH3_3Tag']) &&
	  			    preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['hSliderH3_3Tag']) &&
	  			    isset($_POST['hSliderH3_4sTag']) &&
	  			    preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['hSliderH3_4sTag']) &&
	  			    isset($_POST['hSliderButtonTag']) &&
	  			    preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}$/', $_POST['hSliderButtonTag'])
	  			){

	  				$hSlider = (object)[

	  					"H4 tag"=>TemplateController::capitalize($_POST['hSliderH4Tag']),
	  					"H3-1 tag"=>TemplateController::capitalize($_POST['hSliderH3_1Tag']),
	  					"H3-2 tag"=>TemplateController::capitalize($_POST['hSliderH3_2Tag']),
	  					"H3-3 tag"=>TemplateController::capitalize($_POST['hSliderH3_3Tag']),
	  					"H3-4s tag"=>TemplateController::capitalize($_POST['hSliderH3_4sTag']),
	  					"Button tag"=>TemplateController::capitalize($_POST['hSliderButtonTag']),
	  					"IMG tag"=>$saveImageHSlider

	  				];

	  			}else{

					echo '<script>

						fncFormatInputs();

						fncNotie(3, "Error in the syntax of the fields of Horizontal Slider");

					</script>';

					return;

				}

				/*=============================================
	 			Agrupar información para Vertical Slider
				=============================================*/

				if(isset($_FILES['vSlider']["tmp_name"]) && !empty($_FILES['vSlider']["tmp_name"])){

					$imageVSlider = $_FILES['vSlider'];
			  		$folderVSlider = "img/products";
			  		$pathVSlider = explode("_",$_POST["categoryProduct"])[1]."/vertical";
			  		$widthVSlider = 263;
			  		$heightVSlider = 629;
			  		$nameVSlider = mt_rand(10000, 99999);

			  		$saveImageVSlider = TemplateController::saveImage($imageVSlider, $folderVSlider, $pathVSlider, $widthVSlider, $heightVSlider, $nameVSlider);

			  		if($saveImageVSlider == "error"){

			  			echo '<script>

							fncFormatInputs();

							fncNotie(3, "Error saving vertical slider image");

						</script>';

						return;
			  		
			  		}else{

			  			unlink("views/".$folderVSlider."/".$pathVSlider."/".$_POST["vSliderOld"]);

			  		}

			  	}else{

			  		$saveImageVSlider = $_POST["vSliderOld"];
			  	}

			  	/*=============================================
				Agrupar información para el video
				=============================================*/

				if(!empty($_POST['type_video']) && 
					!empty($_POST['id_video']) 
					){

					$video_product = array();

					if(preg_match('/^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,100}$/', $_POST['id_video'])){

						array_push($video_product, $_POST['type_video']);
						array_push($video_product, $_POST['id_video']);

						$video_product = json_encode($video_product);

						}else{

							echo '<script>

							fncFormatInputs();

							fncNotie(3, "Error in the syntax of the fields of Video");

						</script>';

						return;

					}

				}else{

					$video_product = null;
				}

				/*=============================================
				Agrupar información de oferta
				=============================================*/

				if(!empty($_POST["type_offer"]) && 
					!empty($_POST["value_offer"]) &&
					!empty($_POST["date_offer"])
				){

					if(preg_match('/^[.\\,\\0-9]{1,}$/', $_POST['value_offer'])){

						$offer_product = array($_POST["type_offer"], $_POST["value_offer"], $_POST["date_offer"] );

						$offer_product = json_encode($offer_product);

					}else{

							echo '<script>

							fncFormatInputs();

							fncNotie(3, "Error in the syntax of the fields of Offer");

						</script>';

						return;

					}

				}else{

					$offer_product = null;
				}

				/*=============================================
				Validar información de precio venta, precio envío, dias de entrega y stock
				=============================================*/

				if(isset($_POST["price"]) &&
					preg_match('/^[.\\,\\0-9]{1,}$/', $_POST["price"]) &&
					isset($_POST["shipping"]) &&
					preg_match('/^[.\\,\\0-9]{1,}$/', $_POST["shipping"]) &&
					isset($_POST["delivery_time"]) &&
					preg_match('/^[0-9]{1,}$/', $_POST["delivery_time"]) &&
					isset($_POST["stock"]) &&
					preg_match('/^[0-9]{1,}$/', $_POST["stock"])
					){

					/*=============================================
		 			Crear los datos de la tienda
					=============================================*/

					$dataProduct = "description_product=".TemplateController::htmlClean(html_entity_decode(str_replace('+', '%2b', $_POST["descriptionProduct"])))."&summary_product=".json_encode($summaryProduct)."&details_product=".json_encode($detailsProduct)."&specifications_product=".$specificationsProduct."&tags_product=".json_encode(explode(",",$_POST["tagsProduct"]))."&image_product=".$saveImageProduct."&gallery_product=".json_encode($galleryProduct)."&top_banner_product=".json_encode($topBanner)."&default_banner_product=".$saveImageDefaultBanner."&horizontal_slider_product=".json_encode($hSlider)."&vertical_slider_product=".$saveImageVSlider."&video_product=".$video_product."&offer_product=".$offer_product."&price_product=".$_POST["price"]."&shipping_product=".$_POST["shipping"]."&delivery_time_product=".$_POST["delivery_time"]."&stock_product=".$_POST["stock"];

					$url = CurlController::api()."products?id=".$_POST["id_product"]."&nameId=id_product&token=".$_SESSION["user"]->token_user;
					$method = "PUT";
					$fields = $dataProduct;
					$header = array(

					   "Content-Type" =>"application/x-www-form-urlencoded"

					);

					$saveProduct = CurlController::request($url, $method, $fields, $header);

					if($saveProduct->status == 200){

						echo '<script>

							fncFormatInputs();

							fncSweetAlert(
								"success",
								"Your records were edite successfully",
								"'.TemplateController::path().'account&my-store"
							);

						</script>';



					}else{

						echo '<script>

						fncFormatInputs();

						fncNotie(3, "Error saving product");

					</script>';


					}

				}

			}

		}

	}

	/*=============================================
	Actualizar la orden
	=============================================*/	

	public function orderUpdate(){

		if(isset($_POST["stage"])){

			$process = json_decode(base64_decode($_POST["processOrder"]),true);
			$changeProcess = [];
			
			foreach ($process as $key => $value) {

				if($value["stage"] == $_POST["stage"]){

					$value["date"] = $_POST["date"];

					$value["status"] = $_POST["status"];

					$value["comment"] = $_POST["comment"];
				}

				array_push($changeProcess, $value);
				
			}

			$url = CurlController::api()."orders?id=".$_POST["idOrder"]."&nameId=id_order&token=".$_SESSION["user"]->token_user;
			$method = "PUT";	

			if($_POST["stage"] == "delivered" && $_POST["status"] == "ok"){

				$fields = "status_order=ok&process_order=".json_encode($changeProcess);

				/*=============================================
				Aprobar la venta
				=============================================*/	

				$url2 = CurlController::api()."sales?id=".$_POST["idOrder"]."&nameId=id_order_sale&token=".$_SESSION["user"]->token_user;
				$method2 = "PUT";
				$fields2 = "id_store_sale=".$_POST["idStore"]."&status_sale=ok&name_product_sale=".$_POST["productOrder"];	
				$header2 = array(

				   "Content-Type" =>"application/x-www-form-urlencoded"

				);

				$saleUpdate = CurlController::request($url2, $method2, $fields2, $header2);

			}else{

				$fields = "process_order=".json_encode($changeProcess);

			}

			$header = array(

			   "Content-Type" =>"application/x-www-form-urlencoded"

			);

			$orderUpdate = CurlController::request($url, $method, $fields, $header);

			if($orderUpdate->status == 200){

				/*=============================================
				Enviamos notificación del cambio de orden al correo electrónico
				=============================================*/	

				$name = $_POST["clientOrder"];
				$subject = "A Change has occurred in your purchase order";
				$email = $_POST["emailOrder"];
				$message =  "A Change has occurred in your purchase order for your product ".$_POST["productOrder"];
				$url = TemplateController::path()."account&my-shopping";		

				$sendEmail = TemplateController::sendEmail($name, $subject, $email, $message, $url);

				if($sendEmail == "ok"){

					echo '<script>

							fncFormatInputs();

							fncNotie(1, "The order has been successfully updated");

						</script>
					';

				}

			}

		}

	}

	/*=============================================
	Responder la disputa
	=============================================*/	

	public function answerDispute(){

		if(isset($_POST["answerDispute"])){

			$url = CurlController::api()."disputes?id=".$_POST["idDispute"]."&nameId=id_dispute&token=".$_SESSION["user"]->token_user;
			$method = "PUT";	

			$fields = "answer_dispute=".$_POST["answerDispute"]."&date_answer_dispute=".date("Y-m-d");

			$header = array(

			   "Content-Type" =>"application/x-www-form-urlencoded"

			);

			$answerDispute = CurlController::request($url, $method, $fields, $header);

			if($answerDispute->status == 200){

				/*=============================================
				Enviamos notificación de la respuesta de la disputa al correo electrónico del cliente
				=============================================*/	

				$name = $_POST["clientDispute"];
				$subject = "Your dispute has been answered";
				$email = $_POST["emailDispute"];
				$message =  "Your dispute has been answered";
				$url = TemplateController::path()."account&my-shopping";		

				$sendEmail = TemplateController::sendEmail($name, $subject, $email, $message, $url);

				if($sendEmail == "ok"){

					echo '<script>

							fncFormatInputs();

							fncNotie(1, "The answer has been saved");

						</script>
					';

				}


			}




		}


	}

	/*=============================================
	Responder un mensaje
	=============================================*/	

	public function answerMessage(){

		if(isset($_POST["answerMessage"])){

			$url = CurlController::api()."messages?id=".$_POST["idMessage"]."&nameId=id_message&token=".$_SESSION["user"]->token_user;
			$method = "PUT";	

			$fields = "answer_message=".$_POST["answerMessage"]."&date_answer_message=".date("Y-m-d");


			$header = array(

			   "Content-Type" =>"application/x-www-form-urlencoded"

			);

			$answerMessage = CurlController::request($url, $method, $fields, $header);

			if($answerMessage->status == 200){

				/*=============================================
				Enviamos notificación de la respuesta de la disputa al correo electrónico del cliente
				=============================================*/	

				$name = $_POST["clientMessage"];
				$subject = "Your Message has been answered";
				$email = $_POST["emailMessage"];
				$message =  "Your Message has been answered";
				$url = TemplateController::path().$_POST["urlProduct"];		

				$sendEmail = TemplateController::sendEmail($name, $subject, $email, $message, $url);

				if($sendEmail == "ok"){

					echo '<script>

							fncFormatInputs();

							fncNotie(1, "The answer has been saved");

						</script>
					';

				}


			}

		}


	}


}


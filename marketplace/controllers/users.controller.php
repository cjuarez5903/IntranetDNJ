<?php 

use Firebase\JWT\JWT;

class UsersController{

	/*=============================================
	Registro de usuarios
	=============================================*/	

	public function register(){

		if(isset($_POST["regEmail"])){

			/*=============================================
			Validamos la sintaxis de los campos
			=============================================*/	

			if(preg_match( '/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["regFirstName"] ) &&
			   preg_match( '/^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/', $_POST["regLastName"] ) &&
			   preg_match( '/^[^0-9][.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["regEmail"] ) &&
			     preg_match( '/^[#\\=\\$\\;\\*\\_\\?\\¿\\!\\¡\\:\\.\\,\\0-9a-zA-Z]{1,}$/', $_POST["regPassword"] )
			){

				$displayname = TemplateController::capitalize(strtolower($_POST["regFirstName"]))." ".TemplateController::capitalize(strtolower($_POST["regLastName"]));
				$username = strtolower(explode("@",$_POST["regEmail"])[0]);
				$email =  strtolower($_POST["regEmail"]);

				$url = CurlController::api()."users?register=true";
				$method = "POST";
				$fields = array(

					"rol_user" => "default",
					"displayname_user" => $displayname,
					"username_user" => $username,
					"email_user" => $email,
					"password_user" => $_POST["regPassword"],
					"method_user" => "direct",
					"date_created_user" => date("Y-m-d")

				);

				$header = array(

				   "Content-Type" =>"application/x-www-form-urlencoded"

				);

				$register = CurlController::request($url, $method, $fields, $header);

				if($register->status == 200){

					$name = $displayname;
					$subject = "Verify your account";
					$email = $email;
					$message = "We must verify your account so that you can enter our Marketplace";
					$url = TemplateController::path()."account&login&".base64_encode($email);

					$sendEmail = TemplateController::sendEmail($name, $subject, $email, $message, $url);

					if($sendEmail == "ok"){

						echo '<div class="alert alert-success">Registered user successfully, confirm your account in your email (check spam)</div>

						<script>

							fncFormatInputs()

						</script>

						';

					}else{

						echo '<div class="alert alert-danger">'.$sendEmail.'</div>

						<script>

							fncFormatInputs()

						</script>

						';	

					}

				}
				
			}else{

				echo '<div class="alert alert-danger">Error in the syntax of the fields</div>

				<script>

					fncFormatInputs()

				</script>

				';

			}

		

		}

	}

	/*=============================================
	Login de usuarios
	=============================================*/	

	public function login(){

		if(isset($_POST["loginEmail"])){

			/*=============================================
			Validamos la sintaxis de los campos
			=============================================*/	

			if(preg_match( '/^[^0-9][.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["loginEmail"] ) &&
			     preg_match( '/^[#\\=\\$\\;\\*\\_\\?\\¿\\!\\¡\\:\\.\\,\\0-9a-zA-Z]{1,}$/', $_POST["loginPassword"] )
			){

				echo '<script>

					fncSweetAlert("loading", "", "");

				</script>';

				$url = CurlController::api()."users?login=true";
				$method = "POST";
				$fields = array(

					"email_user" => $_POST["loginEmail"],
					"password_user" => $_POST["loginPassword"],

				);

				$header = array(

				   "Content-Type" =>"application/x-www-form-urlencoded"

				);

				$login = CurlController::request($url, $method, $fields, $header);
				
				if($login->status == 200){
					
					if($login->results[0]->verification_user == 1){

						$_SESSION["user"] = $login->results[0];

						echo '<script>

								fncFormatInputs();

								localStorage.setItem("token_user", "'.$_SESSION["user"]->token_user.'");

								window.location = "'.TemplateController::path().'account&wishlist";

							</script>
						';

					}else{

						echo '<div class="alert alert-danger">Your account has not been verified yet, please check your email inbox.</div>


							<script>

								fncSweetAlert("close", "", "");
								fncFormatInputs()

							</script>
						';

					}

				}else{

					echo '<div class="alert alert-danger">'.$login->results.'</div>

						<script>

							fncSweetAlert("close", "", "");
							fncFormatInputs()

						</script>

					';

				}

			}else{

				echo '<div class="alert alert-danger">Error in the syntax of the fields</div>

					<script>

						fncSweetAlert("close", "", "");
						fncFormatInputs()

					</script>
				';

			}

		}
	}

	/*=============================================
	Conexión con facebook y google
	=============================================*/	

	static public function socialConnect($url, $type){

		/*=============================================
		Conexión con facebook
		=============================================*/	

		if($type == "facebook"){

			$fb = new \Facebook\Facebook([
			  'app_id' => '1402206776923684',
			  'app_secret' => '7297596d937edcb69d1db061c4f23acc',
			  'default_graph_version' => 'v2.10',
			  //'default_access_token' => '{access-token}', // optional
			]);

			/*=============================================
			Creamos la redireccion hacia la API de Facebook
			=============================================*/

			$handler = $fb->getRedirectLoginHelper();

			/*=============================================
			Solicitar datos relacionados al email
			=============================================*/

			$data = ["email"];

			/*=============================================
			Activamos la URL de Facebook con los dos parámetros:
			Url de regreso y los datos que solicitamos
			=============================================*/	

			$fullUrl = $handler->getLoginUrl($url, $data);
			
			/*=============================================
			Redireccionamos nuestro sitio hacia Facebook
			=============================================*/	

			if(!isset($_GET["code"])){ 

				echo '<script>

					window.location = "'.$fullUrl.'";

				</script>';

			}

			/*=============================================
			Recibimos la respuesta de Facebook
			=============================================*/	

			if(isset($_GET["code"])){

				/*=============================================
				Solicitamos el access Token de Facebook
				=============================================*/	

				try {

				    $accessToken = $handler->getAccessToken();
				   
				}catch(\Facebook\Exceptions\FacebookResponseException $e){

				    echo "Response Exception: " . $e->getMessage();
				    exit();

				}catch(\Facebook\Exceptions\FacebookSDKException $e){

				    echo "SDK Exception: " . $e->getMessage();
				    exit();

				}

				/*=============================================
				Solicitamos la data completa del usuario con el access Token y la guardamos en una variable de Sesión
				=============================================*/	

				$oAuth2Client = $fb->getOAuth2Client();

				if(!$accessToken->isLongLived())
					$accessToken = $oAuth2Client->getLongLivedAccesToken($accessToken);
					$response = $fb->get("/me?fields=id, first_name, last_name, email, picture.type(large)", $accessToken);
					$userData = $response->getGraphNode()->asArray();

				if(!isset($userData["email"])){

					echo '<div class="container-fluid" style="background:#f1f1f1">
							<div class="container alert alert-danger mb-0">
								Error: You must allow the use of your email for registration, please try again
							</div>
						</div>';

					return;

				}

				
				$displayname = $userData["first_name"]." ".$userData["last_name"];
				$username = explode("@",$userData["email"])[0];
				$email =  $userData["email"];

				/*=============================================
				Preguntamos primero si el usuario está registrado
				=============================================*/	

				$url = CurlController::api()."users?linkTo=email_user&equalTo=".$email."&select=*";
				$method = "GET";
				$fields = array();
				$header = array();

				$user = CurlController::request($url, $method, $fields, $header);

				if($user->status == 200){

					if($user->results[0]->method_user == "facebook"){

						$_SESSION["user"] = $user->results[0];

						/*=============================================
						Creación de JWT
						=============================================*/

						$time = time();
						$key = "azscdvfbgnhmjkl1q2w3e4r5t6y7u8i9o";

						$token = array(

							"iat" => $time,  // Tiempo que inició el token
							"exp" => $time + (60*60*24), // Tiempo que expirará el token (+1 dia)
							'data' => [
								"id" =>  $user->results[0]->id_user,
								"email" =>  $user->results[0]->email_user
							]
						);

						$jwt = JWT::encode($token, $key);

						$_SESSION["user"]->token_user = $jwt;
						$_SESSION["user"]->token_exp_user = $token["exp"];

						/*=============================================
						Actualizar Token en BD
						=============================================*/

						$url = CurlController::api()."users?id=".$_SESSION["user"]->id_user."&nameId=id_user&token=no&except=token_user";
						$method = "PUT";
						$fields =  "token_user=".$_SESSION["user"]->token_user;
						$header = array();

						$updateTokenUser = CurlController::request($url, $method, $fields, $header);

						/*=============================================
						Actualizar fecha de expiración del Token en BD
						=============================================*/

						$url = CurlController::api()."users?id=".$_SESSION["user"]->id_user."&nameId=id_user&token=no&except=token_exp_user";
						$method = "PUT";
						$fields =  "token_exp_user=".$_SESSION["user"]->token_exp_user;
						$header = array();

						$updateTokenExpUser = CurlController::request($url, $method, $fields, $header);

						echo '<script>

							localStorage.setItem("token_user", "'.$_SESSION["user"]->token_user.'");

							window.location = "'.TemplateController::path().'account&wishlist";

						</script>';

					}else{

						echo '<div class="container-fluid" style="background:#f1f1f1">
								<div class="container alert alert-danger mb-0">
									Error: This user is registered with the '.$user->results[0]->method_user .' method
								</div>
							</div>';	
					}

				}else{

					/*=============================================
					Registramos el usuario con los datos de facebook
					=============================================*/	

					$url = CurlController::api()."users?register=true";
					$method = "POST";
					$fields = array(

						"rol_user" => "default",
						"displayname_user" => $displayname,
						"username_user" => $username,
						"email_user" => $email,
						"picture_user" => $userData['picture']['url'],
						"method_user" => "facebook",
						"date_created_user" => date("Y-m-d")	

					);

					$header = array(

					   "Content-Type" =>"application/x-www-form-urlencoded"

					);

					$register = CurlController::request($url, $method, $fields, $header);

					if($register->status == 200){

						$url = CurlController::api()."users?linkTo=email_user&equalTo=".$email."&select=*";
						$method = "GET";
						$fields = array();
						$header = array();

						$user = CurlController::request($url, $method, $fields, $header);

						if($user->status == 200){

							$_SESSION["user"] = $user->results[0];
					
							echo '<script>

								localStorage.setItem("token_user", "'.$_SESSION["user"]->token_user.'");

								window.location = "'.TemplateController::path().'account&wishlist";
								
							</script>';

						}

					}

				}
					
			}

		}

		/*=============================================
		Conexión con google
		=============================================*/	

		if($type == "google"){

			$client = new Google\Client();
			$client->setAuthConfig('controllers/client_secret.json');
			$client->setScopes(['profile','email']);
			$redirect_uri = $url;
			$client->setRedirectUri($redirect_uri);
			$fullUrl = $client->createAuthUrl();
			
			/*=============================================
			Redireccionamos nuestro sitio hacia Google
			=============================================*/	

			if(!isset($_GET["code"])){ 

				echo '<script>

					window.location = "'.$fullUrl.'";

				</script>';

			}


			/*=============================================
			Recibimos la respuesta de Google
			=============================================*/	

			if(isset($_GET['code'])){

				$token = $client->authenticate($_GET["code"]);
				$_SESSION['id_token_google'] = $token;
				$client->setAccessToken($token);

				/*=============================================
				RECIBIMOS LOS DATOS CIFRADOS DE GOOGLE EN UN ARRAY
				=============================================*/

				if($client->getAccessToken()){

					$userData = $client->verifyIdToken();

					$displayname = $userData["name"];
					$username = explode("@",$userData["email"])[0];
					$email =  $userData["email"];

					
					/*=============================================
					Preguntamos primero si el usuario está registrado
					=============================================*/	

					$url = CurlController::api()."users?linkTo=email_user&equalTo=".$email."&select=*";
					$method = "GET";
					$fields = array();
					$header = array();

					$user = CurlController::request($url, $method, $fields, $header);

					if($user->status == 200){
		
						if($user->results[0]->method_user == "google"){

							$_SESSION["user"] = $user->results[0];

							/*=============================================
							Creación de JWT
							=============================================*/

							$time = time();
							$key = "azscdvfbgnhmjkl1q2w3e4r5t6y7u8i9o";

							$token = array(

								"iat" => $time,  // Tiempo que inició el token
								"exp" => $time + (60*60*24), // Tiempo que expirará el token (+1 dia)
								'data' => [
									"id" =>  $user->results[0]->id_user,
									"email" =>  $user->results[0]->email_user
								]
							);

							$jwt = JWT::encode($token, $key);

							$_SESSION["user"]->token_user = $jwt;
							$_SESSION["user"]->token_exp_user = $token["exp"];

							/*=============================================
							Actualizar Token en BD
							=============================================*/

							$url = CurlController::api()."users?id=".$_SESSION["user"]->id_user."&nameId=id_user&token=no&except=token_user";
							$method = "PUT";
							$fields =  "token_user=".$_SESSION["user"]->token_user;
							$header = array();

							$updateTokenUser = CurlController::request($url, $method, $fields, $header);

							/*=============================================
							Actualizar fecha de expiración del Token en BD
							=============================================*/

							$url = CurlController::api()."users?id=".$_SESSION["user"]->id_user."&nameId=id_user&token=no&except=token_exp_user";
							$method = "PUT";
							$fields =  "token_exp_user=".$_SESSION["user"]->token_exp_user;
							$header = array();

							$updateTokenExpUser = CurlController::request($url, $method, $fields, $header);


							echo '<script>

								localStorage.setItem("token_user", "'.$_SESSION["user"]->token_user.'");

								window.location = "'.TemplateController::path().'account&wishlist";
								
							</script>';

						}else{

							echo '<div class="container-fluid" style="background:#f1f1f1">
									<div class="container alert alert-danger mb-0">
										Error: This user is registered with the '.$user->results[0]->method_user .' method
									</div>
								</div>';	
						}


					}else{

						/*=============================================
						Registramos el usuario con los datos de facebook
						=============================================*/	

						$url = CurlController::api()."users?register=true";
						$method = "POST";
						$fields = array(

							"rol_user" => "default",
							"displayname_user" => $displayname,
							"username_user" => $username,
							"email_user" => $email,
							"picture_user" => $userData['picture'],
							"method_user" => "google",
							"date_created_user" => date("Y-m-d")	

						);

						$header = array(

						   "Content-Type" =>"application/x-www-form-urlencoded"

						);

						$register = CurlController::request($url, $method, $fields, $header);

						if($register->status == 200){

							$url = CurlController::api()."users?linkTo=email_user&equalTo=".$email."&select=*";
							$method = "GET";
							$fields = array();
							$header = array();

							$user = CurlController::request($url, $method, $fields, $header);

							if($user->status == 200){

								$_SESSION["user"] = $user->results[0];
							
								echo '<script>

									localStorage.setItem("token_user", "'.$_SESSION["user"]->token_user.'");

									window.location = "'.TemplateController::path().'account&wishlist";
									
								</script>';

							}

						}

					}

				}

			}

		}

	}

	/*=============================================
	Recuperar contraseña
	=============================================*/

	public function resetPassword(){

		if(isset($_POST["resetPassword"])){

			/*=============================================
			Validamos la sintaxis de los campos
			=============================================*/	

			if(preg_match( '/^[^0-9][.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["resetPassword"] )
			){

					/*=============================================
					Preguntamos primero si el usuario está registrado
					=============================================*/	
					$url = CurlController::api()."users?linkTo=email_user&equalTo=".$_POST["resetPassword"]."&select=*";
					$method = "GET";
					$fields = array();
					$header = array();

					$user = CurlController::request($url, $method, $fields, $header);

					if($user->status == 200){

						if($user->results[0]->method_user == "direct"){

							function genPassword($length){

								$password = "";
								$chain = "123456789abcdefghijklmnopqrstuvwxyz";

								$password = substr(str_shuffle($chain), 0, $length);

								return $password;

							}

							$newPassword = genPassword(11);
							
							$crypt = crypt($newPassword, '$2a$07$azybxcags23425sdg23sdfhsd$');

							/*=============================================
							Actualizar contraseña en base de datos
							=============================================*/

							$url = CurlController::api()."users?id=".$user->results[0]->id_user."&nameId=id_user&token=no&except=password_user";
							$method = "PUT";
							$fields =  "password_user=".$crypt;
							$header = array();

							$updatePassword = CurlController::request($url, $method, $fields, $header);		

							if($updatePassword->status == 200){	

								/*=============================================
								Enviamos nueva contraseña al correo electrónico
								=============================================*/							
							
								$name = $user->results[0]->displayname_user;
								$subject = "Request new password";
								$email = $user->results[0]->email_user;
								$message = "Your new password is ".$newPassword;
								$url = TemplateController::path()."account&login";

								$sendEmail = TemplateController::sendEmail($name, $subject, $email, $message, $url);

								if($sendEmail == "ok"){

									echo '<script>

											fncFormatInputs();

											fncNotie(1, "Your new password has been successfully sent, please check your email inbox.");

										</script>
									';

								}else{

									echo '<script>

											fncFormatInputs();

											fncNotie(3, "'.$sendEmail.'");

										</script>
									';

								}

							}

						}else{

							echo '<script>

									fncFormatInputs();

									fncSweetAlert("error", "It is not allowed to recover password because you registered with '.$user->results[0]->method_user.'", "")

								</script>
							';

						}

					}else{

						echo '<script>

								fncFormatInputs();

								fncSweetAlert("error", "The mail does not exist in the database", "")

							</script>
						';
					}



			}else{

				echo '<script>

						fncFormatInputs();

						fncNotie(3, "Error in the syntax of the fields");

					</script>
				';

			}

		}

	}	

	/*=============================================
	Cambiar contraseña
	=============================================*/	

	public function changePassword(){

		if(isset($_POST["changePassword"])){

			/*=============================================
			Validamos la sintaxis de los campos
			=============================================*/	

		  	if(preg_match('/^[#\\=\\$\\;\\*\\_\\?\\¿\\!\\¡\\:\\.\\,\\0-9a-zA-Z]{1,}$/', $_POST["changePassword"])){
			
	  			/*=============================================
				Encriptamos la contraseña
				=============================================*/
				
				$crypt = crypt($_POST["changePassword"], '$2a$07$azybxcags23425sdg23sdfhsd$');

				/*=============================================
				Actualizar contraseña en base de datos
				=============================================*/	

				$url = CurlController::api()."users?id=".$_SESSION["user"]->id_user."&nameId=id_user&token=".$_SESSION["user"]->token_user;
				$method = "PUT";
				$fields =  "password_user=".$crypt;
				$header = array();					

				$updatePassword = CurlController::request($url, $method, $fields, $header);		

				if($updatePassword->status == 200){	

					/*=============================================
					Enviamos nueva contraseña al correo electrónico
					=============================================*/							
				
					$name = $_SESSION["user"]->displayname_user;
					$subject = "Change of password";
					$email = $_SESSION["user"]->email_user;
					$message = "You have changed your password";
					$url = TemplateController::path()."account&login";

					$sendEmail = TemplateController::sendEmail($name, $subject, $email, $message, $url);

					if($sendEmail == "ok"){

						echo '<script>

								fncFormatInputs();

								fncNotie(1, "Your new password has been successfully sent, please check your email inbox.");

							</script>
						';

					}else{

						echo '<script>

								fncFormatInputs();

								fncNotie(3, "'.$sendEmail.'");

							</script>
						';

					}

				}else{

					if($updatePassword->status == 303){	

						echo '<script>

						fncFormatInputs();

						fncSweetAlert(
							"error",
							"'.$updatePicture->results.'",
							"'.TemplateController::path().'account&logout"
						);

					</script>';		


					}else{

						echo '<script>

							fncFormatInputs();

							fncSweetAlert(
								"error",
								"Password was not updated, please try again",
								""
							);

						</script>';

					}	
				}

			}else{

				echo '<script>

					fncFormatInputs();

					fncSweetAlert(
						"error",
						"Error in the syntax of the fields",
						""
					);

				</script>';	

			}


		}

	}

	/*=============================================
	Cambiar foto de perfil
	=============================================*/

	public function changePicture(){

		/*=============================================
		Validamos la sintaxis de los campos
		=============================================*/	

	  	if(isset($_FILES['changePicture']["tmp_name"]) && !empty($_FILES['changePicture']["tmp_name"])){

	  		$image = $_FILES['changePicture'];
	  		$folder = "img/users";
	  		$path = $_SESSION["user"]->id_user;
	  		$width = 200;
	  		$height = 200;
	  		$name = $_SESSION["user"]->username_user;

	  		$saveImage = TemplateController::saveImage($image, $folder, $path, $width, $height, $name);

	  		if($saveImage != "error"){

	  			/*=============================================
				Actualizar fotografía en base de datos
				=============================================*/	

				$url = CurlController::api()."users?id=".$_SESSION["user"]->id_user."&nameId=id_user&token=".$_SESSION["user"]->token_user;
				$method = "PUT";
				$fields =  "picture_user=".$saveImage;
				$header = array();					

				$updatePicture = CurlController::request($url, $method, $fields, $header);	

				if($updatePicture->status == 200){	

					$_SESSION["user"]->picture_user = $saveImage;

					echo '<script>

						fncFormatInputs();

						fncSweetAlert(
							"success",
							"Your new picture has been changed successfully.",
							"'.$_SERVER["REQUEST_URI"].'"
						);

					</script>';		

				}else{

					if($updatePicture->status == 303){	

						echo '<script>

						fncFormatInputs();

						fncSweetAlert(
							"error",
							"'.$updatePicture->results.'",
							"'.TemplateController::path().'account&logout"
						);

					</script>';		


					}else{

						echo '<script>

							fncFormatInputs();

							fncSweetAlert(
								"error",
								"An error occurred while saving the image, please try again",
								""
							);

						</script>';	

					}
				}				


	  		}else{

	  			echo '<script>

					fncFormatInputs();

					fncSweetAlert(
						"error",
						"An error occurred while creating the image, please try again",
						""
					);

				</script>';	

	  		}

	  	}

	}

	/*=============================================
	Crear una disputa
	=============================================*/	

	public function newDispute(){


		if(isset($_POST["idOrder"])){

			$url = CurlController::api()."disputes?token=".$_SESSION["user"]->token_user;
			$method = "POST";
			$fields = array(

				"id_order_dispute" =>  $_POST["idOrder"],
				"id_user_dispute" =>  $_POST["idUser"],
				"id_store_dispute" =>  $_POST["idStore"],
				"content_dispute" =>  $_POST["contentDispute"],
				"date_created_dispute" => date("Y-m-d")

			);

			$header = array(

			   "Content-Type" =>"application/x-www-form-urlencoded"

			);

			$dispute = CurlController::request($url, $method, $fields, $header);

			if($dispute->status == 200){

				/*=============================================
				Enviamos notificación de la creación de la disputa a la tienda
				=============================================*/		

				$name = $_POST["nameStore"];
				$subject = "A dispute has been created";
				$email = $_POST["emailStore"];
				$message = "A dispute has been created";
				$url = TemplateController::path()."account&my-store&disputes";

				$sendEmail = TemplateController::sendEmail($name, $subject, $email, $message, $url);

				if($sendEmail == "ok"){

					echo '<script>

							fncFormatInputs();

							fncNotie(1, "The dispute has been successfully created");

						</script>
					';

				}

			}

		}


	}

	/*=============================================
	Crear una pregunta
	=============================================*/	

	public function newQuestion(){


		if(isset($_POST["question"])){

			if(!empty($_POST["idUser"])){

				$url = CurlController::api()."messages?token=".$_SESSION["user"]->token_user;
				$method = "POST";
				$fields = array(

					"id_product_message" =>  $_POST["idProduct"],
					"id_user_message" =>  $_POST["idUser"],
					"id_store_message" =>  $_POST["idStore"],
					"content_message" =>  $_POST["question"],
					"date_created_message" => date("Y-m-d")

				);

				$header = array(

				   "Content-Type" =>"application/x-www-form-urlencoded"

				);

				$message = CurlController::request($url, $method, $fields, $header);

				if($message->status == 200){

					/*=============================================
					Enviamos notificación de la creación del mensaje a la tienda
					=============================================*/		

					$name = $_POST["nameStore"];
					$subject = "A message has been created";
					$email = $_POST["emailStore"];
					$message = "A message has been created";
					$url = TemplateController::path()."account&my-store&messages";

					$sendEmail = TemplateController::sendEmail($name, $subject, $email, $message, $url);

					if($sendEmail == "ok"){

						echo '<script>

								fncFormatInputs();

								fncNotie(1, "The message has been successfully created");

							</script>
						';

					}

				}

			}else{

				echo '<script>

						fncFormatInputs();

						fncNotie(3, "You must be logged in to ask the question");

					</script>
				';

			}

		}


	}

	/*=============================================
	Crear una reseña
	=============================================*/	

	public function newReview(){

		if(isset($_POST["rating"])){
			
			$arrayReview = array(
				"review"=> $_POST["rating"], 
				"comment"=> $_POST["commentReview"], 
				"user"=> $_POST["idUser"]
			);

			/*=============================================
	        Traer las reseñas actuales del producto
	        =============================================*/

	        $select = "reviews_product";

	        $url = CurlController::api()."products?linkTo=id_product&equalTo=".$_POST["idProduct"]."&select=".$select;
	        $method = "GET";
	        $fields = array();
	        $header = array();

	        $reviews = CurlController::request($url, $method, $fields, $header)->results;	

          	/*=============================================
	        Preguntar que la reseña no venga vacía
	        =============================================*/

	        $count = 0;

	        if($reviews[0]->reviews_product != null){

	        	$newReview = json_decode($reviews[0]->reviews_product, true);
	        	
	        	/*=============================================
	        	Editar una reseña ya escrita por el usuario
	        	=============================================*/
	        	foreach ($newReview as $index => $item) {
	        		
                                                    
                    if(isset($item["user"])){

                        if($item["user"] == $_POST["idUser"]){

                            $item["review"] = $_POST["rating"]; 
                            $item["comment"] = $_POST["commentReview"];
                            $newReview[$index] = $item;
                          
                            
                        }else{

                        	$count ++;
                        }

                    }
          
                }

	        	/*=============================================
	        	Crear una nueva reseña sobre las ya existentes del producto
	        	=============================================*/

	        	if($count == count($newReview)){

		        	array_push($newReview, $arrayReview);

		        }      
      
	        }else{

	        	$newReview = array();

	        	/*=============================================
	        	Crear una nueva reseña
	        	=============================================*/

	        	array_push($newReview, $arrayReview);

	        }

	         echo '<pre>'; print_r($newReview); echo '</pre>';

	        /*=============================================
        	Actualizar en base de datos
        	=============================================*/

        	$url = CurlController::api()."products?id=".$_POST["idProduct"]."&nameId=id_product&token=".$_SESSION["user"]->token_user;
	        $method = "PUT";
	        $fields =  "reviews_product=".json_encode($newReview);
	        $header = array();

	        $updateReview = CurlController::request($url, $method, $fields, $header);

	        if($updateReview->status == 200){

				echo '<script>

					fncFormatInputs();

					fncSweetAlert(
						"success",
						"The review has been updated",
						"'.TemplateController::path().'account&my-shopping"
					);

				</script>';


			}



		}


	}

}

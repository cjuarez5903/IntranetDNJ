<?php 

$idStore = "";

$url = CurlController::api()."stores?linkTo=id_user_store&equalTo=".$_SESSION["user"]->id_user."&select=id_store";
$method = "GET";
$fields = array();
$header = array();

$responseStore = CurlController::request($url, $method, $fields, $header);

if($responseStore->status == 200){

    $idStore = $responseStore->results[0]->id_store;

    /*=============================================
    Traer el total de órdenes sin entregar
    =============================================*/

    $url = CurlController::api()."orders?linkTo=id_store_order,status_order&equalTo=".$idStore.",pending&select=id_order&token=".$_SESSION["user"]->token_user;
    $method = "GET";
    $fields = array();
    $header = array();

    $dataOrder = CurlController::request($url, $method, $fields, $header);

    if($dataOrder->status == 200){

        $totalOrder = $dataOrder->total;

    }else{

        $totalOrder = 0;
    }

    /*=============================================
    Traer el total de productos
    =============================================*/
   
    $url = CurlController::api()."products?linkTo=id_store_product&equalTo=".$idStore."&select=id_product";
    $method = "GET";
    $fields = array();
    $header = array();

    $dataProduct = CurlController::request($url, $method, $fields, $header);

    if($dataProduct->status == 200){

        $totalProduct = $dataProduct->total;

    }else{

        $totalProduct = 0;
    }


    /*=============================================
    Traer el total de disputas
    =============================================*/


    $url = CurlController::api()."disputes?linkTo=id_store_dispute&equalTo=".$idStore."&select=answer_dispute&token=".$_SESSION["user"]->token_user;
    $method = "GET";
    $fields = array();
    $header = array();

    $disputesData = CurlController::request($url, $method, $fields, $header);

    $totalDispute = 0;

    if($disputesData->status == 200){

        foreach ($disputesData->results as $key => $value) {
            
            if($value->answer_dispute == null){

                $totalDispute++;

            }
        }

    }

    /*=============================================
    Traer el total de mensajes
    =============================================*/


    $url = CurlController::api()."messages?linkTo=id_store_message&equalTo=".$idStore."&select=answer_message&token=".$_SESSION["user"]->token_user;
    $method = "GET";
    $fields = array();
    $header = array();

    $messages = CurlController::request($url, $method, $fields, $header);

    $totalMessage = 0;

    if($messages->status == 200){

        foreach ($messages->results as $key => $value) {
            
            if($value->answer_message == null){

                $totalMessage++;

            }
        }

    }


}


?>

<aside class="ps-block--store-banner">

    <div class="ps-block__user">

        <div class="ps-block__user-avatar">

            <?php if ($_SESSION["user"]->method_user == "direct"): ?>

                <?php if ($_SESSION["user"]->picture_user == ""): ?>

                    <img class="img-fluid rounded-circle ml-auto" style="height:auto" src="img/users/default/default.png">

                <?php else: ?>

                    <img class="img-fluid rounded-circle ml-auto" style="height:auto" src="img/users/<?php echo $_SESSION["user"]->id_user ?>/<?php echo $_SESSION["user"]->picture_user ?>">
                
                <?php endif ?>

            <?php else: ?>

                <?php if (explode("/", $_SESSION["user"]->picture_user)[0] == "https:"): ?>

                    <img class="img-fluid rounded-circle ml-auto" style="height:auto" src="<?php echo $_SESSION["user"]->picture_user ?>">

                <?php else: ?>

                    <img class="img-fluid rounded-circle ml-auto" style="height:auto" src="img/users/<?php echo $_SESSION["user"]->id_user ?>/<?php echo $_SESSION["user"]->picture_user ?>">

                <?php endif ?>
                                             
                
            <?php endif ?>


            <div class="br-wrapper">

               <button class="btn btn-primary btn-lg rounded-circle" data-toggle="modal" data-target="#changePicture"><i class="fas fa-pencil-alt"></i></button>

            </div>

        </div>

        <div class="ps-block__user-content text-center text-lg-left">

            <h2 class="text-white"><?php echo $_SESSION["user"]->displayname_user ?></h2>

            <p><i class="fas fa-user"></i> <?php echo $_SESSION["user"]->username_user ?></p>

            <p><i class="fas fa-envelope"></i> <?php echo $_SESSION["user"]->email_user ?></p>

            <?php if ($_SESSION["user"]->method_user == "direct"): ?>

                <button class="btn btn-warning btn-lg" data-toggle="modal" data-target="#changePassword">Change Password</button>
                
            <?php endif ?>

        </div>

        <?php if ($idStore != ""): ?>

         <div class="row ml-lg-auto pt-5">

            <div class="col-lg-3 col-6">
                <div class="text-center">
                    <a href="<?php echo TemplateController::path() ?>account&my-store&orders">
                        <h1><i class="fas fa-shopping-cart text-white"></i></h1>
                        <h4 class="text-white">Orders <span class="badge badge-secondary rounded-circle"><?php echo $totalOrder ?></span></h4>
                    </a>
                </div>
            </div><!-- box /-->

            <div class="col-lg-3 col-6">
                <div class="text-center">
                    <a href="<?php echo TemplateController::path() ?>account&my-store">
                        <h1><i class="fas fa-shopping-bag text-white"></i></h1>
                        <h4 class="text-white">Products <span class="badge badge-secondary rounded-circle"><?php echo $totalProduct ?></span></h4>
                    </a>
                </div>
            </div><!-- box /-->

            <div class="col-lg-3 col-6">
                <div class="text-center">
                    <a href="<?php echo TemplateController::path() ?>account&my-store&disputes">
                        <h1><i class="fas fa-bell text-white"></i></h1>
                        <h4 class="text-white">Disputes <span class="badge badge-secondary rounded-circle"><?php echo $totalDispute ?></span></h4>
                    </a>
                </div>
            </div><!-- box /-->

            <div class="col-lg-3 col-6">
                <div class="text-center">
                    <a href="<?php echo TemplateController::path() ?>account&my-store&messages">
                        <h1><i class="fas fa-comments text-white"></i></h1>
                        <h4 class="text-white">Messages <span class="badge badge-secondary rounded-circle"><?php echo $totalMessage ?></span></h4>
                    </a>
                </div>
            </div><!-- box /-->
        </div>
            
        <?php endif ?>

       

    </div>

</aside><!-- s -->

<!--=====================================
Ventana modal para cambiar contraseña
======================================-->

<!-- The Modal -->
<div class="modal" id="changePassword">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Change Password</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        
        <form method="post" class="ps-form--account ps-tab-root needs-validation" novalidate>
            
            <div class="form-group form-forgot">

                <input 
                class="form-control" 
                type="password" 
                placeholder="Password"
                pattern="[#\\=\\$\\;\\*\\_\\?\\¿\\!\\¡\\:\\.\\,\\0-9a-zA-Z]{1,}"
                onchange="validateJS(event, 'password')" 
                name="changePassword"
                required>

                <div class="valid-feedback">Valid.</div>
                <div class="invalid-feedback">Please fill in this field correctly.</div>

            </div>

            <?php 

                $change = new UsersController();
                $change -> changePassword();

            ?>

            <div class="form-group submtit">

                <button type="submit" class="ps-btn ps-btn--fullwidth">Submit</button>

            </div>


        </form>


      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<!--=====================================
Ventana modal para cambiar fotografía
======================================-->

<!-- The Modal -->
<div class="modal" id="changePicture">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Change Picture</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        
        <form method="post" class="ps-form--account ps-tab-root needs-validation" novalidate enctype="multipart/form-data">

           <small class="helsmall-block small">Dimensions: 200px * 200px | Max Size. 2MB | Format: JPG o PNG</p>
            
            <div class="custom-file">
                
                <input type="file"
                class="custom-file-input"
                id="customFile"
                accept="image/*"
                maxSize="2000000"
                name="changePicture"
                onchange="validateImageJS(event, 'changePicture')"
                required>

                <label for="customFile" class="custom-file-label">Choose file</label>

            </div>

            <figure class="text-center py-3">
                
                <img src="" class="img-fluid rounded-circle changePicture" style="width:150px">

            </figure>

            <?php 

                $changePicture = new UsersController();
                $changePicture -> changePicture();

            ?>

            <div class="form-group submtit">

                <button type="submit" class="ps-btn ps-btn--fullwidth">Submit</button>

            </div>


        </form>


      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
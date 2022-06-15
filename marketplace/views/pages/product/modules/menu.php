<!--=====================================
Navegación del Menu 
======================================-->

<?php 

if($item->reviews_product != null){

    $allReviews = json_decode($item->reviews_product,true);

}else{

    $allReviews = array();

} 

?>

<ul class="ps-tab-list">

    <li class="active"><a href="#tab-1">Description</a></li>
    <li><a href="#tab-2">Details</a></li>
    <li><a href="#tab-3">Vendor</a></li>
    <li><a href="#tab-4">Reviews (<?php echo count($allReviews) ?>)</a></li>
    <li><a href="#tab-5">Questions and Answers</a></li>

</ul>

<div class="ps-tabs">

    <!--=====================================
    Descripción
    ======================================-->

    <div class="ps-tab active" id="tab-1">

        <div class="ps-document">

         	<?php echo $item->description_product ?>

        </div>

    </div>

    <!--=====================================
    Detalles
    ======================================-->

    <div class="ps-tab" id="tab-2">

        <div class="table-responsive">

            <table class="table table-bordered ps-table ps-table--specification">

                <tbody>

                	<?php 

                		$details = json_decode($item->details_product, true); 

                	?>

                	<?php foreach ($details as $key => $value): ?>
                	
                		 <tr>
	                        <td><?php echo $value["title"] ?></td>
	                        <td><?php echo $value["value"] ?></td>
	                    </tr>
                		
                	<?php endforeach ?>

                </tbody>

            </table>

        </div>

    </div>

    <!--=====================================
    Vendedor
    ======================================-->

    <div class="ps-tab" id="tab-3">

    	<div class="media">

    	  <img src="img/stores/<?php echo $item->url_store ?>/<?php echo $item->logo_store ?>" 
    	  class="mr-5 mt-1 rounded-circle"
    	   alt="<?php echo $item->name_store ?>"
    	   width="120">

    	  <div class="media-body">

    	    <h4><?php echo $item->name_store ?></h4>
    	    
    	    <p><?php echo $item->abstract_store ?></p>

    	    <a href="<?php echo $path.$item->url_store ?>">More Products from <?php echo $item->name_store ?></a>

    	  </div>

    	</div>
  
    </div>

    <!--=====================================
    Reseñas globales
    ======================================-->

    <div class="ps-tab" id="tab-4">

        <div class="row">

            <div class="col-lg-5 col-12 ">

                <!--=====================================
                Bloque de reseña
                ======================================-->

                <div class="ps-block--average-rating">

                    <div class="ps-block__header">

                        <?php 

                            $reviews = TemplateController::averageReviews(
                                json_decode($item->reviews_product,true)
                            );


                         ?>

                        <h3><?php echo $reviews ?></h3>

                        <select class="ps-rating" data-read-only="true">

                           <?php 

                            if($reviews > 0){

                                for($i = 0; $i < 5; $i++){

                                    if($reviews < ($i+1)){

                                        echo '<option value="1">'.($i+1).'</option>';

                                    }else{

                                         echo '<option value="2">'.($i+1).'</option>';

                                    }

                                }

                            }else{

                                echo '<option value="0">0</option>';

                                for($i = 0; $i < 5; $i++){

                                    echo '<option value="1">'.($i+1).'</option>';

                                }

                            }

                            ?>

                        </select>

                        <span><?php echo count($allReviews) ?> Review's</span>

                    </div>

                    <?php if ($item->reviews_product != null): ?>
                        
                        <?php 

                            if(count($allReviews) > 0){

                                //Creamos una matriz vacía para promediar las estrellas 

                                $blockStart = array(
                                
                                    "1" => 0,
                                    "2" => 0,
                                    "3" => 0,
                                    "4" => 0,
                                    "5" => 0
                                );

                                //Separamos las estrellas repetidas
                                
                                $repStart = array();          

                                foreach ($allReviews as $key => $value){
                                
                                   array_push($repStart, $value["review"]);

                                }

                                //Unimos las estrellas repetidas con la matriz vacía de estrellas

                                foreach ($blockStart as $key => $value) {
                                    
                                    if(!empty(array_count_values($repStart)[$key])){
                                       
                                        $blockStart[$key] = array_count_values($repStart)[$key];
                                     
                                    }
                                } 

                            }

                        ?>
            
                        <!--=====================================
                        Bloque de estrellas
                        ======================================-->

                        <?php for ($i = 5; $i > 0; $i--): ?>

                        <div class="ps-block__star">

                            <span><?php echo $i ?> Star</span>

                            <div class="ps-progress" data-value="<?php echo round($blockStart[$i]*100/count($allReviews)) ?>">

                                <span></span>

                            </div>

                            <span><?php echo round($blockStart[$i]*100/count($allReviews)) ?>%</span>

                        </div>
                            
                        <?php endfor ?> 

                    <?php endif ?>   

                </div>

                <!--=====================================
                Escribir una reseña
                ======================================-->

                <hr class="my-5">

  
            </div>

            <?php if ($item->reviews_product != null): ?>

                <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12 col-12 ">

                     <!--=====================================
                    Tomar 5 reseñas aleatoriamente
                    ======================================-->

                    <?php 

                    $rand = array_rand($allReviews, 5);

                    ?>

                    <?php foreach ($rand as $key => $value): ?>
                    
                    <div class="media border p-3 mb-3">

                        <?php if (empty($allReviews[$value]["user"])): ?>

                            <img src="img/users/default/default.png" class="mr-5 mt-1 rounded-circle" width="60">

                        <?php else: ?>

                        <?php

                            $select = "displayname_user,picture_user,method_user";
                            $url = CurlController::api()."users?linkTo=id_user&equalTo=".$allReviews[$value]["user"]."&select=".$select;
                             $method = "GET";
                            $fields = array();
                            $header = array();

                            $user = CurlController::request($url, $method, $fields, $header)->results[0];
  
                        ?>

                            <?php if ($user->method_user == "direct"): ?>

                                <?php if ($user->picture_user == ""): ?>

                                    <img class="mr-5 mt-1 rounded-circle" width="60" src="img/users/default/default.png">

                                <?php else: ?>

                                    <img class="mr-5 mt-1 rounded-circle" width="60" src="img/users/<?php echo $user->id_user ?>/<?php echo $user->picture_user ?>">
                                
                                <?php endif ?>

                            <?php else: ?>

                                <?php if (explode("/", $user->picture_user)[0] == "https:"): ?>

                                    <img class="mr-5 mt-1 rounded-circle" width="60" src="<?php echo $user->picture_user ?>">

                                <?php else: ?>

                                    <img class="mr-5 mt-1 rounded-circle" width="60" src="img/users/<?php echo $user->id_user ?>/<?php echo $user->picture_user ?>">

                                <?php endif ?>
                                                             
                                
                            <?php endif ?>

                        <?php endif ?>
                             
                        <div class="media-body">

                            <select class="ps-rating" data-read-only="true">

                                <?php 

                                if($allReviews[$value]["review"]  > 0){

                                    for($i = 0; $i < 5; $i++){

                                        if($allReviews[$value]["review"] < ($i+1)){

                                            echo '<option value="1">'.($i+1).'</option>';

                                        }else{

                                             echo '<option value="2">'.($i+1).'</option>';

                                        }

                                    }

                                }else{

                                    echo '<option value="0">0</option>';

                                    for($i = 0; $i < 5; $i++){

                                        echo '<option value="1">'.($i+1).'</option>';

                                    }

                                }

                                ?>

                            </select>

                            <?php if (empty($allReviews[$value]["user"])): ?>

                                <h4>Anonimous</h4>

                            <?php else: ?>

                                <h4><?php echo $user->displayname_user ?></h4>

                            <?php endif ?>

                            <p><?php echo $allReviews[$value]["comment"] ?></p>

                        </div>

                    </div>
                        
                    <?php endforeach ?> 

                </div>

            <?php endif ?>  

        </div>

    </div>

    <!--=====================================
    Preguntas y respuestas
    ======================================-->

    <div class="ps-tab" id="tab-5">

        <div class="ps-block--questions-answers">

            <h3>Questions and Answers</h3>

            <form method="post">

                <input type="hidden" name="idProduct" value="<?php echo $item->id_product ?>">

                <?php if (isset($_SESSION["user"])): ?>

                    <input type="hidden" name="idUser"  value="<?php echo $_SESSION["user"]->id_user ?>">

                <?php else: ?>

                    <input type="hidden" name="idUser"  value="">
    
                <?php endif ?>

             
                <input type="hidden" name="idStore"  value="<?php echo $item->id_store ?>">

                <input type="hidden" name="nameStore"  value="<?php echo $item->name_store ?>">
                <input type="hidden" name="emailStore"  value="<?php echo $item->email_store ?>">

                <div class="input-group">

                    <input class="form-control" type="text" name="question" placeholder="Have a question? Search for answer?">

                    <div class="input-group-append">
                      <button type="submit" class="btn btn-warning">Send</button>
                    </div>
                
                </div>

                <?php

                    $question = new UsersController();
                    $question -> newQuestion();



                ?>

            </form>

             <!--=====================================
            Visualizar los mensajes
            ======================================--> 

            <?php 

            if(isset($_SESSION["user"])){

                /*=============================================
                Data de mensajes
                =============================================*/

                $select = "id_product_message,date_created_message,content_message,answer_message,date_answer_message,id_user,method_user,picture_user,displayname_user";

                $url = CurlController::api()."relations?rel=messages,products,users&type=message,product,user&linkTo=id_product_message&equalTo=".$item->id_product."&select=".$select."&orderBy=id_message&orderMode=DESC&token=".$_SESSION["user"]->token_user;
                $method = "GET";
                $fields = array();
                $header = array();

                $messages = CurlController::request($url, $method, $fields, $header)->results;
        
                
                if(!is_array($messages)){

                    $messages = array();

                }

            }else{

               $messages = array(); 
            }

            ?>

            <?php if (count($messages) > 0): ?>

                <?php foreach ($messages as $key => $value): ?>

                    <?php if ($item->id_product == $value->id_product_message): ?>

                        <div class="my-3">
                            
                            <div class="media border p-3">
                                
                                <div class="media-body">

                                    <h4><small>Question on <?php echo $value->date_created_message ?> | <?php echo $value->displayname_user ?></small></h4>

                                    <p><?php echo $value->content_message ?></p>
                                    
                                </div>

                                <?php if ($value->method_user == "direct"): ?>

                                    <?php if ($value->picture_user == ""): ?>

                                        <img class="img-fluid rounded-circle ml-auto" src="img/users/default/default.png" style="width:60px">

                                    <?php else: ?>

                                        <img class="img-fluid rounded-circle ml-auto" src="img/users/<?php echo $value->id_user ?>/<?php echo $value->picture_user ?>" style="width:60px">

                                    <?php endif ?>

                                 <?php else: ?>

                                    <?php if (explode("/", $value->picture_user)[0] == "https:"): ?>

                                       <img class="img-fluid rounded-circle ml-auto" src="<?php echo $value->picture_user ?>" style="width:60px"> 

                                    <?php else: ?>

                                        <img class="img-fluid rounded-circle ml-auto" src="img/users/<?php echo $value->id_user ?>/<?php echo $value->picture_user ?>" style="width:60px"> 

                                    <?php endif ?>

                                <?php endif ?>

                            </div>

                            <?php if ($value->answer_message != null): ?>

                                <div class="media border p-3">

                                    <img class="img-fluid rounded-circle ml-3 mt-3" src="img/stores/<?= $item->url_store ?>/<?= $item->logo_store ?>" style="width:60px">
                                    
                                    <div class="media-body text-right">

                                        <h4><small>Answer on <?php echo $value->date_answer_message ?> | <?php echo $item->name_store ?></small></h4>

                                        <p><?php echo $value->answer_message ?></p>
                                        
                                    </div>

                                </div>

                            <?php endif ?>

                        </div>

                    <?php endif ?>

                <?php endforeach ?>  

            <?php endif ?>
           
        </div>

    </div>

    <div class="ps-tab active" id="tab-6">

        <p>Sorry no more offers available</p>

    </div>

</div>
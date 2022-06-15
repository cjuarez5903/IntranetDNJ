<?php 

if(!isset($_SESSION["user"])){

    echo '<script>

        window.location = "'.$path.'";

    </script>'; 

    return;

}else{

    $time = time();
    
     if($_SESSION["user"]->token_exp_user < $time){
       
        echo '<script>

            fncSweetAlert(
                "error",
                "Error: the token has expired, please login again",
                "'.$path.'account&logout"
            );

        </script>'; 

        return;

    }else{

        /*=============================================
        Data de órdenes
        =============================================*/

        $select = "quantity_order,price_order,details_order,process_order,id_order,name_store,url_store,id_category_product,name_product,url_product,image_product,id_user_order,id_store_order,email_store,id_product,reviews_product";

        $url = CurlController::api()."relations?rel=orders,stores,products&type=order,store,product&linkTo=id_user_order&equalTo=".$_SESSION["user"]->id_user."&select=".$select."&orderBy=id_order&orderMode=DESC&token=".$_SESSION["user"]->token_user;
        $method = "GET";
        $fields = array();
        $header = array();

        $shopping = CurlController::request($url, $method, $fields, $header)->results;
        
        if(!is_array($shopping)){

            $shopping = array();

        }

        /*=============================================
        Data de disputas
        =============================================*/

        $select = "id_order_dispute,content_dispute,answer_dispute,date_answer_dispute,date_created_dispute,method_user,logo_store,url_store";

        $url = CurlController::api()."relations?rel=disputes,orders,users,stores&type=dispute,order,user,store&linkTo=id_user_dispute&equalTo=".$_SESSION["user"]->id_user."&select=".$select."&orderBy=id_dispute&orderMode=DESC&token=".$_SESSION["user"]->token_user;
        $method = "GET";
        $fields = array();
        $header = array();

        $disputes = CurlController::request($url, $method, $fields, $header)->results;
       

        
        if(!is_array($disputes)){

            $disputes = array();

        }

    }


}


?>


<!--=====================================
My Account Content
======================================--> 

<div class="ps-vendor-dashboard pro">

    <div class="container">

        <div class="ps-section__header">



            <!--=====================================
            Profile
            ======================================--> 

            <?php 

            include "views/pages/account/profile/profile.php";

            ?>

            <!--=====================================
            Nav Account
            ======================================--> 

            <div class="ps-section__content">

                  <ul class="ps-section__links">
                    <li><a href="<?php echo $path ?>account&wishlist">My Wishlist</a></li>
                    <li class="active"><a href="<?php echo $path ?>account&my-shopping">My Shopping</a></li>
                    <li><a href="<?php echo $path ?>account&my-store">My Store</a></li>
                    <li><a href="<?php echo $path ?>account&my-sales">My Sales</a></li>
                </ul>

                <!--=====================================
                My Shopping
                ======================================--> 

                <div class="table-responsive">

                    <table class="table ps-table--whishlist dt-responsive dt-client" width="100%">

                        <thead>

                            <tr>      

                                <th>Product name</th>

                                <th>Proccess</th>

                                <th>Price</th>

                                <th>Quantity</th>

                                <th>Review</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php foreach ($shopping  as $key => $value): ?>

                            <tr>

                                <td>

                                    <div class="ps-product--cart">

                                        <div class="ps-product__thumbnail">

                                            <?php 


                                                $url = CurlController::api()."categories?linkTo=id_category&equalTo=".$value->id_category_product."&select=url_category";
                                                $method = "GET";
                                                $fields = array();
                                                $header = array();

                                                $category = CurlController::request($url, $method, $fields, $header)->results[0]; 

                                            ?>

                                            <a href="<?php echo $path.$value->url_product ?>">
                                                <img src="img/products/<?php echo $category->url_category ?>/<?php echo $value->image_product ?>">
                                            </a>
                                            
                                        </div>

                                        <div class="ps-product__content">

                                            <a href="<?php echo $path.$value->url_product ?>"><?php echo $value->name_product ?></a>
                                            <small><?php echo $value->details_order ?></small>
                                            <a href="<?php echo $path.$value->url_store ?>"><?php echo $value->name_store ?></a>

                                        </div>

                                    </div>

                                </td>

                                <td>

                                    <?php 

                                        $process_order = json_decode($value->process_order, true);                                    

                                    ?>

                                    <ul class="timeline">

                                        <?php foreach ($process_order as $key => $item): ?>

                                            <?php if ($item["status"] == "ok"): ?>

                                                <li class="success">
                                                    <h5><?php echo $item["date"] ?></h5>
                                                    <p class="text-success"><?php echo $item["stage"] ?> <i class="fas fa-check"></i></p>
                                                    <p>Comment: <?php echo $item["comment"] ?></p>

                                                </li>

                                            <?php else: ?> 

                                                <li class="process">
                                                    <h5><?php echo $item["date"] ?></h5>
                                                    <p><?php echo $item["stage"] ?></p> 
                                                    <button class="btn btn-primary" disabled>
                                                      <span class="spinner-border spinner-border-sm"></span>
                                                      In process
                                                    </button>
                                                </li> 

                                            <?php endif ?>

                                        <?php endforeach ?>

                                    </ul>  

                                    <?php if ($process_order[2]["status"] == "ok"): ?>

                                        <a class="btn btn-warning btn-lg" href="<?php echo $path.$value->url_product ?>">Repurchase</a>

                                    <?php else: ?>

                                        <!--=====================================
                                        Open Dispute
                                        ======================================--> 

                                       <a 
                                        class="btn btn-danger btn-lg openDispute text-white"
                                        idOrder="<?php echo $value->id_order ?>"
                                        idUser="<?php echo $value->id_user_order ?>" 
                                        idStore="<?php echo $value->id_store_order ?>" 
                                        emailStore="<?php echo $value->email_store ?>"
                                        nameStore="<?php echo $value->name_store ?>"
                                        >Open Dispute</a> 

                                        <!--=====================================
                                        Visualizar las disputas
                                        ======================================--> 

                                        <?php if (count($disputes) > 0): ?>                                      
                                       

                                            <?php foreach ($disputes as $index => $item): ?>

                                                <?php if ($value->id_order == $item->id_order_dispute): ?>

                                                    <div class="my-3">
                                                        
                                                        <div class="media border p-3">
                                                            
                                                            <div class="media-body">

                                                                <h4><small>Dispute on <?php echo $item->date_created_dispute ?></small></h4>

                                                                <p><?php echo $item->content_dispute ?></p>
                                                                
                                                            </div>

                                                            <?php if ($_SESSION["user"]->method_user == "direct"): ?>

                                                                <?php if ($_SESSION["user"]->picture_user == ""): ?>

                                                                    <img class="img-fluid rounded-circle ml-auto" src="img/users/default/default.png" style="width:60px">

                                                                <?php else: ?>

                                                                    <img class="img-fluid rounded-circle ml-auto" src="img/users/<?php echo $_SESSION["user"]->id_user ?>/<?php echo $_SESSION["user"]->picture_user ?>" style="width:60px">

                                                                <?php endif ?>

                                                             <?php else: ?>

                                                                <?php if (explode("/", $_SESSION["user"]->picture_user)[0] == "https:"): ?>

                                                                   <img class="img-fluid rounded-circle ml-auto" src="<?php echo $_SESSION["user"]->picture_user ?>" style="width:60px"> 

                                                                <?php else: ?>

                                                                    <img class="img-fluid rounded-circle ml-auto" src="img/users/<?php echo $_SESSION["user"]->id_user ?>/<?php echo $_SESSION["user"]->picture_user ?>" style="width:60px"> 

                                                                <?php endif ?>

                                                            <?php endif ?>

                                                        </div>

                                                        <?php if ($item->answer_dispute != null): ?>

                                                            <div class="media border p-3">

                                                                <img class="img-fluid rounded-circle ml-3 mt-3" src="img/stores/<?= $item->url_store ?>/<?= $item->logo_store ?>" style="width:60px">
                                                                
                                                                <div class="media-body text-right">

                                                                    <h4><small>Answer on <?php echo $item->date_answer_dispute ?></small></h4>

                                                                    <p><?php echo $item->answer_dispute ?></p>
                                                                    
                                                                </div>

                                                            </div>

                                                        <?php endif ?>

                                                    </div>

                                                <?php endif ?>

                                            <?php endforeach ?>  

                                        <?php endif ?>
                                       
                                    <?php endif ?>

                                </td> 

                                <td class="price text-center">$<?php echo $value->price_order ?></td>

                                <td class="text-center"><?php echo $value->quantity_order ?></td>

                                <td>
                                    <div class="text-center  mt-2">

                                        <?php if ($process_order[2]["status"] == "ok"): ?>


                                            <?php if ($value->reviews_product != null): ?>

                                            <?php 

                                                $rating = 0;
                                                $comment = "";

                                                $reviews = json_decode($value->reviews_product,true);
                                                
                                                foreach ($reviews as $index => $item) {
                                                    
                                                    if(isset($item["user"])){

                                                        if($item["user"] == $value->id_user_order){

                                                            $rating = $item["review"]; 
                                                            $comment = $item["comment"];
                                                           
                                                        
                                                        } 

                                                    }
                                                }

                                            ?>
                                                
                                            <?php endif ?>

                                           


                                            <div class="br-theme-fontawesome-stars">

                                                <select class="ps-rating" data-read-only="true" style="display: none;">
                                                   
                                                    <?php 

                                                    if($rating  > 0){

                                                        for($i = 0; $i < 5; $i++){

                                                            if($rating < ($i+1)){

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

                                            </div>

                                             <p><?php echo $comment ?></p>

                                            <a class="btn btn-warning btn-lg newReview"
                                            idUser="<?php echo $value->id_user_order ?>" 
                                            idProduct="<?php echo $value->id_product ?>"
                                            >
                                                
                                                <?php if ($rating != 0): ?>

                                                    Edit comment

                                                <?php else: ?>

                                                    Add comment

                                                <?php endif ?>       

                                            </a>

                                         <?php else: ?>

                                            <a class="btn btn-warning btn-lg disabled" href="#">Add comment</a>

                                        <?php endif ?>

                                    </div>

                                </td>

                            </tr>
                                
                            <?php endforeach ?>


        
                        </tbody>

                    </table>

                </div><!-- End My Shopping -->

            </div>


        </div>

    </div>

</div>

<!--=====================================
Modal para las disputas
======================================--> 

<!-- The Modal -->
<div class="modal" id="newDispute">

    <div class="modal-dialog">

        <div class="modal-content">

            <form method="post">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">New Dispute</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">

                    <input type="hidden" name="idOrder">
                    <input type="hidden" name="idUser">
                    <input type="hidden" name="idStore">
                    <input type="hidden" name="emailStore">
                    <input type="hidden" name="nameStore">

                    <div class="form-group">

                        <label>Type your message</label>

                        <div class="form-group__content">
                            
                            <textarea 
                            class="form-control" 
                            type="text"
                            name="contentDispute"
                            required></textarea>

                        </div>

                    </div>

                </div>

                <!-- Modal footer -->
                <div class="modal-footer">

                    <div class="float-right">
                        <button type="submit" class="btn btn-warning btn-lg">Send</button>
                    </div>
                   
                </div>

                <?php 

                $dispute = new UsersController();
                $dispute -> newDispute();

                ?>

            </form>

        </div>

    </div>

</div>

<!--=====================================
Modal para las calificaciones
======================================--> 

<!-- The Modal -->
<div class="modal" id="newReview">

    <div class="modal-dialog">

        <div class="modal-content">

            <form method="post">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">New Review</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">

                    <input type="hidden" name="idProduct">
                    <input type="hidden" name="idUser">

                    <div class="form-group form-group__rating">

                        <label>Your rating of this product</label>

                        <select class="ps-rating" name="rating" data-read-only="false">

                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>

                        </select>

                    </div>

                    <div class="form-group">

                        <label>Type your message</label>

                        <div class="form-group__content">
                            
                            <textarea 
                            class="form-control" 
                            type="text"
                            name="commentReview"
                            ></textarea>

                        </div>

                    </div>

                </div>

                <!-- Modal footer -->
                <div class="modal-footer">

                    <div class="float-right">
                        <button type="submit" class="btn btn-warning btn-lg">Send</button>
                    </div>
                   
                </div>

                <?php 

                $review = new UsersController();
                $review -> newReview();

                ?>

            </form>

        </div>

    </div>

</div>

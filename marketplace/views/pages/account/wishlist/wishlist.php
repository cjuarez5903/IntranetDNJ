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
        Traer la Lista de deseos
        =============================================*/

        $select = "url_product,url_category,name_product,image_product,price_product,offer_product,stock_product";
        $productsWishlist = array();

        foreach ($wishlist as $key => $value) {
            
            $url = CurlController::api()."relations?rel=products,categories&type=product,category&linkTo=url_product&equalTo=".$value."&select=".$select;
            $method = "GET";
            $fields = array();
            $header = array();

            array_push($productsWishlist, CurlController::request($url, $method, $fields, $header)->results[0]);
           
        }

    }

}

?>

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
                    <li  class="active"><a href="<?php echo $path ?>account&wishlist">My Wishlist</a></li>
                    <li><a href="<?php echo $path ?>account&my-shopping">My Shopping</a></li>
                    <li><a href="<?php echo $path ?>account&my-store">My Store</a></li>
                    <li><a href="<?php echo $path ?>account&my-sales">My Sales</a></li>
                </ul>

                <!--=====================================
                Wishlist
                ======================================--> 

                <div class="table-responsive">

                    <table class="table ps-table--whishlist dt-responsive dt-client">

                        <thead>

                            <tr>               

                                <th>Product name</th>

                                <th>Unit Price</th>

                                <th>Stock Status</th>

                                <th></th>

                                <th></th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php foreach ($productsWishlist as $key => $value): ?>

                               <tr>

                                    <td>
                                        <div class="ps-product--cart">

                                            <div class="ps-product__thumbnail">
                                                <a href="<?php echo $path.$value->url_product ?>">
                                                    <img src="img/products/<?php echo $value->url_category ?>/<?php echo $value->image_product ?>">
                                                </a>
                                            </div>

                                            <div class="ps-product__content">
                                                <a href="<?php echo $path.$value->url_product ?>">
                                                    <?php echo $value->name_product ?>
                                                </a>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="ps-product">
                                        
                                        <!--=====================================
                                        El precio en oferta del producto
                                        ======================================-->   

                                        <?php if ($value->offer_product != null): ?>

                                            <h4 class="ps-product__price sale">
                                            
                                                <?php 

                                                    echo "$".TemplateController::offerPrice(
                                                        
                                                        $value->price_product, 
                                                        json_decode($value->offer_product,true)[1], 
                                                        json_decode($value->offer_product,true)[0]

                                                    );

                                                ?>        

                                                <del> $<?php echo $value->price_product ?></del>

                                            </h4>

                                        <?php else: ?>

                                            <h4 class="ps-product__price">$<?php echo $value->price_product ?></h4>

                                        <?php endif ?>

                                    </td>

                                    <td>

                                      <!--=====================================
                                        Stock del producto
                                        ======================================-->   

                                       <?php if ($value->stock_product == 0): ?>

                                            <span class="ps-tag ps-tag--out-stock"> Out of stock</span>

                                        <?php else: ?>

                                            <span class="ps-tag ps-tag--in-stock">In-stock</span>
                                            
                                        <?php endif ?>

                                    </td>

                                    <td>
                                        <a 
                                        class="ps-btn" 
                                        onclick="addShoppingCart('<?php echo $value->url_product ?>','<?php echo CurlController::api() ?>', '<?php echo $_SERVER["REQUEST_URI"] ?>', this)"
                                        detailsSC
                                        quantitySC
                                        >Add to cart</a></td>

                                    <td>
                                        <a class="btn" onclick="removeWishlist('<?php echo $value->url_product ?>', '<?php echo CurlController::api() ?>', '<?php echo $path; ?>')">
                                            <i class="icon-cross"></i>
                                        </a>
                                    </td>

                                </tr>  
                                    
                            <?php endforeach ?>


                        </tbody>

                    </table>

                </div>

            </div>


        </div>

    </div>

</div>
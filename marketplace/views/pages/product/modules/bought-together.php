<?php 

$select = "id_product,url_category,image_product,name_product,offer_product,price_product,name_store,reviews_product,stock_product,url_store,url_product";

$url = CurlController::api()."relations?rel=products,categories,subcategories,stores&type=product,category,subcategory,store&linkTo=title_list_product&equalTo=".$item->title_list_product."&select=".$select;
$method = "GET";
$fields = array();
$header = array();

$newProduct =  CurlController::request($url, $method, $fields, $header)->results;

?>

<?php if (count($newProduct) > 1): ?>


<div class="ps-block--bought-toggether">

    <h4>Frequently Bought Together</h4>

    <div class="ps-block__content">

        <div class="ps-block__items">

            <!--=====================================
            Current Product
            ======================================-->
            
            <div class="ps-block__item">

                <div class="ps-product ps-product--simple">

                    <div class="ps-product__thumbnail">

                        <!--=====================================
                        Imagen del producto
                        ======================================-->  

                        <a href="<?php echo $path.$item->url_product ?>">

                            <img src="img/products/<?php echo $item->url_category ?>/<?php echo $item->image_product ?>" alt="<?php echo $item->name_product ?>">

                        </a>

                    </div>

                    <div class="ps-product__container">

                        <div class="ps-product__content">
                            
                           <!--=====================================
                            nombre del producto
                            ======================================-->  

                            <a class="ps-product__title" href="<?php echo $path.$item->url_product ?>">
                            <?php echo $item->name_product ?>
                            </a>

                            <!--=====================================
                            El precio en oferta del producto
                            ======================================-->   
                            
                            <?php if ($item->offer_product != null): ?>

                                <p class="ps-product__price sale">
                                
                                    <?php 
                                        
                                        $price1 = TemplateController::offerPrice(

                                            $item->price_product, 
                                            json_decode($item->offer_product,true)[1], 
                                            json_decode($item->offer_product,true)[0] 

                                        );

                                        echo "$".$price1;

                                    ?>

                                    <del> $<?php echo $item->price_product ?></del>

                                </p>

                            <?php else: ?>

                                <?php $price1 = $item->price_product  ?>

                                <p class="ps-product__price"><?php echo "$".$item->price_product ?></p>

                             <?php endif ?>

                        </div>

                    </div>

                </div>

            </div>

            <!--=====================================
            New Product
            ======================================-->


            <?php foreach ($newProduct as $key => $value): ?>

                <?php if ($value->id_product != $item->id_product): ?>

                                <div class="ps-block__item">

                                    <div class="ps-product ps-product--simple">

                                        <div class="ps-product__thumbnail">

                                            <!--=====================================
                                            Imagen del producto
                                            ======================================-->  

                                            <a href="<?php echo $path.$value->url_product ?>">

                                                <img src="img/products/<?php echo $value->url_category ?>/<?php echo $value->image_product ?>" alt="<?php echo $value->name_product ?>">

                                            </a>

                                        </div>

                                        <div class="ps-product__container">

                                            <div class="ps-product__content">

                                                 <!--=====================================
                                                nombre del producto
                                                ======================================-->  

                                                <a class="ps-product__title" href="<?php echo $path.$value->url_product ?>">
                                                <?php echo $value->name_product ?>
                                                </a>

                                                <!--=====================================
                                                El precio en oferta del producto
                                                ======================================-->   
                                                
                                                <?php if ($value->offer_product != null): ?>

                                                    <p class="ps-product__price sale">
                                                    
                                                        <?php 
                                                             $price2 = TemplateController::offerPrice(

                                                                $value->price_product, 
                                                                json_decode($value->offer_product,true)[1], 
                                                                json_decode($value->offer_product,true)[0] 

                                                            );

                                                             echo "$".$price2;

                                                        ?>

                                                        <del> $<?php echo $value->price_product ?></del>

                                                    </p>

                                                <?php else: ?>

                                                    <?php $price2 = $value->price_product  ?>

                                                    <p class="ps-product__price"><?php echo "$".$value->price_product ?></p>

                                                <?php endif ?>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div class="ps-block__item ps-block__total">

                                    <p>Total Price:<strong> $<?php echo $price1 + $price2  ?></strong></p>

                                    <a 
                                    class="ps-btn" 
                                    onclick="addShoppingCart2('<?php echo $item->url_product ?>','<?php echo $value->url_product ?>','<?php echo CurlController::api() ?>', '<?php echo $_SERVER["REQUEST_URI"] ?>', this)"
                                    detailsSC
                                    quantitySC>Add All to cart</a>
                                    <a 
                                    class="ps-btn ps-btn--gray ps-btn--outline" 
                                    onclick="addWishlist2('<?php echo $item->url_product ?>','<?php echo $value->url_product ?>','<?php echo CurlController::api() ?>')">Add All to whishlist</a>

                                </div>

                            </div>

                        </div>

                    </div>

                    <?php return; ?>
                    
                <?php endif ?>
                
            <?php endforeach ?>

<?php endif ?>
            

<!--=====================================
Preload
======================================-->

<div class="d-flex justify-content-center preloadTrue">
    
    <div class="spinner-border text-muted my-5"></div>

</div>

<!--=====================================
Load
======================================-->

<div class="ps-block--shop-features preloadFalse">

    <div class="ps-block__header">

        <h3>Best Sale Items</h3>

        <div class="ps-block__navigation">

        	<a class="ps-carousel__prev" href="#recommended1">
        		<i class="icon-chevron-left"></i>
        	</a>

        	<a class="ps-carousel__next" href="#recommended1">
        		<i class="icon-chevron-right"></i>
        	</a>

        </div>

    </div>

    <div class="ps-block__content">

        <div class="owl-slider" id="recommended1" data-owl-auto="true" data-owl-loop="true" data-owl-speed="10000" data-owl-gap="30" data-owl-nav="false" data-owl-dots="false" data-owl-item="6" data-owl-item-xs="2" data-owl-item-sm="2" data-owl-item-md="3" data-owl-item-lg="4" data-owl-item-xl="5" data-owl-duration="1000" data-owl-mousedrag="on">

            <?php foreach ($bestSalesItems as $key => $item): ?>

        	<!--=====================================
			Product
			======================================--> 

            <div class="ps-product">

                <div class="ps-product__thumbnail">

                    <!--=====================================
                    Imagen del producto
                    ======================================-->  

                	<a href="<?php echo $path.$item->url_product ?>">

                        <img src="img/products/<?php echo $item->url_category ?>/<?php echo $item->image_product ?>" alt="<?php echo $item->name_product ?>">

                    </a>

                    <!--=====================================
                    Descuento de oferta o fuera de stock
                    ======================================-->  

                    <?php if ($item->stock_product == 0): ?>

                        <div class="ps-product__badge out-stock">Out Of Stock</div>

                    <?php else: ?>

                        <?php if ($item->offer_product != null): ?>

                            <div class="ps-product__badge">
                            -

                            <?php 

                            echo TemplateController::offerDiscount(

                                $item->price_product, 
                                json_decode($item->offer_product,true)[1], 
                                json_decode($item->offer_product,true)[0] 

                            );

                            ?>


                            %

                        </div>

                        <?php endif ?>

                    <?php endif ?>

                    <!--=====================================
                    Botones de acciones
                    ======================================-->  

                    <ul class="ps-product__actions">

                        <li>
                        	<a class="btn"
                                onclick="addShoppingCart('<?php echo $item->url_product ?>','<?php echo CurlController::api() ?>', '<?php echo $_SERVER["REQUEST_URI"] ?>', this)"
                                detailsSC
                                quantitySC
                                data-toggle="tooltip" 
                                data-placement="top" title="Add to Cart">
                        		<i class="icon-bag2"></i>
                        	</a>
                        </li>

                        <li>
                        	<a href="<?php echo $path.$item->url_product ?>" data-toggle="tooltip" data-placement="top" title="Quick View">
                        		<i class="icon-eye"></i>
                        	</a>
                        </li>

                        <li>
                        	<a class="btn" onclick="addWishlist('<?php echo $item->url_product ?>','<?php echo CurlController::api() ?>')" data-toggle="tooltip" data-placement="top" title="Add to Whishlist">
                        		<i class="icon-heart"></i>
                        	</a>
                        </li>                                           

                    </ul>

                </div>

                <div class="ps-product__container">

                    <!--=====================================
                    nombre de la tienda
                    ======================================-->  

                	<a class="ps-product__vendor" href="<?php echo $path.$item->url_store ?>"><?php echo $item->name_store ?></a>

                    <div class="ps-product__content">

                        <!--=====================================
                        nombre del producto
                        ======================================-->  

                    	<a class="ps-product__title" href="<?php echo $path.$item->url_product ?>">
                    	<?php echo $item->name_product ?>
                        </a>

                        <!--=====================================
                        Las reseñas del producto
                        ======================================-->  

                        <div class="ps-product__rating">

                            <?php 

                                $reviews = TemplateController::averageReviews(                                        
                                    json_decode($item->reviews_product,true)
                                );
                              
                            ?>

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

                            <span>(<?php 

                                if($item->reviews_product != null){

                                     echo count(json_decode($item->reviews_product,true));

                                }else{

                                    echo 0;
                                }

                            ?>)</span>

                        </div>

                        <!--=====================================
                        El precio en oferta del producto
                        ======================================-->   
                        
                        <?php if ($item->offer_product != null): ?>

                            <p class="ps-product__price sale">
                            
                                <?php 
                                    echo "$".TemplateController::offerPrice(

                                        $item->price_product, 
                                        json_decode($item->offer_product,true)[1], 
                                        json_decode($item->offer_product,true)[0] 

                                    );

                                ?>

                                <del> $<?php echo $item->price_product ?></del>

                            </p>

                        <?php else: ?>

                            <p class="ps-product__price"><?php echo "$".$item->price_product ?></p>

                         <?php endif ?>

                    </div>

                    <div class="ps-product__content hover">

                        <!--=====================================
                        El nombre del producto
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
                                    echo "$".TemplateController::offerPrice(

                                        $item->price_product, 
                                        json_decode($item->offer_product,true)[1], 
                                        json_decode($item->offer_product,true)[0] 

                                    );

                                ?>

                                <del> $<?php echo $item->price_product ?></del>

                            </p>

                        <?php else: ?>

                            <p class="ps-product__price"><?php echo "$".$item->price_product ?></p>

                        <?php endif ?>

                    </div>

                </div>

            </div><!-- End Product -->

            <?php endforeach ?>


        </div>

    </div>

</div><!-- End Best Sales Items -->
<header class="header header--product header--sticky" data-sticky="true">

    <nav class="navigation">

        <div class="container">

            <article class="ps-product--header-sticky">

                <div class="ps-product__thumbnail">

                    <img src="img/products/<?php echo $item->url_category ?>/<?php echo $item->image_product ?>" alt="<?php echo $item->name_product ?>">

                </div>

                <div class="ps-product__wrapper">

                    <div class="ps-product__content">

                        <a class="ps-product__title" href="#"><?php echo $item->name_product ?></a>
                     
                    </div>

                    <div class="ps-product__shopping">

                        <!--=====================================
                        El precio en oferta del producto
                        ======================================-->   
                        
                        <?php if ($item->offer_product != null): ?>

                            <p class="ps-product__price sale text-danger">
                            
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

                        <a class="ps-btn"
                                onclick="addShoppingCart('<?php echo $item->url_product ?>','<?php echo CurlController::api() ?>', '<?php echo $_SERVER["REQUEST_URI"] ?>', this)"
                                detailsSC
                                quantitySC> Add to Cart</a>

                    </div>

                </div>

            </article>

        </div>

    </nav>

</header>

<!--=====================================
Preload
======================================-->

<div class="container-fluid preloadTrue">
    
     <div class="container">

        <div class="ph-item">

            <div class="ph-col-2">

                <div class="ph-row">

                    <div class="ph-col-12 big"></div>

                    <div class="ph-col-12 empty"></div>

                    <div class="ph-col-12 empty"></div>

                    <div class="ph-col-8"></div>

                    <div class="ph-col-4 empty"></div>

                    <div class="ph-col-12 empty"></div>

                    <div class="ph-col-12"></div>

                    <div class="ph-col-12 empty"></div>

                    <div class="ph-col-12"></div>

                    <div class="ph-col-12 empty"></div>

                    <div class="ph-col-12"></div>

                    <div class="ph-col-12 empty"></div>

                    <div class="ph-col-12"></div>

                    <div class="ph-col-12 empty"></div>

                    <div class="ph-col-12"></div>

                </div>

            </div>

            <div class="ph-col-2">

                <div class="ph-picture" style="height:500px"></div> 

            </div>

             <div class="ph-col-8">

               <div class="ph-picture" style="height:500px"></div> 

            </div>

        </div>

    </div>

</div>

<!--=====================================
Load
======================================-->

<div class="ps-section--gray preloadFalse">

    <div class="container">

        <?php foreach ($topCategories as $key => $value): ?>

    	<!--=====================================
    	Products of category
    	======================================-->  

        <div class="ps-block--products-of-category">

        	<!--=====================================
    		Menu subcategory
    		======================================-->  

            <div class="ps-block__categories">

                <h3><?php echo $value->name_category ?></h3>

                    <ul>

                        <!--=====================================
                        Traer las subcategorías según el id de la categoría
                        ======================================-->

                        <?php 

                        $select = "name_subcategory,url_subcategory";

                        $url = CurlController::api()."subcategories?linkTo=id_category_subcategory&equalTo=".$value->id_category."&select=".$select;
                        $method = "GET";
                        $fields = array();
                        $header = array();

                        $listSubcategories = CurlController::request($url, $method, $fields, $header)->results;        

                        ?>
                    
                        <?php foreach ($listSubcategories as $index => $item): ?>

                            <li><a href="<?php echo $path.$item->url_subcategory ?>"><?php echo $item->name_subcategory ?></a></li>
                            
                        <?php endforeach ?>

                        
                     
                    </ul>

                    <a class="ps-block__more-link" href="<?php echo $path.$value->url_category ?>">View All</a>

            </div>


            <?php 

            $select ="vertical_slider_product,name_product,image_product,stock_product,offer_product,url_product,reviews_product,price_product";

            $url = CurlController::api()."products?linkTo=id_category_product&equalTo=".$value->id_category."&orderBy=views_product&orderMode=DESC&startAt=0&endAt=6&select=".$select;
            $method = "GET";
            $fields = array();
            $header = array();

            $listProducts = CurlController::request($url, $method, $fields, $header)->results;

            ?>

            <!--=====================================
    		Vertical Slider Category
    		======================================-->  

            <div class="ps-block__slider">

                <div class="ps-carousel--product-box owl-slider" data-owl-auto="true" data-owl-loop="true" data-owl-speed="7000" data-owl-gap="0" data-owl-nav="true" data-owl-dots="true" data-owl-item="1" data-owl-item-xs="1" data-owl-item-sm="1" data-owl-item-md="1" data-owl-item-lg="1" data-owl-duration="500" data-owl-mousedrag="off">

                    <?php foreach ($listProducts as $index => $item): ?>

                        <a href="<?php echo $path.$item->url_product ?>">

                            <img src="img/products/<?php echo $value->url_category ?>/vertical/<?php echo $item->vertical_slider_product ?>" alt="<?php echo $item->name_product ?>">

                        </a>
                        
                    <?php endforeach ?>


                </div>

            </div>

            <!--=====================================
    		Block Product Box
    		======================================-->  

            <div class="ps-block__product-box">
    			
    			<!--=====================================
    			Product Simple
    			======================================--> 

                <?php foreach ($listProducts as $index => $item): ?>

                <div class="ps-product ps-product--simple">

                    <div class="ps-product__thumbnail">

                        <!--=====================================
                        Imagen del producto
                        ======================================-->  

                    	<a href="<?php echo $path.$item->url_product ?>">

                    		<img src="img/products/<?php echo $value->url_category ?>/<?php echo $item->image_product ?>" alt="<?php echo $item->name_product ?>">

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

                    </div>

                    <div class="ps-product__container">

                        <div class="ps-product__content" data-mh="clothing">

                            <!--=====================================
                            Título del producto
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

                                <span>(

                                <?php 

                                    if($item->reviews_product != null){

                                         echo count(json_decode($item->reviews_product,true));

                                    }else{

                                        echo 0;
                                    }

                                ?> 

                                review's)</span>

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

                    </div>

                </div> <!-- End Product Simple -->

                <?php endforeach ?>


            </div><!-- End Block Product Box -->
          
        </div><!-- End Products of category -->

        <?php endforeach ?>

    </div><!-- End Container-->

</div><!-- End Section Gray-->
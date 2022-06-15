<?php 

/*=============================================
Traer todos los productos
=============================================*/

$today = date("Y-m-d");

$select = "offer_product,stock_product,gallery_product,url_category,name_product,offer_product,price_product,name_category,reviews_product,stock_product,url_product";

$url = CurlController::api()."relations?rel=products,categories&type=product,category&select=".$select;
$method = "GET";
$fields = array();
$header = array();

$hotProducts = CurlController::request($url, $method, $fields, $header)->results;

foreach ($hotProducts as $key => $value) {

    /*=============================================
    Preguntamos si el producto trae ofertas y trae stock
    =============================================*/
    if($value->offer_product == null || $value->stock_product == 0){

        unset($hotProducts[$key]);   

    }

    /*=============================================
    Preguntamos si la fecha de la oferta aún no ha vencido
    =============================================*/

    if($value->offer_product != null){

        if($today > date(json_decode($value->offer_product,true)[2])){

            unset($hotProducts[$key]);   

        }

    }
}

/*=============================================
Si vienen más de 10 productos para ser mostrados
=============================================*/

if(count($hotProducts)>10){
   
    $random = rand(0, (count($hotProducts)-10));

    $hotProducts = array_slice($hotProducts, $random, 10);

}

?>


<div class="col-xl-9 col-12 ">

    <!--=====================================
    Preload
    ======================================-->

    <div class="container-fluid preloadTrue2">
        
        <div class="ph-item">

            <div class="ph-col-6">

                <div class="ph-item border-0">

                    <div class="ph-col-2">

                        <div class="ph-picture" style="height:300px"></div>                              

                    </div>

                    <div class="ph-col-10">

                        <div class="ph-picture" style="height:300px"></div>

                    </div>  

                </div>           

            </div>

            <div class="ph-col-6">

                <div class="ph-row mt-5">

                    <div class="ph-col-4 big"></div>
                    <div class="ph-col-8 empty"></div>

                    <div class="ph-col-6 big"></div>
                    <div class="ph-col-6 empty"></div>

                    <div class="ph-col-8"></div>
                    <div class="ph-col-4 empty"></div>

                    <div class="ph-col-12 big"></div>

                    <div class="ph-col-6" style="height:70px"></div> 
                    <div class="ph-col-6 empty"></div>

                    <div class="ph-col-8 big"></div>
                    <div class="ph-col-4 empty"></div>

                    <div class="ph-col-12 big"></div>

                    <div class="ph-col-8"></div>
                    <div class="ph-col-4 empty"></div>

                    <div class="ph-col-12 big"></div>

                    <div class="ph-col-12" style="height:70px"></div>                                 

                </div>                                      

            </div>

        </div>

    </div>

    <!--=====================================
    Load
    ======================================-->

    <div class="ps-block--deal-hot preloadFalse2" data-mh="dealhot">

        <div class="ps-block__header">

            <h3>Deal hot today</h3>

            <div class="ps-block__navigation">
            	<a class="ps-carousel__prev" href=".ps-carousel--deal-hot">
            		<i class="icon-chevron-left"></i>
            	</a>
            	<a class="ps-carousel__next" href=".ps-carousel--deal-hot">
            		<i class="icon-chevron-right"></i>
            	</a>
            </div>

        </div>

        <div class="ps-product__content">

            <div class="ps-carousel--deal-hot ps-carousel--deal-hot owl-slider" data-owl-auto="true" data-owl-loop="true" data-owl-speed="5000" data-owl-gap="0" data-owl-nav="false" data-owl-dots="false" data-owl-item="1" data-owl-item-xs="1" data-owl-item-sm="1" data-owl-item-md="1" data-owl-item-lg="1" data-owl-duration="1000" data-owl-mousedrag="on">

                <?php foreach ($hotProducts as $key => $value): ?>

                    <!--=====================================
    				Product Deal Home
    				======================================-->                                 

                    <div class="ps-product--detail ps-product--hot-deal">

                        <div class="ps-product__header">

                            <div class="ps-product__thumbnail" data-vertical="true">

                                <figure>

                                    <div class="ps-wrapper">

                                        <!--=====================================
                                        Galería del producto
                                        ======================================-->        

                                        <div class="ps-product__gallery" data-arrow="true">

                                            <?php 

                                                $gallery = json_decode($value->gallery_product,true);
                                               
                                            ?>

                                            <?php foreach ($gallery as $index => $image): ?>

                                            <div class="item">
                                                <a href="img/products/<?php echo $value->url_category ?>/gallery/<?php echo $image ?>">
                                                    <img src="img/products/<?php echo $value->url_category ?>/gallery/<?php echo $image ?>" alt="<?php echo $value->name_product ?>">
                                                </a>
                                            </div>
                                                
                                            <?php endforeach ?>

                                        </div>

                                        <!--=====================================
                                        Valor de ahorro de la oferta
                                        ======================================-->        

                                        <div class="ps-product__badge">
                                        	<span>Save <br>

                                            <?php 
                                            echo "$".TemplateController::savingValue(

                                                $value->price_product, 
                                                json_decode($value->offer_product,true)[1], 
                                                json_decode($value->offer_product,true)[0] 

                                            );

                                            ?>

                                            </span>
                                        </div>

                                    </div>

                                </figure>

                                <!--=====================================
                                Más de la galería del producto
                                ======================================-->        

                                <div class="ps-product__variants" data-item="4" data-md="3" data-sm="3" data-arrow="false">

                                    <?php foreach ($gallery as $index => $image): ?>

                                        <div class="item">
                                            <img src="img/products/<?php echo $value->url_category ?>/gallery/<?php echo $image ?>" alt="<?php echo $value->name_product ?>">
                                        </div>
                                        
                                    <?php endforeach ?>

                                </div>

                            </div>

                            <div class="ps-product__info">

                                <!--=====================================
                                Nombre de la categoría
                                ======================================-->       

                                <h5><?php echo $value->name_category ?></h5>

                                <!--=====================================
                                Nombre del producto
                                ======================================-->   

                                <h3 class="ps-product__name">

                                    <a href="<?php echo $path.$value->url_product ?>">

                                        <?php echo $value->name_product ?>

                                    </a>
                                        

                                </h3>

                              
                                <div class="ps-product__meta">

                                    <!--=====================================
                                    El precio en oferta del producto
                                    ======================================-->   

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

                                    <!--=====================================
                                    Las reseñas del producto
                                    ======================================-->  

                                    <div class="ps-product__rating">

                                        <?php 

                                            $reviews = TemplateController::averageReviews(                                        
                                                json_decode($value->reviews_product,true)
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

                                            if($value->reviews_product != null){

                                                 echo count(json_decode($value->reviews_product,true));

                                            }else{

                                                echo 0;
                                            }

                                        ?> 

                                        review's)</span>

                                    </div>

                                    <!--=====================================
                                    Producto en Stock
                                    ======================================-->  

                                    <div class="ps-product__specification">

                                        <p>Status:<strong class="in-stock"> In Stock</strong></p>

                                    </div>

                                </div>

                                <!--=====================================
                                Conteo regresivo de la oferta
                                ======================================-->  

                                <div class="ps-product__expires">

                                    <p>Expires In</p>

                                    <ul class="ps-countdown" data-time="<?php echo json_decode($value->offer_product,true)[2] ?>">

                                        <li><span class="days"></span>
                                            <p>Days</p>
                                        </li>

                                        <li><span class="hours"></span>
                                            <p>Hours</p>
                                        </li>

                                        <li><span class="minutes"></span>
                                            <p>Minutes</p>
                                        </li>

                                        <li><span class="seconds"></span>
                                            <p>Seconds</p>
                                        </li>

                                    </ul>

                                </div>

                                <!--=====================================
                                Barra de inventario
                                ======================================-->  

                                <div class="ps-product__processs-bar">

                                    <div class="ps-progress" data-value="<?php echo $value->stock_product ?>">
                                    	<span class="ps-progress__value"></span>
                                    </div>

                                    <p><strong><?php echo $value->stock_product ?>/100</strong> Sold</p>

                                </div>

                            </div>

                        </div>

                    </div><!-- End Product Deal Hot -->

                <?php endforeach ?>
    

            </div><!-- End carousel Deal Hot -->

        </div><!-- End product content Deal Hot -->

    </div><!-- End deal hot -->

</div><!-- End Columns --> 

<script>

$(".preloadFalse2").ready(function(){

    $(".preloadTrue2").remove();

$(".preloadFalse2").css({"opacity":1,"height":"auto"})

})

</script>


          
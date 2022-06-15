<?php 

/*=============================================
Traer 5 productos aleatoriamente
=============================================*/

$randomStart = rand(0, ($totalProducts-5));

$select = "url_category,horizontal_slider_product,url_product,default_banner_product,name_product";

$url = CurlController::api()."relations?rel=products,categories&type=product,category&orderBy=id_product&orderMode=ASC&startAt=".$randomStart."&endAt=5&select=".$select;
$method = "GET";
$fields = array();
$header = array();

$productsHSlider = CurlController::request($url, $method, $fields, $header)->results;

?>

<!--=====================================
Preload
======================================-->

<div class="container-fluid preloadTrue">
    
   <!--  <div class="spinner-border text-muted my-5"></div> -->

   <div class="container">

       <div class="ph-item border-0">

            <div class="ph-col-4">
                
                <div class="ph-row">
                    
                    <div class="ph-col-10"></div>  

                    <div class="ph-col-10 big"></div>  

                    <div class="ph-col-6 big"></div>  

                    <div class="ph-col-6 empty"></div>  

                    <div class="ph-col-6 big"></div> 

                </div>

            </div>

            <div class="ph-col-8">

               <div class="ph-picture"></div> 

            </div>
            
        </div>

    </div>

</div>

<!--=====================================
Load
======================================-->

<div class="ps-home-banner preloadFalse">
    <div class="ps-carousel--nav-inside owl-slider" data-owl-auto="true" data-owl-loop="true" data-owl-speed="5000" data-owl-gap="0" data-owl-nav="true" data-owl-dots="true" data-owl-item="1" data-owl-item-xs="1" data-owl-item-sm="1" data-owl-item-md="1" data-owl-item-lg="1" data-owl-duration="1000" data-owl-mousedrag="on" data-owl-animate-in="fadeIn" data-owl-animate-out="fadeOut">

        <?php foreach ($productsHSlider as $key => $value): ?>

            <?php 

                $hSlider = json_decode($value->horizontal_slider_product, true);

            ?>

            <div class="ps-banner--market-4" data-background="img/products/<?php echo $value->url_category ?>/horizontal/<?php echo  $hSlider["IMG tag"] ?>">
                <img src="img/products/<?php echo $value->url_category ?>/horizontal/<?php echo  $hSlider["IMG tag"] ?>" alt="<?php echo $value->name_product ?>">
                <div class="ps-banner__content">
                    <!-- <h4><?php echo $hSlider["H4 tag"] ?></h4>
                    <h3><?php echo $hSlider["H3-1 tag"] ?><br/> 
                        <?php echo $hSlider["H3-2 tag"] ?><br/> 
                        <p> <?php echo $hSlider["H3-3 tag"] ?> <strong>  <?php echo $hSlider["H3-4s tag"] ?></strong></p>
                    </h3> -->
                    <!-- <a class="ps-btn" href="<?php echo $path.$value->url_product ?>"> <?php echo $hSlider["Button tag"] ?></a> -->
                </div>
            </div>
            
        <?php endforeach ?>
   
    </div>

</div><!-- End Home Banner-->
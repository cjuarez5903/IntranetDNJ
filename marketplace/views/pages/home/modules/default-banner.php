<?php 

/*=============================================
Traer 2 productos aleatoriamente
=============================================*/

$productsDefaultBanner = array_slice($productsHSlider, 0, 2);


?>

<!--=====================================
Preload
======================================-->

<div class="container-fluid preloadTrue">
    
    <div class="container">

        <div class="ph-item border-0">

            <div class="ph-col-6">

                <div class="ph-picture"></div>

            </div>

            <div class="ph-col-6">

                <div class="ph-picture"></div>

            </div>

        </div>

    </div>

</div>

<!--=====================================
Load
======================================-->

<div class="ps-promotions preloadFalse">

    <div class="container">

        <div class="row">

            <?php foreach ($productsDefaultBanner as $key => $value): ?>

                <div class="col-md-6 col-12 ">
                    <a class="ps-collection" href="<?php echo $path.$value->url_product ?>">
                        <img src="img/products/<?php echo $value->url_category ?>/default/<?php echo $value->default_banner_product ?>" alt="<?php echo $value->name_product ?>">
                    </a>
                </div>
                
            <?php endforeach ?>

        </div>

    </div>

</div><!-- End Home Promotions-->
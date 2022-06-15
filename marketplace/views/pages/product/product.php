<?php 

/*=============================================
Recibir variable GET de cupones y convertirla en Cookie
=============================================*/
if(isset($_GET["coupon"])){

	if(isset($_COOKIE["couponsMP"])){
		
		$arrayCoupon = json_decode($_COOKIE["couponsMP"],true);

		foreach ($arrayCoupon as $key => $value) {
			
			if($value != $_GET["coupon"]){

				array_push($arrayCoupon, $_GET["coupon"]);
			}
		}

		setcookie("couponsMP", json_encode($arrayCoupon), time()+3600*24*7);

	}else{

		$arrayCoupon = array($_GET["coupon"]);
		setcookie("couponsMP", json_encode($arrayCoupon), time()+3600*24*7);

	}

}
/*=============================================
Traer toda la información del producto
=============================================*/

$select = "id_product,url_category,url_product,image_product,name_product,offer_product,price_product,name_category,name_subcategory,gallery_product,reviews_product,url_store,name_store,stock_product,summary_product,video_product,specifications_product,title_list_product,views_product,id_store,tags_product,description_product,details_product,abstract_store,logo_store,email_store,url_subcategory";

$url = CurlController::api()."relations?rel=products,categories,subcategories,stores&type=product,category,subcategory,store&linkTo=url_product,approval_product,state_product&equalTo=".$urlParams[0].",approved,show&select=".$select;
$method = "GET";
$fields = array();
$header = array();

$item = CurlController::request($url, $method, $fields, $header)->results[0];

if($item == "N"){

	echo '<script>

        fncSweetAlert(
            "error",
            "Error: The product is not enabled",
            "'.$path.'"
        );

    </script>'; 

    return;

}

/*=============================================
Actualizar las vistas de producto
=============================================*/

$view = $item->views_product+1;

$url = CurlController::api()."products?id=".$item->id_product."&nameId=id_product&token=no&except=views_product";
$method = "PUT";
$fields =  "views_product=".$view;
$header = array();

$updateViewsProduct = CurlController::request($url, $method, $fields, $header);

?>

<!--=====================================
Preload
======================================-->

<!-- <div id="loader-wrapper">
    <img src="img/template/loader.jpg">
    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>
</div>   -->

<!--=====================================
Call to Action
======================================-->

<?php include "modules/call-to-action.php" ?>

<!--=====================================
Breadcrumb
======================================-->  

<?php include "modules/breadcrumb.php" ?>

<!--=====================================
Product Content
======================================--> 

<div class="ps-page--product">

    <div class="ps-container">

    	<!--=====================================
		Product Container
		======================================--> 

        <div class="ps-page__container">

    		<!--=====================================
			Left Column
			======================================--> 

            <div class="ps-page__left">

                <div class="ps-product--detail ps-product--fullwidth">

                	<!--=====================================
					Product Header
					======================================--> 

                    <div class="ps-product__header">

                    	<!--=====================================
						Gallery
						======================================--> 

                      	<?php Include "modules/gallery.php" ?>

                        <!--=====================================
						Product Info
						======================================--> 

                      	<?php include "modules/product-info.php" ?>

                    </div> <!-- End Product header -->

                	<!--=====================================
					Product Content
					======================================--> 
					
					<div class="ps-product__content ps-tab-root">

						<!--=====================================
						Bought Together
						======================================--> 

					    <?php include "modules/bought-together.php" ?>

					    <!--=====================================
						Menu
						======================================--> 
						
						<?php include "modules/menu.php" ?>			   

					</div><!--  End product content -->
                
                </div>

            </div><!-- End Left Column -->

            <!--=====================================
			Right Column
			======================================--> 

         	<?php include "modules/right-column.php" ?>

        </div><!--  End Product Container -->

		<!--=====================================
		Customers who bought
		======================================--> 

		<?php //include "modules/customers-bought.php" ?>

        <!--=====================================
		Related products
		======================================--> 

     	<?php include "modules/related-product.php" ?>
    </div>

</div><!-- End Product Content -->
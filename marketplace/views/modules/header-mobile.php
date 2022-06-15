<header class="header header--mobile" data-sticky="true">

    <div class="header__top">

   <div class="header__left">

            <ul class="d-flex justify-content-center">
				<li><a href="#" target="_blank"><i class="fab fa-facebook-f mr-4"></i></a></li>
				<li><a href="#" target="_blank"><i class="fab fa-instagram mr-4"></i></a></li>					
				<li><a href="#" target="_blank"><i class="fab fa-twitter mr-4"></i></a></li>
				<li><a href="#" target="_blank"><i class="fab fa-youtube mr-4"></i></a></li>
			</ul>
        </div>

        <div class="header__right">

            <ul class="navigation__extra">
                
                <li><a href="/become-vendor"></a></li>
                <li><a href="/store-list">Store List</a></li>
                <li><i class="icon-telephone"></i><strong> </strong></li>    

                <li>

                    <div class="ps-dropdown language">
                        <a class="btn" onclick="changeLang('en')">
                            <img src="img/template/en.png" alt="">English
                        </a>

                        <ul class="ps-dropdown-menu">

                            <li>
                                <a class="btn" onclick="changeLang('es')">
                                    <img src="img/template/es.png" alt=""> Español
                                </a>
                            </li>

                        </ul>

                    </div>

                </li>

            </ul>

        </div>

    </div>

    <div class="navigation--mobile">

        <div class="navigation__left">

    	  	<!--=====================================
			Menu Mobile
			======================================-->

            <div class="menu--product-categories">
                
                <div class="ps-shop__filter-mb mt-4" id="filter-sidebar">
                	<i class="icon-menu "></i>
                </div>

            	<div class="ps-filter--sidebar">

				    <div class="ps-filter__header">
				        <h3>Categories</h3><a class="ps-btn--close ps-btn--no-boder" href="#"></a>
				    </div>

				    <div class="ps-filter__content">

				        <aside class="widget widget_shop">

				            <ul class="ps-list--categories">

			            	<?php foreach ($menuCategories as $key => $value): ?>
				            		
				                <li class="current-menu-item menu-item-has-children">
				                	
				                	<a href="<?php echo $path.$value->url_category ?>">
				                		<?php echo $value->name_category ?>
				                	</a>

				               		<span class="sub-toggle">
				               			<i class="fa fa-angle-down"></i>
				               		</span>

				                    <ul class="sub-menu" style="display: none;">

				                    	<!--=====================================
                                        Traer las subcategorías
                                        ======================================-->

                                        <?php 

                                        $url = CurlController::api()."subcategories?linkTo=id_category_subcategory&equalTo=".rawurlencode($value->id_category)."&select=url_subcategory,name_subcategory";
                                        $method = "GET";
                                        $fields = array();
                                        $header = array();

                                        $menuSubcategories = CurlController::request($url, $method, $fields, $header)->results;

                                        ?>
             						
         							 	<?php foreach ($menuSubcategories as $key => $value): ?>

                                            <li class="current-menu-item ">
                                                <a href="<?php echo $path.$value->url_subcategory ?>"><?php echo $value->name_subcategory ?></a>
                                            </li>
                                            
                                        <?php endforeach ?>
    
				                    </ul>
				                </li>

			                <?php endforeach ?>
				               
				            </ul>

				        </aside>   

				    </div>

				</div>        

            </div><!-- End menu-->

        	<a class="ps-logo pl-3 pl-sm-5" href="/">
        		<img src="img/template/logo_light.png" class="pt-3" alt="">
        	</a>

        </div>

        <div class="navigation__right">

            <div class="header__actions">

                <!--=====================================
                Cart
                ======================================-->

                <?php 

                    $totalPriceSC = 0;

                    if(isset($_COOKIE["listSC"])){

                        $shoppingCart = json_decode($_COOKIE["listSC"], true);

                        $totalSC = count($shoppingCart);

                    }else{

                        $totalSC = 0; 
                    }

                ?>

                <div class="ps-cart--mini">

                    <a class="header__extra">
                        <i class="icon-bag2"></i><span><i><?php echo $totalSC ?></i></span>
                    </a>

                    <?php if ($totalSC > 0): ?>

                        <div class="ps-cart__content">

                            <div class="ps-cart__items">

                                <?php foreach ($shoppingCart as $key => $value): ?>

                                    <?php 

                                        /*=============================================
                                        Traer productos del carrito de compras
                                        =============================================*/
                                        $select = "url_product,url_category,name_product,image_product,price_product,offer_product,name_store,shipping_product";

                                        $url = CurlController::api()."relations?rel=products,categories,stores&type=product,category,store&linkTo=url_product&equalTo=".$value["product"]."&select=".$select;
                                        $method = "GET";
                                        $fields = array();
                                        $header = array();

                                        $item = CurlController::request($url, $method, $fields, $header)->results[0];
                                        
                                    ?>
                                    
                                    <div class="ps-product--cart-mobile">

                                        <div class="ps-product__thumbnail">
                                            <a href="<?php echo $path.$item->url_product ?>">
                                                <img src="img/products/<?php echo $item->url_category ?>/<?php echo $item->image_product ?>">
                                            </a>
                                        </div>

                                        <div class="ps-product__content">
                                            
                                            <!-- Eliminar el producto -->
                                            <a 
                                            class="ps-product__remove btn" 
                                            onclick="removeSC('<?php echo $item->url_product ?>','<?php echo $_SERVER["REQUEST_URI"] ?>')">
                                                <i class="icon-cross"></i>
                                            </a>
                                            
                                            <!-- Nombre del producto -->
                                            <a href="<?php echo $path.$item->url_product ?>">
                                                <?php echo $item->name_product ?>     
                                            </a>
                                            
                                            <!-- Tienda del producto -->
                                            <p class="mb-0"><strong>Sold by: </strong><?php echo $item->name_store ?></p>

                                            <!-- Detalles del producto -->
                                            <div class="small text-secondary">

                                                <?php 

                                                    if($value["details"] != ""){
                                                        
                                                        foreach (json_decode($value["details"],true)  as $key => $detail) {

                                                            foreach (array_keys($detail) as $key => $list) {

                                                                echo '<div>'.$list.':'.array_values($detail)[$key].'</div>';               
                                                            }
                                                            
                                                        }

                                                    }

                                                ?>

                                            </div>

                                            <!-- El precio de envío del producto -->
                                            <p class="mb-0">
                                                <strong>Shipping:</strong> $
                                                <?php echo $item->shipping_product * $value["quantity"] ?>

                                                <?php $totalPriceSC += ($item->shipping_product * $value["quantity"]); ?>

                                            </p>

                                            <!-- El precio del producto y la cantidad -->
                                            <p class="mb-0">

                                                <!-- La cantidad del producto -->
                                                <p class="float-left">
                                                    
                                                    <strong>Quantity:</strong> <?php echo $value["quantity"] ?>
                                                
                                                </p>

                                                <!-- Precio del producto -->
              

                                                <?php if ($item->offer_product != null): ?>

                                                    <h4 class="ps-product__price sale float-right text-danger mt-5">
                                                    
                                                        <?php 

                                                        $price = TemplateController::offerPrice(
                                                                
                                                                $item->price_product, 
                                                                json_decode($item->offer_product,true)[1], 
                                                                json_decode($item->offer_product,true)[0]

                                                            );

                                                            echo "$".$price;

                                                            $totalPriceSC += ($price*$value["quantity"]);

                                                        ?>        

                                                        <del class="text-muted"> $<?php echo $item->price_product ?></del>

                                                    </h4>

                                                <?php else: ?>

                                                    <h4 class="ps-product__price float-right text-secondary mt-5">$
                                                        <?php echo $item->price_product ?>
                                                        <?php  $totalPriceSC += ($item->price_product*$value["quantity"]); ?>    

                                                        </h4>

                                                <?php endif ?>

                                            </p>


                                        </div>

                                    </div>

                                <?php endforeach ?>


                            </div>

                            <div class="ps-cart__footer">

                                <h3>Total:<strong>$<?php echo $totalPriceSC ?></strong></h3>
                                <figure>
                                    <a class="ps-btn" href="<?php echo $path ?>shopping-cart">View Cart</a>
                                    <a class="ps-btn" href="<?php echo $path ?>checkout">Checkout</a>
                                </figure>

                            </div>

                        </div>

                    <?php endif ?>

                </div>

                <!--=====================================
                Cuentas de usuario
                ======================================-->

                <?php if (isset($_SESSION["user"])): ?>

                    <div class="ps-block--user-header">
                        <div class="ps-block__left">

                            <?php if ($_SESSION["user"]->method_user == "direct"): ?>

                                <?php if ($_SESSION["user"]->picture_user == ""): ?>

                                    <img class="img-fluid rounded-circle w-50 ml-auto" src="img/users/default/default.png">

                                <?php else: ?>

                                    <img class="img-fluid rounded-circle w-50 ml-auto" src="img/users/<?php echo $_SESSION["user"]->id_user ?>/<?php echo $_SESSION["user"]->picture_user ?>">
                                
                                <?php endif ?>

                            <?php else: ?>

                                  <?php if (explode("/", $_SESSION["user"]->picture_user)[0] == "https:"): ?>

                                        <img class="img-fluid rounded-circle w-50 ml-auto" src="<?php echo $_SESSION["user"]->picture_user ?>">

                                    <?php else: ?>

                                        <img class="img-fluid rounded-circle w-50 ml-auto" src="img/users/<?php echo $_SESSION["user"]->id_user ?>/<?php echo $_SESSION["user"]->picture_user ?>">

                                    <?php endif ?>
                                
                            <?php endif ?>

                        </div>
                        <div class="ps-block__right"> 
                            <a href="<?php echo $path ?>account&wishlist">My Acccount</a>
                        </div>
                    </div>


                <?php else: ?>
                              
                    <!--=====================================
                    Login and Register
                    ======================================-->

                    <div class="ps-block--user-header">
                        <div class="ps-block__left">
                            <i class="icon-user"></i>
                        </div>
                        <div class="ps-block__right">
                            <a href="<?php echo $path ?>account&login">Login</a>
                            <a href="<?php echo $path ?>account&enrollment">Register</a>
                        </div>
                    </div>

                <?php endif ?>

            </div>

        </div>

    </div>

    <!--=====================================
	Search
	======================================-->

    <div class="ps-search--mobile">

        <form class="ps-form--search-mobile">
            <div class="form-group--nest">
                <input class="form-control inputSearch" type="text" placeholder="Search something...">
                <button type="button" class="btnSearch" path="<?php echo $path ?>"><i class="icon-magnifier"></i></button>
            </div>
        </form>

    </div>

</header> <!-- End Header Mobile -->
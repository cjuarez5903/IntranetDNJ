<!--=====================================
My Account Content
======================================--> 

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

            <div class="ps-section__content" id="vendor-store">

                <ul class="ps-section__links">
                    <li><a href="<?php echo $path ?>account&wishlist">My Wishlist</a></li>
                    <li><a href="<?php echo $path ?>account&my-shopping">My Shopping</a></li>
                    <li class="active"><a href="<?php echo $path ?>account&my-store">My Store</a></li>
                    <li><a href="<?php echo $path ?>account&my-sales">My Sales</a></li>
                </ul>

                <!--=====================================
                My Store
                ======================================--> 
                <div class="ps-vendor-store">

                    <div class="container">

                        <div class="ps-section__container">

                            <!--=====================================
                            Vendor Profile
                            ======================================--> 

                           <?php 

                            include "modules/store.php";

                            ?>

                            <!--=====================================
                            Products
                            ======================================--> 

                            <?php 

                            if(isset($urlParams[2])){

                                if($urlParams[2] == "orders" || $urlParams[2] == "disputes" || $urlParams[2] == "messages"){

                                    include "modules/".$urlParams[2].".php";
                                
                                }else{

                                    include "modules/products.php";
                                
                                }

                            }else{

                                include "modules/products.php";
                            
                            }
                            
                            ?>
                       
                        </div>
                    </div>
                </div>
             

            </div>


        </div>

    </div>

</div>
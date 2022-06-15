<?php if (isset($_GET["product"])): ?>   

<?php 

if($_GET["product"] != "new"){

    include "views/pages/account/my-store/modules/edit-product.php";

}else{

    include "views/pages/account/my-store/modules/new-product.php";
}

?>

<?php else: ?>


<div class="ps-section__right">

    
    <div class="d-flex justify-content-between">
    
        <div>
            <a href="<?php echo TemplateController::path()  ?>account&my-store?product=new#vendor-store" class="btn btn-lg btn-warning my-3">Create new product</a>
        </div>
        
        <div>
            <ul class="nav nav-tabs">  

                <li class="nav-item">
                  <a class="nav-link active" href="<?php echo TemplateController::path() ?>account&my-store">Products</a>
                </li>

                <li class="nav-item">
                  <a class="nav-link" href="<?php echo TemplateController::path() ?>account&my-store&orders">Orders</a>
                </li>

                <li class="nav-item">
                  <a class="nav-link" href="<?php echo TemplateController::path() ?>account&my-store&disputes">Disputes</a>
                </li>

                <li class="nav-item">
                  <a class="nav-link" href="<?php echo TemplateController::path() ?>account&my-store&messages">Messages</a>
                </li>
               
            </ul>

        </div>

    </div>

    <input type="hidden" id="path" value="<?= TemplateController::path() ?>">
    <input type="hidden" id="idStore" value="<?= $store[0]->id_store ?>">
    <input type="hidden" id="urlApi" value="<?= CurlController::api() ?>">

    <table class="table dt-responsive dt-server-products" width="100%">
        
        <thead>

            <tr>   
                
                <th>#</th>   

                 <th>Actions</th>

                <th>Feedback</th>  

                <th>State</th>    

                <th>Image</th>   

                <th>Name</th>

                <th>Category</th>

                <th>Subcategory</th>

                 <th>Price</th>

                <th>Shipping</th>

                <th>Stock</th>

                <th>Delivery time</th>

                <th>Offer</th>

                <th>Summary</th>

                <th>Specification</th>

                <th>Details</th>

               <th>Description</th>      

               <th>Gallery</th>

                <th>Top Banner</th>

                <th>Default Banner</th>

                <th>Horizontal Slider</th>

                <th>Vertical Slider</th>

                <th>Video</th>

                <th>Tags</th>

                <th>Views</th>

                <th>Sales</th>

                <th>Reviews</th>

                <th>Date Created</th> 

            </tr>

        </thead>

    </table>
    
       
</div>

<?php endif ?>

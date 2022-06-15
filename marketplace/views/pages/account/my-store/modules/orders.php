<div class="ps-section__right">

	 <div class="d-flex justify-content-between">

	 	<div>
            <a href="<?php echo TemplateController::path()  ?>account&my-store?product=new#vendor-store" class="btn btn-lg btn-warning my-3">Create new product</a>
        </div>

        <div>
            <ul class="nav nav-tabs">  

                <li class="nav-item">
                  <a class="nav-link" href="<?php echo TemplateController::path() ?>account&my-store">Products</a>
                </li>

                <li class="nav-item">
                  <a class="nav-link active" href="<?php echo TemplateController::path() ?>account&my-store&orders">Orders</a>
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

    <table class="table dt-responsive dt-server-orders" width="100%">

     	<thead>

            <tr>   
                
                <th>#</th>   

                <th>Status</th>

                <th>Client</th>  

                <th>Email</th>    

                <th>Country</th>   

                <th>City</th>

                <th>Address</th>

                <th>Phone</th>

                <th>Product</th>

                <th>Quantity</th>

                <th>Details</th>

                <th>Price</th>

                <th>Process</th>

                <th>Date</th>

            </tr>

        </thead>

    </table>

</div>

<!--=====================================
Ventana modal para el proceso de entrega
======================================-->

<div class="modal" id="nextProcess">
	
	<div class="modal-dialog modal-lg">
	 	
	 	<div class="modal-content">
	 		
	 		<form method="post">
	 			
	 			<!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Next Process for <span></span></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">

                    <div class="card my-3 orderBody">

                        

                    </div>

                </div>

                <!-- Modal footer -->
                <div class="modal-footer">

                    <div class="form-group submtit">
                        
                        <button class="ps-btn ps-btn--fullwidth orderUpdate">Save</button>

                    </div>

                </div>  

                <?php

                	$order = new VendorsController();
                	$order -> orderUpdate();

                ?>

	 		</form>


	 	</div>

	</div>

</div>
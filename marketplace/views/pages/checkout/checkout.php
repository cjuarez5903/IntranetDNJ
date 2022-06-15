<?php 

if(!isset($_SESSION["user"])){

    echo '<script>

        fncSweetAlert(
                "error",
                "Please login",
                "'.$path.'account&login"
            );

    </script>'; 

    return;

}else{

    date_default_timezone_set("America/Bogota");

    $time = time();
    
     if($_SESSION["user"]->token_exp_user < $time){
       
        echo '<script>

            fncSweetAlert(
                "error",
                "Error: the token has expired, please login again",
                "'.$path.'account&logout"
            );

        </script>'; 

        return;

    }

}

?>

<!--=====================================
Breadcrumb
======================================-->  

<div class="ps-breadcrumb">

    <div class="container">

        <ul class="breadcrumb">

            <li><a href="/">Home</a></li>

            <li><a href="<?php echo $path ?>shopping-cart">Shopping cart</a></li>

            <li>Checkout</li>

        </ul>

    </div>

</div>

<!--=====================================
Checkout
======================================--> 

<div class="ps-checkout ps-section--shopping">

    <div class="container">

        <div class="ps-section__header">

            <h1>Checkout</h1>

        </div>

        <div class="ps-section__content">

            <form class="ps-form--checkout needs-validation" novalidate method="post" onsubmit="return checkout()">

                <input type="hidden" id="idUser" value="<?php echo $_SESSION["user"]->id_user ?>">
                <input type="hidden" id="urlApi" value="<?php echo CurlController::api() ?>">
                <input type="hidden" id="url" value="<?php echo $path ?>">

                <div class="row">

                    <div class="col-xl-7 col-lg-8 col-sm-12">

                        <div class="ps-form__billing-info">

                            <h3 class="ps-form__heading">Billing Details</h3>

                            <!--=====================================
							Nombre completo del usuario
							======================================--> 

                            <div class="form-group">

                                <label>Display Name<sup>*</sup></label>

                                <div class="form-group__content">

                                    <input class="form-control" value="<?php echo $_SESSION["user"]->displayname_user ?>"  type="text" readonly required>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill in this field correctly.</div>

                                </div>

                            </div>

                            <!--=====================================
							Email del usuario
							======================================--> 

                            <div class="form-group">

                                <label>Email Address<sup>*</sup></label>

                                <div class="form-group__content">

                                    <input id="emailOrder" class="form-control" type="email" value="<?php echo $_SESSION["user"]->email_user ?>" readonly required>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill in this field correctly.</div>

                                </div>

                            </div>

                            <!--=====================================
							Pais del usuario
							======================================--> 

                            <div class="form-group">

                                <label>Country<sup>*</sup></label>

                                <?php

                                	$data = file_get_contents("views/json/countries.json");
                                	$countries = json_decode($data, true);

                                ?>

                                <div class="form-group__content">

                                    <select
                                    id="countryOrder" 
                                    class="form-control select2" 
                                    onchange="changeCountry(event)"
                                    required>

                                    <?php if ($_SESSION["user"]->country_user != null): ?>

                                    	<option value="<?php echo $_SESSION["user"]->country_user ?>_<?php echo explode("_", $_SESSION["user"]->phone_user)[0] ?>"><?php echo $_SESSION["user"]->country_user ?></option>

                                    <?php else: ?>

                                    	<option value>Select Country</option>

                                    <?php endif ?>

                                    <?php foreach ($countries as $key => $value): ?>

                                    	<option value="<?php echo $value["name"] ?>_<?php echo $value["dial_code"] ?>"><?php echo $value["name"] ?></option>
                                    	
                                    <?php endforeach ?>

                                    </select>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill in this field correctly.</div>

                                </div>

                            </div>

                            <!--=====================================
							Ciudad del usuario
							======================================--> 

                            <div class="form-group">

                                <label>City<sup>*</sup></label>

                                <div class="form-group__content">

                                    <input 
                                    id="cityOrder" 
                                    class="form-control" 
                                    type="text" 
                                    pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}"
                                    onchange="validateJS(event, 'text')" 
                                    value="<?php echo $_SESSION["user"]->city_user ?>" 
                                    required>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill in this field correctly.</div>

                                </div>

                            </div>

                            <!--=====================================
							Teléfono del usuario
							======================================--> 

                            <div class="form-group">

                                <label>Phone<sup>*</sup></label>

                                <div class="form-group__content input-group">

                                	<?php if ($_SESSION["user"]->phone_user != null): ?>

                                		<div class="input-group-append">
	                                		<span class="input-group-text dialCode"><?php echo explode("_", $_SESSION["user"]->phone_user)[0] ?></span>
	                                	</div>

	                                	<?php

	                                	$phone = explode("_", $_SESSION["user"]->phone_user)[1];

	                                	?>

                                	<?php else: ?>

                                		<div class="input-group-append">
	                                		<span class="input-group-text dialCode">+00</span>
	                                	</div>

	                                	<?php

	                                	$phone = "";

	                                	?>
                                		
                                	<?php endif ?>

                                	

                                    <input 
                                    id="phoneOrder" 
                                    class="form-control" 
                                    type="text" 
                                    pattern="[-\\(\\)\\0-9 ]{1,}"
                                    onchange="validateJS(event, 'phone')" 
                                    value="<?php echo $phone ?>" 
                                    required>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill in this field correctly.</div>

                                </div>

                            </div>

                            <!--=====================================
							Dirección del usuario
							======================================--> 

                            <div class="form-group">

                                <label>Address<sup>*</sup></label>

                                <div class="form-group__content">
 
                                    <input
                                    id="addressOrder" 
                                    class="form-control" 
                                    type="text"
                                    pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
                                    onchange="validateJS(event, 'paragraphs')" 
                                    value="<?php echo $_SESSION["user"]->address_user ?>" 
                                    required>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill in this field correctly.</div>

                                </div>

                            </div>

                            <!--=====================================
							Guardar Dirección del usuario
							======================================--> 

                            <div class="form-group">

                                <div class="ps-checkbox">

                                    <input class="form-control" type="checkbox" id="create-account">

                                    <label for="create-account">Save address?</label>

                                </div>

                            </div>

                            <!--=====================================
							Información adicional de la orden
							======================================--> 

                            <h3 class="mt-40"> Addition information</h3>

                            <div class="form-group">

                                <label>Order Notes</label>

                                <div class="form-group__content">

                                    <textarea 
                                    id="infoOrder" 
                                    class="form-control" 
                                    rows="7"
                                    pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
                                    onchange="validateJS(event, 'paragraphs')"   
                                    placeholder="Notes about your order, e.g. special notes for delivery."></textarea>

                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill in this field correctly.</div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!--=====================================
					Información de la orden
					======================================--> 

                    <div class="col-xl-5 col-lg-4 col-sm-12">

                        <div class="ps-form__total">

                            <h3 class="ps-form__heading">Your Order</h3>

                            <div class="content">

                                <div class="ps-block--checkout-total">

                                    <div class="ps-block__header d-flex justify-content-between">

                                        <p>Product</p>

                                        <p>Total</p>

                                    </div>

                                    <?php 

                                    $totalOrder = 0;

                                    if(isset($_COOKIE["listSC"])){

                                    	$order = json_decode($_COOKIE["listSC"],true);

                                    }else{

                                    	echo '<script>

                                            window.location = "'.$path.'";

                                        </script>'; 

                                        return;

                                    }

                                    ?>

                                    <div class="ps-block__content">

                                        <table class="table ps-block__products">

                                            <tbody>

                                            	<?php foreach ($order as $key => $value): ?>
                                            		
                                            		<?php 

                                            			$subTotalOrder = 0;

                                            			/*=============================================
                                                    	Traer productos del carrito de compras
                                                    	=============================================*/

                                                    	$select = "id_product,name_product,url_product,id_store,name_store,url_store,shipping_product,price_product,offer_product,delivery_time_product,sales_product,stock_product";

                                                    	$url = CurlController::api()."relations?rel=products,categories,stores&type=product,category,store&linkTo=url_product&equalTo=".$value["product"]."&select=".$select;
                                                    	 $method = "GET";
                                                    	$fields = array();
                                                    	$header = array();

                                                    	$pOrder = CurlController::request($url, $method, $fields, $header)->results[0];
                                                 

                                            		?>

                                                <tr>

                                                    <td>

                                                        <input type="hidden" class="idStore" value="<?php echo $pOrder->id_store ?>">
                                                        <input type="hidden" class="idProduct" value="<?php echo $pOrder->id_product ?>">
                                                        <input type="hidden" class="deliveryTime" value="<?php echo $pOrder->delivery_time_product ?>">
                                                        <input type="hidden" class="urlStore" value="<?php echo $pOrder->url_store ?>">
                                                        <input type="hidden" class="salesProduct" value="<?php echo $pOrder->sales_product ?>">
                                                        <input type="hidden" class="stockProduct" value="<?php echo $pOrder->stock_product ?>">

                                                    	<!--=====================================
														Nombre del producto
														======================================--> 
                                                        
                                                        <a href="<?php echo $path.$pOrder->url_product ?>" class="nameProduct"> <?php echo $pOrder->name_product ?></a>

                                                        <div class="small text-secondary">

                                                        	<!--=====================================
															Tienda del producto
															======================================--> 
                                                        
	                                                        <div>Sold By: <a href="<?php echo $path.$pOrder->url_store ?>"><strong><?php echo $pOrder->name_store ?></strong></a></div>

	                                                        <!--=====================================
															Detalles del producto
															======================================--> 
	                                                        
	                                                        <div class="detailsOrder">
	                                                        	
	                                                        	<?php if ($value["details"] != ""): ?>
	                                                        	
	                                                        		<?php foreach (json_decode($value["details"], true) as $key => $item): ?>

	                                                        			<?php foreach (array_keys($item) as $key => $detail): ?>

	                                                        					<div><?php echo $detail.": ".array_values($item)[$key] ?></div>
	                                                        				
	                                                        			<?php endforeach ?>
     			
	                                                        		<?php endforeach ?>
	                                  
	                                                        	<?php endif ?>

	                                                        </div>

	                                                        <!--=====================================
															Precio de envío del producto
															======================================--> 

															<div>Shipping: <?php echo $pOrder->shipping_product * $value["quantity"] ?> </div>

															<?php 

																$subTotalOrder += $pOrder->shipping_product * $value["quantity"];
																

															?>


	                                                        <!--=====================================
															Cantidad del producto
															======================================--> 

	                                                        <div>Quantity: <span class="quantityOrder"><?php echo $value["quantity"] ?></span> </div>

                                                        </div>
                                                    </td>

                                                    <!--=====================================
													Precio definitivo del producto
													======================================--> 

													<?php 

														if ($pOrder->offer_product != null){

															 	$price = TemplateController::offerPrice(

															 	 	$pOrder->price_product, 
                                                                    json_decode($pOrder->offer_product,true)[1], 
                                                                    json_decode($pOrder->offer_product,true)[0]

                                                                );

                                                                $subTotalOrder += $price*$value["quantity"];

														}else{


															$subTotalOrder += $pOrder->price_product*$value["quantity"];


														}

														$totalOrder += $subTotalOrder;

													?>     

                                                    <td class="text-right">$<span class="priceOrder"><?php echo $subTotalOrder ?></span></td>

                                                </tr>

                                                <?php endforeach ?>

                                            </tbody>

                                        </table>
                                        
                                        <h3 class="text-right totalOrder" total="<?php echo $totalOrder ?>">Total <span>$<?php echo $totalOrder ?></span></h3>

                                    </div>

                                </div>

                                <hr class="py-3">

                                <div class="form-group">

                                    <div class="ps-radio">

                                        <input 
                                        class="form-control" 
                                        type="radio" 
                                        id="pay-paypal" 
                                        name="payment-method"
                                        value="paypal" 
                                        onchange="changeMethodPaid(event)"
                                        checked>

                                        <label for="pay-paypal">Pay with paypal?  <span><img src="img/payment-method/paypal.jpg" class="w-50"></span></label>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <div class="ps-radio">

                                        <input 
                                        class="form-control" 
                                        type="radio" 
                                        id="pay-payu" 
                                        name="payment-method" 
                                        value="payu"
                                        onchange="changeMethodPaid(event)">

                                        <label for="pay-payu">Pay with payu? <span><img src="img/payment-method/payu.jpg" class="w-50"></span></label>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <div class="ps-radio">

                                        <input 
                                        class="form-control" 
                                        type="radio" 
                                        id="pay-mercadopago" 
                                        name="payment-method" 
                                        value="mercado-pago"
                                        onchange="changeMethodPaid(event)">

                                        <label for="pay-mercadopago">Pay with Mercado Pago? <span><img src="img/payment-method/mercado_pago.jpg" class="w-50"></span></label>

                                    </div>

                                </div>

                                <button type="submit" class="ps-btn ps-btn--fullwidth">Proceed to checkout</button>

                            </div>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

<?php 

/*=============================================
Recibir variables de PAYU página de respuesta
=============================================*/

if (isset($_REQUEST['transactionState']) && $_REQUEST['transactionState'] == 4 && isset($_REQUEST['reference_pol'])) {

    $idPayment = $_REQUEST['reference_pol'];

    endCheckout($_REQUEST['reference_pol']);

}

/*=============================================
Recibir variables de PAYU página de confirmación
=============================================*/

if (isset($_REQUEST['state_pol']) && $_REQUEST['state_pol'] == 4 && isset($_REQUEST['reference_pol'])) {

    $idPayment = $_REQUEST['reference_pol'];

    endCheckout($_REQUEST['reference_pol']);

}

/*=============================================
Recibir variables de MP
=============================================*/

if(isset($_COOKIE["mp"])){

    $mp = json_decode($_COOKIE["mp"], true);

    MercadoPago\SDK::setAccessToken("YOUR ACCESS TOKEN");

    $payment = new MercadoPago\Payment();
    $payment->transaction_amount = $mp["transaction_amount"];
    $payment->token = $mp["token"];
    $payment->description = $mp['description'];
    $payment->installments = $mp['installments'];
    $payment->payment_method_id = $mp['payment_method_id'];
    $payment->issuer_id = $mp['issuer_id'];

    $payer = new MercadoPago\Payer();
    $payer->email = $mp['email'];
    $payer->identification = array(
        "type" => $mp['identificationType'],
        "number" => $mp['identificationNumber']
    );
    $payment->payer = $payer;

    $payment->save();

    if($payment->status == "approved"){

        endCheckout($payment->id);

    }
}

/*=============================================
Función para finalizar el checkout
=============================================*/

function endCheckout($idPayment){

    $totalProcess = 0;

    /*=============================================
    Actualizamos las ventas y disminuir el stock de los productos
    =============================================*/
    if(isset($_COOKIE['idProduct']) && isset($_COOKIE['quantityOrder']) ){       
       
        $idProduct = json_decode($_COOKIE['idProduct'], true);
        $quantityOrder = json_decode($_COOKIE['quantityOrder'], true);

        foreach ($idProduct as $key => $value) {

            $url = CurlController::api()."products?linkTo=id_product&equalTo=".$value."&select=stock_product,sales_product";
            $method = "GET";
            $fields = array();
            $header = array();

            $products = CurlController::request($url, $method, $fields, $header)->results[0];

            /*=============================================
            Actualizamos las ventas y disminuimos el stock de los productos
            =============================================*/

            $stock = $products->stock_product-$quantityOrder[$key];          
            $sales = $products->sales_product+$quantityOrder[$key]; 

            /*=============================================
            Actualizar el stock y las ventas de cada producto
            =============================================*/

            $url = CurlController::api()."products?id=".$value."&nameId=id_product&token=".$_SESSION["user"]->token_user;
            $method = "PUT";
            $fields =  "sales_product=".$sales."&stock_product=".$stock;
            $header = array();

            $updateProducts = CurlController::request($url, $method, $fields, $header);        

            if($updateProducts->status == 200){

                $totalProcess++;
            }
        }

    }

    /*=============================================
    Actualizamos el estado de la orden
    =============================================*/
    if(isset($_COOKIE['idOrder'])){
       
        $idOrder= json_decode($_COOKIE['idOrder'], true);

        foreach ($idOrder as $key => $value) {

            $url = CurlController::api()."orders?id=".$value."&nameId=id_order&token=".$_SESSION["user"]->token_user;
            $method = "PUT";
            $fields =  "status_order=pending";
            $header = array();

            $updateOrders = CurlController::request($url, $method, $fields, $header);

            if($updateOrders->status == 200){

                $totalProcess++;
            }

        }

    }

    /*=============================================
    Actualizamos el estado de la venta
    =============================================*/

    if(isset($_COOKIE['idSale'])){
        
        $idSale = json_decode($_COOKIE['idSale'], true);

        foreach ($idSale as $key => $value) {

            $url = CurlController::api()."sales?id=".$value."&nameId=id_sale&token=".$_SESSION["user"]->token_user;
            $method = "PUT";
            $fields =  "status_sale=pending&id_payment_sale=".$idPayment;
            $header = array();

            $updateSales = CurlController::request($url, $method, $fields, $header);

            if($updateSales->status == 200){

                $totalProcess++;
            }

        }

    }
      /*=============================================
    Cerramos el proceso de venta
    =============================================*/

    if($totalProcess == (count($idProduct)+count($idOrder)+count($idSale))){

         echo '<script>

            document.cookie = "listSC=; max-age=0";
            document.cookie = "idProduct=; max-age=0";
            document.cookie = "quantityOrder=; max-age=0";
            document.cookie = "idOrder=; max-age=0";
            document.cookie = "idSale=; max-age=0";
            fncSweetAlert("success", "The purchase has been executed successfully", "/account&my-shopping");

        </script>';

    }
}

?>

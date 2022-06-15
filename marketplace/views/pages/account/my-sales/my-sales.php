<?php 

if(!isset($_SESSION["user"])){

    echo '<script>

        window.location = "'.$path.'";

    </script>'; 

    return;

}else{

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

    }else{

        /*=============================================
       Traer el Id de la tienda
        =============================================*/

        $select = "id_store";

        $url = CurlController::api()."stores?linkTo=id_user_store&equalTo=".$_SESSION["user"]->id_user."&select=".$select;
        $method = "GET";
        $fields = array();
        $header = array();

        $idStore = CurlController::request($url, $method, $fields, $header);
        
        if($idStore->status == 200){

            if(!empty($idStore->results[0]->id_store)){

                $idStore = $idStore->results[0]->id_store;

               /*=============================================
                Preguntar si viene rango de fechas
                =============================================*/

                if(isset($_GET["between1"]) && isset($_GET["between2"])){

                    $between1 = date("Y-m-d", strtotime($_GET["between1"]));
                    $between2 = date("Y-m-d", strtotime($_GET["between2"]));
                    
                    /*=============================================
                    Data de ventas
                    =============================================*/

                    $select = "unit_price_sale,commission_sale,date_created_sale,quantity_order,name_product_sale";

                    $url = CurlController::api()."relations?rel=sales,orders&type=sale,order&linkTo=date_created_sale&between1=".$between1."&between2=".$between2."&filterTo=id_store_sale&inTo=".$idStore."&select=".$select."&orderBy=id_sale&orderMode=ASC&token=".$_SESSION["user"]->token_user;
                    $method = "GET";
                    $fields = array();
                    $header = array();

                    $sales = CurlController::request($url, $method, $fields, $header)->results;

                }else{
      
                    /*=============================================
                    Data de ventas
                    =============================================*/

                    $select = "unit_price_sale,commission_sale,date_created_sale,quantity_order,name_product_sale";

                    $url = CurlController::api()."relations?rel=sales,orders&type=sale,order&linkTo=id_store_sale&equalTo=".$idStore."&select=".$select."&orderBy=id_sale&orderMode=ASC&token=".$_SESSION["user"]->token_user;

                    $method = "GET";
                    $fields = array();
                    $header = array();

                    $sales = CurlController::request($url, $method, $fields, $header)->results;

                }
                    
                if(!is_array($sales)){

                    $sales = array();
                   

                }

               

            }

        }


    }


}


?>


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

            <div class="ps-section__content">

                  <ul class="ps-section__links">
                    <li><a href="<?php echo $path ?>account&wishlist">My Wishlist</a></li>
                    <li><a href="<?php echo $path ?>account&my-shopping">My Shopping</a></li>
                    <li><a href="<?php echo $path ?>account&my-store">My Store</a></li>
                    <li class="active"><a href="<?php echo $path ?>account&my-sales">My Sales</a></li>
                </ul>

                <!--=====================================
                Elegir rango de fecha
                ======================================--> 

                <form class="ps-form--vendor-datetimepicker mt-5" method="get">

                    <div class="row">

                        <div class="col-lg-3 col-md-4 col-sm-6 col-12 ">

                            <div class="input-group">

                                <div class="input-group-prepend">

                                    <span class="input-group-text" id="time-from">From</span>

                                </div>

                                <input class="form-control ps-datepicker" name="between1" value="<?php if(isset($_GET["between1"])) echo $_GET["between1"] ?>" aria-describedby="time-from">

                            </div>

                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 col-12 ">

                            <div class="input-group">

                                <div class="input-group-prepend">

                                    <span class="input-group-text" id="time-form">To</span>

                                </div>

                                <input class="form-control ps-datepicker" name="between2" value="<?php if(isset($_GET["between2"])) echo $_GET["between2"] ?>" aria-describedby="time-to">

                            </div>

                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 col-12 ">

                            <button type="submit" class="ps-btn"><i class="icon-sync2"></i> Update</button>

                        </div>

                    </div>

                </form>

                <!--=====================================
                Tabla de comisiones
                ======================================--> 

                <?php 

                error_reporting(0);

                $profits = 0;
                $commissions = 0;
                $totals = 0;

                $arrayDate = array();
                $sumSales = array();

                foreach ($sales as $key => $value){

                    $profits+= $value->unit_price_sale;
                    $commissions+=$value->commission_sale;
                    $totals+=$profits+$commissions;

                    //Capturamos año y mes
                    $date = substr($value->date_created_sale, 0, 7);

                    //Introducir fechas en un nuevo array
                    array_push($arrayDate, $date);
                   
                    //Capturar las ventas que ocurrieron en dichas fechas
                    $arraySales = array($date => $value->unit_price_sale);
                    
                    //Sumamos los pagos que ocurrieron el mismo mes
                    foreach ($arraySales  as $index => $item) {

                        $sumSales[$index] += $item;
                        
                        
                    }

                }

                //Agrupar las fechas en un nuevo arreglo para que no se repitan
                $dateNoRepeat = array_unique($arrayDate);
     
                ?>

                <div class="row">

                    <div class="col-12 ">

                        <figure class="ps-block--vendor-status">

                            <figcaption>Commissions Sales</figcaption>

                            <table class="table ps-table ps-table--vendor-status">

                                <tbody>

                                    <tr>
                                        <td>Store Profits</td>
                                        <td>$<?php echo number_format($profits,2) ?></td>
                                    </tr>

                                    <tr>
                                        <td>Commissions</td>
                                        <td>$<?php echo number_format($commissions,2) ?></td>
                                    </tr>

                                    <tr>
                                        <td><strong>Total</strong></td>
                                        <td>$<?php echo number_format($totals,2) ?></td>
                                    </tr>

                                </tbody>

                            </table>

                        </figure>

                    </div>

                </div>

                <!--=====================================
                Gráfico de ventas
                ======================================--> 

                <div class="col-12">

                    <figure class="ps-block--vendor-status">

                        <figcaption>Sales Graph</figcaption>

                        <canvas id="line-chart" width="585" height="292" class="chartjs-render-monitor" style="display: block; width: 585px; height: 292px;"></canvas>

                    </figure>

                </div>

                <!--=====================================
                Tabla de ventas individuales
                ======================================--> 

                <div class="col-12">
                    
                    <figure class="ps-block--vendor-status">

                        <figcaption>Sales Table</figcaption>

                    </figure>

                    <div class="table-responsive">
                        
                        <table class="table ps-table ps-table--vendor dt-responsive dt-client" datatable width="100%">

                            <thead>
                                <tr>
                                    <th>date</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Comission</th>
                                    <th>Total</th>
                                </tr>
                            </thead>

                            <tbody>  

                                <?php foreach ($sales as $key => $value): ?>

                                    <tr>
                                        <td><?php echo $value->date_created_sale ?></td>
                                        <td><?php echo $value->name_product_sale ?></td>   
                                        <td><?php echo $value->quantity_order ?></td>  
                                        <td>$ <?php echo $value->unit_price_sale ?></td>
                                        <td>$ <?php echo $value->commission_sale ?></td>
                                        <td>$ <?php echo $value->unit_price_sale + $value->commission_sale ?></td>
                                    </tr>
                                    
                                <?php endforeach ?>

                               

                            </tbody>

                        </table>

                    </div>

                </div>


            </div>

        </div>

    </div>

</div>

<script>
    
    var config = {
        type: 'line',
         data: {
            labels: [

            <?php 

            foreach ($dateNoRepeat as $key => $value) {
                
                echo "'".$value."',";
            }


            ?>

            ],
            datasets: [{
                label: 'Sales',
                backgroundColor: 'red',
                borderColor: 'red',
                data: [

                    <?php

                        foreach($dateNoRepeat as $key => $value){

                            echo "'".$sumSales[$value]."',";

                        }

                    ?>


                ],
                fill: false,
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Total is <?php echo count($sales) ?> sales from <?php echo $sales[0]->date_created_sale ?> - <?php echo $sales[count($sales)-1]->date_created_sale ?>'
            }
                   
        }
    };

window.onload = function() {
    var ctx = document.getElementById('line-chart').getContext('2d');
    window.myLine = new Chart(ctx, config);
 
};


</script>
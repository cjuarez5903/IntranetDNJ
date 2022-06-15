<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";


class DataTableController{

    /*=============================================
    Función DataTable
    =============================================*/ 

    public function dataOrders(){

        if (!empty($_POST)){

            $draw = $_POST["draw"]; //Contador utilizado por DataTables para garantizar que los retornos de Ajax de las solicitudes de procesamiento del lado del servidor sean dibujados en secuencia por DataTables 

            $orderByColumnIndex = $_POST['order'][0]['column']; //Índice de la columna de clasificación (0 basado en el índice, es decir, 0 es el primer registro)

            $orderBy = $_POST['columns'][$orderByColumnIndex]['data']; //Obtener el nombre de la columna de clasificación de su índice

            $orderType = $_POST['order'][0]['dir']; // Obtener el orden ASC o DESC

            $start  = $_POST["start"];//Indicador de primer registro de paginación.

            $length = $_POST['length'];//Indicador de la longitud de la paginación.

            /*=============================================
            Traer el total de la data de órdenes
            =============================================*/

            $select = "id_order";

            $url = CurlController::api()."orders?linkTo=id_store_order&equalTo=".$_GET["idStore"]."&select=".$select."&token=".$_GET["token"];

            $method = "GET";
            $fields = array();
            $header = array();

            $data = CurlController::request($url, $method, $fields, $header);

            if($data->status == 200){

               $totalData = $data->total;

            }else{

                echo '{"data": []}';

                return;
          
            }

            /*=============================================
            Traer la data de productos de acuerdo a la paginación o al orden o a la búsqueda
            =============================================*/

            $select = "id_order,id_store_order,id_user_order,id_product_order,details_order,quantity_order,price_order,email_order,country_order,city_order,phone_order,address_order,notes_order,process_order,status_order,date_created_order,name_product,displayname_user,email_user";

            /*=============================================
            Cuando se usa el buscador de DataTable
            =============================================*/

            if(!empty($_POST['search']['value'])){

                $linkTo = ["name_product","displayname_user","email_user","country_order","city_order","price_order","status_order"];

                $search = str_replace(" ", "_", $_POST['search']['value']);

                foreach ($linkTo as $key => $value) {
                   
                   $url = CurlController::api()."relations?rel=orders,stores,users,products&type=order,store,user,product&linkTo=".$value.",id_store_order&search=".$search.",".$_GET["idStore"]."&orderBy=".$orderBy."&orderMode=".$orderType."&startAt=".$start."&endAt=".$length."&select=".$select."&token=".$_GET["token"];
                  
                   $searchOrders = CurlController::request($url, $method, $fields, $header)->results; 

                    if($searchOrders == "Not Found"){

                      $dataOrders = array();

                    }else{

                        $dataOrders = $searchOrders; 
                        $recordsFiltered = count($dataOrders); 

                        break; 

                    }
                }

            }else{

                $url = CurlController::api()."relations?rel=orders,stores,users,products&type=order,store,user,product&linkTo=id_store_order&equalTo=".$_GET["idStore"]."&orderBy=".$orderBy."&orderMode=".$orderType."&startAt=".$start."&endAt=".$length."&select=".$select."&token=".$_GET["token"];

                $dataOrders = CurlController::request($url, $method, $fields, $header)->results;

                $recordsFiltered = $totalData;   

            }
          
           /*=============================================
            Verificamos que la tabla no venga vacía
            =============================================*/

            if(count($dataOrders) == 0){

                echo '{"data": []}';

                return;
            }

            /*=============================================
            Construimos el dato JSON a regresar
            =============================================*/

            $dataJson = '{ 

                "Draw": '.intval($draw).',
                "recordsTotal": '. $totalData.',
                "recordsFiltered": '.$recordsFiltered.',
                "data": [

            ';

            /*=============================================
            Recorremos la data de productos
            =============================================*/

            foreach ($dataOrders as $key => $value) {


                /*=============================================
                Status de la orden
                =============================================*/

                if($value->status_order == 'pending'){

                    $status_order = "<span class='badge badge-danger p-3'>".$value->status_order."</span>";
                
                }else{

                    $status_order = "<span class='badge badge-success p-3'>".$value->status_order."</span>";

                }

                /*=============================================
                Cliente de la orden
                =============================================*/

                $client_order = $value->displayname_user;

                /*=============================================
                EMail del Cliente de la orden
                =============================================*/

                $email_order = $value->email_user;

                /*=============================================
                País de la orden
                =============================================*/

                $country_order = $value->country_order;

                /*=============================================
                Ciudad de la orden
                =============================================*/

                $city_order = $value->city_order;

                /*=============================================
                Dirección de la orden
                =============================================*/

                $address_order = $value->address_order;

                /*=============================================
                Teléfono de la orden
                =============================================*/

                $phone_order = $value->phone_order;

                /*=============================================
                Producto de la orden
                =============================================*/

                $product_order = $value->name_product;

                /*=============================================
                Cantidad de la orden
                =============================================*/

                $quantity_order = $value->quantity_order;

                /*=============================================
                Detalles de la orden
                =============================================*/

                $details_order  =  TemplateController::htmlClean($value->details_order);

                /*=============================================
                Precio de la orden
                =============================================*/

                $price_order = $value->price_order;

                /*=============================================
                Proceso de la orden
                =============================================*/

                $process_order = "<ul class='timeline'>";

                    foreach (json_decode($value->process_order, true) as $index => $item){


                        if ($item["status"] == "ok"){

                            $process_order .= "<li class='success pl-5 ml-5'>
                                                <h5>".$item["date"]."</h5>
                                                <p class='text-success'>".$item["stage"]."<i class='fas fa-check pl-3'></i></p>
                                                <p>Comment: ".$item["comment"]."</p>
                                            </li>";

                        }else{

                            $process_order .= "<li class='process pl-5 ml-5'>
                                                <h5>".$item["date"]."</h5>
                                                <p>".$item["stage"]."</p> 
                                                <button class='btn btn-primary' disabled>
                                                  <span class='spinner-border spinner-border-sm'></span>
                                                  In process
                                                </button>
                                            </li>";

                        }

                    }                                  

                $process_order .= "</ul>";

                if($value->status_order == 'pending'){

                    $process_order .= "<a class='btn btn-warning btn-lg nextProcess' idOrder='".$value->id_order."' processOrder='".base64_encode($value->process_order)."' clientorder='".$client_order."' emailOrder='".$email_order."' productOrder='".$product_order."'>Next Process</a>";

                }

                $process_order  =  TemplateController::htmlClean($process_order);

                /*=============================================
                Fecha de creación del la orden
                =============================================*/

                $date_created_order = $value->date_created_order;

                /*=============================================
                Creamos los campos a mostrar
                =============================================*/

                $dataJson.='{ 

                    "id_order":"'.($start+$key+1).'",
                    "status_order":"'.$status_order.'",
                    "displayname_user":"'.$client_order.'",
                    "email_order":"'.$email_order.'",
                    "country_order":"'.$country_order.'",
                    "city_order":"'.$city_order.'",
                    "address_order":"'.$address_order.'",
                    "phone_order":"'.$phone_order.'",
                    "name_product":"'.$product_order.'",
                    "quantity_order":"'.$quantity_order.'",
                    "details_order":"'.$details_order.'",
                    "price_order":"$'.$price_order.'",
                    "process_order":"'.$process_order.'",
                    "date_created_order":"'.$date_created_order.'"             
                },';

            }

            $dataJson = substr($dataJson, 0, -1);  // este substr quita el último caracter de la cadena, que es una coma, para impedir que rompa la tabla

            $dataJson .=']}';

            echo $dataJson;

        }

    }


}

/*=============================================
Activar función DataTable
=============================================*/ 

$data  = new DataTableController();
$data -> dataOrders();


<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";


class DataTableController{

    /*=============================================
    Función DataTable
    =============================================*/ 

    public function dataMessages(){

        if (!empty($_POST)){

            $draw = $_POST["draw"];
            
            $orderByColumnIndex = $_POST['order'][0]['column']; 
            
            $orderBy = $_POST['columns'][$orderByColumnIndex]['data'];
            
            $orderType = $_POST['order'][0]['dir']; 

            $start  = $_POST["start"];

            $length = $_POST['length'];

            /*=============================================
            Traer el total de la data de disputas
            =============================================*/

            $select = "id_message";

            $url = CurlController::api()."messages?linkTo=id_store_message&equalTo=".$_GET["idStore"]."&select=".$select."&token=".$_GET["token"];
            
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
            Traer la data de mensajes de acuerdo a la paginación o al orden o a la búsqueda
            =============================================*/

            $select = "id_message,content_message,answer_message,date_answer_message,date_created_message,name_product,url_product,displayname_user,email_user";

            /*=============================================
            Cuando se usa el buscador de DataTable
            =============================================*/

            if(!empty($_POST['search']['value'])){

                $linkTo = ["name_product","displayname_user","email_user","date_answer_message","date_created_message"];

                $search = str_replace(" ", "_", $_POST['search']['value']);

                foreach ($linkTo as $key => $value) {
                   
                   $url = CurlController::api()."relations?rel=messages,products,users&type=message,product,user&linkTo=".$value."&search=".$search.",".$_GET["idStore"]."&orderBy=".$orderBy."&orderMode=".$orderType."&startAt=".$start."&endAt=".$length."&select=".$select."&token=".$_GET["token"];
                  
                   $searchMessages = CurlController::request($url, $method, $fields, $header)->results; 

                    if($searchMessages == "Not Found"){

                      $dataMessages = array();

                    }else{

                        $dataMessages = $searchMessages; 
                        $recordsFiltered = count($dataMessages); 

                        break; 

                    }
                }

            }else{

                $url = CurlController::api()."relations?rel=messages,products,users&type=message,product,user&linkTo=id_store_message&equalTo=".$_GET["idStore"]."&orderBy=".$orderBy."&orderMode=".$orderType."&startAt=".$start."&endAt=".$length."&select=".$select."&token=".$_GET["token"];

                $dataMessages = CurlController::request($url, $method, $fields, $header)->results;

                $recordsFiltered = $totalData;   
                
            }

            /*=============================================
            Verificamos que la tabla no venga vacía
            =============================================*/

            if(count($dataMessages) == 0){

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
            Recorremos la data de órdenes
            =============================================*/

            foreach ($dataMessages as $key => $value) {

                /*=============================================
                Nombre del producto
                =============================================*/

                $name_product = $value->name_product;

                /*=============================================
                Cliente del mensaje
                =============================================*/

                $client_user = $value->displayname_user;

                /*=============================================
                EMail del Cliente que crea el mensaje
                =============================================*/

                $email_user = $value->email_user;

                /*=============================================
                Contenido del mensaje
                =============================================*/

                $content_message = $value->content_message;

                /*=============================================
                Respuesta del mensaje
                =============================================*/

                if($value->answer_message == null){

                    $answer_message = "<button class='btn btn-sm btn-secondary answerMessage' idMessage='".$value->id_message."' clientMessage='".$client_user."' emailMessage='".$email_user."' urlProduct='".$value->url_product."'>Answer Message</button>";

                }else{

                    $answer_message = $value->answer_message;
                }

                

                /*=============================================
                Fecha de respuesta del mensaje
                =============================================*/

                $date_answer_message = $value->date_answer_message;
                
               /*=============================================
                Fecha de creación del mensaje
                =============================================*/

                $date_created_message = $value->date_created_message;

                /*=============================================
                Creamos los campos a mostrar
                =============================================*/

                $dataJson.='{ 

                    "id_message":"'.($start+$key+1).'",
                    "name_product":"'.$name_product.'",
                    "displayname_user":"'.$client_user.'",
                    "email_user":"'.$email_user.'",
                    "content_message":"'.$content_message.'",
                    "answer_message":"'.$answer_message.'",
                    "date_answer_message":"'.$date_answer_message.'",            
                    "date_created_message":"'.$date_created_message.'"             
                },';

            }

            $dataJson = substr($dataJson, 0, -1);  

            $dataJson .=']}';

            echo $dataJson;

        }
    }

}

/*=============================================
Activar función DataTable
=============================================*/ 

$data  = new DataTableController();
$data -> dataMessages();
<?php

require_once "../controllers/curl.controller.php";
require_once "../controllers/template.controller.php";


class DataTableController{

    /*=============================================
    Función DataTable
    =============================================*/ 

    public function dataDisputes(){

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

            $select = "id_dispute";

            $url = CurlController::api()."disputes?linkTo=id_store_dispute&equalTo=".$_GET["idStore"]."&select=".$select."&token=".$_GET["token"];
            
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
            Traer la data de disputas de acuerdo a la paginación o al orden o a la búsqueda
            =============================================*/

            $select = "id_dispute,id_order_dispute,content_dispute,answer_dispute,date_answer_dispute,date_created_dispute,displayname_user,email_user";

            /*=============================================
            Cuando se usa el buscador de DataTable
            =============================================*/

            if(!empty($_POST['search']['value'])){

                $linkTo = ["displayname_user","email_user","date_answer_dispute","date_created_dispute"];

                $search = str_replace(" ", "_", $_POST['search']['value']);

                foreach ($linkTo as $key => $value) {
                   
                   $url = CurlController::api()."relations?rel=disputes,users&type=dispute,user&linkTo=".$value."&search=".$search.",".$_GET["idStore"]."&orderBy=".$orderBy."&orderMode=".$orderType."&startAt=".$start."&endAt=".$length."&select=".$select."&token=".$_GET["token"];
                  
                   $searchDisputes = CurlController::request($url, $method, $fields, $header)->results; 

                    if($searchDisputes == "Not Found"){

                      $dataDisputes = array();

                    }else{

                        $dataDisputes = $searchDisputes; 
                        $recordsFiltered = count($dataDisputes); 

                        break; 

                    }
                }

            }else{

                $url = CurlController::api()."relations?rel=disputes,users&type=dispute,user&linkTo=id_store_dispute&equalTo=".$_GET["idStore"]."&orderBy=".$orderBy."&orderMode=".$orderType."&startAt=".$start."&endAt=".$length."&select=".$select."&token=".$_GET["token"];

                $dataDisputes = CurlController::request($url, $method, $fields, $header)->results;

                $recordsFiltered = $totalData;   
                
            }

            /*=============================================
            Verificamos que la tabla no venga vacía
            =============================================*/

            if(count($dataDisputes) == 0){

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

            foreach ($dataDisputes as $key => $value) {

                /*=============================================
                Id de la orden
                =============================================*/

                $id_order = $value->id_order_dispute;

                 /*=============================================
                Cliente de la disputa
                =============================================*/

                $client_user = $value->displayname_user;

                /*=============================================
                EMail del Cliente que abre la disputa
                =============================================*/

                $email_user = $value->email_user;

                /*=============================================
                Contenido de la disputa
                =============================================*/

                $content_dispute = $value->content_dispute;

                /*=============================================
                Respuesta de la disputa
                =============================================*/

                if($value->answer_dispute == null){

                    $answer_dispute = "<button class='btn btn-sm btn-secondary answerDispute' idDispute='".$value->id_dispute."' clientDispute='".$client_user."' emailDispute='".$email_user."'>Answer Dispute</button>";

                }else{

                    $answer_dispute = $value->answer_dispute;
                }

                

                /*=============================================
                Fecha de respuesta de la disputa
                =============================================*/

                $date_answer_dispute = $value->date_answer_dispute;
                
               /*=============================================
                Fecha de creación de la disputa
                =============================================*/

                $date_created_dispute = $value->date_created_dispute;

                /*=============================================
                Creamos los campos a mostrar
                =============================================*/

                $dataJson.='{ 

                    "id_dispute":"'.($start+$key+1).'",
                    "id_order_dispute":"'.$id_order.'",
                    "displayname_user":"'.$client_user.'",
                    "email_user":"'.$email_user.'",
                    "content_dispute":"'.$content_dispute.'",
                    "answer_dispute":"'.$answer_dispute.'",
                    "date_answer_dispute":"'.$date_answer_dispute.'",            
                    "date_created_dispute":"'.$date_created_dispute.'"             
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
$data -> dataDisputes();
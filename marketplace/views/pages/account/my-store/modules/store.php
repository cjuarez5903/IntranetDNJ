<?php 

$select = "id_store,name_store,url_store,logo_store,cover_store,about_store,abstract_store,email_store,country_store,city_store,address_store,phone_store,socialnetwork_store,reviews_product";
$url = CurlController::api()."relations?rel=products,stores&type=product,store&linkTo=id_user_store&equalTo=".$_SESSION["user"]->id_user."&select=".$select;
$method = "GET";
$fields = array();
$header = array();

$store = CurlController::request($url, $method, $fields, $header)->results;

$reviews = 0;
$totalReviews = 0;

?>

<div class="ps-section__left">

    <div class="ps-block--vendor">

        <div class="ps-block__thumbnail">

          <img src="img/stores/<?= $store[0]->url_store ?>/<?= $store[0]->logo_store ?>">

        </div>

        <div class="ps-block__container">

            <div class="ps-block__header">

                <h4><?= $store[0]->name_store ?></h4>

                <div class="br-wrapper br-theme-fontawesome-stars">

                    <?php 

                    foreach ($store as $item){

                        if($item->reviews_product != null){

                            foreach (json_decode($item->reviews_product, true) as $key => $value){
                                
                                $reviews += $value["review"];
                                $totalReviews++;
                               
                              
                            }



                        }

                    }

                    if($reviews > 0 && $totalReviews > 0){

                        $reviews = round($reviews/$totalReviews);
                    }

                    ?>

                    <select class="ps-rating" data-read-only="true" style="display: none;">

                        <?php 

                            if($reviews > 0){

                                for($i = 0; $i < 5; $i++){

                                    if($reviews < ($i+1)){

                                        echo '<option value="1">'.($i+1).'</option>';

                                    }else{

                                         echo '<option value="2">'.($i+1).'</option>';

                                    }

                                }

                            }else{

                                echo '<option value="0">0</option>';

                                for($i = 0; $i < 5; $i++){

                                    echo '<option value="1">'.($i+1).'</option>';

                                }

                            }

                        ?>

                    </select>

               </div>

                <p><strong><?php echo ($reviews*100)/5 ?>% Positive</strong> (<?php echo $totalReviews ?> rating)</p>

            </div><span class="ps-block__divider"></span>

            <div class="ps-block__content">

                <p><?= $store[0]->abstract_store ?></p>

                <span class="ps-block__divider"></span>

                <p><strong>Address</strong> <?= $store[0]->address_store ?></p>
                
                <?php if ($store[0]->socialnetwork_store != null): ?>   

                    <figure>

                        <figcaption>Follow us on social</figcaption>

                        <ul class="ps-list--social-color">

                            <?php foreach (json_decode($store[0]->socialnetwork_store, true) as $key => $value):  ?>

                                <li>
                                    <a class="<?php echo array_keys($value)[0] ?>" href="<?php echo $value[array_keys($value)[0]] ?>">
                                        <i class="fab fa-<?php echo array_keys($value)[0] ?>"></i></a>
                                </li>
                           

                            <?php endforeach ?>


                        </ul>

                    </figure>

                <?php endif ?>

            </div>

            <div class="ps-block__footer">

                <p>Call us directly<strong><small><?= $store[0]->phone_store ?></small></strong></p>

                <p>or Or if you have any question <strong><small><?= $store[0]->email_store ?></small></strong></p>

                <a class="ps-btn ps-btn--fullwidth" data-toggle='modal' data-backdrop='static' data-keyboard='false' data-target='#editStore'>Edit</a>

            </div>

        </div>

    </div>

</div><!-- End Vendor Profile -->

<!--=====================================
Modal para editar la tienda
======================================-->

<div class="modal" id="editStore">
    
    <div class="modal-dialog modal-lg">
         
        <div class="modal-content">
            
            <form class="needs-validation" novalidate method="post" enctype="multipart/form-data">

                <input type="hidden" value="<?= $store[0]->id_store ?>" name="idStore">
   
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title text-center">Edit Store</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                 <!-- Modal body -->
                <div class="modal-body p-5 text-left">
                    
                    <!--=====================================
                    Nombre de la tienda
                    ======================================-->

                    <div class="form-group">
                        
                        <label>Store Name<sup class="text-danger">*</sup></label>

                        <div class="form-group__content">
                            
                            <input 
                            type="text"
                            class="form-control"
                            name="nameStore"
                            value="<?= $store[0]->name_store ?>"
                            readonly
                            required>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>

                        </div>

                    </div>

                    <!--=====================================
                    Url de la tienda
                    ======================================-->

                    <div class="form-group">
                    
                        <label>Store Url<sup class="text-danger">*</sup></label>

                        <div class="form-group__content">
                            
                            <input 
                            type="text"
                            class="form-control"
                            name="urlStore"
                            value="<?= $store[0]->url_store ?>"
                            readonly
                            required>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>

                        </div>

                    </div>

                    <!--=====================================
                    Información de la tienda
                    ======================================-->

                    <div class="form-group">
                    
                        <label>Store About<sup class="text-danger">*</sup></label>

                        <div class="form-group__content">
                            
                            <textarea
                            class="form-control"
                            rows="7"
                            placeholder="Notes about your store in maximum 1000 characters, ex: We are a store specialized in technology..."
                            name="aboutStore"
                            pattern="[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\'\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,1000}"
                            onchange="validateJS(event,'paragraphs')"
                            required><?= $store[0]->about_store ?></textarea>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>

                        </div>

                    </div>

                    <!--=====================================
                    Email de la tienda
                    ======================================-->

                    <div class="form-group">
                    
                        <label>Store Email<sup class="text-danger">*</sup></label>

                        <div class="form-group__content">
                            
                            <input type="email"
                            class="form-control"
                            name="emailStore"
                            class="form-control" 
                            value="<?php echo $store[0]->email_store ?>" 
                            required>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>

                        </div>

                    </div>

                     <!--=====================================
                    Pais de la tienda
                    ======================================--> 

                    <div class="form-group">

                        <label>Store Country<sup class="text-danger">*</sup></label>

                        <?php

                            $data = file_get_contents("views/json/countries.json");
                            $countries = json_decode($data, true);

                        ?>

                        <div class="form-group__content">

                            <select
                            name="countryStore" 
                            class="form-control select2"
                            style="width:100%" 
                            onchange="changeCountry(event)"
                            required>

                            <?php if ($store[0]->country_store != null): ?>

                                <option value="<?php echo $store[0]->country_store ?>_<?php echo explode("_", $store[0]->phone_store)[0]  ?>"><?php echo $store[0]->country_store   ?></option>

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

                        <label>Store City<sup>*</sup></label>

                        <div class="form-group__content">

                            <input 
                            name="cityStore" 
                            class="form-control" 
                            type="text" 
                            pattern="[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}"
                            onchange="validateJS(event, 'text')" 
                            value="<?php echo $store[0]->city_store ?>" 
                            required>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill in this field correctly.</div>

                        </div>

                    </div>

                    <!--=====================================
                    Teléfono del usuario
                    ======================================--> 

                    <div class="form-group">

                        <label>Store Phone<sup>*</sup></label>

                        <div class="form-group__content input-group">

                            <?php if ($store[0]->phone_store != null): ?>

                                <div class="input-group-append">
                                    <span class="input-group-text dialCode"><?php echo explode("_", $store[0]->phone_store)[0] ?></span>
                                </div>

                                <?php

                                $phone = explode("_", $store[0]->phone_store)[1];

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
                            name="phoneStore" 
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
                    Dirección de la tienda
                    ======================================--> 

                    <div class="form-group">

                        <label>Store Address<sup>*</sup></label>

                        <div class="form-group__content">

                            <input
                            name="addressStore" 
                            class="form-control" 
                            type="text"
                            pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
                            onchange="validateJS(event, 'paragraphs')" 
                            value="<?php echo $store[0]->address_store ?>" 
                            required>

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill in this field correctly.</div>

                        </div>

                    </div>

                    <!--=====================================
                    Logo de la tienda
                    ======================================-->

                    <div class="form-group">

                        <input type="hidden" value="<?= $store[0]->logo_store ?>" name="logoStoreOld">
                        
                        <label>Store Logo<sup class="text-danger">*</sup></label> 

                        <div class="form-group__content">
                            
                            <label class="pb-5" for="logoStore">
                               <img src="img/stores/<?= $store[0]->url_store ?>/<?= $store[0]->logo_store ?>" class="img-fluid changeLogo" style="width:150px">
                            </label> 

                            <div class="custom-file">       

                                <input 
                                type="file"
                                id="logoStore"
                                class="custom-file-input"
                                name="logoStore"
                                accept="image/*"
                                maxSize="2000000"
                                onchange="validateImageJS(event, 'changeLogo')"
                                >

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>

                                <label class="custom-file-label" for="logoStore">Choose file</label>

                            </div>


                        </div>

                    </div>

                     <!--=====================================
                    Portada de la tienda
                    ======================================-->

                    <div class="form-group">

                         <input type="hidden" value="<?= $store[0]->cover_store ?>" name="coverStoreOld">
                        
                        <label>Store Cover<sup class="text-danger">*</sup></label> 

                        <div class="form-group__content">
                            
                            <label class="pb-5" for="coverStore">
                               <img src="img/stores/<?= $store[0]->url_store ?>/<?= $store[0]->cover_store ?>" class="img-fluid changeCover">
                            </label> 

                            <div class="custom-file">       

                                <input 
                                type="file"
                                id="coverStore"
                                class="custom-file-input"
                                name="coverStore"
                                accept="image/*"
                                maxSize="2000000"
                                onchange="validateImageJS(event, 'changeCover')"
                                >

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>

                                <label class="custom-file-label" for="coverStore">Choose file</label>

                            </div>


                        </div>

                    </div>

                    <!--=====================================
                    Redes sociales de la tienda
                    ======================================-->

                    <div class="form-group">
                        
                        <label>Social Networks</label>

                        <?php 

                        $facebook = "";
                        $linkedin = "";
                        $twitter = "";
                        $youtube = "";
                        $instagram = "";

                        if($store[0]->socialnetwork_store != null){

                            foreach (json_decode($store[0]->socialnetwork_store, true) as $key => $value){

                                if (array_keys($value)[0] == "facebook"){

                                    $facebook = explode("/",$value[array_keys($value)[0]])[3];
                                  
                                }

                                if (array_keys($value)[0] == "linkedin"){

                                    $linkedin = explode("/",$value[array_keys($value)[0]])[3];
                                  
                                }

                                if (array_keys($value)[0] == "twitter"){

                                    $twitter = explode("/",$value[array_keys($value)[0]])[3];
                                  
                                }

                                if (array_keys($value)[0] == "youtube"){

                                    $youtube = explode("/",$value[array_keys($value)[0]])[3];
                                  
                                }

                                if (array_keys($value)[0] == "instagram"){

                                    $instagram = explode("/",$value[array_keys($value)[0]])[3];
                                  
                                }

                            }

                        }


                         ?>

                        <!--=====================================
                        Facebook
                        ======================================-->

                        <div class="form-group__content input-group mb-5">
                            
                            <div class="input-group-append">
                                <span class="input-group-text">https://facebook.com/</span>
                            </div>

                            <input type="text"
                            class="form-control"
                            name="facebookStore"
                            pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
                            onchange="validateJS(event, 'paragraphs')" 
                            value="<?php echo $facebook ?>"
                            >

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>

                        </div>

                        <!--=====================================
                        Instagram
                        ======================================-->

                        <div class="form-group__content input-group mb-5">
                            
                            <div class="input-group-append">
                                <span class="input-group-text">https://instagram.com/</span>
                            </div>

                            <input type="text"
                            class="form-control"
                            name="instagramStore"
                            pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
                            onchange="validateJS(event, 'paragraphs')" 
                             value="<?php echo $instagram?>"
                            >

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>

                        </div>

                        <!--=====================================
                        Twitter
                        ======================================-->

                        <div class="form-group__content input-group mb-5">
                            
                            <div class="input-group-append">
                                <span class="input-group-text">https://twitter.com/</span>
                            </div>

                            <input type="text"
                            class="form-control"
                            name="twitterStore"
                            pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
                            onchange="validateJS(event, 'paragraphs')" 
                             value="<?php echo $twitter ?>"
                            >

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>

                        </div>

                        <!--=====================================
                        Linkedin
                        ======================================-->

                        <div class="form-group__content input-group mb-5">
                            
                            <div class="input-group-append">
                                <span class="input-group-text">https://linkedin.com/</span>
                            </div>

                            <input type="text"
                            class="form-control"
                            name="linkedinStore"
                            pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
                            onchange="validateJS(event, 'paragraphs')" 
                             value="<?php echo $linkedin ?>"
                            >

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>

                        </div>

                         <!--=====================================
                        Youtube
                        ======================================-->

                        <div class="form-group__content input-group mb-5">
                            
                            <div class="input-group-append">
                                <span class="input-group-text">https://youtube.com/</span>
                            </div>

                            <input type="text"
                            class="form-control"
                            name="youtubeStore"
                            pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
                            onchange="validateJS(event, 'paragraphs')"
                             value="<?php echo $youtube ?>" 
                            >

                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>

                        </div>


                    </div>

                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    
                    <div class="form-group submtit">
                       
                        <?php 

                        $editStore = new VendorsController();
                        $editStore -> editStore();

                        ?>   

                        <button 
                        type="submit"
                        class="ps-btn ps-btn--fullwidth">Save</button>

                    </div>

                </div>

            </form>

        </div>

    </div>


</div>

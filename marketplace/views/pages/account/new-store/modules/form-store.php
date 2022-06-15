<!--=====================================
Crear tienda
======================================-->

<div class="tab-pane container fade" id="createStore">

 	<!-- Modal Header -->
    <div class="modal-header">
        <h4 class="modal-title text-center">2. Create Store</h4>
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
        		class="form-control formStore"
        		name="nameStore"
        		pattern="[0-9A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}"
        		onchange="validateDataRepeat(event, 'store')"
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
                class="form-control formStore"
                name="urlStore"
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
                class="form-control formStore"
                rows="7"
                placeholder="Notes about your store in maximum 1000 characters, ex: We are a store specialized in technology..."
                name="aboutStore"
                pattern="[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\'\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,1000}"
                onchange="validateJS(event,'paragraphs')"
                required></textarea>

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
                class="form-control formStore"
                name="emailStore"
                class="form-control" 
                value="<?php echo $_SESSION["user"]->email_user ?>" 
                readonly
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
                class="form-control select2 formStore"
                style="width:100%" 
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

            <label>Store City<sup>*</sup></label>

            <div class="form-group__content">

                <input 
                name="cityStore" 
                class="form-control formStore" 
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

            <label>Store Phone<sup>*</sup></label>

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
                name="phoneStore" 
                class="form-control formStore" 
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

            <label>Store Address<sup>*</sup></label>

            <div class="form-group__content">

                <input
                name="addressStore" 
                class="form-control formStore" 
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
        Logo de la tienda
        ======================================-->

        <div class="form-group">
        	
        	<label>Store Logo<sup class="text-danger">*</sup></label> 

        	<div class="form-group__content">
        		
        		<label class="pb-5" for="logoStore">
        		  	
        		  	<img src="img/stores/default/default-logo.jpg" class="img-fluid changeLogo" style="width:150px">

        		</label>

        		<div class="custom-file">		

        			<input 
        			type="file"
        			id="logoStore"
        			class="custom-file-input formStore"
        			name="logoStore"
        			accept="image/*"
        			maxSize="2000000"
        			onchange="validateImageJS(event, 'changeLogo')"
        			required>

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
        	
        	<label>Store Cover<sup class="text-danger">*</sup></label> 

        	<div class="form-group__content">
        		
        		<label class="pb-5" for="coverStore">
        		  	
        		  	<img src="img/stores/default/default-cover.jpg" class="img-fluid changeCover">

        		</label>

        		<div class="custom-file">		

        			<input 
        			type="file"
        			id="coverStore"
        			class="custom-file-input formStore"
        			name="coverStore"
        			accept="image/*"
        			maxSize="2000000"
        			onchange="validateImageJS(event, 'changeCover')"
        			required>

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
                >

                <div class="valid-feedback">Valid.</div>
                <div class="invalid-feedback">Please fill out this field.</div>

            </div>


        </div>

    </div>

    <!-- Modal footer -->
    <div class="modal-footer">
    	
    	<button type="button" class="btn btn-warning btn-lg" onclick="validateFormStore()">Next</button>

    </div>

</div>
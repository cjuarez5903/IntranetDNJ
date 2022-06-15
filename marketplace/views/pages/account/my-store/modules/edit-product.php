<?php 

if(isset($_GET["product"])){

	$select = "id_product,approval_product,state_product,url_product,feedback_product,image_product,name_product,id_category,url_category,name_category,id_subcategory,title_list_subcategory,name_subcategory,price_product,shipping_product,stock_product,delivery_time_product,offer_product,summary_product,specifications_product,details_product,description_product,tags_product,gallery_product,top_banner_product,default_banner_product,horizontal_slider_product,vertical_slider_product,video_product,views_product,sales_product,reviews_product,date_created_product";

	$url = CurlController::api()."relations?rel=products,categories,subcategories&type=product,category,subcategory&linkTo=id_product&equalTo=".$_GET["product"]."&select=".$select;
	$method = "GET";
    $fields = array();
    $header = array();

    $product = CurlController::request($url, $method, $fields, $header)->results[0];

}

?>

<!--=====================================
Editar Producto
======================================-->

<form class="needs-validation" novalidate method="post" enctype="multipart/form-data">   

    <input type="hidden" value="<?php echo CurlController::api() ?>" id="urlApi">
    <input type="hidden" value="<?= $product->id_product ?>" name="id_product">

    <div>
    	
    	<!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title text-center">Edit Product</h4>
            <a href="<?= TemplateController::path() ?>account&my-store#vendor-store" class="btn btn-dark">Cancel</a>
        </div>

        <!-- Modal body -->
        <div class="modal-body p-5 text-left">

        	<!--=====================================
            Nombre del producto
            ======================================-->

            <div class="form-group">
            
                <label>Product Name<sup class="text-danger">*</sup></label>

                <div class="form-group__content">
                    
                    <input type="text"
                    class="form-control"
                    name="nameProduct"
                   	value="<?= $product->name_product ?>"
                    readonly
                    required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>

                </div>

            </div>

             <!--=====================================
            Url del producto
            ======================================-->

            <div class="form-group">
            
                <label>Product Url<sup class="text-danger">*</sup></label>

                <div class="form-group__content">
                    
                    <input type="text"
                    class="form-control"
                    name="urlProduct"
                     value="<?= $product->url_product ?>"
                    readonly
                    required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>

                </div>

            </div>

              <!--=====================================
            Categoría del producto
            ======================================-->

            <div class="form-group">
                
                <label>Product Category<sup class="text-danger">*</sup></label>

                <div class="form-group__content">
                    
                    <select
                    class="form-control"
                    name="categoryProduct" 
                    readonly
                    required>        

                        <option value="<?php echo $product->id_category."_".$product->url_category ?>"><?php echo $product->name_category ?></option>                    

                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>

                </div>
            </div>

            <!--=====================================
            SubCategoría del producto
            ======================================-->

            <div class="form-group">
            
                <label>Product SubCategory<sup class="text-danger">*</sup></label>

                <div class="form-group__content">
                    
                    <select
                    class="form-control"
                    name="subCategoryProduct" 
                    readonly
                    required>        

                        <option value="<?php echo $product->id_subcategory."_".$product->title_list_subcategory ?>"><?php echo $product->name_subcategory ?></option>                    

                    </select>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>

                </div>

            </div>

            <!--=====================================
            Descripción del producto
            ======================================-->

            <div class="form-group">
                
                <label>Product Description<sup class="text-danger">*</sup></label>

                <textarea
                class="summernote editSummernote"
                name="descriptionProduct"
                iDProduct="<?php echo $product->id_product ?>"
                required
                >
    
                </textarea>

                <div class="valid-feedback">Valid.</div>
                <div class="invalid-feedback">Please fill out this field.</div>

            </div>

             <!--=====================================
            Resumen del producto
            ======================================-->

            <div class="form-group">
                
                <label>Product Summary<sup class="text-danger">*</sup> Ex: 20 hours of portable capabilities</label>

                <?php foreach (json_decode($product->summary_product, true) as $key => $value): ?>

	                <input type="hidden" name="inputSummary" value="<?php echo $key+1 ?>">

	                <div class="form-group__content input-group mb-3 inputSummary">
	                     
	                    <div class="input-group-append">
	                        <span class="input-group-text">
	                             <button type="button" class="btn btn-danger" onclick="removeInput(<?php echo $key ?>,'inputSummary')">&times;</button>
	                        </span>
	                    </div>

	                    <input
	                    class="form-control" 
	                    type="text"
	                    name="summaryProduct_<?php echo $key ?>"
	                    pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
	                    onchange="validateJS(event,'paragraphs')"
	                    value="<?= $value ?>"
	                    required>

	                    <div class="valid-feedback">Valid.</div>
	                    <div class="invalid-feedback">Please fill out this field.</div>

                	</div>

                 <?php endforeach ?>

                <button type="button" class="btn btn-primary mb-2" onclick="addInput(this, 'inputSummary')">Add Summary</button>

            </div>

             <!--=====================================
            Detalles del producto
            ======================================-->

            <div class="form-group">
                
                <label>Product Details<sup class="text-danger">*</sup> Ex: <strong>Title:</strong> Bluetooth, <strong>Value:</strong> Yes</label>

                <?php foreach (json_decode($product->details_product, true) as $key => $value): ?>

	                <input type="hidden" name="inputDetails" value="<?php echo $key+1 ?>">

	                <div class="row mb-3 inputDetails">

	                    <!--=====================================
	                    Entrada para el título del detalle
	                    ======================================--> 

	                    <div class="col-12 col-lg-6 form-group__content input-group">
	                         
	                        <div class="input-group-append">
	                            <span class="input-group-text">
	                                 <button type="button" class="btn btn-danger" onclick="removeInput(<?php echo $key ?>,'inputDetails')">&times;</button>
	                            </span>
	                        </div>

	                        <div class="input-group-append">
	                            <span class="input-group-text">
	                                Title:
	                            </span>
	                        </div>

	                        <input
	                        class="form-control" 
	                        type="text"
	                        name="detailsTitleProduct_<?php echo $key ?>"
	                        pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
	                        onchange="validateJS(event,'paragraphs')"
	                        value="<?= $value["title"] ?>"
	                        required>

	                        <div class="valid-feedback">Valid.</div>
	                        <div class="invalid-feedback">Please fill out this field.</div>

	                    </div>

	                    <!--=====================================
	                    Entrada para el valor del detalle
	                    ======================================--> 

	                    <div class="col-12 col-lg-6 form-group__content input-group">

	                        <div class="input-group-append">
	                            <span class="input-group-text">
	                                 Value:
	                            </span>
	                        </div>

	                        <input
	                        class="form-control" 
	                        type="text"
	                        name="detailsValueProduct_<?php echo $key ?>"
	                        pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
	                        onchange="validateJS(event,'paragraphs')"
	                        value="<?= $value["value"] ?>"
	                        required>

	                        <div class="valid-feedback">Valid.</div>
	                        <div class="invalid-feedback">Please fill out this field.</div>

	                    </div>

	                </div>

	            <?php endforeach ?> 

                <button type="button" class="btn btn-primary mb-2" onclick="addInput(this, 'inputDetails')">Add Details</button>

            </div>


            <!--=====================================
            Especificaciones técnicas del producto
            ======================================-->

            <div class="form-group">
                
                <label>Product Specifications Ex: <strong>Type:</strong> Color, <strong>Values:</strong> Black, Red, White</label>

                <?php if ($product->specifications_product != null): ?>

                	<?php foreach (json_decode($product->specifications_product, true) as $key => $value): ?>

		                <input type="hidden" name="inputSpecifications" value="<?php echo $key+1 ?>">

		                <div class="row mb-3 inputSpecifications">

		                    <!--=====================================
		                    Entrada para el tipo de especificación técnica
		                    ======================================--> 

		                    <div class="col-12 col-lg-6 form-group__content input-group">
		                         
		                        <div class="input-group-append">
		                            <span class="input-group-text">
		                                 <button type="button" class="btn btn-danger" onclick="removeInput(<?php echo $key ?>,'inputSpecifications')">&times;</button>
		                            </span>
		                        </div>

		                        <div class="input-group-append">
		                            <span class="input-group-text">
		                                Type:
		                            </span>
		                        </div>

		                        <input
		                        class="form-control" 
		                        type="text"
		                        name="specificationsTypeProduct_<?php echo $key ?>"
		                        pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
		                        onchange="validateJS(event,'paragraphs')"
		                        value="<?php echo array_keys($value)[0] ?>" >

		                        <div class="valid-feedback">Valid.</div>
		                        <div class="invalid-feedback">Please fill out this field.</div>

		                    </div>

		                    <!--=====================================
		                    Entrada para el valor de la especificación técnica
		                    ======================================--> 

		                    <div class="col-12 col-lg-6 form-group__content input-group">

		                        <input
		                        class="form-control tags-input" 
		                        data-role="tagsinput"
		                        type="text"
		                        name="specificationsValuesProduct_<?php echo $key ?>"
		                        placeholder="Type And Press Enter"
		                        pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
		                        onchange="validateJS(event,'paragraphs')"
		                        value="<?php echo implode(",", array_values($value)[0]); ?>">
		                        

		                        <div class="valid-feedback">Valid.</div>
		                        <div class="invalid-feedback">Please fill out this field.</div>

		                    </div>

		                </div>

		             <?php endforeach ?>   

	            <?php else: ?>

	            	<input type="hidden" name="inputSpecifications" value="1">

	                <div class="row mb-3 inputSpecifications">

	                    <!--=====================================
	                    Entrada para el tipo de especificación técnica
	                    ======================================--> 

	                    <div class="col-12 col-lg-6 form-group__content input-group">
	                         
	                        <div class="input-group-append">
	                            <span class="input-group-text">
	                                 <button type="button" class="btn btn-danger" onclick="removeInput(0,'inputSpecifications')">&times;</button>
	                            </span>
	                        </div>

	                        <div class="input-group-append">
	                            <span class="input-group-text">
	                                Type:
	                            </span>
	                        </div>

	                        <input
	                        class="form-control" 
	                        type="text"
	                        name="specificationsTypeProduct_0"
	                        pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
	                        onchange="validateJS(event,'paragraphs')">

	                        <div class="valid-feedback">Valid.</div>
	                        <div class="invalid-feedback">Please fill out this field.</div>

	                    </div>

	                    <!--=====================================
	                    Entrada para el valor de la especificación técnica
	                    ======================================--> 

	                    <div class="col-12 col-lg-6 form-group__content input-group">

	                        <input
	                        class="form-control tags-input" 
	                        data-role="tagsinput"
	                        type="text"
	                        name="specificationsValuesProduct_0"
	                        placeholder="Type And Press Enter"
	                        pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
	                        onchange="validateJS(event,'paragraphs')">

	                        <div class="valid-feedback">Valid.</div>
	                        <div class="invalid-feedback">Please fill out this field.</div>

	                    </div>

	                </div>


	            <?php endif ?>

                <button type="button" class="btn btn-primary mb-2" onclick="addInput(this, 'inputSpecifications')">Add Specifications</button>

            </div>

            <!--=====================================
            Palabras claves del producto
            ======================================--> 

            <div class="form-group">
                
                <label>Product Tags<sup class="text-danger">*</sup></label>

                <div class="form-group__content">

                    <input
                    class="form-control tags-input" 
                    data-role="tagsinput"
                    type="text"
                    name="tagsProduct"
                    placeholder="Type And Press Enter"
                    pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
                    onchange="validateJS(event,'paragraphs')"
                    value="<?php echo implode(",", json_decode($product->tags_product,true)); ?>"
                    required
                    >

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>

                </div>

            </div>

            <!--=====================================
            Imagen del producto
            ======================================-->

            <div class="form-group">

            	 <input type="hidden" name="imageProductOld" value="<?php echo $product->image_product ?>">
                
                <label>Product Image<sup class="text-danger">*</sup></label> 

                <div class="form-group__content">
                    
                    <label class="pb-5" for="imageProduct">
                        
                        <img src="img/products/<?= $product->url_category ?>/<?= $product->image_product ?>" class="img-fluid changeImage" style="width:150px">

                    </label>

                    <div class="custom-file">       

                        <input 
                        type="file"
                        id="imageProduct"
                        class="custom-file-input"
                        name="imageProduct"
                        accept="image/*"
                        maxSize="2000000"
                        onchange="validateImageJS(event, 'changeImage')"
                        >

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                        <label class="custom-file-label" for="imageProduct">Choose file</label>

                    </div>


                </div>

            </div>

            <!--=====================================
            Galería del Producto
            ======================================-->    

            <label>Product Gallery: <sup class="text-danger">*</sup></label> 

            <div class="dropzone mb-3">

            	<?php foreach (json_decode($product->gallery_product,true) as $value): ?>

            		<div class="dz-preview dz-file-preview"> 

            			<div class="dz-image">
            			 	
            			 	<img src="img/products/<?= $product->url_category ?>/gallery/<?= $value ?>">

            			</div>

            			<a class="dz-remove" data-dz-remove remove="<?=$value?>" onclick="removeGallery(this)">Remove file</a>

            		</div>   
            		
            	<?php endforeach ?>
                
                <div class="dz-message">
                    
                     Drop your images here, size max 500px * 500px

                </div>

            </div>

            <input type="hidden" name="galleryProductOld" value='<?=$product->gallery_product ?>'>

            <input type="hidden" name="galleryProduct">

			<input type="hidden" name="deleteGalleryProduct">

            <!--=====================================
            Banner Top del producto
            ======================================--> 

            <div class="form-group">
                
                <label>Product Top Banner<sup class="text-danger">*</sup>, Ex:</label>

                <figure class="pb-5">
                    
                    <img src="img/products/default/example-top-banner.png" class="img-fluid">

                </figure>

                <div class="row mb-5">
                    
                     <!--=====================================
                    H3 Tag
                    ======================================-->

                    <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">
                         
                        <div class="input-group-append">
                            <span class="input-group-text">
                                H3 Tag:
                            </span>
                        </div>

                        <input 
                        type="text"
                        class="form-control"
                        placeholder="Ex: 20%"
                        name="topBannerH3Tag"
                        pattern="[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\'\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}"
                        maxlength="50"
                        onchange="validateJS(event,'paragraphs')" 
                        value="<?php echo json_decode($product->top_banner_product, true)["H3 tag"] ?>"
                        required
                        >

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                     </div>

                    <!--=====================================
                    P1 Tag
                    ======================================-->

                    <div class="col-12 col-lg-6 form-group__content input-group mx-0 mb-3">

                        <div class="input-group-append">
                            <span class="input-group-text">
                                P1 Tag:
                            </span>
                        </div>

                        <input type="text"
                        class="form-control"
                        placeholder="Ex: Disccount"
                        name="topBannerP1Tag"
                        pattern="[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\'\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}"
                        maxlength="50"
                        onchange="validateJS(event,'paragraphs')" 
                        value="<?php echo json_decode($product->top_banner_product, true)["P1 tag"] ?>" 
                        required
                        >

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    H4 Tag
                    ======================================-->

                    <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">

                        <div class="input-group-append">
                            <span class="input-group-text">
                                H4 Tag:
                            </span>
                        </div>

                        <input type="text"
                        class="form-control"
                        placeholder="Ex: For Books Of March"
                        name="topBannerH4Tag"
                        pattern="[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\'\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}"
                        maxlength="50"
                        onchange="validateJS(event,'paragraphs')" 
                        value="<?php echo json_decode($product->top_banner_product, true)["H4 tag"] ?>"
                        required
                        >

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                     <!--=====================================
                    P2 Tag
                    ======================================-->

                    <div class="col-12 col-lg-6 form-group__content input-group mx-0 mb-3">

                        <div class="input-group-append">
                            <span class="input-group-text">
                                P2 Tag:
                            </span>
                        </div>

                        <input type="text"
                        class="form-control"
                        placeholder="Ex: Enter Promotion"
                        name="topBannerP2Tag"
                        pattern="[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\'\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}"
                        maxlength="50"
                        onchange="validateJS(event,'paragraphs')" 
                        value="<?php echo json_decode($product->top_banner_product, true)["P2 tag"] ?>"
                        required
                        >

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    Span Tag
                    ======================================-->

                    <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">

                        <div class="input-group-append">
                            <span class="input-group-text">
                                Span Tag:
                            </span>
                        </div>

                        <input 
                        type="text"
                        class="form-control"
                        placeholder="Ex: Sale2019"
                        name="topBannerSpanTag"
                        pattern="[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\'\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}"
                        maxlength="50"
                        onchange="validateJS(event,'paragraphs')"
                        value="<?php echo json_decode($product->top_banner_product, true)["Span tag"] ?>" 
                        required
                        >

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                     <!--=====================================
                    Button Tag
                    ======================================-->

                    <div class="col-12 col-lg-6 form-group__content input-group mx-0 mb-3">

                        <div class="input-group-append">
                            <span class="input-group-text">
                                Button Tag:
                            </span>
                        </div>

                        <input 
                        type="text"
                        class="form-control"
                        placeholder="Ex: Shop now"
                        name="topBannerButtonTag"
                        pattern="[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\'\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}"
                        maxlength="50"
                        onchange="validateJS(event,'paragraphs')" 
                        value="<?php echo json_decode($product->top_banner_product, true)["Button tag"] ?>"
                        required
                        >

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    IMG Tag
                    ======================================-->

                    <div class="col-12">

                    	<input type="hidden" name="topBannerOld" value="<?php echo json_decode($product->top_banner_product, true)["IMG tag"] ?>">

                        <label>IMG Tag:</label>

                        <div class="form-group__content">

                            <label class="pb-5" for="topBanner">
                                <img src="img/products/<?= $product->url_category ?>/top/<?php echo json_decode($product->top_banner_product, true)["IMG tag"] ?>" class="img-fluid changeTopBanner">
                            </label> 

                            <div class="custom-file">

                                <input type="file"
                                class="custom-file-input"
                                id="topBanner"
                                name="topBanner"
                                accept="image/*"
                                maxSize="2000000"
                                onchange="validateImageJS(event, 'changeTopBanner')"
                                >

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>

                                <label class="custom-file-label" for="topBanner">Choose file</label>   

                            </div>       

                        </div>

                    </div>


                </div>

            </div>

            <!--=====================================
            Banner por defecto del producto
            ======================================--> 

            <div class="form-group">

            	<input type="hidden" name="defaultBannerOld" value="<?= $product->default_banner_product ?>">

                <label>Product Default Banner<sup class="text-danger">*</sup></label>

                <div class="form-group__content">

                    <label class="pb-5" for="defaultBanner">
                        <img src="img/products/<?= $product->url_category ?>/default/<?= $product->default_banner_product ?>" class="img-fluid changeDefaultBanner" style="width:500px">
                    </label> 

                    <div class="custom-file">

                        <input type="file"
                        class="custom-file-input"
                        id="defaultBanner"
                        name="defaultBanner"
                        accept="image/*"
                        maxSize="2000000"
                        onchange="validateImageJS(event, 'changeDefaultBanner')"
                        >

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                        <label class="custom-file-label" for="defaultBanner">Choose file</label>   

                    </div>         
                    
                </div>

            </div>

             <!--=====================================
            Slide Horizontal del producto
            ======================================--> 

            <div class="form-group">
                
                <label>Product Horizontal Slider<sup class="text-danger">*</sup>, Ex:</label>

                <figure class="pb-5">
                    
                    <img src="img/products/default/example-horizontal-slider.png" class="img-fluid">

                </figure>

                <div class="row mb-3">
                    
                    <!--=====================================
                    H4 Tag
                    ======================================-->

                    <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">

                        <div class="input-group-append">
                            <span class="input-group-text">
                                H4 Tag:
                            </span>
                        </div>

                        <input type="text"
                        class="form-control"
                        placeholder="Ex: Limit Edition"
                        name="hSliderH4Tag"       
                        pattern="[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\'\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}"
                        maxlength="50"
                        onchange="validateJS(event,'paragraphs')" 
                         value="<?php echo json_decode($product->horizontal_slider_product, true)["H4 tag"] ?>"
                        required
                        >

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    H3-1 Tag
                    ======================================-->

                    <div class="col-12 col-lg-6 form-group__content input-group mx-0 mb-3">

                        <div class="input-group-append">
                            <span class="input-group-text">
                                H3-1 Tag:
                            </span>
                        </div>

                        <input type="text"
                        class="form-control"
                        placeholder="Ex: Happy Summer"
                        name="hSliderH3_1Tag"
                        pattern="[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\'\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}"
                        maxlength="50"
                        onchange="validateJS(event,'paragraphs')" 
                        value="<?php echo json_decode($product->horizontal_slider_product, true)["H3-1 tag"] ?>"
                        required
                        >

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    H3-2 Tag
                    ======================================-->

                    <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">

                        <div class="input-group-append">
                            <span class="input-group-text">
                                H3-2 Tag:
                            </span>
                        </div>

                        <input type="text"
                        class="form-control"
                        placeholder="Ex: Combo Super Cool"
                        name="hSliderH3_2Tag"
                        pattern="[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\'\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}"
                        maxlength="50"
                        onchange="validateJS(event,'paragraphs')" 
                         value="<?php echo json_decode($product->horizontal_slider_product, true)["H3-2 tag"] ?>"
                        required
                        >

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    H3-3 Tag
                    ======================================-->

                    <div class="col-12 col-lg-6 form-group__content input-group mx-0 mb-3">

                        <div class="input-group-append">
                            <span class="input-group-text">
                                H3-3 Tag:
                            </span>
                        </div>

                        <input type="text"
                        class="form-control"
                        placeholder="Ex: Up to"
                        name="hSliderH3_3Tag"
                        pattern="[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\'\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}"
                        maxlength="50"
                        onchange="validateJS(event,'paragraphs')"
                        value="<?php echo json_decode($product->horizontal_slider_product, true)["H3-3 tag"] ?>" 
                        required
                        >

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    H3-4s Tag
                    ======================================-->

                    <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0 mb-3">

                        <div class="input-group-append">
                            <span class="input-group-text">
                                H3-4s Tag:
                            </span>
                        </div>

                        <input type="text"
                        class="form-control"
                        placeholder="Ex: 40%"
                        name="hSliderH3_4sTag"
                        pattern="[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\'\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}"
                        maxlength="50"
                        onchange="validateJS(event,'paragraphs')" 
                         value="<?php echo json_decode($product->horizontal_slider_product, true)["H3-4s tag"] ?>"
                        required
                        >

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>


                    <!--=====================================
                    Button Tag
                    ======================================-->

                    <div class="col-12 col-lg-6 form-group__content input-group mx-0 mb-3">

                        <div class="input-group-append">
                            <span class="input-group-text">
                                Button Tag:
                            </span>
                        </div>

                        <input type="text"
                        class="form-control"
                        placeholder="Ex: Shop now"
                        name="hSliderButtonTag"
                        pattern="[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\'\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,50}"
                        maxlength="50"
                        onchange="validateJS(event,'paragraphs')" 
                        value="<?php echo json_decode($product->horizontal_slider_product, true)["Button tag"] ?>" 
                        required
                        >

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    IMG Tag
                    ======================================-->

                    <div class="col-12">

                    	<input type="hidden" name="hSliderOld" value="<?php echo json_decode($product->horizontal_slider_product, true)["IMG tag"] ?>">

                        <label>IMG Tag:</label>

                        <div class="form-group__content">

                            <label class="pb-5" for="hSlider">
                               <img src="img/products/<?= $product->url_category ?>/horizontal/<?php echo json_decode($product->horizontal_slider_product, true)["IMG tag"] ?>" class="img-fluid changeHSlider">
                            </label> 

                            <div class="custom-file">

                                <input type="file"
                                class="custom-file-input"
                                id="hSlider"
                                name="hSlider"
                                accept="image/*"
                                maxSize="2000000"
                                onchange="validateImageJS(event, 'changeHSlider')"
                                >

                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>

                                <label class="custom-file-label" for="hSlider">Choose file</label>   

                            </div>         
     
                            
                        </div>

                    </div>

                </div>

            </div> 

            <!--=====================================
            Slide Vertical del producto
            ======================================--> 

            <div class="form-group">

            	<input type="hidden" name="vSliderOld" value="<?= $product->vertical_slider_product ?>">

                <label>Product Vertical Slider<sup class="text-danger">*</sup></label>

                <div class="form-group__content">

                    <label class="pb-5" for="vSlider">

                         <img src="img/products/<?= $product->url_category ?>/vertical/<?= $product->vertical_slider_product ?>" class="img-fluid changeVSlider" style="width:260px">

                    </label>

                    <div class="custom-file">

                        <input type="file" 
                        class="custom-file-input" 
                        id="vSlider"
                        name="vSlider"
                        accept="image/*"
                        maxSize="2000000"
                        onchange="validateImageJS(event, 'changeVSlider')"
                        >

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                        <label class="custom-file-label" for="vSlider">Choose file</label>

                    </div>     
                    
                </div>

            </div> 

            <!--=====================================
            Video del producto
            ======================================-->

            <div class="form-group">
                 
                <label>Product Video, Ex: <strong>Type:</strong> YouTube, <strong>Id:</strong> Sl5FaskVpD4</label> 

                <div class="row mb-3">
                    
                    <div class="col-12 col-lg-6 form-group__content input-group mx-0 pr-0">
                      
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Type:
                            </span>
                        </div>

                        <select 
                        class="form-control"                               
                        name="type_video"
                        >

                        <?php if ($product->video_product != null): ?>

                        	<?php if (json_decode($product->video_product, true)[0] == "youtube"): ?>

                        		<option value="youtube">YouTube</option>
                                <option value="vimeo">Vimeo</option>

                        	<?php else: ?>

                        		<option value="vimeo">Vimeo</option>
                                <option value="youtube">YouTube</option>

                        	<?php endif ?>

                         <?php else: ?>

                            <option value="">Select Platform</option>
                            <option value="youtube">YouTube</option>
                            <option value="vimeo">Vimeo</option>

                        <?php endif ?>

                        </select>

                    </div>

                    <div class="col-12 col-lg-6 form-group__content input-group mx-0">
                        
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Id:
                            </span>
                        </div>

                        <input
                        class="form-control"                               
                        name="id_video"
                        pattern="[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\'\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,100}"
                        maxlength="100"
                        onchange="validateJS(event,'paragraphs')"
                        <?php if ($product->video_product != null): ?>
                        value="<?php echo json_decode($product->video_product, true)[1] ?>"
                        <?php endif ?>
                        >

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>         

                    </div>

                </div>

            </div>

             <!--=====================================
            Precio de venta, precio de envío, dias de entrega y stock
            ======================================-->

            <div class="form-group">
                
                <div class="row mb-3">
                   
                    <!--=====================================
                    Precio de venta
                    ======================================-->
                    
                    <div class="col-12 col-lg-3">
                        
                        <label>Product Price<sup class="text-danger">*</sup></label>

                        <div class="form-group__content input-group mx-0 pr-0">         

                            <div class="input-group-append">
                                <span class="input-group-text">
                                    Price $:
                                </span>
                            </div>

                            <input type="number"
                            class="form-control"
                            name="price"
                            min="0"
                            step="any"
                            pattern="[.\\,\\0-9]{1,}"
                            onchange="validateJS(event, 'numbers')"
                             value="<?= $product->price_product ?>"
                            required>
                        
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>

                        </div>      

                    </div>

                    <!--=====================================
                    Precio de envío
                    ======================================-->

                    <div class="col-12 col-lg-3">
                        
                        <label>Product Shipping<sup class="text-danger">*</sup></label>

                        <div class="form-group__content input-group mx-0 pr-0"> 

                            <div class="input-group-append">
                                <span class="input-group-text">
                                    Shipping $:
                                </span>
                            </div>

                            <input type="number"
                            class="form-control"
                            name="shipping"
                            min="0"
                            step="any"
                            pattern="[.\\,\\0-9]{1,}"
                            onchange="validateJS(event, 'numbers')"
                            value="<?= $product->shipping_product ?>"
                            required>
                        
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div> 

                        </div>     

                    </div>

                    <!--=====================================
                    Días de entrega
                    ======================================-->

                    <div class="col-12 col-lg-3">
                        
                        <label>Product Delivery Time<sup class="text-danger">*</sup></label>

                        <div class="form-group__content input-group mx-0 pr-0"> 

                            <div class="input-group-append">
                                <span class="input-group-text">
                                    Days:
                                </span>
                            </div>

                            <input type="number"
                            class="form-control"
                            name="delivery_time"
                            min="0"
                            pattern="[.\\,\\0-9]{1,}"
                            onchange="validateJS(event, 'numbers')"
                            value="<?= $product->delivery_time_product ?>"
                            required>
                        
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>  

                        </div>    

                    </div> 

                     <!--=====================================
                    Stock
                    ======================================-->

                    <div class="col-12 col-lg-3">
                        
                        <label>Product Stock<sup class="text-danger">*</sup> (Max:100 unit)</label>

                        <div class="form-group__content input-group mx-0 pr-0"> 

                            <div class="input-group-append">
                                <span class="input-group-text">
                                    Stock:
                                </span>
                            </div>

                            <input type="number"
                            class="form-control"
                            name="stock"
                            min="0"
                            max="100"
                            pattern="[0-9]{1,}"
                            onchang onchange="validateJS(event, 'numbers')"
                            value="<?= $product->stock_product ?>"
                            required>
                        
                            <div class="valid-feedback">Valid.</div>
                            <div class="invalid-feedback">Please fill out this field.</div>  

                        </div>    

                    </div>

                </div>

            </div>

             <!--=====================================
            Oferta del producto
            ======================================-->

            <div class="form-group">
                
                <label>Product Offer Ex: <strong>Type:</strong> Disccount, <strong>Percent %:</strong> 25, <strong>End offer:</strong> 30/06/2020</label>

                <div class="row mb-3">

                    <!--=====================================
                    Tipo de Oferta
                    ======================================-->
                    
                    <div class="col-12 col-lg-4 form-group__content input-group mx-0 pr-0">
                        
                        <div class="input-group-append">
                            <span class="input-group-text">
                                Type:
                            </span>
                        </div>

                        <select
                        class="form-control"
                        name="type_offer"
                        onchange="changeOffer(event)">

                             <?php if ($product->offer_product != null): ?>

                             	<?php if (json_decode($product->offer_product, true)[0] == "Discount"): ?>

                             		<option value="Discount">Disccount</option>
                                	<option value="Fixed">Fixed</option>

                            <?php else: ?>
            
                                <option value="Fixed">Fixed</option>
                                <option value="Discount">Disccount</option>
                                    
                            <?php endif ?>

                            <?php else: ?>

	                            <option value="Discount">Disccount</option>
	                            <option value="Fixed">Fixed</option>
	                            
	                        <?php endif ?>

                        </select>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>        

                    </div>

                    <!--=====================================
                    El valor de la oferta
                    ======================================-->

                    <div class="col-12 col-lg-4 form-group__content input-group mx-0 pr-0">

                    	<?php if ($product->offer_product != null): ?>

            		   		<div class="input-group-append">

            		   	 		<?php if (json_decode($product->offer_product, true)[0] == "Discount"): ?>

        		   	 			 	<span 
                            		class="input-group-text typeOffer">
	                                	Percent %:
	                            	</span>

	                             <?php else: ?>
            
                                    <span 
                                    class="input-group-text typeOffer">
                                        Price $:
                                    </span>
                                        
                                <?php endif ?>

            		   		</div>

            		   		<input type="number"
	                        class="form-control"
	                        name="value_offer"
	                        min="0"
	                        step="any"
	                        pattern="[.\\,\\0-9]{1,}"
	                        onchange="validateJS(event, 'numbers')"
	                        value="<?php echo json_decode($product->offer_product, true)[1] ?>">


                    	<?php else: ?>
                    
	                        <div class="input-group-append">
	                           
	                            <span 
	                            class="input-group-text typeOffer">
	                                Percent %:
	                            </span>

	                        </div>

	                        <input type="number"
	                        class="form-control"
	                        name="value_offer"
	                        min="0"
	                        step="any"
	                        pattern="[.\\,\\0-9]{1,}"
	                         onchange="validateJS(event, 'numbers')">

	                     <?php endif ?>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>     

                    </div>

                    <!--=====================================
                    Fecha de vencimiento de la oferta
                    ======================================-->

                    <div class="col-12 col-lg-4 form-group__content input-group mx-0 pr-0">
                        
                        <div class="input-group-append">
                            <span class="input-group-text">
                                End Offer:
                            </span>
                        </div>

                        <?php if ($product->offer_product != null): ?>

                            <input type="date"
                            class="form-control"
                            name="date_offer"
                            value="<?php echo json_decode($product->offer_product, true)[2] ?>">

                        <?php else: ?>

                            <input type="date"
                            class="form-control"
                            name="date_offer">
                            
                        <?php endif ?>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>     

                    </div>
                      

                </div>   

            </div>

        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
            
            <div class="form-group submtit">
                 
                <button
                type="submit"
                class="ps-btn ps-btn--fullwidth">Save Product</button>

                <?php 

                    $editProduct = new vendorsController();
                    $editProduct -> editProduct();
                ?>


            </div>


        </div>

    </div>

</form>
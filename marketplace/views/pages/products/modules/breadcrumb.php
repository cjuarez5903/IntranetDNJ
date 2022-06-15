<div class="ps-breadcrumb">

    <div class="container">

        <ul class="breadcrumb">

            <li><a href="/">Home</a></li>

            <?php if (!empty($showcaseProducts[0]->name_category)): ?>

            	<li><?php echo $showcaseProducts[0]->name_category ?></li>

            <?php else: ?>

            	<li><?php echo $showcaseProducts[0]->name_subcategory ?></li>
            	
            <?php endif ?>       

        </ul>

    </div>

</div>
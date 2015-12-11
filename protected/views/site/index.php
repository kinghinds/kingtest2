
  <!-- Mainbar starts -->
  <div class="mainbar">

    <div class="matter">
      <div class="container-fluid">

        <div id="portfolio-one">
          <?php foreach ($products as $key => $product): ?>
          <!-- Element -->
           <div class="element">
            <a href="<?php echo $product->getPermalink(); ?>">
              <!-- Image -->
               <img class="server_img" src="<?php echo $product->thumbFileUrl;?>" alt=""/>
               <!-- Content -->
               <span class="image-info">
                <!-- Title -->
                  <span class="image-title"><?php echo $product->name; ?></span>
                <!-- Desc -->
                  <span class="image-desc"><?php echo $product->sub_content; ?></span>
               </span>
            </a>
           </div>  
          <?php endforeach ?>
          
          <?php foreach ($brands as $key => $brand): ?>
          <!-- Element -->
           <div class="element">
            <a href="<?php echo $brand->permalink; ?>">
              <!-- Image -->
               <img class="server_img" src="<?php echo $brand->thumbFileUrl;?>" alt=""/>
               <!-- Content -->
               <span class="image-info">
                <!-- Title -->
                  <span class="image-title"><?php echo $brand->title; ?></span>
                <!-- Desc -->
                  <span class="image-desc"><?php echo $brand->sub_content; ?></span>
               </span>
            </a>
           </div>  
          <?php endforeach ?>

          <?php foreach ($servers as $key => $server): ?>
          <!-- Element -->
           <div class="element">
            <a href="<?php echo $server->getPermalink(); ?>">
              <!-- Image -->
               <img class="server_img" src="<?php echo $server->thumbFileUrl;?>" alt=""/>
               <!-- Content -->
               <span class="image-info">
                <!-- Title -->
                  <span class="image-title"><?php echo $server->name; ?></span>
                <!-- Desc -->
                  <span class="image-desc"><?php echo $server->sub_content; ?></span>
               </span>
            </a>
           </div>  
          <?php endforeach ?>

        </div>
      </div>
    </div>
                     

  </div>
  <!-- Mainbar ends -->


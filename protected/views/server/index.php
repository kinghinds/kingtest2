<!-- Mainbar starts -->
  <div class="mainbar">

    <div class="matter">
      <div class="container-fluid">
          <!-- Title starts -->
        <div class="page-title">
          <img src="<?php if(!empty($banner)) {echo $banner->largeUrl;}else{echo "/images/title-back.jpg";}?>" alt="">
          <h2>
              <?php if(!empty($banner)) {echo $banner->title;}else{echo "广告位";}?>
              <span>
                  <?php if(!empty($banner)) {echo $banner->sub_content;}else{echo "广告位";} ?>
              </span>
          </h2>
        </div>
          
        <div id="portfolio-one">
          <?php foreach ($servers as $key => $value): ?>
          <!-- Element -->
           <div class="element">
            <a href="<?php echo $value->getPermalink(); ?>">
              <!-- Image -->
               <img class="server_img" src="<?php echo $value->thumbFileUrl;?>" alt=""/>
               <!-- Content -->
               <span class="image-info">
                <!-- Title -->
                  <span class="image-title"><?php echo $value->name; ?></span>
                <!-- Desc -->
                  <span class="image-desc"><?php echo $value->sub_content; ?></span>
               </span>
            </a>
           </div>  
          <?php endforeach ?>
          
        </div>
      </div>
    </div>
                     

  </div>
  <!-- Mainbar ends -->
  
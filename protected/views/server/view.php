
  <!-- Mainbar starts -->
  <div class="mainbar">

    <div class="matter">
      <div class="container-fluid">

        <!-- Title starts -->
        <div class="page-title">
          <img src="<?php if(!empty($banner)) {echo $banner->largeUrl;}else{echo "/images/title-back.jpg";}?>" alt="">
          <h2><?php echo $server->name; ?><span><?php echo $server->sub_content; ?></span></h2>
        </div>
        <!-- Title ends -->

        <!-- Content starts -->

        <div class="box-body">
			<div class="row-fluid">
				<?php echo $server->content; ?>
			</div>
        </div>

        <!-- Content ends -->

      </div>
    </div>
                     

  </div>
  <!-- Mainbar ends -->

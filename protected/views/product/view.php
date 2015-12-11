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
        <!-- Title ends -->

        <!-- Content starts -->

        <div class="box-body blog">
          <div class="row-fluid">

            <div class="span12">
               <div class="posts">
               
                  <!-- Each posts should be enclosed inside "entry" class" -->
                  <!-- Post one -->
                  <div class="entry">
                     <h2><?php echo $serie->title;?>   <span style="color:red;"><?php echo Yii::t('common','系列'); ?></span></h2>
                     
                     <!-- Meta details -->
                     <div class="meta">
                        <ul>
                            <li><?php echo Yii::t('common','规格'); ?>：<?php echo $serie->norms;?></li>
                            <li><?php echo Yii::t('common','型号'); ?>：<?php echo $serie->series_model;?></li>    
                        </ul>
                      </div>
                     <!-- Content begin -->
                     <?php echo $serie->content;?>

                     <!-- Content end -->
                  </div>
                  <?php if (!empty($products)): ?>
                  <h6><?php echo Yii::t('common','相关产品'); ?></h6>
                  <div class="clearfix"></div>
                  <hr />
                  
                  <div class="aboutus">
                      <?php foreach ($products as $key => $product): ?>
                      <div class="widget span2">
                          <!-- Staff #4 -->
                        <div class="staff">
                           <div class="pic">
                              <img src="<?php echo $product->thumbFileUrl;?>" alt="" />
                           </div>
                           <div class="details">
                              <div class="desig pull-left">
                                 <p class="name"><?php echo $product->name;?></p>
                              </div>
                           </div>
                        </div>
                        <div class="clearfix"></div>
                      </div>  
                      <?php endforeach ?>
                      

                   <div class="clearfix"></div>
                  </div>
                  <hr/>
                  <?php endif ?>
                  <!-- Navigation -->
                  <?php if ($previd !=0 || $nextid != 0): ?>
                  <div class="navigation-button">
                        <?php if ($previd != 0): ?>
                        <div class="pull-left left_padding">
                          <a href="<?php echo CHtml::normalizeUrl(array('product/view','seriesid'=>$previd)); ?>">&laquo; <?php echo $prevname; ?></a>
                        </div>    
                        <?php endif ?>  
                        <?php if ($nextid != 0): ?>
                        <div class="pull-right right_padding">
                          <a href="<?php echo CHtml::normalizeUrl(array('product/view','seriesid'=>$nextid)); ?>"><?php echo $nextname; ?> &raquo;</a>
                        </div>
                        <?php endif ?>
                        <div class="clearfix"></div>
                  </div>
                  <?php endif ?>
                  
                  <div class="clearfix"></div>
               </div>
            </div>  
          </div>
        </div>
        <!-- Content ends -->
      </div>
    </div>
  </div>
  <!-- Mainbar ends -->
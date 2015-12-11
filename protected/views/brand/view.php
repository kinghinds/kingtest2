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
                     <h2><a href="#"><?php echo $brand->title; ?></a></h2>
                     
                     <!-- Meta details -->
                     <div class="meta">
                        <i class="icon-eye-open"></i> <?php echo $brand->view_count; ?> <?php echo Yii::t('common','浏览量'); ?>
                     </div>
                     
                     <!-- Content begin -->
                      <?php echo $brand->content; ?>

                     <!-- Content end -->
                  </div>
                  <?php if (!empty($products)): ?>
                  <h6><?php echo Yii::t('common','相关产品'); ?></h6>
                  <div class="clearfix"></div>
                  <hr />
                  
                  <div class="aboutus">
                    <?php foreach ($products as $key => $value): ?>
                    <a href="<?php echo CHtml::normalizeUrl(array('product/index','brand_id'=>$value->brand_id)); ?>">
                      <div class="widget span3">
                          <!-- Staff #4 -->
                        <div class="staff">
                           <div class="pic">
                              <img src="<?php echo $value->thumbFileUrl; ?>" alt="" />
                           </div>
                           <div class="details">
                              <div class="desig pull-left">
                                 <p class="name"><?php echo $value->name; ?></p>
                              </div>
                           </div>
                        </div>
                        <div class="clearfix"></div>
                      </div>
                    </a>  
                    <?php endforeach ?>
                   <div class="clearfix"></div>
                   <div class="pull-left"><a href="<?php echo CHtml::normalizeUrl(array('product/index','brand_id'=>$brand->brand_id)); ?>">&raquo; <?php echo Yii::t('common','更多'); ?></a></div>
                  </div>
                  
                  <div class="clearfix"></div>
                  <hr/>
                  <?php endif ?>
                  <!-- Navigation -->
                  <?php if ($previd !=0 || $nextid != 0): ?>
                  <div class="navigation-button">
                        <?php if ($previd != 0): ?>
                        <div class="pull-left left_padding">
                          <a href="<?php echo CHtml::normalizeUrl(array('brand/view','id'=>$previd)); ?>">&laquo; <?php echo $prevname; ?></a>
                        </div>    
                        <?php endif ?>  
                        <?php if ($nextid != 0): ?>
                        <div class="pull-right right_padding">
                          <a href="<?php echo CHtml::normalizeUrl(array('brand/view','id'=>$nextid)); ?>"><?php echo $nextname; ?> &raquo;</a>
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
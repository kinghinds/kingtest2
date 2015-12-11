<!-- Mainbar starts -->
<div class="mainbar">
  <div class="matter">
    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span12">
            <div class="box-body">

              <!-- Well starts -->
              <div class="well">
                <?php echo Yii::t('common','您搜索的关键词'); ?> “<?php echo $keyword; ?>” <?php echo Yii::t('common','有 {num} 个结果。',array('{num}'=>$num)); ?>
              </div>
              <!-- Well ends -->

              <!-- Navigation tabs starts -->

              <!-- Don't forget the id "myTab" -->
                <ul class="nav nav-tabs" id="myTab">
                  <li <?php if((empty($brands) && empty($products)) || !empty($brands)) echo 'class="active"'; ?>><a href="#brands"><?php echo Yii::t('common','品牌中心'); ?></a></li>
                  <li <?php if((empty($brands) && !empty($products))) echo 'class="active"'; ?>><a href="#products"><?php echo Yii::t('common','产品中心'); ?></a></li>
                </ul>
                 
                <div class="tab-content">
                  <div class="tab-pane <?php if((empty($brands) && !empty($products))) echo 'active'; ?>" id="products">
                    <div class="aboutus">
                      <?php foreach ($products as $key => $value): ?>
                      <a href="<?php echo CHtml::normalizeUrl(array('product/view','seriesid'=>$value->series_id)); ?>">
                        <div class="widget span2">
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
                    </div>
                  </div>
                  <div class="tab-pane <?php if((empty($brands) && empty($products)) || !empty($brands)) echo 'active'; ?>" id="brands">
                    <div id="portfolio-one">
                        <?php foreach ($brands as $key => $brand){ ?>
                        <div class="element">
                            <a href="<?php echo $brand->permalink; ?>">
                                <img class="server_img" src="<?php echo $brand->thumbFileUrl; ?>" alt="" height="230px" width="350px"/>
                                <span class="image-info">
                                <!-- Title -->
                                  <span class="image-title"><?php echo $brand->title; ?></span>
                                <!-- Desc -->
                                  <span class="image-desc"><?php echo $brand->sub_content; ?></span>
                               </span>
                            </a>
                        </div>    
                        <?php } ?>
                        
                        
                    </div>
                  </div>
                </div>
              <!-- Navigation tabs ends -->
            </div>
        </div>
      </div>
    </div>
  </div>              
</div>
<!-- Mainbar ends -->

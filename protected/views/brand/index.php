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
            <div class="box-body">
                <div class="row-fluid">
                    <div class="span12">
                        <p>
                            <!-- Add filter names inside "data-filter". For example ".web-design",
                            ".seo", etc., Filter names are used to filter the below images. -->
                            <div class="button">
                                <ul id="filters">
                                    <li>
                                        <?php echo Yii::t('common','选所有'); ?>：
                                    </li>
                                    <li>
                                        <a href="#" data-filter="*" class="current">
                                            <?php echo Yii::t('common','所有品牌'); ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </p>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <p>
                            <!-- Add filter names inside "data-filter". For example ".web-design",
                            ".seo", etc., Filter names are used to filter the below images. -->
                            <div class="button">
                                <ul id="filters">
                                    <li>
                                        <?php echo Yii::t('common','按地区'); ?>：
                                    </li>
                                    <?php foreach ($regions as $key => $title): ?>
                                    <li>
                                        <a href="#" data-filter=".region<?php echo $key; ?>">
                                            <?php echo $title; ?>
                                        </a>
                                    </li>    
                                    <?php endforeach ?>
                                    
                                </ul>
                            </div>
                        </p>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <p>
                            <!-- Add filter names inside "data-filter". For example ".web-design",
                            ".seo", etc., Filter names are used to filter the below images. -->
                            <div class="button">
                                <ul id="filters">
                                    <li>
                                        <?php echo Yii::t('common','按首字母'); ?>：
                                    </li>
                                    <?php foreach ($codes as $key => $code): ?>
                                    <li>
                                        <a href="#" data-filter=".<?php echo $code; ?>">
                                            <?php echo $code; ?>
                                        </a>
                                    </li>    
                                    <?php endforeach ?>
                                    
                                </ul>
                            </div>
                        </p>
                    </div>
                </div>
            </div>
            <div id="portfolio-one">
                <?php foreach ($brands as $key => $brand){
                    $criteria = new CDbCriteria();
                    $criteria->compare('owner_id', $brand->brand_id);
                    $model = BrandI18n::model()->find($criteria);
                    $title = $model->title;
                    $str = strtoupper($title{0});
                 ?>
                <div class="element <?php echo $str; ?> <?php echo 'region'.$brand->region_id; ?>">
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
</div>
<script>
    $(document).ready(function() {
        $("#filters a").on('click',
        function() {
            $("#filters a").each(function(index, el) {
                $(this).removeClass();
            });
            $(this).addClass('current');
            var $container = $('#portfolio-one');
            // initialize isotope
            $container.isotope({
              resizable : false
            });
            var selector = $(this).attr('data-filter');
            $container.isotope({ filter: selector });
            return false;
        });
    });
</script>
<!-- Mainbar ends -->

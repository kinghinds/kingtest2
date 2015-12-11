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
            <div class="box-body">
                <div class="row-fluid">
                    <div class="span12">
                        <?php if (!empty($brand)): ?>
                        <p>
                            <!-- Add filter names inside "data-filter". For example ".web-design",
                            ".seo", etc., Filter names are used to filter the below images. -->
                            <div class="button">
                                <ul id="filters" class="brand">
                                    <li>
                                        <?php echo Yii::t('common','品牌'); ?>：<a class="current" href="<?php echo CHtml::normalizeUrl(array('brand/view','id'=>$brand->brand_id)); ?>">
                                            <?php echo $brand->title; ?>
                                        </a>
                                    </li>                                        
                                </ul>
                            </div>
                        </p>   
                        <?php endif ?>
                        
                        <p>
                            <div class="button">
                                <ul id="filters" class="category_list">
                                    <li><a href="#" data-id="*" class="current"><?php echo Yii::t('common','所有分类'); ?></a></li>
                                    <?php foreach ($productCategoryOptions as $key => $value): ?>
                                    <li>
                                        <a href="#" data-id="<?php echo $value['category_id']; ?>">
                                            <?php echo $value['name']; ?>
                                        </a>
                                    </li>    
                                    <?php endforeach ?>
                                </ul>
                            </div>
                            
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="row-fluid">
                <div class="span12">
                    <!-- Sheet starts -->
                    <div class="box-body">
                        <table class="table table-striped table-bordered table-hover">
                            <caption>
                            <?php echo Yii::t('common','产品列表'); ?>
                            </caption>
                            <thead>
                                <tr>
                                    <th>
                                        <?php echo Yii::t('common','名称'); ?>
                                    </th>
                                    <th>
                                        <?php echo Yii::t('common','品牌'); ?>
                                    </th>
                                    <th>
                                        <?php echo Yii::t('common','系列'); ?>
                                    </th>
                                    <th>
                                        <?php echo Yii::t('common','分类'); ?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach ($products as $key => $value): ?>
                                <tr data-id="<?php echo $value['category_id']; ?>">
                                    <td>
                                        <?php echo $value['name']; ?>
                                    </td>
                                    <td>
                                        <a class="product_name" href="<?php echo Yii::app()->createUrl('brand/view',array('id'=>$value['brand_id'])); ?>"><?php echo $brands[$value['brand_id']]; ?></a>
                                    </td>
                                    <td>
                                        <a href="<?php echo Yii::app()->createUrl('product/view',array('seriesid'=>$value['series_id'])); ?>"><?php echo $series[$value['series_id']]; ?></a>
                                    </td>
                                    <td>
                                        <?php echo $categorys[$value['category_id']]; ?>
                                    </td>
                                </tr>    
                                <?php endforeach ?>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Content ends -->
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {        
        $(".brand a").click(function(event) {
            window.location.href = $(this).attr('href');
        });
        $(".category_list a").on('click',
        function() {
            $(".category_list a").each(function(index, el) {
              $(this).removeClass();
            });
            
            $(this).addClass('current');

            if ($(this).attr("data-id") == "*") {
                $("tbody tr[data-id!='" + $(this).attr("data-id") + "']").show('slow');
            } else{
            $("tbody tr[data-id='" + $(this).attr("data-id") + "']").show('slow');
            $("tbody tr[data-id!='" + $(this).attr("data-id") + "']").hide('slow');
            };
        });
    });
</script>
<!-- Mainbar ends -->
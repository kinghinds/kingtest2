<!-- Mainbar starts -->
  <div class="mainbar">

    <div class="matter">
      <div class="container-fluid">

        <!-- Title starts -->
        <div class="page-title">
          <img src="/images/title-back.jpg" alt="">
          <h2><?php echo Yii::t('common','啊哦！！！'); ?><span><?php echo Yii::t('common','您搜索的内容不存在！'); ?></span></h2>
        </div>
        <!-- Title ends -->

        <!-- Content starts -->

        <div class="box-body error">
          <div class="row-fluid">

            <div class="span12">

              <h5><?php echo Yii::t('common','本站搜索'); ?></h5>
                     <div class="form">
                      <form method="get" id="searchform" action="#" class="form-search">
                        <input type="text" value="" name="s" id="s" class="input-medium"/>
                        <button type="submit" class="btn"><?php echo Yii::t('common','搜 索'); ?></button>
                      </form>
                    </div>
                    <hr />

                    <div class="link-list">
                      <h5><?php echo Yii::t('common','在我们的网站看看'); ?></h5>
                      <a href="<?php echo CHtml::normalizeUrl(array('site/index')); ?>"></i> <?php echo Yii::t('common', '首页'); ?></a>
                      <a href="<?php echo CHtml::normalizeUrl(array('product/index')); ?>"> <?php echo Yii::t('common', '产品中心'); ?></a>
                      <a href="<?php echo CHtml::normalizeUrl(array('brand/index')); ?>"> <?php echo Yii::t('common', '品牌中心'); ?></a>
                      <a href="<?php echo CHtml::normalizeUrl(array('servers/index')); ?>"> <?php echo Yii::t('common', '服务中心'); ?></a>
                      <a href="<?php echo CHtml::normalizeUrl(array('feedback/index')); ?>"></i> <?php echo Yii::t('common', '反馈中心'); ?></a>
                    </div>

            </div>

          </div>
        </div>

        <!-- Content ends -->

      </div>
    </div>
                     

  </div>
  <!-- Mainbar ends -->
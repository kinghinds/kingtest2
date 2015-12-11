<!DOCTYPE html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <!-- Title and other stuffs -->
  <?php echo CHtml::tag('title', array(), $this->pageTitle) . "\r\n"; ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php if ($this->metaKeywords) echo CHtml::metaTag($this->metaKeywords,'keywords') . "\r\n"; ?>
  <?php if ($this->metaDescription) echo CHtml::metaTag($this->metaDescription,'description') . "\r\n"; ?>
  <!-- Stylesheets -->
  <?php echo CHtml::cssFile(Helper::mediaUrl('style/css/bootstrap.css')) . "\r\n"; ?>
  <!-- Font awesome icon -->
  <link rel="stylesheet" href="/style/css/font-awesome.css">
  <!-- Flexslider -->
  <link rel="stylesheet" href="/style/css/flexslider.css">  
  <!-- prettyPhoto -->
  <link rel="stylesheet" href="/style/css/prettyPhoto.css">
  <!-- Main stylesheet -->
  <link href="/style/css/style.css" rel="stylesheet">
	<link href="/style/css/king.css" rel="stylesheet">
  <!-- Bootstrap responsive -->
  <link href="/style/css/bootstrap-responsive.css" rel="stylesheet">
  <script src="/style/js/jquery.js"></script>
  <!-- JiaThis Button BEGIN -->
  <script src="/style/js/share.js"></script>

  <script>
  $(document).ready(function(){
    // 搜索
    $("#search-submit").click(function(){

      var str = $("#searchkeyword").val();
      var pattern = new RegExp("[~'!@#$%^&*()-+_=:]");
      if (pattern.test(str)) {
        alert("<?php echo Yii::t('common','关键词含有非法字符！'); ?>");
        return false;
      }
      if ($.trim(str) == "") {
        alert("<?php echo Yii::t('common','请输入关键词！'); ?>");
        return false;
      }

     $(".form-search").submit();
      
    });
  })
  </script>
  <!-- HTML5 Support for IE -->
  <!--[if lt IE 9]>
  <script src="/style/js/html5shim.js"></script>
  <![endif]-->

  <!-- Favicon -->
  <link rel="shortcut icon" href="images/favicon/favicon.ico">
</head>

<body>

<!-- 顶部 starts -->

  <div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
      <div class="container-fluid">
        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
        <div class="nav-collapse collapse">
          <ul class="nav pull-right">
          	<?php $this->widget('LanguageSelector'); ?>
          </ul>
        </div>
      </div>
    </div>
  </div>

<!-- 顶部 ends -->


<!-- 分享盒子 box starts -->
   <div class="slide-box">
      <div class="bor"></div>
      <div class="padd">
        <div class="slide-box-button">
          <i class="icon-chevron-left"></i>
        </div>
        <h5><?php echo Yii::t('common','欢迎您'); ?></h5>
        <?php echo Yii::t('common','喜欢我们的网站吗？那就分享给你的朋友吧！'); ?>

        <hr />

        <div class="social">

          <a onclick="share2sina('<?php echo Yii::app()->request->hostInfo . Yii::app()->request->getUrl(); ?>','<?php echo $this->pageTitle; ?>','<?php echo Yii::app()->request->hostInfo; ?>/images/b-logo.png')" href="javascript:void(0)" title="分享到新浪微博"><i class="icon-weibo weibo"></i></a>
          <a onclick="share2renren('<?php echo Yii::app()->request->hostInfo . Yii::app()->request->getUrl(); ?>','<?php echo $this->pageTitle; ?>','<?php echo Yii::app()->request->hostInfo; ?>/images/b-logo.png')" href="javascript:void(0)" title="分享到人人网"><i class="icon-renren renren"></i></a>
          <a onclick="share2QQ('<?php echo Yii::app()->request->hostInfo . Yii::app()->request->getUrl(); ?>','<?php echo $this->pageTitle; ?>','<?php echo Yii::app()->request->hostInfo; ?>/images/b-logo.png')" href="javascript:void(0)" title="分享到QQ空间"><i class="icon-qq linkedin"></i></a>
          <a onclick="share2tx('<?php echo Yii::app()->request->hostInfo . Yii::app()->request->getUrl(); ?>','<?php echo $this->pageTitle; ?>','<?php echo Yii::app()->request->hostInfo; ?>/images/b-logo.png')" href="javascript:void(0)" title="分享到腾讯微博"><i class="icon-tencent-weibo google-plus"></i></a>
         <!--  <a href="#"><i class="icon-pinterest pinterest"></i></a> -->
        </div>
      
      </div>
    </div>

<!-- 分享盒子 box ends -->    

<!-- Main content starts -->

<div class="content">

  <!-- 右侧菜单栏 starts -->
  <div class="sidebar">

    <!-- Logo -->
    <div class="logo">
      <a href="/"><img src="/images/b-logo.png" alt="" /></a>
    </div>



        <div class="sidebar-dropdown"><a href="#"><?php echo Yii::t('common','导航'); ?></a></div>

        <!--- Sidebar navigation -->
        <!-- If the main navigation has sub navigation, then add the class "has_sub" to "li" of main navigation. -->

        <!-- Colors: Add the class "br-red" or "br-blue" or "br-green" or "br-orange" or "br-purple" or "br-yellow" to anchor tags to create colorful left border -->
        <div class="s-content">
			<?php $this->widget('Menu'); ?>
            <!-- Sidebar search -->
    

            <form class="form-search" method="get" action="<?php echo CHtml::normalizeUrl(array('site/search')); ?>" enctype="multipart/form-data">
              <div class="input-append">
                <input type="text" class="input-medium search-query" id="searchkeyword" name="keyword">
                <input id="search-submit" type="button" value="Search" name="search-submit">
              </div>
            </form>
            
            <!-- Sidebar widget -->
            
            <div class="s-widget">
               <h6><?php echo Yii::t('common','联系我们'); ?></h6>
               <p>电话号码（TEL):(0755) 456-7890</p>
            </div>

        </div>



  </div>
  <!-- 右侧菜单栏 ends -->

<?php echo $content; ?>

<div class="clearfix"></div>

  <!-- Foot starts -->             
    <div class="foot">
    <?php echo Setting::getValueByCode('copyright', true); ?>
<!-- Copyright 2015 &copy; - <?php echo Yii::t('common','技术支持'); ?> <a href="http://www.kinghinds.icoc.cc/" title="天下楚云" target="_blank">天下楚云</a> -->
    </div> 
  <!-- Foot ends -->

</div>

<div class="clearfix"></div>

<!-- Main content ends -->

<!-- JS -->
<script src="/style/js/bootstrap.js"></script> <!-- Bootstrap -->
<script src="/style/js/imageloaded.js"></script> <!-- Imageloaded -->
<script src="/style/js/jquery.isotope.js"></script> <!-- Isotope -->
<script src="/style/js/jquery.prettyPhoto.js"></script> <!-- prettyPhoto -->
<script src="/style/js/jquery.flexslider-min.js"></script> <!-- Flexslider -->
<script src="/style/js/custom.js"></script> <!-- Main js file -->	

<!-- Scroll to top -->
<span class="totop"><a href="#"><i class="icon-chevron-up"></i></a></span> 
</body>
</html>

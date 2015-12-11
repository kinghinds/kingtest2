<!-- Mainbar starts -->
  <div class="mainbar">

    <div class="matter">
      <div class="container-fluid">

        <!-- Title starts -->
        <div class="page-title">
          <img src="<?php if(!empty($banner)) {echo $banner->largeUrl;}else{echo "/images/title-back.jpg";}?>" alt="">
          <h2><?php echo Yii::t('common','反馈中心'); ?><span><?php echo Yii::t('common','留下您的建议或者您的需求'); ?></span></h2>
        </div>
        <!-- Title ends -->

        <!-- Content starts -->

        <div class="box-body contactus">          
          <div class="row-fluid">
             <div class="span12">
               <div class="well">
                  <!-- Address section -->
                 <h5><?php echo Yii::t('common','您的反馈已经提交成功，我们会及时与您联系！'); ?></h5>
                 <hr />
                 <div class="address">
                     <address>
                        <!-- Company name -->
                        <h6><?php echo Yii::t('common','联系信息'); ?></h6>
                        <!-- Address -->
                        795 Folsom Ave, Suite 600<br>
                        San Francisco, CA 94107<br>
                        <!-- Phone number -->
                        <abbr title="Phone">P:</abbr> (123) 456-7890.
                     </address>
                      
                     <address>
                        <!-- Name -->
                        <h6><?php echo Yii::t('common','联系邮箱'); ?></h6>
                        <!-- Email -->
                        <a href="mailto:#">first.last@gmail.com</a>
                     </address>
                     
                 </div>
               </div> 
             </div>
          </div>
          
        </div>

        <!-- Content ends -->

      </div>
    </div>
                     

  </div>
  <!-- Mainbar ends -->
 
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

        <div class="box-body contactus">

          <div class="row-fluid">
            <div class="span12">
               <div class="accordion" id="accordion2">
                 <!-- Each item should be enclosed inside the class "accordion-group". Note down the below markup. -->
                <?php foreach($feedbacks as $key=>$feedback) { 
                $criteria = new CDbCriteria();
                $criteria->compare('feedback_id',$feedback->id);
                $answer = Answer::model()->find($criteria);
                ?>
                 <!-- First Accordion -->
                <div class="accordion-group">
                  <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse<?php echo $key; ?>">
                       <!-- Title with experience details. -->
                      <h6><?php echo $key+1; ?>.  <?php echo $feedback['content']; ?></h6>
                    </a>
                  </div>
                  <div id="collapse<?php echo $key; ?>" class="accordion-body collapse <?php if($key==0) echo 'in'; ?>">
                    <div class="accordion-inner">
                       <!-- Details about job -->
                      <p><?php echo $answer->content; ?></p>
                    </div>
                  </div>
                </div>  
                <?php } ?>
                
                

              </div>
             </div>   
          </div>
          
          <div class="row-fluid">
             <div class="span6">
                <div class="well">
                   <!-- Contact form -->
                  <h5><?php echo Yii::t('common','联系我们'); ?></h5>
                  <hr />
                  <div class="form">
                    <!-- Contact form (not working)-->
                    <?php echo CHtml::errorSummary($feedbackForm); ?>
                    <?php echo CHtml::beginForm('', 'post', array('enctype' => 'multipart/form-data')); ?>
                     <div class="control-group">
                        <label class="control-label" for="name"><?php echo Yii::t('common','您的名字'); ?><span class="required">*</span></label>
                        <div class="controls">
                          <?php echo CHtml::activeTextField($feedbackForm, 'name', array('class'=>"input-large",'type'=>'text')); ?>
                        </div>
                      </div>
                      <!-- Email -->
                      <div class="control-group">
                        <label class="control-label" for="phone"><?php echo Yii::t('common','您的手机'); ?><span class="required">*</span></label>
                        <div class="controls">
                          <?php echo CHtml::activeTextField($feedbackForm, 'phone', array('class'=>"input-large",'type'=>'text')); ?>
                        </div>
                      </div>
                      <!-- Email -->
                      <div class="control-group">
                        <label class="control-label" for="email"><?php echo Yii::t('common','您的Email'); ?><span class="required">*</span></label>
                        <div class="controls">
                          <?php echo CHtml::activeTextField($feedbackForm, 'email', array('class'=>"input-large",'type'=>'text')); ?>
                        </div>
                      </div>
                      
                      <!-- Comment -->
                      <div class="control-group">
                        <label class="control-label" for="content"><?php echo Yii::t('common','您的留言'); ?><span class="required">*</span></label>
                        <div class="controls">
                          <?php echo CHtml::activeTextArea($feedbackForm, 'content',array('rows' => '4', 'class' => 'input-xlarge' ,'placeholder'=>Yii::t('common','您的留言')));?>
                        </div>
                      </div>
                      <!-- Buttons -->
                      <div class="form-actions">
                         <!-- Buttons -->
                        <button type="submit" class="btn btn-success"><?php echo Yii::t('common','提 交'); ?></button>
                        <button type="reset" class="btn"><?php echo Yii::t('common','重 置'); ?></button>
                      </div>
                    <?php echo CHtml::endForm(); ?> 
                  </div>
               </div>
             </div>
             <div class="span6">
               <div class="well">
                  <!-- Address section -->
                 <h5><?php echo Yii::t('common','我们的地址'); ?></h5>
                 <hr />
                 <div class="address">
                     <address>
                        <?php echo Setting::getValueByCode('address', true); ?>
                     </address>
                      
                     <address>
                        <!-- Name -->
                        <h6><?php echo Yii::t('common','联系邮箱'); ?></h6>
                        <!-- Email -->
                        <a href="mailto:<?php echo Setting::getValueByCode('email', true); ?>"><?php echo Setting::getValueByCode('email', true); ?></a>
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
 
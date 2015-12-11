<?php $this->beginClip('extraHead'); ?>
<script type="text/javascript">
<!--
$(function(){
	$('.send-test-mail').click(function(){
		var e = this;
		var host = $('#mail_host_value').val();
		var username = $('#mail_username_value').val();
		var password = $('#mail_password_value').val();
		var from = $('#mail_from_value').val();
		var recipient = $('#mail_recipient').val();
		if (host.length == 0) {
			alert('请输入服务器地址');
			return false;
		} else if (username.length == 0) {
			alert('请输入用户名');
			return false;
		} else if (password.length == 0) {
			alert('请输入密码');
			return false;
		} else if (from.length == 0) {
			alert('请输入发件人电子邮箱');
			return false;
		} else if (recipient.length == 0) {
			alert('请输入测试邮件收件人电子邮箱');
			return false;			
		}

		$.ajax({
			type: 'POST',
			url: '<?php echo $this->createUrl("sendTestMail"); ?>',
			async : true,
			dataType: 'JSON',
			data: {
				host: host,
				username: username,
				password: password,
				from: from,
				recipient: recipient,
				rand: Math.random()
			},
			beforeSend: function() {
				$(e).attr('disabled', 'disabled');
				$(e).next().show();
			},
			complete: function() {
				$(e).removeAttr('disabled');
				$(e).next().hide();
			},
			success: function(data) {
				if (data.result) {
					alert('邮件已成功发送');
				} else {
					alert(data.message);
				}
			},
			error: function() {
				alert('发送邮件时出现错误 ，请联系管理员。');
			}
		});
	});
});
//-->
</script>
<?php $this->endClip(); ?>

<div class="heading">
	<h1><?php echo CHtml::image(Yii::app()->baseUrl . '/image/setting.png'); ?>系统设置</h1>
	<div class="buttons">
		<?php echo CHtml::link('<span>保存</span>', '#', array(
				'class' => 'button',
				'onclick' => "$('#form1').submit();return false;")); ?>
		<?php echo CHtml::link('<span>取消</span>', $returnUrl, array(
				'class' => 'button')); ?>
	</div>
</div>
<div class="content">
	<?php if (!$returnUrl = Yii::app()->request->getQuery('returnUrl'))
	$returnUrl = array('site/setting');
	?>
	<?php echo CHtml::form(array('', 'return_url' => $returnUrl), 'post',
		array('id' => 'form1', 'enctype' => 'multipart/form-data'));
	?>
	
	<?php ob_start(); ?>
	<table class="form" width="100%">
		<tr>
			<th width="160">系统维护</th>
			<td>
				<?php echo CHtml::checkBox('system_maintaining[value]',
		$models['system_maintaining']['value']);
				?>
				<?php echo Helper::fieldTips('打勾后，网站将显示 "系统维护中" 无法访问。'); ?>
			</td>
		</tr>
		<tr>
			<th>首页页面标题</th>
			<td>
				<?php echo CHtml::textField('home_title[value]',
		$models['home_title']['value'], array('size' => 80));
				?>
			</td>
		</tr>
		<tr>
			<th>首页内链关键词</th>
			<td>
				<?php echo CHtml::textField('home_internal_link_keywords[value]',
		$models['home_internal_link_keywords']['value'], array('size' => 80));
				?>
				<?php echo Helper::fieldTips('使用逗号 , 分隔关键词'); ?>
				<?php echo Helper::fieldTips('如果其他页面出现此关键词，其字符将被自动替换成本页面链接'); ?>
			</td>
		</tr>
		<tr>
			<th>内页页面标题</th>
			<td>
				<?php echo CHtml::textField('inside_title[value]',
		$models['inside_title']['value'], array('size' => 80));
				?>
			</td>
		</tr>	
				
		<tr>
			<th>版权信息</th>
			<td>
				<?php echo CHtml::textField('copyright[value]',
		$models['copyright']['value'], array('size' => 80));
				?>
			</td>
		</tr>
		<tr>
			<th>联系邮箱</th>
			<td>
				<?php echo CHtml::textField('email[value]',
		$models['email']['value'], array('size' => 80));
				?>
			</td>
		</tr>
		<tr>
			<th>地址信息</th>
			<td>
				<?php $this->widget('ext.tinymce.TinyMCEWidget', array(
						'name' => 'address[value]', 
						'value' => $models['address']['value'], 
						'htmlOptions' => array('cols' => 60, 'rows' => 5)
				)); ?>
			</td>
		</tr>	
		<tr>
			<th>页面关键词</th>
			<td>
				<?php echo CHtml::textArea('meta_keywords[value]',
		$models['meta_keywords']['value'], array('cols' => 60, 'rows' => 5));
				?>
			</td>
		</tr>
		<tr>
			<th>页面描述</th>
			<td>
				<?php echo CHtml::textArea('meta_description[value]',
		$models['meta_description']['value'], array('cols' => 60, 'rows' => 5));
				?>
			</td>
		</tr>
		<tr>
			<th>页脚脚本</th>
			<td>
				<?php echo CHtml::textArea('footer_js[value]',
		$models['footer_js']['value'], array('cols' => 60, 'rows' => 5));
				?>
			</td>
		</tr>
	
	</table>
	<?php $basic = ob_get_clean(); ?>
	
	<?php
$i18nTabs = array();
foreach (I18nHelper::getFrontendLanguages() as $lang => $prop)
	$i18nTabs[$prop['label']] = $this
			->renderPartial('settingI18n',
					array('models' => $models, 'lang' => $lang, 'prop' => $prop),
					true);
	?>
	
	<?php ob_start(); ?>
	<table class="form" width="100%">
		<tr>
			<th>robots.txt文件内容</th>
			<td>
				<?php echo CHtml::textArea('robots', $robots, array(
						'cols' => 100, 'rows' => 30)); ?>
			</td>
		</tr>
	</table>
	<?php $robots = ob_get_clean(); ?>

	<?php ob_start(); ?>
	<table class="form" width="100%">
		<tr>
			<th width="160">用户名</th>
			<td>
				<?php echo CHtml::textField('google_analytics_account[value]',
		$models['google_analytics_account']['value'], array('size' => 60));
				?>				
			</td>
		</tr>
		<tr>
			<th>密码</th>
			<td>
				<?php echo CHtml::passwordField(
		'google_analytics_password[value]',
		$models['google_analytics_password']['value']);
				?>				
			</td>
		</tr>
		<tr>
			<th>报表ID</th>
			<td>
				<?php echo CHtml::textField(
		'google_analytics_report_id[value]',
		$models['google_analytics_report_id']['value']);
				?>				
			</td>
		</tr>
	</table>
	<?php $googleAnalytics = ob_get_clean(); ?>
	
	<?php $this->widget('zii.widgets.jui.CJuiTabs', array(
			'tabs' => array(
				'基本设置' => $basic,
				// 'google设置'=> $googleAnalytics
			)
			+ $i18nTabs
	)); ?>
	
	<?php echo CHtml::endForm(); ?>
</div>
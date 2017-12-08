

	<?php echo $form->textFieldControlGroup($model,'title',array('class'=>'span8','maxlength'=>255)); ?>
    
    <?php echo $form->textFieldControlGroup($model,'default_street',array('maxlength'=>255, 'prepend' => 'ул.', 'span'=>8)); ?>
    
    

	<div class='control-group'>
		<?php echo CHtml::activeLabelEx($model, 'img_logo'); ?>
		<?php echo $form->fileField($model,'img_logo', array('class'=>'span3')); ?>
		<div class='img_preview'>
			<?php if ( !empty($model->img_logo) ) echo TbHtml::imageRounded( $model->imgBehaviorLogo->getImageUrl('small') ) ; ?>
			<span class='deletePhoto btn btn-danger btn-mini' data-modelname='Malls' data-attributename='Logo' <?php if(empty($model->img_logo)) echo "style='display:none;'"; ?>><i class='icon-remove icon-white'></i></span>
		</div>
		<?php echo $form->error($model, 'img_logo'); ?>
	</div>


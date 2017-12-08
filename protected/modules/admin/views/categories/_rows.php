	<?php echo $form->textFieldControlGroup($model,'title',array('class'=>'span8','maxlength'=>255)); ?>

	

	<div class='control-group'>
		<?php echo CHtml::activeLabelEx($model, 'img_category'); ?>
		<?php echo $form->fileField($model,'img_category', array('class'=>'span3')); ?>
		<div class='img_category'>
			<?php if ( !empty($model->img_category) ) echo TbHtml::imageRounded( $model->imgBehaviorCategory->getImageUrl('small') ) ; ?>
			<span class='deletePhoto btn btn-danger btn-mini' data-modelname='Categories' data-attributename='Preview' <?php if(empty($model->img_category)) echo "style='display:none;'"; ?>><i class='icon-remove icon-white'></i></span>
		</div>
		<?php echo $form->error($model, 'img_category'); ?>
	</div>

	<?php echo $form->dropDownListControlGroup($model, 'status', Categories::getStatusAliases(), array('class'=>'span8', 'displaySize'=>1)); ?>

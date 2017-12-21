	<?php echo $form->textFieldControlGroup($model,'firstname',array('class'=>'span8','maxlength'=>255)); ?>

	<?php echo $form->textFieldControlGroup($model,'lastname',array('class'=>'span8','maxlength'=>255)); ?>

	<div class='control-group'>
		<?php echo CHtml::activeLabelEx($model, 'img_avatar'); ?>
		<?php echo $form->fileField($model,'img_avatar', array('class'=>'span3')); ?>
		<div class='img_preview'>
			<?php if ( !empty($model->img_avatar) ) echo TbHtml::imageRounded( $model->imgBehaviorAvatar->getImageUrl('small') ) ; ?>
			<span class='deletePhoto btn btn-danger btn-mini' data-modelname='Users' data-attributename='Avatar' <?php if(empty($model->img_avatar)) echo "style='display:none;'"; ?>><i class='icon-remove icon-white'></i></span>
		</div>
		<?php echo $form->error($model, 'img_avatar'); ?>
	</div>

	<?php echo $form->dropDownListControlGroup($model, 'status', Users::getStatusAliases(), array('class'=>'span8', 'displaySize'=>1)); ?>
	<?php echo $form->textFieldControlGroup($model,'balance',array('class'=>'span8','maxlength'=>8)); ?>

	<?php echo $form->textFieldControlGroup($model,'login',array('class'=>'span8','maxlength'=>255)); ?>

	<?php echo $form->passwordFieldControlGroup($model,'password',array('class'=>'span8','maxlength'=>255)); ?>

	<?php echo $form->textFieldControlGroup($model,'rating',array('class'=>'span8')); ?>

	<?php echo $form->textFieldControlGroup($model,'address',array('class'=>'span8','maxlength'=>255)); ?>

	<?php echo $form->textFieldControlGroup($model,'zipcode',array('class'=>'span8','maxlength'=>25)); ?>

	<?php echo $form->textFieldControlGroup($model,'email',array('class'=>'span8','maxlength'=>255)); ?>

	<?php echo $form->textFieldControlGroup($model,'currency',array('class'=>'span8')); ?>

	<?php echo $form->textFieldControlGroup($model,'phone',array('class'=>'span8','maxlength'=>50)); ?>


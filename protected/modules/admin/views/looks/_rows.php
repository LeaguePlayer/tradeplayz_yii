	<div class='control-group'>
		<?php echo CHtml::activeLabelEx($model, 'dttm_date_create'); ?>
		<?php $this->widget('yiiwheels.widgets.datetimepicker.WhDateTimePicker', array(
			'model' => $model,
			'attribute' => 'dttm_date_create',
			'pluginOptions' => array(
				'format' => 'dd-MM-yyyy hh:mm',
				'language' => 'ru',
                'pickSeconds' => false,
			)
		)); ?>
		<?php echo $form->error($model, 'dttm_date_create'); ?>
	</div>

	<div class='control-group'>
		<?php echo CHtml::activeLabelEx($model, 'img_look'); ?>
		<?php echo $form->fileField($model,'img_look', array('class'=>'span3')); ?>
		<div class='img_preview'>
			<?php if ( !empty($model->img_look) ) echo TbHtml::imageRounded( $model->imgBehaviorLook->getImageUrl('small') ) ; ?>
			<span class='deletePhoto btn btn-danger btn-mini' data-modelname='Looks' data-attributename='Look' <?php if(empty($model->img_look)) echo "style='display:none;'"; ?>><i class='icon-remove icon-white'></i></span>
		</div>
		<?php echo $form->error($model, 'img_look'); ?>
	</div>

	<?php echo $form->dropDownListControlGroup($model, 'status', Looks::getStatusAliases(), array('class'=>'span8', 'displaySize'=>1)); ?>
	<?php echo $form->textFieldControlGroup($model,'user_id',array('class'=>'span8','maxlength'=>255)); ?>
	<?php echo $form->textFieldControlGroup($model,'place_name_foursquare',array('class'=>'span8','maxlength'=>255)); ?>
	<?php echo $form->textFieldControlGroup($model,'id_foursquare',array('class'=>'span8','maxlength'=>255)); ?>


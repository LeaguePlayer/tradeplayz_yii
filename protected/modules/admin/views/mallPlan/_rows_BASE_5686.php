	<?php // echo $form->textFieldControlGroup($model,'malls_id',array('class'=>'span8')); ?>

	<?php echo $form->textFieldControlGroup($model,'floor_room',array('class'=>'span8')); ?>

	<div class='control-group'>
		<?php echo CHtml::activeLabelEx($model, 'img_map'); ?>
		<?php echo $form->fileField($model,'img_map', array('class'=>'span3')); ?>
		<div class='img_preview'>
			<?php if ( !empty($model->img_map) ) echo TbHtml::imageRounded( $model->imgBehaviorMap->getImageUrl('small') ) ; ?>
			<span class='deletePhoto btn btn-danger btn-mini' data-modelname='MallPlan' data-attributename='Map' <?php if(empty($model->img_map)) echo "style='display:none;'"; ?>><i class='icon-remove icon-white'></i></span>
		</div>
		<?php echo $form->error($model, 'img_map'); ?>
	</div>

	<?php // echo $form->textAreaControlGroup($model,'json_areas',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->dropDownListControlGroup($model, 'status', MallPlan::getStatusAliases(), array('class'=>'span8', 'displaySize'=>1)); ?>

<fieldset class="rgb_control control-group">
<legend>Управление RGB</legend>
	
	<div class="pick_color">
		<label for="rgb_r">R</label>
		<input type="text" value="<? echo $model->color_rgb['R']; ?>" name="RGB[R]" id="rgb_r" maxlength="3" />
	</div>

	<div class="pick_color">
		<label for="rgb_r">G</label>
		<input type="text" value="<? echo $model->color_rgb['G']; ?>" name="RGB[G]" id="rgb_g" maxlength="3" />
	</div>

	<div class="pick_color">
		<label for="rgb_r">B</label>
		<input type="text" value="<? echo $model->color_rgb['B']; ?>" name="RGB[B]" id="rgb_b" maxlength="3" />
	</div>

</fieldset>

<fieldset class="rgb_control control-group">
<legend>Управление изображениями пакетов</legend>

	<div class='control-group'>
		<?php echo CHtml::activeLabelEx($model, 'img_discount_preview'); ?>
		<?php echo $form->fileField($model,'img_discount_preview', array('class'=>'span3')); ?>
		<div class='img_discount_preview'>
			<?php if ( !empty($model->img_discount_preview) ) echo TbHtml::imageRounded( $model->imgBehaviorDiscountPreview->getImageUrl('small') ) ; ?>
			<span class='deletePhoto btn btn-danger btn-mini' data-modelname='Categories' data-attributename='Preview' <?php if(empty($model->img_discount_preview)) echo "style='display:none;'"; ?>><i class='icon-remove icon-white'></i></span>
		</div>
		<?php echo $form->error($model, 'img_discount_preview'); ?>
	</div>

	<div class='control-group'>
		<?php echo CHtml::activeLabelEx($model, 'img_preview'); ?>
		<?php echo $form->fileField($model,'img_preview', array('class'=>'span3')); ?>
		<div class='img_preview'>
			<?php if ( !empty($model->img_preview) ) echo TbHtml::imageRounded( $model->imgBehaviorPreview->getImageUrl('small') ) ; ?>
			<span class='deletePhoto btn btn-danger btn-mini' data-modelname='Categories' data-attributename='Preview' <?php if(empty($model->img_preview)) echo "style='display:none;'"; ?>><i class='icon-remove icon-white'></i></span>
		</div>
		<?php echo $form->error($model, 'img_preview'); ?>
	</div>

</fieldset>
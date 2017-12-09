	<div class='control-group'>
		<?php echo CHtml::activeLabelEx($model, 'dttm_begin'); ?>
		<?php $this->widget('yiiwheels.widgets.datetimepicker.WhDateTimePicker', array(
			'model' => $model,
			'attribute' => 'dttm_begin',
			'pluginOptions' => array(
				'format' => 'dd-MM-yyyy hh:mm',
				'language' => 'ru',
                'pickSeconds' => false,
			)
		)); ?>
		<?php echo $form->error($model, 'dttm_begin'); ?>
	</div>

	<?php echo $form->dropDownListControlGroup($model, 'status', Tournaments::getStatusAliases(), array('class'=>'span8', 'displaySize'=>1)); ?>
	<?php echo $form->textFieldControlGroup($model,'prize_places',array('class'=>'span8')); ?>

	<?php echo $form->textFieldControlGroup($model,'byuin',array('class'=>'span8','maxlength'=>8)); ?>

	<?php echo $form->dropDownListControlGroup($model, 'id_format', Tournaments::getFormats(), array('class'=>'span8', 'displaySize'=>1)); ?>

	<?php echo $form->dropDownListControlGroup($model, 'id_currency', Currency::getTournamentAllowedCurrencies(), array('class'=>'span8', 'displaySize'=>1)); ?>



	<?
		$place = "rulezzz";
		$name_place = "Правила";
	?>
		<div class='control-group'>
				<label class="control-label"><? echo $name_place; ?></label>

				<?
					$this->widget('appext.ckeditor.CKEditorWidget', 
						array(
							'name' => "ContentLang[{$place}]",
							'config' => array('width' => '100%', 'height'=>'280'),
							'value'=>$model->$place->text,
						)
					);
				?>


		</div>





	<?php echo $form->textFieldControlGroup($model,'prize_pool',array('class'=>'span8','maxlength'=>8)); ?>

	<div class='control-group'>
		<?php echo CHtml::activeLabelEx($model, 'dttm_finish'); ?>
		<?php $this->widget('yiiwheels.widgets.datetimepicker.WhDateTimePicker', array(
			'model' => $model,
			'attribute' => 'dttm_finish',
			'pluginOptions' => array(
				'format' => 'dd-MM-yyyy hh:mm',
				'language' => 'ru',
                'pickSeconds' => false,
			)
		)); ?>
		<?php echo $form->error($model, 'dttm_finish'); ?>
	</div>

	<?php echo $form->textFieldControlGroup($model,'begin_stack',array('class'=>'span8','maxlength'=>8)); ?>


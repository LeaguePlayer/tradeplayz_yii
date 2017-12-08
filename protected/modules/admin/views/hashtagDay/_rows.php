	<div class='control-group'>
		<?php echo CHtml::activeLabelEx($model, 'dt_date_begin'); ?>
		<?php $this->widget('yiiwheels.widgets.datetimepicker.WhDateTimePicker', array(
			'model' => $model,
			'attribute' => 'dt_date_begin',
			'pluginOptions' => array(
				'format' => 'dd-MM-yyyy',
				'language' => 'ru',
                'pickSeconds' => false,
                'pickTime' => false
			)
		)); ?>
		<?php echo $form->error($model, 'dt_date_begin'); ?>
	</div>

	<div class='control-group'>
		<?php echo CHtml::activeLabelEx($model, 'dt_date_finish'); ?>
		<?php $this->widget('yiiwheels.widgets.datetimepicker.WhDateTimePicker', array(
			'model' => $model,
			'attribute' => 'dt_date_finish',
			'pluginOptions' => array(
				'format' => 'dd-MM-yyyy',
				'language' => 'ru',
                'pickSeconds' => false,
                'pickTime' => false
			)
		)); ?>
		<?php echo $form->error($model, 'dt_date_finish'); ?>
	</div>

	<?php echo $form->textFieldControlGroup($model,'title',array('maxlength'=>255, 'prepend' => '#', 'span' => 8)); ?>

	<?php // echo $form->dropDownListControlGroup($model,'malls_id', Malls::getMalls(true) ,array('class'=>'span8')); ?>

	<?php echo $form->dropDownListControlGroup($model, 'status', HashtagDay::getStatusAliases(), array('class'=>'span8', 'displaySize'=>1)); ?>

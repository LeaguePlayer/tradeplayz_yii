	<?php echo $form->dropDownListControlGroup($model,'id_type', EventsStock::getTypes(),array('class'=>'span8')); ?>

	<?php echo $form->textFieldControlGroup($model,'title',array('class'=>'span8','maxlength'=>255)); ?>

	<?php echo $form->textAreaControlGroup($model,'description',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

<div class="control-group">
	<?php
		 echo CHtml::label('Привязка к магазину','Portfolio_services');
		 $this->widget('ext.select2.ESelect2',array(
		   'model'=>$model,
		   'attribute'=>'shops_id',
		   'data'=>$data['shops'],
		   'htmlOptions'=>array(
		   ),
		   'options'=>array(
		     'placeholder'=>'Не выбран',
		     'width'=>'66%',
		   ),
		 ));
	?>
</div>

<div class="control-group">
	<?php
		 echo CHtml::label('Привязка к ТРЦ','Portfolio_services');
		 $this->widget('ext.select2.ESelect2',array(
		   'model'=>$model,
		   'attribute'=>'malls_id',
		   'data'=>$data['malls'],
		   'htmlOptions'=>array(
		   ),
		   'options'=>array(
		     'placeholder'=>'Не выбран',
		     'width'=>'66%',
		   ),
		 ));
	?>
</div>
 


<div class="input-append">



	<div class='control-group'>
		<?php echo CHtml::activeLabelEx($model, 'dttm_date_start'); ?>
		<?php $this->widget('yiiwheels.widgets.datetimepicker.WhDateTimePicker', array(
			'model' => $model,
			'attribute' => 'dttm_date_start',
			'pluginOptions' => array(
				'format' => 'dd-MM-yyyy hh:mm',
				'language' => 'ru',
                'pickSeconds' => false,
			)
		)); ?>
		<?php echo $form->error($model, 'dttm_date_start'); ?>
	</div>

	<div class='control-group'>
		<?php echo CHtml::activeLabelEx($model, 'dttm_date_finish'); ?>
		<?php $this->widget('yiiwheels.widgets.datetimepicker.WhDateTimePicker', array(
			'model' => $model,
			'attribute' => 'dttm_date_finish',
			'pluginOptions' => array(
				'format' => 'dd-MM-yyyy hh:mm',
				'language' => 'ru',
                'pickSeconds' => false,
			)
		)); ?>
		<?php echo $form->error($model, 'dttm_date_finish'); ?>
	</div>

	<div class='control-group'>
		<?php echo CHtml::activeLabelEx($model, 'dttm_date_hide'); ?>
		<?php $this->widget('yiiwheels.widgets.datetimepicker.WhDateTimePicker', array(
			'model' => $model,
			'attribute' => 'dttm_date_hide',
			'pluginOptions' => array(
				'format' => 'dd-MM-yyyy hh:mm',
				'language' => 'ru',
                'pickSeconds' => false,
			)
		)); ?>
		<?php echo $form->error($model, 'dttm_date_hide'); ?>
	</div>

	<?php echo $form->dropDownListControlGroup($model, 'status', EventsStock::getStatusAliases(), array('class'=>'span8', 'displaySize'=>1)); ?>
	





	

</div>


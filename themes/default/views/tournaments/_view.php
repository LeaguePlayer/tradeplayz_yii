<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dttm_begin')); ?>:</b>
	<?php echo CHtml::encode($data->dttm_begin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('prize_places')); ?>:</b>
	<?php echo CHtml::encode($data->prize_places); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('byuin')); ?>:</b>
	<?php echo CHtml::encode($data->byuin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_format')); ?>:</b>
	<?php echo CHtml::encode($data->id_format); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_currency')); ?>:</b>
	<?php echo CHtml::encode($data->id_currency); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('prize_pool')); ?>:</b>
	<?php echo CHtml::encode($data->prize_pool); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dttm_finish')); ?>:</b>
	<?php echo CHtml::encode($data->dttm_finish); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('begin_stack')); ?>:</b>
	<?php echo CHtml::encode($data->begin_stack); ?>
	<br />

	*/ ?>

</div>
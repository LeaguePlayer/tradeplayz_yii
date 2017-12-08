<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_type')); ?>:</b>
	<?php echo CHtml::encode($data->id_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dttm_date_start')); ?>:</b>
	<?php echo CHtml::encode($data->dttm_date_start); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dttm_date_finish')); ?>:</b>
	<?php echo CHtml::encode($data->dttm_date_finish); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dttm_date_hide')); ?>:</b>
	<?php echo CHtml::encode($data->dttm_date_hide); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('shops_id')); ?>:</b>
	<?php echo CHtml::encode($data->shops_id); ?>
	<br />

	*/ ?>

</div>
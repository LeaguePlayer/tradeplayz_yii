<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dt_date_begin')); ?>:</b>
	<?php echo CHtml::encode($data->dt_date_begin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dt_date_finish')); ?>:</b>
	<?php echo CHtml::encode($data->dt_date_finish); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('malls_id')); ?>:</b>
	<?php echo CHtml::encode($data->malls_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />


</div>
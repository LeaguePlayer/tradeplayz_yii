<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('malls_id')); ?>:</b>
	<?php echo CHtml::encode($data->malls_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('floor_room')); ?>:</b>
	<?php echo CHtml::encode($data->floor_room); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('img_map')); ?>:</b>
	<?php echo CHtml::encode($data->img_map); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('json_areas')); ?>:</b>
	<?php echo CHtml::encode($data->json_areas); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />


</div>
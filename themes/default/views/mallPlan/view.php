<?php
$this->breadcrumbs=array(
	'Mall Plans'=>array('index'),
	$model->id,
);

<h1>View MallPlan #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'malls_id',
		'floor_room',
		'img_map',
		'json_areas',
		'status',
	),
)); ?>

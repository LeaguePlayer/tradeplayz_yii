<?php
$this->breadcrumbs=array(
	'Apppages'=>array('index'),
	$model->id,
);

<h1>View Apppage #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'meta_alias',
		'create_time',
		'update_time',
	),
)); ?>

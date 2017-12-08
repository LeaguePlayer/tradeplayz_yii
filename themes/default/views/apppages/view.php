<?php
$this->breadcrumbs=array(
	'Apppages'=>array('index'),
	$model->title,
);

<h1>View Apppages #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'wswg_body',
		'meta_alias',
		'create_time',
		'update_time',
	),
)); ?>

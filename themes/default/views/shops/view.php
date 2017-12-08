<?php
$this->breadcrumbs=array(
	'Shops'=>array('index'),
	$model->title,
);

<h1>View Shops #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'categories_id',
		'status',
		'homepage',
	),
)); ?>

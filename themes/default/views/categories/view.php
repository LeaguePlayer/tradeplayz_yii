<?php
$this->breadcrumbs=array(
	'Categories'=>array('index'),
	$model->title,
);

<h1>View Categories #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'img_preview',
		'status',
	),
)); ?>

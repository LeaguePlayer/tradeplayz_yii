<?php
$this->breadcrumbs=array(
	'Looks'=>array('index'),
	$model->id,
);

<h1>View Looks #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'dttm_date_create',
		'img_look',
		'status',
		'user_id',
	),
)); ?>

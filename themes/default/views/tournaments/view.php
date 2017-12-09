<?php
$this->breadcrumbs=array(
	'Tournaments'=>array('index'),
	$model->id,
);

<h1>View Tournaments #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'dttm_begin',
		'status',
		'prize_places',
		'byuin',
		'id_format',
		'id_currency',
		'prize_pool',
		'dttm_finish',
		'begin_stack',
	),
)); ?>

<?php
$this->breadcrumbs=array(
	'Events Stocks'=>array('index'),
	$model->title,
);

<h1>View Eventsstock #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_type',
		'title',
		'description',
		'dttm_date_start',
		'dttm_date_finish',
		'dttm_date_hide',
		'status',
		'shops_id',
	),
)); ?>

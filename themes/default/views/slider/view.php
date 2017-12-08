<?php
$this->breadcrumbs=array(
	'Sliders'=>array('index'),
	$model->title,
);

<h1>View Slider #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'post_type',
		'post_id',
		'title',
		'sub_title',
		'img_preview',
		'status',
		'sort',
		'create_time',
		'update_time',
	),
)); ?>

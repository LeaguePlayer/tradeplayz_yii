<?php
$this->breadcrumbs=array(
	'Hashtag Days'=>array('index'),
	$model->title,
);

<h1>View HashtagDay #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'dt_date_begin',
		'dt_date_finish',
		'title',
		'malls_id',
		'status',
	),
)); ?>

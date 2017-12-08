<?php
$this->breadcrumbs=array(
	'Comments'=>array('index'),
	$model->id,
);

<h1>View Comments #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'looks_id',
		'comment_text',
		'date_create',
		'users_id',
		'status',
	),
)); ?>

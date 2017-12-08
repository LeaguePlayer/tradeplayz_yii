<?php
$this->breadcrumbs=array(
	'Malls'=>array('index'),
	$model->title,
);

<h1>View Malls #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_type',
		'title',
		'img_logo',
	),
)); ?>

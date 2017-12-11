<?php
$this->breadcrumbs=array(
	'Faqs'=>array('index'),
	$model->id,
);

<h1>View Faq #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'status',
	),
)); ?>

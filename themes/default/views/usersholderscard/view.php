<?php
$this->breadcrumbs=array(
	'Usersholderscards'=>array('index'),
	$model->id,
);

<h1>View Usersholderscard #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_user',
		'id_card',
		'card_number',
		'create_time',
		'update_time',
	),
)); ?>

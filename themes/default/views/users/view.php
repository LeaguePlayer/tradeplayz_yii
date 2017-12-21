<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->id,
);

<h1>View Users #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'firstname',
		'lastname',
		'img_avatar',
		'status',
		'balance',
		'login',
		'password',
		'rating',
		'address',
		'zipcode',
		'email',
		'currency',
		'phone',
	),
)); ?>

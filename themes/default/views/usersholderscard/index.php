<?php
/* @var $this UsersholderscardController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Usersholderscards',
);

$this->menu=array(
	array('label'=>'Create Usersholderscard', 'url'=>array('create')),
	array('label'=>'Manage Usersholderscard', 'url'=>array('admin')),
);
?>

<h1>Usersholderscards</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

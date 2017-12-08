<?php
/* @var $this MallsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Malls',
);

$this->menu=array(
	array('label'=>'Create Malls', 'url'=>array('create')),
	array('label'=>'Manage Malls', 'url'=>array('admin')),
);
?>

<h1>Malls</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

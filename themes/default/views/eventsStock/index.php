<?php
/* @var $this EventsstockController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Events Stocks',
);

$this->menu=array(
	array('label'=>'Create Eventsstock', 'url'=>array('create')),
	array('label'=>'Manage Eventsstock', 'url'=>array('admin')),
);
?>

<h1>Events Stocks</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

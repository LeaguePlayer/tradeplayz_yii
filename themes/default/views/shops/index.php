<?php
/* @var $this ShopsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Shops',
);

$this->menu=array(
	array('label'=>'Create Shops', 'url'=>array('create')),
	array('label'=>'Manage Shops', 'url'=>array('admin')),
);
?>

<h1>Shops</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

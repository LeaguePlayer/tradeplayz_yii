<?php
/* @var $this ApppagesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Apppages',
);

$this->menu=array(
	array('label'=>'Create Apppages', 'url'=>array('create')),
	array('label'=>'Manage Apppages', 'url'=>array('admin')),
);
?>

<h1>Apppages</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

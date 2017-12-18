<?php
/* @var $this ApppageController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Apppages',
);

$this->menu=array(
	array('label'=>'Create Apppage', 'url'=>array('create')),
	array('label'=>'Manage Apppage', 'url'=>array('admin')),
);
?>

<h1>Apppages</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

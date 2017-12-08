<?php
/* @var $this LooksController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Looks',
);

$this->menu=array(
	array('label'=>'Create Looks', 'url'=>array('create')),
	array('label'=>'Manage Looks', 'url'=>array('admin')),
);
?>

<h1>Looks</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

<?php
/* @var $this TournamentsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tournaments',
);

$this->menu=array(
	array('label'=>'Create Tournaments', 'url'=>array('create')),
	array('label'=>'Manage Tournaments', 'url'=>array('admin')),
);
?>

<h1>Tournaments</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

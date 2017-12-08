<?php
/* @var $this MallPlanController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Mall Plans',
);

$this->menu=array(
	array('label'=>'Create MallPlan', 'url'=>array('create')),
	array('label'=>'Manage MallPlan', 'url'=>array('admin')),
);
?>

<h1>Mall Plans</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

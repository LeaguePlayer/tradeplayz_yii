<?php
/* @var $this HashtagDayController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Hashtag Days',
);

$this->menu=array(
	array('label'=>'Create HashtagDay', 'url'=>array('create')),
	array('label'=>'Manage HashtagDay', 'url'=>array('admin')),
);
?>

<h1>Hashtag Days</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

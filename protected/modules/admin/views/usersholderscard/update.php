<?php
$this->breadcrumbs=array(
	"{$model->translition()}"=>array('list'),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('list')),
);
?>

<h1><?php echo $model->translition(); ?> - Редактирование</h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
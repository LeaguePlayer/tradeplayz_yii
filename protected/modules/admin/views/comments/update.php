<?php
$this->breadcrumbs=array(
	"{$model->translition()}"=>array('list','id_look'=>$model->looks_id),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('list', 'id_look'=>$model->looks_id)),
);
?>

<h1><?php echo $model->translition(); ?> - Редактирование</h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
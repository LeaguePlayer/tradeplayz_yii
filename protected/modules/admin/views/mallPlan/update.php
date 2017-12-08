<?php
$this->breadcrumbs=array(
	"{$model->mall->translition()}"=>array('malls/list'),
	"{$model->translition()}"=>array('/admin/mallplan/list/malls_id/'.$model->malls_id),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('/admin/mallplan/list/malls_id/'.$model->malls_id)),
	array('label'=>'Добавить','url'=>array('create')),
);
?>

<h1><?php echo $model->translition(); ?> - Редактирование</h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
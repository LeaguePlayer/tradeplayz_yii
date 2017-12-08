<?php
$this->breadcrumbs=array(
	"{$model->mall->translition()}"=>array('malls/list'),
	"{$model->translition()}"=>array('/admin/mallplan/list/malls_id/'.$model->malls_id),
	'Создание',
);

$this->menu=array(
	array('label'=>'Список','url'=>array('/admin/mallplan/list/malls_id/'.$model->malls_id)),
);
?>

<h1><?php echo $model->translition(); ?> - Добавление</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
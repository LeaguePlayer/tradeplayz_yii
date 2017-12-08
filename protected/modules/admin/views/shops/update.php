<?php
$this->breadcrumbs=array(
	"{$model->translition()}"=>array('list','Shops_page'=>$Shops_page),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('list','Shops_page'=>$Shops_page)),
	array('label'=>'Добавить','url'=>array('create')),
);
?>

<h1><?php echo $model->translition(); ?> - Редактирование</h1>

<?php echo $this->renderPartial('_form',array('model'=>$model, 'data'=>$data, 'Shops_page'=>$Shops_page)); ?>
<?php
$this->breadcrumbs=array(
	"PUSH"=>array('list'),
	'Создание',
);

$this->menu=array(
	array('label'=>'Список','url'=>array('list')),
);
?>

<h1>Добавление позиции</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
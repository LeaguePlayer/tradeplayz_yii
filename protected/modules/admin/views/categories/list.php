<?php
$this->menu=array(
	array('label'=>'Добавить','url'=>array('create')),
);
?>

<h1>Управление <?php echo $model->translition(); ?></h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'categories-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'type'=>TbHtml::GRID_TYPE_HOVER,
    'afterAjaxUpdate'=>"function() {sortGrid('categories')}",
    'rowHtmlOptionsExpression'=>'array(
        "id"=>"items[]_".$data->id,
        "class"=>"status_".(isset($data->status) ? $data->status : ""),
    )',
	'columns'=>array(
		'title',
		array(
			'header'=>'Фото',
			'type'=>'raw',
			'value'=>'TbHtml::imageCircle($data->imgBehaviorPreview->getImageUrl("icon"))'
		),
		array(
			'name'=>'status',
			'type'=>'raw',
			'value'=>'Categories::getStatusAliases($data->status)',
			'filter'=>Categories::getStatusAliases()
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>"{update} {delete}"
		),
	),
)); ?>

<?php if($model->hasAttribute('sort')) Yii::app()->clientScript->registerScript('sortGrid', 'sortGrid("categories");', CClientScript::POS_END) ;?>
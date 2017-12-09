<?php
$this->menu=array(
	array('label'=>'Добавить','url'=>array('create')),
);
?>

<h1>Управление <?php echo $model->translition(); ?></h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'tournaments-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'type'=>TbHtml::GRID_TYPE_HOVER,
    'afterAjaxUpdate'=>"function() {sortGrid('tournaments')}",
    'rowHtmlOptionsExpression'=>'array(
        "id"=>"items[]_".$data->id,
        "class"=>"status_".(isset($data->status) ? $data->status : ""),
    )',
	'columns'=>array(
		array(
			'name'=>'dttm_begin',
			'type'=>'raw',
			'value'=>'SiteHelper::russianDate($data->dttm_begin)'
		),
		array(
			'name'=>'status',
			'type'=>'raw',
			'value'=>'Tournaments::getStatusAliases($data->status)',
			'filter'=>Tournaments::getStatusAliases()
		),
		'prize_places',
		'byuin',
		'id_format',
		'id_currency',
		'prize_pool',
		array(
			'name'=>'dttm_finish',
			'type'=>'raw',
			'value'=>'SiteHelper::russianDate($data->dttm_finish)'
		),
		'begin_stack',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>

<?php if($model->hasAttribute('sort')) Yii::app()->clientScript->registerScript('sortGrid', 'sortGrid("tournaments");', CClientScript::POS_END) ;?>
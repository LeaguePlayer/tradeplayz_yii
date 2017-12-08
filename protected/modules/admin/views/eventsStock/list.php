<?php
$this->menu=array(
	array('label'=>'Добавить','url'=>array('create')),
);
?>

<h1>Управление <?php echo $model->translition(); ?></h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'events-stock-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'type'=>TbHtml::GRID_TYPE_HOVER,
    'afterAjaxUpdate'=>"function() {sortGrid('eventsstock')}",
    'rowHtmlOptionsExpression'=>'array(
        "id"=>"items[]_".$data->id,
        "class"=>"status_".(isset($data->status) ? $data->status : ""),
    )',
	'columns'=>array(
		array(
			'name'=>'id_type',
			'type'=>'raw',
			'value'=>'EventsStock::getTypes($data->id_type)',
			'filter'=>EventsStock::getTypes()
		),
		'title',
		array(
			'name'=>'dttm_date_start',
			'type'=>'raw',
			'value'=>'SiteHelper::russianDate($data->dttm_date_start)'
		),
		array(
			'name'=>'dttm_date_finish',
			'type'=>'raw',
			'value'=>'SiteHelper::russianDate($data->dttm_date_finish)'
		),
		array(
			'name'=>'dttm_date_hide',
			'type'=>'raw',
			'value'=>'SiteHelper::russianDate($data->dttm_date_hide)'
		),
		array(
			'name'=>'status',
			'type'=>'raw',
			'value'=>'EventsStock::getStatusAliases($data->status)',
			'filter'=>EventsStock::getStatusAliases()
		),
		array(
			'name'=>'shops_id',
			'type'=>'raw',
			'value'=>'$data->shop->title',
		),
		array(
			'name'=>'malls_id',
			'type'=>'raw',
			'value'=>'$data->mall->title',
			),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>"{update} {delete}"
		),
	),
)); ?>

<?php if($model->hasAttribute('sort')) Yii::app()->clientScript->registerScript('sortGrid', 'sortGrid("eventsstock");', CClientScript::POS_END) ;?>
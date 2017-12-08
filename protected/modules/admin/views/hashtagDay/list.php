<?php
$this->menu=array(
	array('label'=>'Добавить','url'=>array('create')),
);
?>

<h1>Управление <?php echo $model->translition(); ?></h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'hashtag-day-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'type'=>TbHtml::GRID_TYPE_HOVER,
    'afterAjaxUpdate'=>"function() {sortGrid('hashtagday')}",
    'rowHtmlOptionsExpression'=>'array(
        "id"=>"items[]_".$data->id,
        "class"=>"status_".(isset($data->status) ? $data->status : ""),
    )',
	'columns'=>array(
		array(
			'name'=>'dt_date_begin',
			'type'=>'raw',
			'value'=>'SiteHelper::russianDate($data->dt_date_begin)'
		),
		array(
			'name'=>'dt_date_finish',
			'type'=>'raw',
			'value'=>'SiteHelper::russianDate($data->dt_date_finish)'
		),
		'title',
		// 'malls_id',
		array(
			'name'=>'status',
			'type'=>'raw',
			'value'=>'HashtagDay::getStatusAliases($data->status)',
			'filter'=>HashtagDay::getStatusAliases()
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update} {delete}',
		),
	),
)); ?>

<?php if($model->hasAttribute('sort')) Yii::app()->clientScript->registerScript('sortGrid', 'sortGrid("hashtagday");', CClientScript::POS_END) ;?>
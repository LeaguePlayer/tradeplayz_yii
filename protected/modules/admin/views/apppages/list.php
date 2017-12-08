<h1>Управление <?php echo $model->translition(); ?></h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'apppages-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'type'=>TbHtml::GRID_TYPE_HOVER,
    'afterAjaxUpdate'=>"function() {sortGrid('apppages')}",
    'rowHtmlOptionsExpression'=>'array(
        "id"=>"items[]_".$data->id,
        "class"=>"status_".(isset($data->status) ? $data->status : ""),
    )',
	'columns'=>array(
		'title',
		'meta_alias',
		array(
			'name'=>'create_time',
			'type'=>'raw',
			'value'=>'$data->create_time ? SiteHelper::russianDate($data->create_time).\' в \'.date(\'H:i\', strtotime($data->create_time)) : ""'
		),
		array(
			'name'=>'update_time',
			'type'=>'raw',
			'value'=>'$data->update_time ? SiteHelper::russianDate($data->update_time).\' в \'.date(\'H:i\', strtotime($data->update_time)) : ""'
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}',
		),
	),
)); ?>

<?php if($model->hasAttribute('sort')) Yii::app()->clientScript->registerScript('sortGrid', 'sortGrid("apppages");', CClientScript::POS_END) ;?>
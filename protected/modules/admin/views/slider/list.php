<?php
$this->menu=array(
	array('label'=>'Добавить','url'=>array('create')),
);
?>

<h1>Управление <?php echo $model->translition(); ?></h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'slider-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'type'=>TbHtml::GRID_TYPE_HOVER,
    'afterAjaxUpdate'=>"function() {sortGrid('slider')}",
    'rowHtmlOptionsExpression'=>'array(
        "id"=>"items[]_".$data->id,
        "class"=>"status_".(isset($data->status) ? $data->status : ""),
    )',
	'columns'=>array(
		// 'post_type',
		// 'post_id',
		array(
			'header'=>'Фото',
			'type'=>'raw',
			'value'=>'TbHtml::imageCircle($data->imgBehaviorPreview->getImageUrl("icon"))'
		),
		'title',
		'sub_title',
		
		array(
			'name'=>'status',
			'type'=>'raw',
			'value'=>'Slider::getStatusAliases($data->status)',
			'filter'=>Slider::getStatusAliases()
		),
		// 'sort',
		// array(
		// 	'name'=>'create_time',
		// 	'type'=>'raw',
		// 	'value'=>'$data->create_time ? SiteHelper::russianDate($data->create_time).\' в \'.date(\'H:i\', strtotime($data->create_time)) : ""'
		// ),
		array(
			'name'=>'update_time',
			'type'=>'raw',
			'value'=>'$data->update_time ? SiteHelper::russianDate($data->update_time).\' в \'.date(\'H:i\', strtotime($data->update_time)) : ""'
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update} {delete}',
		),
	),
)); ?>

<?php if($model->hasAttribute('sort')) Yii::app()->clientScript->registerScript('sortGrid', 'sortGrid("slider");', CClientScript::POS_END) ;?>
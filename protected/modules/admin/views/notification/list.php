<?php
$this->menu=array(
	array('label'=>'Добавить','url'=>array('create')),
);
?>

<h1>Push-уведомления</h1>

<h4>приложение установлено на <?php echo UserDevices::getCountTokens();?> устройствах</h4>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'notification-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'type'=>TbHtml::GRID_TYPE_HOVER,
    'afterAjaxUpdate'=>"function() {sortGrid('notification')}",
    'rowHtmlOptionsExpression'=>'array(
        "id"=>"items[]_".$data->id,
        "class"=>"status_".(isset($data->id) ? $data->id : ""),
    )',
	'columns'=>array(
		// array(
		// 	'name'=>'id',
		// 	'type'=>'raw',
		// 	'value'=>'$data->id',
		// ),
		array(
			'name'=>'text',
			'type'=>'raw',
			'value'=>'$data->text',
			// 'value'=>'CHtml::link($data->text, array("/admin/notification/update/id/{$data->id}"))',
			
		),
		
		array(
			'name'=>'create_date',
			'type'=>'raw',
			'value'=>'date($data->create_date)'
		),

		// array(
		// 	'class'=>'bootstrap.widgets.TbButtonColumn',
		// 	'template'=>"{update} {delete}",
		// ),
	),
)); ?>

<?php if($model->hasAttribute('sort')) Yii::app()->clientScript->registerScript('sortGrid', 'sortGrid("notification");', CClientScript::POS_END) ;?>
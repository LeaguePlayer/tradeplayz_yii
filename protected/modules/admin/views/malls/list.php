<?php
$this->menu=array(
	array('label'=>'Добавить','url'=>array('create')),
);
?>

<h1>Управление <?php echo $model->translition(); ?></h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'malls-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'type'=>TbHtml::GRID_TYPE_HOVER,
    'afterAjaxUpdate'=>"function() {sortGrid('malls')}",
    'rowHtmlOptionsExpression'=>'array(
        "id"=>"items[]_".$data->id,
        "class"=>"status_".(isset($data->status) ? $data->status : ""),
    )',
	'columns'=>array(
		// 'id_type',
		
		 array(
            'name'=>'title',
            'type'=>'raw',
            'value'=>'TbHtml::link($data->title, array("/admin/mallplan/list/", "malls_id"=>$data->id))'
        ),
		array(
			'header'=>'Фото',
			'type'=>'raw',
			'value'=>'TbHtml::imageCircle($data->imgBehaviorLogo->getImageUrl("icon"))'
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>"{update} {delete}"
		),
	),
)); ?>

<?php if($model->hasAttribute('sort')) Yii::app()->clientScript->registerScript('sortGrid', 'sortGrid("malls");', CClientScript::POS_END) ;?>
<?php
$this->menu=array(
	array('label'=>'Вернуться к лукам','url'=>array('/admin/looks')),
	// array('label'=>'Добавить','url'=>array('create')),
);
?>

<h1>Управление комментариями</h1>

<div class='control-group'>
		
		<div class='img_preview'>
			<?php if ( !empty($model->img_look) ) echo TbHtml::imageRounded( $model->imgBehaviorLook->getImageUrl('small') ) ; ?>
		</div>
		
	</div>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'comments-grid',
	'dataProvider'=>$comments_finder->search(),
	'filter'=>$comments_finder,
	'type'=>TbHtml::GRID_TYPE_HOVER,
    'afterAjaxUpdate'=>"function() {sortGrid('comments')}",
    'rowHtmlOptionsExpression'=>'array(
        "id"=>"items[]_".$data->id,
        "class"=>"status_".(isset($data->status) ? $data->status : ""),
    )',
	'columns'=>array(
		
		'norm_comment',
		array(
			'name'=>'date_create',
			'type'=>'raw',
			'value'=>'date("d.m.Y H:i",strtotime($data->date_create))',
			
		),
		array(
			'name'=>'users_id',
			'type'=>'raw',
			'value'=>'$data->user->name',
			
		),
		
		array(
			'name'=>'status',
			'type'=>'raw',
			'value'=>'Comments::getStatusAliases($data->status)',
			'filter'=>Comments::getStatusAliases()
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update} {delete}',
		),
	),
)); ?>

<?php if($model->hasAttribute('sort')) Yii::app()->clientScript->registerScript('sortGrid', 'sortGrid("comments");', CClientScript::POS_END) ;?>
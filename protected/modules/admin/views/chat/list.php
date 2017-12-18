<?php
$this->menu=array(
	array('label'=>'Добавить','url'=>array('create')),
);
?>

<h1>Управление <?php echo $models->translition(); ?></h1>

<?
	$columns = array();
	if(Yii::app()->user->isAdmin())
	{
		$columns[] = array(
			'header'=>'Пользователь',
			'type'=>'raw',
			'value'=>function($data){
			  return CHtml::link($data->user->username, array('/admin/users/view','id'=>$data->user->id));
			});
		$columns[] = 'message';
		$columns[] = 'answer';
		$columns[] = array(
			'name'=>'create_time',
			'type'=>'raw',
			'value'=>'$data->create_time ? SiteHelper::russianDate($data->create_time).\' в \'.date(\'H:i\', strtotime($data->create_time)) : ""'
		);
	}
	else
	{
		$columns[] = 'message';
		$columns[] = 'answer';
		$columns[] = array(
			'name'=>'create_time',
			'type'=>'raw',
			'value'=>'$data->create_time ? SiteHelper::russianDate($data->create_time).\' в \'.date(\'H:i\', strtotime($data->create_time)) : ""'
		);
	}
?>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'chat-grid',
	'dataProvider'=>$models->search(),
	'filter'=>$models,
	'type'=>TbHtml::GRID_TYPE_HOVER,
    'afterAjaxUpdate'=>"function() {sortGrid('chat')}",
    'rowHtmlOptionsExpression'=>'array(
        "id"=>"items[]_".$data->id,
        "class"=>"status_".(isset($data->status) ? $data->status : ""),
        "alt"=>$data->id,
    )',
	'columns'=>$columns,
)); ?>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'chat-form',
	'enableAjaxValidation'=>false,
)); ?>

	<? if(!Yii::app()->user->isAdmin()) { ?>

		<?php echo $form->textAreaControlGroup($model,'message',array('rows'=>6, 'cols'=>50, 'class'=>'span12')); ?>
		<? Chat::setAllViewed(); ?>
	<? } else { ?>
		<div class="answer_to">Выберите пользователя, которому хотите ответить</div>
		<?php echo $form->hiddenField($model,'id',array('class'=>'span12')); ?>
		<?php echo $form->textAreaControlGroup($model,'answer',array('rows'=>6, 'cols'=>50, 'class'=>'span12')); ?>

	<? } ?>

	<div class="form-actions">
		<?php echo TbHtml::submitButton('Отправить сообщение', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)); ?>

	</div>

<?php $this->endWidget(); ?>




<?php if($models->hasAttribute('sort')) Yii::app()->clientScript->registerScript('sortGrid', 'sortGrid("chat");', CClientScript::POS_END) ;?>
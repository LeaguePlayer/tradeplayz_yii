<?php
$this->menu=array(
	array('label'=>'Держатели карт Malloko','url'=>array('holders')),
);
?>

<h1>Привязка карт Malloko к пользователям</h1>



<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'usersholderscard-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'type'=>TbHtml::GRID_TYPE_HOVER,
    'afterAjaxUpdate'=>"function() {sortGrid('usersholderscard')}",
    'rowHtmlOptionsExpression'=>'array(
        "id"=>"items[]_".$data->id,
        "class"=>"status_".(isset($data->status) ? $data->status : ""),
    )',
	'columns'=>array(
		array(
			'name'=>'id_user',
			'type'=>'raw',
			'value'=>'$data->user->nameWithCheckCard',
			
		),
		array(
			'name'=>'card_number',
			'type'=>'raw',
			'value'=>' Mallokocards::checkHolderCardByNumber( $data->card_number ) ',
			
		),
		
		array(
			'name'=>'create_time',
			'type'=>'raw',
			'value'=>'$data->create_time ? SiteHelper::russianDate($data->create_time).\' в \'.date(\'H:i\', strtotime($data->create_time)) : ""'
		),
		
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{apply} {dismiss}',
            'buttons'=>array
            (
            	'apply' => array
                (
                    'label'=>'Привязать',
                    //'icon'=>'lock',
                    'url'=>'Yii::app()->createUrl("/admin/usersholderscard/apply", array("id_user_holder_card"=>$data->id))',
                    'options'=>array(
                        'class'=>'btn btn-small btn-success action_to_card',
                    ),
                ),
                'dismiss' => array
                (
                    'label'=>'Отклонить',
                    'url'=>'Yii::app()->createUrl("/admin/usersholderscard/remove", array("id_user_holder_card"=>$data->id))',
                    'options'=>array(
                        'class'=>'btn btn-small btn-danger action_to_card',
                    ),
                ),
               
                ),
		),
	),
)); ?>

<?php if($model->hasAttribute('sort')) Yii::app()->clientScript->registerScript('sortGrid', 'sortGrid("usersholderscard");', CClientScript::POS_END) ;?>
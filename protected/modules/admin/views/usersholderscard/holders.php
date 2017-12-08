<?php
$this->menu=array(
	array('label'=>'Привязка карт Malloko','url'=>array('list')),
);
?>

<h1>Управление держателями карт</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'Usersholderscard-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'type'=>TbHtml::GRID_TYPE_HOVER,
 //    'afterAjaxUpdate'=>"function() {sortGrid('Usersholderscard')}",
 //    'rowHtmlOptionsExpression'=>'array(
 //        "id"=>"items[]_".$data->id,
 //        "class"=>"status_".(isset($data->status) ? $data->status : ""),
 //    )',
	// 'afterAjaxUpdate'=>"function(id,data){ jQuery('#Usersholderscard_user_id').select2({'placeholder':'Select to filter'});}",
	'columns'=>array(
		// 'id',
		
		// array(
		// 	'header'=>'Фото',
		// 	'type'=>'raw',
		// 	'value'=>'TbHtml::imageCircle($data->imgBehaviorLook->getImageUrl("icon"))'
		// ),
		// array(
		// 	'name'=>'status',
		// 	'type'=>'raw',
		// 	'value'=>'Usersholderscard::getStatusAliases($data->status)',
		// 	'filter'=>Usersholderscard::getStatusAliases()
		// ),
		array(
			'name'=>'id_user',
			'type'=>'raw',
			'value'=>'$data->user->name',
		),
		'card_number',
		'card_number_for_user',
		array(
			'name'=>'create_time',
			'type'=>'raw',
			'value'=>'SiteHelper::russianDate($data->create_time)'
		),

		// array(
		//    'name'=>'user_id',
		//    'type'=>'raw',
		//    'value'=>'$data->user->name',
		//    'filter'=>$this->widget('ext.select2.ESelect2', array(
		//     'model'=>$model,
		//     'attribute'=>'user_id',
		//     'data'=>$data['users'],
		//     // 'asDropDownList' => false,
		//     // 'pluginOptions' => array(
		//     //  'width' => '150px',
		//     //  'ajax' => array(
		//     //   'url' => '/admin/categories/allJson',
		//     //   'dataType' => 'json',
		//     //   'quietMillis' => 300,
		//     //   'data' => 'js: function(term, page){return {q: term};}',
		//     //   'results' => 'js: function(data, page){return { results: data };}'
		//     //  ),
		//     //  'initSelection' => 'js:function (element, callback) {var id=$(element).val(); $.getJSON("/admin/categories/getOneById", {id: id}, function(data) { callback(data); }) }'
		//     // )
		//    ), true)
		//   ),
		// array(
  //       'name'=>'user_id',
  //       'value'=>'1',
  //       'filter' => $this->widget('ext.select2.ESelect2',array(
  //         'name'=>'user_id',
  //         'data'=>array(1,2,3,4),
  //       ), true)

        // ),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{delete}',
            // 'buttons'=>array
            // (
            // 	'comments' => array
            //     (
            //         'label'=>'Комментарии',
            //         //'icon'=>'lock',
            //         'url'=>'Yii::app()->createUrl("/admin/comments/list", array("id_look"=>$data->id))',
            //         'options'=>array(
            //             'class'=>'btn btn-small',
            //         ),
            //     ),
                
               
            //     ),
		),
	),
)); ?>

<?php // if($model->hasAttribute('sort')) Yii::app()->clientScript->registerScript('sortGrid', 'sortGrid("Usersholderscard");', CClientScript::POS_END) ;?>


<h1>Управление <?php echo $model->translition(); ?></h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'looks-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'type'=>TbHtml::GRID_TYPE_HOVER,
    'afterAjaxUpdate'=>"function() {sortGrid('looks')}",
    'rowHtmlOptionsExpression'=>'array(
        "id"=>"items[]_".$data->id,
        "class"=>"status_".(isset($data->status) ? $data->status : ""),
    )',
	'afterAjaxUpdate'=>"function(id,data){ jQuery('#Looks_user_id').select2({'placeholder':'Select to filter'});}",
	'columns'=>array(
		'id',
		array(
			'name'=>'dttm_date_create',
			'type'=>'raw',
			'value'=>'SiteHelper::russianDate($data->dttm_date_create)'
		),
		array(
			'header'=>'Фото',
			'type'=>'raw',
			'value'=>'TbHtml::imageCircle($data->imgBehaviorLook->getImageUrl("icon"))'
		),
		array(
			'name'=>'status',
			'type'=>'raw',
			'value'=>'Looks::getStatusAliases($data->status)',
			'filter'=>Looks::getStatusAliases()
		),
		// array(
		// 	'name'=>'user_id',
		// 	'type'=>'raw',
		// 	'value'=>'$data->user->name',
		// ),

		array(
		   'name'=>'user_id',
		   'type'=>'raw',
		   'value'=>'$data->user->name',
		   'filter'=>$this->widget('ext.select2.ESelect2', array(
		    'model'=>$model,
		    'attribute'=>'user_id',
		    'data'=>$data['users'],
		    // 'asDropDownList' => false,
		    // 'pluginOptions' => array(
		    //  'width' => '150px',
		    //  'ajax' => array(
		    //   'url' => '/admin/categories/allJson',
		    //   'dataType' => 'json',
		    //   'quietMillis' => 300,
		    //   'data' => 'js: function(term, page){return {q: term};}',
		    //   'results' => 'js: function(data, page){return { results: data };}'
		    //  ),
		    //  'initSelection' => 'js:function (element, callback) {var id=$(element).val(); $.getJSON("/admin/categories/getOneById", {id: id}, function(data) { callback(data); }) }'
		    // )
		   ), true)
		  ),
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
			'template'=>'{comments} {delete}',
            'buttons'=>array
            (
            	'comments' => array
                (
                    'label'=>'Комментарии',
                    //'icon'=>'lock',
                    'url'=>'Yii::app()->createUrl("/admin/comments/list", array("id_look"=>$data->id))',
                    'options'=>array(
                        'class'=>'btn btn-small',
                    ),
                ),
                
               
                ),
		),
	),
)); ?>

<?php if($model->hasAttribute('sort')) Yii::app()->clientScript->registerScript('sortGrid', 'sortGrid("looks");', CClientScript::POS_END) ;?>
<?php
$this->menu=array(
	array('label'=>'Добавить','url'=>array('create')),
);
?>

<h1>Управление <?php echo $model->translition(); ?></h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'tournaments-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'type'=>TbHtml::GRID_TYPE_HOVER,
    'afterAjaxUpdate'=>"function() {sortGrid('tournaments')}",
    'rowHtmlOptionsExpression'=>'array(
        "id"=>"items[]_".$data->id,
        "class"=>"status_".(isset($data->status) ? $data->status : ""),
    )',
	'columns'=>array(
		'id',
		array(
			'name'=>'dttm_begin',
			'type'=>'raw',
			'value'=>'SiteHelper::russianDate($data->dttm_begin)'
		),
		array(
			'name'=>'status',
			'type'=>'raw',
			'value'=>'Tournaments::getStatusAliases($data->status)',
			'filter'=>Tournaments::getStatusAliases()
		),
		'prize_places',
		'byuin',
		'id_format',
		'id_currency',
		'prize_pool',
		array(
			'name'=>'dttm_finish',
			'type'=>'raw',
			'value'=>'SiteHelper::russianDate($data->dttm_finish)'
		),
		array(
			'header'=>"Языки",
			'type'=>"html",
			'value'=>function($m){
				$string = "";
				$cl_name = get_class($m);
				foreach($m->places as $rus_name => $place)
				{
					$all_posts = $this->ALLOWED_COUNTRIES;
					$contents = ContentLang::model()->findAll( "model_name = '{$cl_name}' and id_place='{$place}' and post_id = {$m->id}");
					foreach($contents as $c)
						{
							$key = array_search($c->id_lang, $all_posts);
							unset($all_posts[$key]);
							$class = (empty($c->wswg_body)) ? "unfill" : "fill";
							$string .= "<div class='alert_lang {$class}'>{$rus_name}_{$c->id_lang}</div>";
						}
					foreach($all_posts as $post)
					{
						$string .= "<div class='alert_lang unfill'>{$rus_name}_{$post}</div>";
					}
				}
				return $string;
			},
		),
		'begin_stack',
				array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{test} {update} {delete}',
            'buttons'=>array
            (
            	
                'test' => array
                (
                    'label'=>'Test',
                    'url'=>'Yii::app()->createUrl("/admin/tournaments/test", array("id_tour"=>$data->id))',
                    'options'=>array(
                        'class'=>'btn btn-small',
                    ),
                ),
               
                ),
		),
	),
)); ?>

<?php if($model->hasAttribute('sort')) Yii::app()->clientScript->registerScript('sortGrid', 'sortGrid("tournaments");', CClientScript::POS_END) ;?>
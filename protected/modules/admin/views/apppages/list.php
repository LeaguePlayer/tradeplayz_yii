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
<?php
$this->menu=array(
    array('label'=>'Добавить','url'=>array('create')),
    array('label'=>'Импортировать магазины из xlsx','url'=>array('importshops')),
);


if($type=='without_packets')
{

   
    $this->menu[] = array('label'=>'Показать все магазины','url'=>array('list'));
$this->menu[] = array('label'=>'-------------------');
     $this->menu[] = array('label'=>'Снять с публикации эти магазины','url'=>array('publicAll'));
}
else
{
    $this->menu[] = array('label'=>'Показать магазины без пакетов','url'=>array('list','type'=>'without_packets'));
    $this->menu[] = array('label'=>'-------------------');
    $this->menu[] = array('label'=>'Опубликовать все магазины','url'=>array('publicAll?type=public'));
    $this->menu[] = array('label'=>'Снять с публикации магазины не участники Malloko','url'=>array('hideMalloko'));
}

?>

<h1>Управление <?php echo $model->translition(); ?></h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'shops-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'ajaxUpdate'=>false,
	'type'=>TbHtml::GRID_TYPE_HOVER,
    'afterAjaxUpdate'=>"function() {sortGrid('shops')}",
    'rowHtmlOptionsExpression'=>'array(
        "id"=>"items[]_".$data->id,
        "class"=>"status_".(isset($data->status) ? $data->status : ""),
    )',
	'columns'=>array(
		'title',
		
        array(
            'name'=>'categories_id',
            'type'=>'raw',
            'value'=>'$data->category->title',
            'filter'=>Categories::getCategories(),
        ),
		array(
			'name'=>'status',
			'type'=>'raw',
			'value'=>'Shops::getStatusAliases($data->status)',
			'filter'=>Shops::getStatusAliases()
		),
		array(
			'header'=>'Пакет',
			'type'=>'raw',
			'value'=>'TbHtml::image($data->getSmallPackageUrl())'
		),
        array(
            'name'=>'discount',
            'type'=>'raw',
            'value'=>'number_format($data->malloko->discount,0,""," ")',
            'filter'=>false,
        ),
		
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{foursquare} {package} {update} {delete}',
            'buttons'=>array
            (
            	'foursquare' => array
                (
                    'label'=>'Foursquare',
                    //'icon'=>'lock',
                    'url'=>'Yii::app()->createUrl("/admin/shops/bindfoursquare", array("id_shop"=>$data->id,"Shops_page"=>$_GET[Shops_page]))',
                    'options'=>array(
                        'class'=>'btn btn-small',
                    ),
                ),
                'update' => array
                (
                    
                    'url'=>'Yii::app()->createUrl("/admin/shops/update", array("id"=>$data->id,"Shops_page"=>$_GET[Shops_page]))',
                    
                ),
                'package' => array
                (
                    'label'=>'Управление пакетами',
                    'icon'=>'lock',
                    'url'=>'Yii::app()->createUrl("/admin/shops/buildpackage", array("id_shop"=>$data->id,"Shops_page"=>$_GET[Shops_page]))',
                    'options'=>array(
                        'class'=>'btn btn-small',
                    ),
                ),
               
                ),
		),
	),
)); ?>

<?php if($model->hasAttribute('sort')) Yii::app()->clientScript->registerScript('sortGrid', 'sortGrid("shops");', CClientScript::POS_END) ;?>
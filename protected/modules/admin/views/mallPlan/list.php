<?php
$this->breadcrumbs=array(
    
	"Список ТРЦ"=>array('/admin/malls/list/'),
	"{$model->title}",
);

$this->menu=array(
   // array('label'=>'Структура сайта','url'=>array('/admin/structure/list')),
    array('label'=>'Вернуться к списку ТРЦ', 'url'=>array('/admin/malls/list/')),
);
?>

<h1><?php echo $model->title; ?> - Этажи</h1>

<?php echo TbHtml::linkButton('Добавить этаж', array(
    'icon'=>TbHtml::ICON_PLUS,
    'url'=>array('/admin/mallplan/create', 'malls_id'=>$model->id)
)); ?>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
    'id'=>'mallplan-grid',
    'dataProvider'=>$mallplan_finder->search(),
    'filter'=>$mallplan_finder,
    'type'=>TbHtml::GRID_TYPE_HOVER,
    'afterAjaxUpdate'=>"function() {sortGrid('mallplan')}",
    'rowHtmlOptionsExpression'=>'array(
            "id"=>"items[]_".$data->id,
            "class"=>"status_".$data->status,
        )',
    'columns'=>array(
       
        array(
            'name'=>'floor_name',
            'type'=>'raw',
            'value'=>'TbHtml::link($data->floor_name, array("/admin/mallplan/marking/", "id"=>$data->id))'
        ),
        array(
            'header'=>'Фото',
            'type'=>'raw',
            'value'=>'TbHtml::imageCircle($data->getImageUrl("icon"))'
        ),
        array(
            'name'=>'status',
            'type'=>'raw',
            'value'=>'MallPlan::getStatusAliases($data->status)',
            'filter'=>MallPlan::getStatusAliases()
        ),
        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update} {delete}',
            'buttons'=>array(
                'delete'=>array(
                    'url'=>'array("/admin/mallplan/delete", "id"=>$data->id)'
                ),
                'view'=>array(
                    'url'=>'array("/admin/mallplan/view", "id"=>$data->id)'
                ),
            ),
        ),
    ),
)); ?>
<?php
$this->menu=array(
	array('label'=>'Список','url'=>array('list', 'Shops_page'=>$Shops_page)),
	array('label'=>'Список магазинов без пакетов','url'=>array('/admin/shops/list/type/without_packets')),
	array('label'=>'------------'),
	
	array('label'=>'Редактирование магазина','url'=>array('update','id'=>$model->id, 'Shops_page'=>$Shops_page)),
);
?>

<h1 id="got_name"><? echo  $model->title;?></h1>
<input type="hidden" value="<?=$model->id?>" id="id_shop">
<? if(count($model->places)) {?>
	<div>
		<? foreach($model->places as $place) { ?>
			<div class="row_street_foursquare">
				<div class="left actual_street row_obj">
					<input type="hidden" value="<?=$place->id?>" class="place_id" />
					<div class="hidden_binds">
						<? foreach($place->bind_foursquare as $bind) { ?>
							<input type="hidden" value="<?=$bind->id_foursquare?>" class="id_foursquare" />
						<? } ?>
					</div>
					<? echo ($place->mall->title) ? $place->mall->title : ""; ?> 
					<span><? echo (!empty($place->mall)) ? $place->mall->default_street : $place->street; ?></span>
				</div>
				<ul class="right got_foursqaure row_obj">
					
				</ul>
			</div>
		<? } ?>
	</div>
<? } else { ?>
	<div>
		Вы не указали ни одного адреса, чтобы связать его с данными из foursquare
	</div>
<? } ?>
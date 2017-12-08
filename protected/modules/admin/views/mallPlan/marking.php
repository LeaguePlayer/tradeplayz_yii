<div class="row-fluid">
	<div class="span12">
		<div id="mapContainer">
			<div id="map" data-map-id="<?=$model->id?>"><img class="marking_map" src="<? echo $model->getImageUrl(); ?>" alt=""></div>
			<div id="mapControls"><a id="up" href="javascript:;"></a><a id="down" href="javascript:;"></a></div>
		</div>
	</div>
</div>

<?php
Yii::app()->clientScript->registerScript('init', 'var regions = [];', CClientScript::POS_END);
if(!empty($all_areas)){
	foreach ($all_areas as $key => $coords) {

		//$this->renderPartial('/areas/_form', array('model' => $a, 'index' => $key));
		//regions.push({id: 889, coords: 'M287.75,625.49L252.04,469.34L130.5,508.35L156.2,672L287.75,625.49Z', reserve: true});
		Yii::app()->clientScript->registerScript("#area".$key, "regions.push({id: {$key}, coords: '{$coords}'});", CClientScript::POS_END);
	}
}
?>
<? echo CHtml::hiddenField('id_plan', $model->id); ?>
<? echo CHtml::hiddenField('id_mall', $model->malls_id); ?>
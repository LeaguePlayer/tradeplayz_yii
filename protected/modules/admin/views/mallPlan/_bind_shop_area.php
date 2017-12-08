<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'mall-plan-form',
	'enableAjaxValidation'=>false,
	//	'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

	<?php echo $form->errorSummary($bind); ?>

	<?php echo $form->dropDownListControlGroup($bind, 'shops_id', $data['shops'], array('class'=>'span8', 'displaySize'=>1)); ?>


	<div class="form-actions">
		<?php echo TbHtml::submitButton('Привязать магазин к региону', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)); ?>
		<?php echo TbHtml::submitButton('Удалить регион', array('color' => TbHtml::BUTTON_COLOR_DANGER,'name'=>"BindsShopArea[removeArea]",'value'=>'remove')); ?>
        <?php echo TbHtml::linkButton('Отмена', array('url'=>['/admin/mallplan/marking/', 'id'=>$data['id_plan']])); ?>
	</div>

<?php $this->endWidget(); ?>

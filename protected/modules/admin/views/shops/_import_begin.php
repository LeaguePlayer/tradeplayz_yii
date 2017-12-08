<h1>Импорт магазинов через .xlsx ONLY</h1>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'import-form',
	'enableAjaxValidation'=>false,
		'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

	<div class='control-group'>
		<?php echo CHtml::label('Выберите ТРЦ к которому будете загружать магазины', 'id_mall'); ?>
		<?php echo CHtml::dropDownList('Import[id_mall]','id_mall', $data['malls'], array('class'=>'span8')); ?>
		
	</div>

	<div class='control-group'>
		<?php echo CHtml::label('Выберете xls файл с данными', 'img_preview'); ?>
		<?php echo CHtml::fileField('Import[xls]','img_preview', array('class'=>'span3')); ?>
		
	</div>

	<div class="form-actions">
		<?php echo TbHtml::submitButton('Начать импорт', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)); ?>
        <?php echo TbHtml::linkButton('Отмена', array('url'=>'/admin/shops/list')); ?>
	</div>

<?php $this->endWidget(); ?>
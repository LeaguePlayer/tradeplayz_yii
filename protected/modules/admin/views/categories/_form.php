<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'categories-form',
	'enableAjaxValidation'=>false,
		'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<?php $tabs = array(); ?>
	<?php $tabs[] = array('label' => 'Основные данные', 'content' => $this->renderPartial('_rows', array('form'=>$form, 'model' => $model), true), 'active' => true); ?>
	<?php $tabs[] = array('label' => 'Настройки для опытных', 'content' => $this->renderPartial('_experienced', array('form'=>$form, 'model' => $model), true), 'active' => false); ?>
	

	<?php $this->widget('bootstrap.widgets.TbTabs', array( 'tabs' => $tabs)); ?>

	<div class="form-actions">
		<?php echo TbHtml::submitButton('Сохранить', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)); ?>
        <?php echo TbHtml::linkButton('Отмена', array('url'=>'/admin/categories/list')); ?>
	</div>

<?php $this->endWidget(); ?>

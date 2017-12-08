<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'notification-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<?php $tabs = array(); ?>
	<?php $tabs[] = array('label' => 'Основные данные', 'content' => $this->renderPartial('_rows', array('form'=>$form, 'model' => $model), true), 'active' => true); ?>
	
	<?php $this->widget('bootstrap.widgets.TbTabs', array( 'tabs' => $tabs)); ?>

	<div class="form-actions">
		<?php echo TbHtml::submitButton('Отправить PUSH', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)); ?>
        <?php echo TbHtml::linkButton('Отмена', array('url'=>'/admin/notification/list')); ?>
	</div>

<?php $this->endWidget(); ?>

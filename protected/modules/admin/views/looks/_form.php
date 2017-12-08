<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'looks-form',
	'action'=>'http://malloko.amobile2.tmweb.ru/api/looks/create?token=1078ec804536ca905b685ef553924e7f',
	'enableAjaxValidation'=>false,
		'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<?php $tabs = array(); ?>
	<?php $tabs[] = array('label' => 'Основные данные', 'content' => $this->renderPartial('_rows', array('form'=>$form, 'model' => $model), true), 'active' => true); ?>
	
	<?php $this->widget('bootstrap.widgets.TbTabs', array( 'tabs' => $tabs)); ?>

	<div class="form-actions">
		<?php echo TbHtml::submitButton('Сохранить', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)); ?>
        <?php echo TbHtml::linkButton('Отмена', array('url'=>'/admin/looks/list')); ?>
	</div>

	<!-- <textarea name="Comments[text]"></textarea> -->

<?php $this->endWidget(); ?>

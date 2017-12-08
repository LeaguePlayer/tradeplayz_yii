<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'shops-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<?php $tabs = array(); ?>
	<?php $tabs[] = array('label' => 'Основные данные', 'content' => $this->renderPartial('_rows', array('form'=>$form, 'model' => $model, 'data'=>$data), true), 'active' => true); ?>
    <?php $tabs[] = array('label' => 'Альтернативные названия', 'content' => $this->renderPartial('_altnames', array('form'=>$form, 'model' => $model, 'data'=>$data), true)); ?>
    <?php $tabs[] = array('label' => 'Места', 'content' => $this->renderPartial('_places', array('form'=>$form, 'model' => $model, 'data'=>$data), true)); ?>
    <?php $tabs[] = array('label' => 'Программа Malloko', 'content' => $this->renderPartial('_malloko', array('form'=>$form, 'model' => $model, 'data'=>$data), true)); ?>
	
	<?php $this->widget('bootstrap.widgets.TbTabs', array( 'tabs' => $tabs)); ?>

	<div class="form-actions">
		<?php echo TbHtml::submitButton('Сохранить', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)); ?>
        <?php echo TbHtml::linkButton('Отмена', array('url'=>array('/admin/shops/list','Shops_page'=>$Shops_page))); ?>
	</div>

<?php $this->endWidget(); ?>

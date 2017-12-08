	<?php echo $form->dropDownListControlGroup($model,'id_type', Shops::getTypes() ,array('class'=>'span8')); ?>

	<?php echo $form->textFieldControlGroup($model,'title',array('class'=>'span8','maxlength'=>255)); ?>

	<?php echo $form->dropDownListControlGroup($model,'categories_id', Categories::getCategories() ,array('class'=>'span8')); ?>

	<?php echo $form->dropDownListControlGroup($model, 'status', Shops::getStatusAliases(), array('class'=>'span8', 'displaySize'=>1)); ?>
	<?php echo $form->textFieldControlGroup($model,'homepage',array('prepend' => 'http://', 'span' => 8,'maxlength'=>255)); ?>



<div class='control-group'>
		<?php echo CHtml::activeLabelEx($model, 'wswg_body'); ?>
		<?php $this->widget('appext.ckeditor.CKEditorWidget', array('model' => $model, 'attribute' => 'wswg_body', 'config' => array('width' => '100%')
		)); ?>
		<?php echo $form->error($model, 'wswg_body'); ?>
	</div>

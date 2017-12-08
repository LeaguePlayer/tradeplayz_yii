	<?php // echo $form->textFieldControlGroup($model,'looks_id',array('class'=>'span8')); ?>

	<?php echo $form->textAreaControlGroup($model,'norm_comment',array('rows'=>6, 'cols'=>50, 'class'=>'span8', 'readonly'=>true)); ?>

	<?php echo $form->textFieldControlGroup($model,'date_create',array('class'=>'span8', 'readonly'=>true)); ?>

	<?php // echo $form->textFieldControlGroup($model,'users_id',array('class'=>'span8')); ?>

	<?php echo $form->dropDownListControlGroup($model, 'status', Comments::getStatusAliases(), array('class'=>'span8', 'displaySize'=>1)); ?>

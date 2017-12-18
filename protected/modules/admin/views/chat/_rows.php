	<?php echo $form->textFieldControlGroup($model,'id_user',array('class'=>'span8')); ?>

	<?php echo $form->textAreaControlGroup($model,'message',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textAreaControlGroup($model,'answer',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->dropDownListControlGroup($model, 'status', Chat::getStatusAliases(), array('class'=>'span8', 'displaySize'=>1)); ?>

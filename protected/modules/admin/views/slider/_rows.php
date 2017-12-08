	<?php echo $form->dropDownListControlGroup($model,'post_type', Slider::getPostType(),array('class'=>'span8')); ?>

	<?php echo $form->dropDownListControlGroup($model,'post_id',[], array('class'=>'span8')); ?>

    <?php echo CHtml::hiddenField("hidden_post_type", $model->post_type); ?>

	<?php echo CHtml::hiddenField("slider_id", $model->id); ?>

	<?php echo CHtml::hiddenField("hidden_post_id", $model->post_id); ?>

	<?php echo $form->textFieldControlGroup($model,'title',array('class'=>'span8','maxlength'=>255)); ?>
	 

	<?php echo $form->textFieldControlGroup($model,'sub_title',array('class'=>'span8','maxlength'=>255)); ?>
	




	<div class="cropper_block">
                     

                     <div class="cont_cropper">
                         
                         <div class="img-container">
                              <img src="<? echo $model->imgBehaviorPreview->getImageUrl(); ?>">
                            </div>


                            <div class="control_image">
                               
                                <label class="btn btn-primary" for="inputImage_<? echo $type ?>" title="Upload image file">
                                              <input class="hide inputImage" id="inputImage_<? echo $type ?>" name="file" type="file" accept="image/*">
                                              <span class="docs-tooltip" data-toggle="tooltip" title="Загрузить фотографию">
                                                Выбрать фотографию
                                              </span>
                                            </label>

                                <button class="btn btn-success uplaodAjax" data-type="<? echo $type; ?>" data-toggle="tooltip" type="button" title="">Сохранить картинку</button>
                            </div>

                     </div>
                 </div>


	 <div>
        <?php echo TbHtml::labelTb('Внимание!', array('color' => TbHtml::LABEL_COLOR_WARNING)); ?>
        Если значение будет больше 0, то магазин автоматически становится участником дисконтной программы Malloko.
    </div>

	<?php echo $form->dropDownListControlGroup($model, 'status', Slider::getStatusAliases(), array('class'=>'span8', 'displaySize'=>1)); ?>

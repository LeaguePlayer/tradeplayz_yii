<fieldset>
    <legend>Программа Malloko</legend>


    
        <?php echo $form->textFieldControlGroup($model->malloko,'discount',array('class'=>'span8 replacePoint','maxlength'=>255)); ?>
    
    <div>
        <?php echo TbHtml::labelTb('Внимание!', array('color' => TbHtml::LABEL_COLOR_WARNING)); ?>
        Если значение будет больше 0, то магазин автоматически становится участником дисконтной программы Malloko.
    </div>

</fieldset>
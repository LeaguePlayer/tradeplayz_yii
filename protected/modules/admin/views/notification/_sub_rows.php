<div class="controls">
                    <input class="span1" name="Composition[id][]" value="<?=$object->id?>" type="hidden">
                    <input class="span6" name="Composition[title][]" value="<?=$object->title?>" placeholder="Название ингредиента" type="text">
                    <input class="span3" name="Composition[composition][]" value="<?=$object->composition?>"  placeholder="Объем ингредиента" type="text">
                    <? echo CHtml::dropDownList("Composition[parameter][]", $object->parameter, SiteHelper::getParameter(), array('class'=>'span2') ); ?>
                    <?php echo TbHtml::button('Удалить', array('color' => TbHtml::BUTTON_COLOR_DANGER, 'class'=>'del_row')); ?>
</div>
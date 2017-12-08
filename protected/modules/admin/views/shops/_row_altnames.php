<div data-numeric="<?=$id_slot?>" class="controls altnames_selector">
                    <input name="AlternativeNamesShop[<? echo $id_slot; ?>][id]" class="in_row" value="<?=$object->id?>" type="hidden">
                    
                    
                    <?  echo CHtml::textField("AlternativeNamesShop[{$id_slot}][title]", $object->title, array('placeHolder'=>'Укажите альтернативное название','class'=>'qwe') ); ?>
                    
                    
                   
                    
                    <?php echo TbHtml::button('Удалить', array('color' => TbHtml::BUTTON_COLOR_DANGER, 'class'=>'del_row')); ?>
</div>
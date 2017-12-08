<div data-numeric="<?=$id_slot?>" class="controls place_phone_selector">
                    <input name="Place[<? echo $parentSlot; ?>][phone][id][<? echo $id_slot; ?>]" class="in_row" value="<?=$object->id?>" type="hidden">
                    <?  echo CHtml::textField("Place[$parentSlot][phone][number][{$id_slot}]", $object->phone, array('placeHolder'=>'Укажите номер телефона','class'=>'like_span') ); ?>
                    <?php echo TbHtml::button('Удалить', array('color' => TbHtml::BUTTON_COLOR_DANGER, 'class'=>'del_row')); ?>
                </div>
<div data-numeric="<?=$id_slot?>" class="controls place_selector its_ceil_block">
    <div class="left compain_a place_a">
    
        <input name="Place[<? echo $id_slot; ?>][id]" class="in_row" value="<? echo $object->id;?>" type="hidden">
        <?  echo CHtml::dropDownList("Place[{$id_slot}][malls_id]", $object->malls_id, Malls::getMalls(), array('class'=>'like_span span6 set_mall') ); ?>
        <?  echo TbHtml::textField("Place[{$id_slot}][street]", $object->street, array('placeHolder'=>'Укажите адрес', 'prepend' => 'ул.','class'=>'like_span set_street') ); ?>
        <?  echo CHtml::dropDownList("Place[{$id_slot}][status]", $object->status, Place::getStatusAliases(), array('class'=>'like_span') ); ?>
        
    
    </div>
    <div class="right compain_a phone_a">
        <div class="control-group">
            <div class="place_c">
                <?  if( count( $object->phones ) > 0 ) { ?>
                	<?  foreach( $object->phones as $slot => $phone ) { ?>
    					<?=$this->renderPartial('_place_phone', array('object'=>$phone, 'id_slot'=>$slot, 'parentSlot'=>$id_slot) );?>
                    <?  } ?>
                <?  } ?>
            </div>
            <?php echo TbHtml::button('Добавить телефон', array('class'=>'add_row', 'data-selector'=>'.place_phone_selector')); ?>
        </div>
        
    </div>
    <div class="block_with_delete"><a class="del_row" href="#"></a></div>
</div>


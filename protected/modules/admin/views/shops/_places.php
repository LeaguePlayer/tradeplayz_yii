<fieldset>
	<legend>Места</legend>


	<div class="control-group">
    		
        <div class="place_c">
       
       		<?  if( count( $data['array_places'] ) > 0 ) { ?>
            	<?  foreach( $data['array_places'] as $id_slot => $place ) { ?>
					<?=$this->renderPartial('_row_place_phone', array('object'=>$place, 'id_slot'=>$id_slot, 'phones'=>$data['array_places']['phones'][$place->id]) );?>
                <?  } ?>
            <?  } ?>
            
        </div>
        <?php echo TbHtml::button('Добавить строку', array('class'=>'add_row', 'data-selector'=>'.place_selector')); ?>
     </div>

</fieldset>
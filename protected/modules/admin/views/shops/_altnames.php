<fieldset>
	<legend>Список альтернативных названий</legend>


	<div class="control-group">
    		
        <div class="place_c">
       
       		<? if( count( $data['array_altnames'] ) > 0 ) { ?>
            	<? foreach( $data['array_altnames'] as $id_slot => $altname ) { ?>
					<?=$this->renderPartial('_row_altnames', array('object'=>$altname, 'id_slot'=>$id_slot) );?>
                <? } ?>
            <? } ?>
            
        </div>
        <?php echo TbHtml::button('Добавить строку', array('class'=>'add_row', 'data-selector'=>'.altnames_selector')); ?>
     </div>

</fieldset>
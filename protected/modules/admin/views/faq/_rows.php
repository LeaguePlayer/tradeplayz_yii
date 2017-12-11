	<?php echo $form->dropDownListControlGroup($model, 'status', Faq::getStatusAliases(), array('class'=>'span8', 'displaySize'=>1)); ?>

<?
	$place = "title";
	$name_place = "Название";
?>
<label class="control-label"><? echo $name_place; ?></label>
<?
echo CHtml::textField("ContentLang[{$place}]", $model->$place->text,array('class'=>'span8','maxlength'=>255));
?>


<?
	$place = "description";
	$name_place = "Правила";
?>
	<div class='control-group'>
			<label class="control-label"><? echo $name_place; ?></label>

			<?
				$this->widget('appext.ckeditor.CKEditorWidget', 
					array(
						'name' => "ContentLang[{$place}]",
						'config' => array('width' => '100%', 'height'=>'280'),
						'value'=>$model->$place->text,
					)
				);
			?>


	</div>


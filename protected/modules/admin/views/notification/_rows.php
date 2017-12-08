	

	<?php echo $form->textFieldControlGroup($model,'text',array('id' => 'notif_text', 'class'=>'span12','maxlength'=>95)); ?>

	<?php echo CHtml::label('Осталось символов: 95', null, array('id' => 'label_text')); ?>
<script type="text/javascript">
	$('#notif_text').on('keyup', function() {
		ut = $("#notif_text").val().length;
		u = 95 - ut;
		console.log(u);
		$("#label_text").text('Осталось символов: ' + u);
	});
	$('#notif_text').on('keypress',function(e){
		console.log(e.which);
		// if( e.which!=8 && e.which!=0 && (e.which<48 || e.which>57)) {
		if( e.which == 34) {
			return false;
		}
	});
</script>
$(document).ready(function(){
	$('#chat-grid tbody tr td').click(function(){

		if($('#Chat_id').is('input'))
		{
			if($('textarea#Chat_answer').val() != "")
			{
				alert('Ваше поле с ответом не пустое, вы не можете выбрать пользователя для ответа!');
				return false;
			}

			var id_chat = $(this).closest('tr').attr('alt');
			var username = $(this).closest('tr').find('td').first().text();
			var answer = $(this).closest('tr').find('td:nth-child(3)').text();

			console.log(id_chat);
			console.log(username);
			console.log(answer);
			// console.log($('textarea#Chat_answer'));

			$('#Chat_id').val(id_chat);
			$('.answer_to').html("Вы отвечаете для пользователя <strong>"+username+"</strong>");
			$('textarea#Chat_answer').val(answer);
			$('textarea#Chat_answer').focus();

			$("html, body").animate({ scrollTop: $(document).height() }, "fast");
		}
		
	});
});
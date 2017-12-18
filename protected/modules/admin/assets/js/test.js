$(document).ready(function(){
	var id_tour = $("#id_tour").val();
	
	setInterval(function(){

		$.ajax({
		    url: '/admin/tournaments/datatest?id_tour='+id_tour,             // указываем URL и
		    // dataType : "json",                     // тип загружаемых данных
		    success: function (data, textStatus) { // вешаем свой обработчик на функцию success
		        $("#maindiv").html(data);
		    } 
		});
		
	},1000);
});
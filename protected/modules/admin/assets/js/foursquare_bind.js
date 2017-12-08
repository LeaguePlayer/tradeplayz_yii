$(document).ready(function(){
	var client_id = "Y430104A3Z4CYZCAJNVTHM0KUONIWJLOIUSAZWJC1EE5KYSG";
	var client_secret = "NHFW0RI2BUBX2ILZNVXWLXAPNQAN3HF4XS1O0A2P2NVFAGJR";
	var shop_name_url = encodeURIComponent($("#got_name").text());
	var url_city = encodeURIComponent("Тюмень");


	$('.row_street_foursquare').on("click", ".btn_bind_foursqaure", function(e) { 
		//console.log('clicked!');]
		console.log(e);
		var $this = $(this);
		var foursquare_id = $this.parent('li').find('.foursquare_id').val();
		var id_shop = $('#id_shop').val();
		var place_id = $this.parents('.row_street_foursquare').find('.place_id').val();
		var action = "";
		var old_class = "";
		var new_class = "";
		var new_title = "";
		if($this.is('.btn-success'))
		{
			
			action = 'create';
			old_class = "btn-success";
			new_class = "btn-danger";
			new_title = "Отвязать";
		}
		else
		{
			
			action = 'delete';
			old_class = "btn-danger";
			new_class = "btn-success";
			new_title = "Привязать";
		}

		jQuery.ajax({
						url: '/admin/shops/bindfoursquare',
						type: "GET",
						dataType: 'json',
						data: {foursquare_id: foursquare_id, id_shop: id_shop, place_id: place_id, action: action},
						success: function(data){
							if(data.done)
							{
								$this.removeClass(old_class);
								$this.addClass(new_class);
								$this.text(new_title);
							}
							
						}
					});

		
	});

	

	$.each( $('.actual_street') , function( index, value ) {
		var $this = $(this);
		var row_street = "ул. "+$(value).find('span').text();
		//		  console.log( url_send );

				  jQuery.ajax({
						url: 'https://api.foursquare.com/v2/venues/search?client_id='+client_id+'&client_secret='+client_secret+'&v=20130815&ll=40.7,-74&query='+shop_name_url+'&near='+url_city,
						//type: "GET",
						dataType: 'json',
						//data: {type: type},
						success: function(data){
							//console.log(data);
							$.each( data.response.venues , function( index, value ) {
								if(value.location.address)
								{
									var button;
									var is_binded = false
									if($this.parent('.row_street_foursquare').find('.id_foursquare[value="'+value.id+'"]').is('input'))
									{
										 button = '<button class="btn btn-danger btn_bind_foursqaure" type="submit">Отвязать</button>';
										 is_binded = true;
									}
									else
									{
										 button = '<button class="btn btn-success btn_bind_foursqaure" type="submit">Привязать</button>';
									}
									

									//var button = '<button class="btn btn-success btn_bind_foursqaure" type="submit">Привязать</button>';
									var id_input = '<input class="foursquare_id" type="hidden" value="'+value.id+'">';
									var row = "<li>"+"<b>"+value.name+"</b> "+value.location.address+" "+button+" "+id_input+"</li>";
									row = $.parseHTML( row );
								  $this.parent('.row_street_foursquare').find('.got_foursqaure').prepend(row);

								  if(row_street == value.location.address && !is_binded)
									{

										$(row).find('.btn_bind_foursqaure').click();
										//$this.parent('.row_street_foursquare').find('.btn_bind_foursqaure').hide();
									}
								}
								
							  
							});
						}
					});

				});

		

	});
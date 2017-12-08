// JavaScript Document

function showHideDelButtons()
{
	var cnt = $('.place_c .controls').size();

	if(cnt==1)
	{
		// скрываем кнопки
        
        if($('.place_c .controls .qwe').val() == "")
        {
            $('.del_row').hide();
        }
        else
        {
            $('.del_row').show();
        }
		
	}
	else
	{
		// показываем кнопки	
		$('.del_row').show();
	}
}

function replacer(str, p1, p2, offset, s) {
  return p1 + ", " + p2;
}


function BlockInputForMall()
{
    $( ".set_mall" ).each(function( index ) {
        var $this = $(this);
          if($this.val() != 0)
          {
            $this.next('.input-prepend').find('.set_street').attr('disabled',true);
          }
    });
    
}

$(document).ready(function(e) {



  
	showHideDelButtons();
    BlockInputForMall();


    $('.control-group').on("change", ".set_mall", function(e) { 
        
        var $this = $(this);
          if($this.val() != 0)
          {
            $this.next('.input-prepend').find('.set_street').attr('disabled',true);
          }
          else
          {
            $this.next('.input-prepend').find('.set_street').attr('disabled',false);
          }
    });

$('.control-group').on("click", ".add_row", function(e) {
    
    
            var selector = $(this).data('selector');
           // console.log('-----'+selector);

			var parent_div = $(this).closest('.control-group');
            
         

			var numeric = parseInt( $(parent_div).find('.controls'+selector+':last').data('numeric') );
			var new_numeric = numeric+1;
            
           // console.log(numeric);
          //  console.log(new_numeric);
            
	
			var block = $(parent_div).find('.controls'+selector+':last').clone();

            
            

			//block.html( block.html().replace(new RegExp(numeric,'g'),new_numeric) );
            
           

		    //  block.html( block.html().replace(/(\d+)\][\[\]a-z]*$/ ,function () {}) );
            var phone_block_counter;
            
            
                phone_block_counter = parseInt($(block).find('input:first').attr('name').match(/(\d+)\][\[\]a-z]*$/)[1]) + 1;
           
            
            
                     
             
           console.log("counter = "+phone_block_counter);

			$.each( $(block).find('input'), function(  ) {
			     var $this = $(this);
                 
                 
                 if(selector == ".place_selector" && $this.closest('.controls').hasClass('place_phone_selector'))
                 {
                    console.log('if');
                    var name = $this.attr('name').replace(/^([a-zA-Z\[\]]*\[)(\d+)(\][\w\[\]]*)/ , "$1" + new_numeric + "$3" );
                    
                     $this.val('').attr('name', name);
                 }
                 else
                 {
                    console.log('else');

                     var counter = $this.closest('.control').hasClass('place_phone_selector') ? phone_block_counter : new_numeric;
                // console.log(counter);
                    var name = $this.attr('name').replace(/^([\[\]\w\d]*\[)([\d]+)(\][\[\]a-z]*)$/ , "$1" + counter + "$3" );
                
                    $this.val('').attr('name', name);
                 }
            
            
                
			});

        $.each( $(block).find('select'), function(  ) {
            var $this = $(this);
            var name = $this.attr('name').replace(/^([a-zA-Z\[\]]*\[)(\d+)(\][\w\[\]]*)/ , "$1" + phone_block_counter + "$3" );
                    
            $this.val('').attr('name', name);
        });
            
            
            $(block).find('.phone_a').find('.controls:not(:first-child)').remove()
            
            
           

			$(parent_div).find('.place_c:first').append(block);
			$(parent_div).find('.controls:last-child').attr('data-numeric', new_numeric);
			showHideDelButtons();
            
            return false;
		});


	$('.place_c').on("click", ".del_row", function(e) {
         
           
			if (confirm('Точно удалить позицию?')) { 
			     var parent_div = $(this).closest('.control-group');
			     var block = $(parent_div).find('.controls:first').clone();
                 $(e.target).closest('.controls').remove();
				
                 
                 if(  $(parent_div).find('.controls').size() == 0)
                 {
                    $.each( $(block).find('input'), function(  ) {
        				$(this).val("");
        			});
                    $(parent_div).find('.place_c:first').append(block);
                 }
                 
                 showHideDelButtons();
				}


			return false;
		});

    
    $('.control-group').on("mouseenter", ".its_ceil_block",function(){
        $(this).find(".block_with_delete a").stop(true,true).animate({opacity:1},400);
    });
    
    $('.control-group').on("mouseleave", ".its_ceil_block",function(){
        $(this).find(".block_with_delete a").stop(true,true).animate({opacity:0.05},400);
    });
    
    
    

	

});
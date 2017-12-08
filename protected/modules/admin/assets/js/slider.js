var croppers = {};
var selectFiles = {};

$(document).ready(function(){

	var post_type = $('#Slider_post_type').val();
	gotPostIdByType(post_type);

	$('#Slider_post_type').change(function(){
		post_type = $(this).val();
		gotPostIdByType(post_type);
	});


	$.each($(".cropper_block"), function(index,value)
	{
		var $this =$(this);
		var $image =$this.find('.img-container img');
		var attrName = $this.find('.uplaodAjax').data('type');
		
		window.croppers[attrName] = {'cropper': $image};
		$image.cropper({ aspectRatio: 1076 / 608, resizable: false });	



		var $inputImage = $this.find(".inputImage"),
	        blobURL;



	    if (window.URL) {
	      $inputImage.change(function () {

	        var files = this.files,
	            file;

	        if (files && files.length) {
	          file = files[0];

	          
	           window.selectFiles[attrName] = {'filetype': file.type};

	          if (/^image\/\w+$/.test(file.type)) {
	            if (blobURL) {
	              URL.revokeObjectURL(blobURL); // Revoke the old one
	            }

	            blobURL = URL.createObjectURL(file);
	            $image.cropper("reset", true).cropper("replace", blobURL);
	            $inputImage.val("");
	          } else {
	            showMessage("Please choose an image file.");
	          }
	        }
	      });
	    } else {
	      $inputImage.parent().remove();
	    }


	});



	$('.uplaodAjax').click(function(){
		$.fancybox.showLoading();
		
			var $this = $(this);
			var type = $this.data('type');

			var cropper = window.croppers[type]['cropper'];
			$(cropper).cropper("disable");
			// var filetype = window.selectFiles[type]['filetype'] ;
		
	
			var dataimage = $(cropper).cropper("getDataURL");
	// console.log(dataimage);
			var carbody_id = $('#slider_id').val();

			$.ajax({
						
			                url: "/admin/slider/update/id/"+carbody_id,
							
							type: 'POST',
			                data: {image_base64: dataimage},
						    success: function(result) {
						    	
						    	 // console.log(result);
						    	 $this.hide();
						    	 
						    	 $.fancybox.hideLoading();
						    	
							  
							}
						});

			
			// saveImage(dataimage);

			// console.log('test');
			// // $('#asdb').val(dataimage);
			// $("#asdb")[0].files[0] = dataimage;
			// console.log();
			return false;
		});

});

function gotPostIdByType(type)
{
	
	jQuery.ajax({
			url: '/admin/slider/getpostid',
			type: "GET",
			dataType: 'json',
			data: {type: type},
			success: function(data){
				console.log(data);
				
				var combobox = $("#Slider_post_id");
				var selected_type = $("#Slider_post_type").val();

				var was_selected_id = $('#hidden_post_id').val();
				var was_selected_type = $('#hidden_post_type').val();
				combobox.html("");

				var prefix = "";
				$.each(data, function( index, value ) {
					
					  if( ( selected_type == was_selected_type ) && ( was_selected_id == value.value ) )
					  {
					  		prefix = " selected='selected'";
					  }
					  else
					  {
					  		prefix = "";
					  }
					  combobox.prepend( "<option"+prefix+" value='"+value.value+"'>"+value.label+"</option>" );

					});
			}
		});
}
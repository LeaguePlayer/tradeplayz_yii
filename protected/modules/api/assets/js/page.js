function makeHorizontalLine()
{
	$.ajax({
	      url: "/api/charts/getLastGraph",
	      dataType: 'json',
	      success: function(data) {
		      	window.globalWidget.chart().createShape({time: data.coord_x, price: data.coord_y},
                    {
                        shape: "horizontal_line",
                        lock: false,
                        disableSelection: false,
                        disableSave: true,
						disableUndo: false,
						showInObjectsTree: true,
                        overrides: { linewidth: 3  }
                    });
	      }
	    });


	
}

function makeVerticalLine()
{
	$.ajax({
	      url: "/api/charts/getLastGraph",
	      dataType: 'json',
	      success: function(data) {
		      	window.globalWidget.chart().createShape({time: data.coord_x, price: data.coord_y},
                    {
                        shape: "vertical_line",
                        lock: false,
                        disableSelection: false,
                        disableSave: true,
						disableUndo: false,
						showInObjectsTree: true,
                        overrides: { linewidth: 3  }
                    });
	      }
	    });
}

function makeRay()
{
	$.ajax({
	      url: "/api/charts/getLastGraph",
	      dataType: 'json',
	      success: function(data) {
		      	window.globalWidget.chart().createMultipointShape([{time: data.coord_x, price: data.coord_y},{time: data.coord_x_2, price: data.coord_y_2}],
							                            {
							                                shape: "ray",
							                                lock: false,
							                                disableSelection: false,
							                                disableSave: true,
															disableUndo: false,
															showInObjectsTree: true,
							                                overrides: { linewidth: 3  }
							                            });
	      }
	    });

	

		
}
function showObjectsTree()
{
	window.globalWidget.chart().executeActionById("paneObjectTree");
}

function makeIndicator(name){
	// window.globalWidget.chart().createStudy('Moving Average', false, false, [26], null, {'Plot.linewidth': 1, "Plot.color" : "#f0ff00"});
	window.globalWidget.chart().createStudy(name, false, false, [26], null, {'Plot.linewidth': 1, "Plot.color" : "#f0ff00"});
}

$(document).ready(function(){
	$('[data-countdown]').each(function() {
	  var $this = $(this), finalDate = $(this).data('countdown');
	  $this.countdown(finalDate, function(event) {
	    $this.html(event.strftime('%H:%M:%S'));
	  });
	});

	$("#menu li a").click(function(){
		if($(this).parent().find("ul").length)
		{
			if($(this).parent().find("ul").is(':visible'))
				$(this).parent().removeClass('active');
			else
				$(this).parent().addClass('active');

			return false;
		}
		else
		{
			// console.log($(this).data('event'));
			if( $(this).data('event') !== undefined )
			{
				window[ $(this).data('event') ]();
				return false;
			}
			else
			{
				$("#menu li.active").removeClass("active");
			}

		}

		
	});
});
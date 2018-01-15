<div id="scores">
	<div class="top_title"><? echo Yii::t('main','at_start_tour'); ?></div>
	<div class="counter" data-countdown="<? echo date("Y/m/d H:i:s",strtotime($this->user->active_participant->tournament->dttm_begin)); ?>"></div>
	<div class="round_players"></div>
</div>
<div id="tv_chart_container"></div>
<div id="bottom_nav">
	<ul id="menu">
	    <li>
	        <a class="charts" href="javascript:void(0);"></a>
	        <ul>
	            <li><a href="javascript:void(0);" onclick="window.globalWidget.chart().setChartType(3)">Area</a></li>
	            <li><a href="javascript:void(0);" onclick="window.globalWidget.chart().setChartType(0)">Bars</a></li>
	            <li><a href="javascript:void(0);" onclick="window.globalWidget.chart().setChartType(1)">Candles</a></li>
	            <li><a href="javascript:void(0);" onclick="window.globalWidget.chart().setChartType(2)">Line</a></li>
	            <li><a href="javascript:void(0);" onclick="window.globalWidget.chart().setChartType(8)">Heiken Ashi</a></li>
	        </ul>
	    </li>
	    <li>
	        <a class="indicators" href="javascript:void(0);"></a>
	        <ul>
	            <li><a href="javascript:void(0);" onclick="makeIndicator('Moving Average')">Moving Average</a></li>
	            <li><a href="javascript:void(0);" onclick="makeIndicator('Bollinger Bands')">Bollinger Bands</a></li>
	            <li><a href="javascript:void(0);" onclick="makeIndicator('Williams Alligator')">Alligator</a></li>
	            <li><a href="javascript:void(0);" onclick="makeIndicator('Relative Strength Index')">RSI</a></li>
	        </ul>
	    </li>
	    <li>
	        <a class="lines" href="javascript:void(0);"></a>
	        <ul>
	            <li><a href="javascript:void(0);" onclick="makeHorizontalLine()">Horizontal line</a></li>
	            <li><a href="javascript:void(0);" onclick="makeVerticalLine()">Vertical line</a></li>
	            <li><a href="javascript:void(0);" onclick="makeRay()">Ray</a></li>
	        </ul>
	    </li>
	    <li>
	        <a class="erase" href="javascript:void(0);" data-event="showObjectsTree"></a>
	    </li>
	</ul>
</div>


		<script type="text/javascript">
			var shapes = {};
			var globalWidget = null;
			$(document).ready(function(){
				function getParameterByName(name) {
			    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
			    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
			            results = regex.exec(location.search);
			    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
			}

			TradingView.onready(function()
			{
				var widget = new TradingView.widget({
					// fullscreen: true,
					width:"100%",
					height:"100%",
					symbol: 'BTC',
					interval: 'D',
					debug: false,
					resolution: 'D',
					container_id: "tv_chart_container",
					loading_screen: { backgroundColor: "#000000", foregroundColor: "#000000" },

					datafeed: new Datafeeds.UDFCompatibleDatafeed("<? echo $url_to_api_charts; ?>",1000),
					library_path: "<? echo $this->getAssetsUrl(); ?>/charting_library/",
					locale: "en",
					custom_css_url: "<? echo $this->getAssetsUrl(); ?>/charting_library/css/chart.css",
					//	Regression Trend-related functionality is not implemented yet, so it's hidden for a while
					drawings_access: { type: 'black', tools: [ { name: "Regression Trend" } ] },
					// studies_access: { type: 'black', tools: [ { name: "Regression Trend" } ] },
					disabled_features: ["use_localstorage_for_settings", "header_widget", "edit_buttons_in_legend", "context_menus", "show_logo_on_all_charts", "header_layouttoggle", "chart_crosshair_menu", "open_account_manager"],
					preset: "mobile",
					overrides: {
						"paneProperties.background": "#111820",
                        "paneProperties.vertGridProperties.color": "#272d34",
                        "paneProperties.horzGridProperties.color": "#272d34",
						"symbolWatermarkProperties.transparency": 0,
						"symbolWatermarkProperties.color" : "rgba(0, 0, 0, 0.00)",
						"scalesProperties.textColor" : "#AAA",
						"mainSeriesProperties.style" : 3,
						"timeScale.rightOffset" : 2,
						"paneProperties.crossHairProperties.color" : "#ff4900",
						"paneProperties.legendProperties.showStudyArguments": false,
						"paneProperties.legendProperties.showStudyTitles": false,
						"paneProperties.legendProperties.showStudyValues": false,
						"paneProperties.legendProperties.showSeriesTitle": false,
						"paneProperties.legendProperties.showSeriesOHLC": false,
						"mainSeriesProperties.priceLineWidth": 1,
						"mainSeriesProperties.areaStyle.color1": "rgba(254,73,9,0.6)",
						"mainSeriesProperties.areaStyle.color2": "rgba(254,73,9,0)",
						"mainSeriesProperties.areaStyle.linecolor": "rgba(254,73,9,1)",
						"mainSeriesProperties.areaStyle.linewidth": 2,
						"mainSeriesProperties.areaStyle.priceSource": "open",
						"paneProperties.bottomMargin": 20,
						"scalesProperties.scaleSeriesOnly": true,
						"scalesProperties.fontSize": 8,
						"mainSeriesProperties.lineStyle.color": "rgba(254,73,9,1)",
						"mainSeriesProperties.lineStyle.linewidth": 2,
						

					}
				});

				widget.onChartReady(function() {
						window.globalWidget = widget;
						// widget.chart().setVisibleRange({from: <? echo time(); ?>, to: <? echo strtotime("+1 minute"); ?>}, function(){
						// 	console.log('changed_visible');
						// });

						// widget.chart().setChartType(8)

						 

						// widget.chart().setResolution("5S",function(){
						// 	console.log('setResolution ready');
						// });
						
						setInterval(function(){

							$.ajax({
							      url: "/api/charts/getAllTrades?token=<? echo $this->token; ?>",
							      dataType: 'json',
							      type: 'POST',
							      data: {
							        shapes: window.shapes
							      },
							      success: function(data) {
								      	if(data.result == 1)
								      	{
								      		$.each(data.response.trades,function(i,v){
								      				var gotTime = v.time;
													console.log( widget.chart().getAllShapes() );
													
													var id_chart = widget.chart().createShape({time: gotTime, price: v.coord_y},
							                            {
							                                shape: v.arrow,
							                                lock: true,
							                                disableSelection: true,
							                                disableSave: true,
															disableUndo: true,
															showInObjectsTree: false,
															zOrder: 'top',
							                                text: v.text,
							                                overrides: { backgroundColor: "#ffffff", fontsize: 11, fontWeight: 400, color: "#ffffff"  }
							                            });
													console.log(id_chart);

													
													if(window.shapes[gotTime] === undefined)
														window.shapes[gotTime] = id_chart;
													else
														widget.chart().removeEntity( id_chart );
								      		});

											if(data.response.modal)
											{
												var $scores = $('#scores');
												if(data.response.modal.show)
												{
													$scores.find(".top_title").html( data.response.modal.title );
													$scores.find(".round_players").html( data.response.modal.content );

													if(data.response.modal.timer != $scores.find(".counter").data('countdown'))
													{
														$scores.find(".counter").attr( "data-countdown", data.response.modal.timer );
														  $scores.find(".counter").countdown(data.response.modal.timer, function(event) {
														    $this.html(event.strftime('%H:%M:%S'));
														  });
													}
													

													$scores.show();
												}
												else
													$scores.hide();

												
											}
								      	}
							      }
							    });

						},1000)
				});

			})
			})

		</script>
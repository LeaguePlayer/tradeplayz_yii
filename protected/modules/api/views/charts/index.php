<div id="tv_chart_container"></div>
		<script type="text/javascript">

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
					fullscreen: true,
					symbol: 'BTC',
					interval: 'D',
					debug: false,
					resolution: 'D',
					container_id: "tv_chart_container",
					loading_screen: { backgroundColor: "#000000", foregroundColor: "#000000" },

					datafeed: new Datafeeds.UDFCompatibleDatafeed("<? echo $url_to_api_charts; ?>"),
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

					}
				});

				widget.onChartReady(function() {
						widget.chart().setVisibleRange({from: 1515322800, to:1515326400}, function(){
							console.log('changed_visible');
						});
						console.log(widget.chart());
				});

			})
			})

		</script>
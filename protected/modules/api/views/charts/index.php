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
					container_id: "tv_chart_container",
					loading_screen: { backgroundColor: "#000000" },

					datafeed: new Datafeeds.UDFCompatibleDatafeed("<? echo $url_to_api_charts; ?>"),
					library_path: "<? echo $this->getAssetsUrl(); ?>/charting_library/",
					locale: "en",
					//	Regression Trend-related functionality is not implemented yet, so it's hidden for a while
					drawings_access: { type: 'black', tools: [ { name: "Regression Trend" } ] },
					// disabled_features: ["use_localstorage_for_settings"],
					preset: "mobile",
					overrides: {
						"paneProperties.background": "#222222",
                        "paneProperties.vertGridProperties.color": "#454545",
                        "paneProperties.horzGridProperties.color": "#454545",
						"symbolWatermarkProperties.transparency": 0,
						"scalesProperties.textColor" : "#AAA",
						"mainSeriesProperties.style" : 3,
						"timeScale.rightOffset" : 50,

					}
				});
			})
			})

		</script>
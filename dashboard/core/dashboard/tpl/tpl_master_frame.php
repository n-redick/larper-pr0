<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"> 

        <title>Lychas :: Dashboard</title>

        <link rel="stylesheet" href="./dashboard/css/jquery-ui-1.8.18.custom.css" type="text/css" media="screen">
        <link rel="stylesheet" href="./dashboard/css/jquery.jgrowl.css" type="text/css">
        <link rel="stylesheet" href="./dashboard/css/jquery.jqplot.css" type="text/css">
        <link rel="stylesheet" href="./dashboard/css/icons.css" type="text/css">
        <link rel="stylesheet" href="./dashboard/css/forms.css" type="text/css">
        <link rel="stylesheet" href="./dashboard/css/tables.css" type="text/css">
        <link rel="stylesheet" href="./dashboard/css/ui.css" type="text/css">
        <link rel="stylesheet" href="./dashboard/css/dashboard_style.css" type="text/css">
        <link rel="stylesheet" href="./dashboard/css/responsiveness.css" type="text/css">

        <!-- jQuery -->
        <script src="./dashboard/js/dashboard_jquery.min.js"></script>
        <script src="./dashboard/js/jquery-ui.min.js"></script>
        <!-- jqPlot -->
        <script type="text/javascript" src="./dashboard/js/jquery.jqplot.min.js"></script>
        <script type="text/javascript" src="./dashboard/js/jqplot.barRenderer.min.js"></script>
        <script type="text/javascript" src="./dashboard/js/jqplot.highlighter.min.js"></script>
        <script type="text/javascript" src="./dashboard/js/jqplot.cursor.min.js"></script> 
        <script type="text/javascript" src="./dashboard/js/jqplot.pointLabels.min.js"></script>
        <script type="text/javascript" src="./dashboard/js/jqplot.pieRenderer.min.js"></script> 
        <script type="text/javascript" src="./dashboard/js/jqplot.donutRenderer.min.js"></script>
        <!-- jgrowl -->
        <script type="text/javascript" src="./dashboard/js/jquery.jgrowl.min.js"></script>
        <!-- Knob -->
        <script type="text/javascript" src="./dashboard/js/jquery.knob.js"></script>
        <!-- WYSIHTML5 -->
        <script type="text/javascript" src="./dashboard/js/jquery.wysihtml5.js"></script>
        <!-- SparkLine -->
        <script type="text/javascript" src="./dashboard/js/jquery.sparkline.js"></script><style type="text/css"></style>

        <!-- Caffeine custom JS -->
      

        <script type="text/javascript">
            jQuery(document).ready(function () {
                if (jQuery('#chart_dashboard').length) {
			var chart = jQuery('#chart_dashboard');
			chart.width(chart.parent().width());
			chart.height(chart.parent().height());
			
			var s1 = {[visitor_graph]}
			plot1 = jQuery.jqplot("chart_dashboard", [s1], {
				seriesColors:['#bee058', '#659ebe', '#d3a1ce', '#9bd49c', '#ba7979'],
				animate: ((jQuery.browser.mozilla) ? false : true),
				animateReplot: false,
				cursor: { showTooltip: false },
				grid: {
					backgroundColor: 'transparent',
					gridLineColor: '#121212',
					borderColor: '#121212',
					borderWidth: 0,
					shadowAlpha: 0.03
				},
				series:[
					{	// Serie 2
						rendererOptions: {
							animation: {
								speed: 3000
							}
						}
					}
				],
				seriesDefaults: {
					lineWidth: 3.5,
					markerOptions: {
						show: true,             // wether to show data point markers.
						style: 'filledCircle',  // circle, diamond, square, filledCircle, filledDiamond or filledSquare.
						lineWidth: 2,       // width of the stroke drawing the marker.
						size: 10,            // size (diameter, edge length, etc.) of the marker.
						shadow: true,       // wether to draw shadow on marker or not.
						shadowAngle: 45,    // angle of the shadow.  Clockwise from x axis.
						shadowOffset: 1,    // offset from the line of the shadow,
						shadowDepth: 3,     // Number of strokes to make when drawing shadow.  Each stroke offset by shadowOffset from the last.
						shadowAlpha: 0.1   // Opacity of the shadow
					}
				},
				axesDefaults: {
					pad: 0
				},
				axes: {
					// These options will set up the x axis like a category axis.
					xaxis: {
						tickInterval: 1,
						drawMajorGridlines: false,
						drawMinorGridlines: true,
						drawMajorTickMarks: false,
						rendererOptions: {
							tickInset: 0.5,
							minorTicks: 1
						}
					},
					yaxis: {
						tickOptions: {
							formatString: "%'d"
						},
						rendererOptions: {
							forceTickAt0: true
						}
					}
				},
				highlighter: {
					show: true, 
					showLabel: true, 
					tooltipAxes: 'y',
					sizeAdjust: 7.5 , 
					tooltipLocation : 'n'
				}
			});
			jQuery(window).bind('resize', function(event, ui) {
				// Resize the chart to its parent (or any other container)
				var chart = jQuery('#chart_dashboard');
				chart.width(chart.parent().width());
				chart.height(chart.parent().height());
				// replot the chart
				plot1.replot({resetAxes: true});
			});
                        
                        jQuery('.jqplot-xaxis-tick').each(function(index,value){
                           if(value.innerHTML != '' && 
                                   value.innerHTML < 1){
                               value.innerHTML = (12 + parseInt(value.innerHTML));
                               console.log(value.innerHTML);
                           } 
                        });
		}
            });
        </script>
        <style type="text/css">.jqstooltip { position: absolute;left: 0px;top: 0px;visibility: hidden;background: rgb(0, 0, 0) transparent;background-color: rgba(0,0,0,0.6);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";color: white;font: 10px arial, san serif;text-align: left;white-space: nowrap;padding: 5px;border: 1px solid white;}.jqsfield { color: white;font: 10px arial, san serif;text-align: left;}</style>
    </head>

    <body id="index" class="home wysihtml5-supported jqtransformdone">

        <div id="loading-block" style="display: none;"></div> <!-- Loading block -->

        <!-- Container -->
        <div id="container">

            <!-- Header -->
            <header id="header">
                <figure id="logo">
                    <a href="./Dashboard __ Caffeine Admin Template_files/Dashboard __ Caffeine Admin Template.html" class="logo"></a>
                </figure>

                <section id="general-options">
                    <a href="{[_tpl_path]}?url=dashboard&option" class="settings tipsy-header" original-title="Optionen"></a>
                    <a href="{[_tpl_path]}?url=dashboard&user" class="users tipsy-header" original-title="Benutzer"></a>
                    <a href="{[_tpl_path]}?url=dashboard&admin_messages" class="messages tipsy-header" original-title="Nachrichten"></a>
                    <a href="{[_tpl_path]}?url=dashboard&error_logs" class="messages tipsy-header" original-title="Nachrichten"></a>
                </section>

                <section id="userinfo">
                    <span class="welcome">Hallo <strong>{[username]}</strong>.</span>
                    <span class="last-login">Letzter Login: {[last_login]}</span>
                    <div class="profile">
                        <div class="links">
                            <a href="">Account</a> | 
                            <a href="">Nachrichten</a> | 
                            <a href="{[_tpl_path]}?url=login&logout" class="logout">Logout</a>
                        </div>
                        <!-- <img src="./Dashboard __ Caffeine Admin Template_files/profile-pict.jpg" alt="John Doe"> -->
                    </div>
                </section>
            </header> <!-- /Header -->

            <div class="clear"></div>		

            <!-- Sidebar -->
            <nav id="sidebar">
                <div class="sidebar-top"></div>

                <h3>Navigate</h3>

                <!-- Nav menu -->
                <ul class="nav">
                    {[navigation]}
                </ul> <!-- /Nav menu -->

                <div class="blocks-separator"></div>

                <h3>Stats</h3>
                <section class="widgets">

                    <!-- Sidebar Widgets -->
                    <div class="{[visitors_arrow]}">
                        <span class="title">Visitors</span>
                        <span class="perc"><span data-value="{[visitors_percent]}">{[visitors_percent]}</span>%</span>
                    </div>

                    <div class="{[uploads_arrow]}">
                        <span class="title">Uploads</span>
                        <span class="perc"><span data-value="{[uploads_percent]}">{[uploads_percent]}</span>%</span>
                    </div>

                    <div class="{[comments_arrow]}">
                        <span class="title">Comments</span>
                        <span class="perc"><span data-value="{[comments_percent]}">{[comments_percent]}</span>%</span>
                    </div>
                    <!--
                                        <div class="line-separator"></div>
                    
                                        <div class="one-half center">
                                            <div style="display:inline;width:89px;height:89px;"><canvas width="89" height="89px"></canvas><input class="knob" data-width="89" data-height="89" data-fgcolor="#bee058" data-thickness=".2" data-readonly="true" value="35" data-value="35" readonly="readonly" style="width: 48px; height: 29px; position: absolute; vertical-align: middle; margin-top: 29px; margin-left: -68px; border: 0px; font-weight: bold; font-style: normal; font-variant: normal; font-stretch: normal; font-size: 17px; line-height: normal; font-family: Arial; text-align: center; color: rgb(190, 224, 88); padding: 0px; -webkit-appearance: none; background: none;">35</input></div>
                                            <span class="full-width center">Disk Space</span>
                                        </div>
                    
                                        <div class="one-half center">
                                            <div style="display:inline;width:89px;height:89px;"><canvas width="89" height="89px"></canvas><input class="knob" data-width="89" data-height="89" data-fgcolor="#5479aa" data-thickness=".2" data-readonly="true" value="83" data-value="83" readonly="readonly" style="width: 48px; height: 29px; position: absolute; vertical-align: middle; margin-top: 29px; margin-left: -68px; border: 0px; font-weight: bold; font-style: normal; font-variant: normal; font-stretch: normal; font-size: 17px; line-height: normal; font-family: Arial; text-align: center; color: rgb(84, 121, 170); padding: 0px; -webkit-appearance: none; background: none;">83</input></div>
                                            <span class="full-width center">Bandwidth</span>
                                        </div>
                    -->
                    <!-- /Sidebar Widgets -->

                    <div class="clear"></div>

                </section>
            </nav> <!-- Sidebar -->

            <div id="jgrowl" class="bottom-right jGrowl"><div class="jGrowl-notification"></div><div class="jGrowl-closer highlight ui-corner-all default" style="display: block; opacity: 0.378363;">[ close all ]</div></div>

            <!-- Playground -->
            <section id="playground">

                <!-- Breadcrumb -->
                <div class="three-fourths breadcrumb">
                    <span><a href="{[_tpl_path]}?url=dashboard" class="icon entypo home"></a></span>
                    {[breadcrumbs]}
                    <span class="end"></span>
                </div> <!-- /Breadcrumb -->

                <div class="clear"></div>
                {[TPL_MASTER_CONTENT]}
                <div class="clear"></div>

                <!-- Copyright / Footer -->
                <div class="full-width">
                        <div class="line-separator"></div>
                        <span style="font-family: 'bonvenocflight', Arial, Helvetica, sans-serif;text-align:center;margin-bottom:8px;padding-top:0px;" class="copyright">LYCHAS by <a href="http://www.n-redick.de"> Nico Redick</a></span>
                </div><!-- /Copyright / Footer -->

            </section> <!-- /Playground -->

        </div> <!-- /Container -->


    </body></html>


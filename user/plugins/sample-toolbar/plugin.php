<?php
/*
Plugin Name: YOURLS Toolbar
Plugin URI: http://yourls.org/
Description: Add a social toolbar to your redirected short URLs. Fork this plugin if you want to make your own toolbar.
Version: 1.0
Author: Ozh
Author URI: http://ozh.org/
Disclaimer: Toolbars ruin the user experience. Be warned.
*/

global $ozh_toolbar;
$ozh_toolbar['do'] = false;
$ozh_toolbar['keyword'] = '';

// When a redirection to a shorturl is about to happen, register variables
yourls_add_action( 'redirect_shorturl', 'ozh_toolbar_add' );
function ozh_toolbar_add( $args ) {
	global $ozh_toolbar;
	$ozh_toolbar['do'] = true;
	$ozh_toolbar['keyword'] = $args[1];
}

// On redirection, check if this is a toolbar and draw it if needed
yourls_add_action( 'pre_redirect', 'ozh_toolbar_do' );
function ozh_toolbar_do( $args ) {
	global $ozh_toolbar;
	
	// Does this redirection need a toolbar?
	if( !$ozh_toolbar['do'] )
		return;

	// Do we have a cookie stating the user doesn't want a toolbar?
	if( isset( $_COOKIE['yourls_no_toolbar'] ) && $_COOKIE['yourls_no_toolbar'] == 1 )
		return;
	
	// Get URL and page title
	$url = $args[0];
	$keyword = $ozh_toolbar['keyword'];
	$pagetitle = yourls_get_keyword_title( $ozh_toolbar['keyword'] );

	// Update title if it hasn't been stored yet
	if( $pagetitle == '' ) {
		$pagetitle = yourls_get_remote_title( $url );
		yourls_edit_link_title( $ozh_toolbar['keyword'], $pagetitle );
	}
	$_pagetitle = htmlentities( yourls_get_remote_title( $url ) );
	
	$www = YOURLS_SITE;
	$ver = YOURLS_VERSION;
	$md5 = md5( $url );
	$sql = yourls_get_num_queries();

	// When was the link created (in days)
	$diff = abs( time() - strtotime( yourls_get_keyword_timestamp( $ozh_toolbar['keyword'] ) ) );
	$days = floor( $diff / (60*60*24) );
	if( $days == 0 ) {
		$created = 'today';
	} else {
		$created = $days.' '.yourls_plural( 'day', $days).' ago';
	}
	
	// How many hits on the page
	$hits = 1 + yourls_get_keyword_clicks( $ozh_toolbar['keyword'] );
	$hits = $hits.' '.yourls_plural( 'view', $hits);
	
	// Plugin URL (no URL is hardcoded)
	$pluginurl = YOURLS_PLUGINURL . '/'.yourls_plugin_basename( dirname(__FILE__) );

	// All set. Draw the toolbar itself.
	echo '
<html>
<head>
	<title>MemeBro</title>
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<link rel="apple-touch-startup-image" href="images/fb-splash.png">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="viewport" content="user-scalable=no,width=device-width,height=device-height" />
	<link rel="apple-touch-icon-precomposed" href="/images/facebook.png"/>
	<meta name="apple-touch-fullscreen" content="yes" />
	<meta property="og:title" content="MEME BRO" />
	<meta property="og:type" content="website" />
	<meta property="og:site_name" content="http://www.memebro.com/" />
	<meta property="og:image" content="'.$url.'" />
	<meta property="og:description" content="Need to create a quick meme? MEME BRO can generate your meme via MMS in less than 10 seconds! Just text your meme to (614) MEME-BRO." />
	<link rel="stylesheet" href="css/global.css" type="text/css" media="screen" />
	<link href="http://fonts.googleapis.com/css?family=Knewave|Source+Sans+Pro:200,400,700|Dosis:200,400,800|Cherry+Cream+Soda|Flamenco:300,400|Arbutus|Chewy|Bowlby+One|Mrs+Sheppards|Stardos+Stencil:400,700|Quicksand:300,400,700" rel="stylesheet" type="text/css">
	
</head>
<body>		
		<section class="content">
			<header>
			<a href="http://www.memebro.com/">
			<img class="logo" src="images/memebro64.png" />
			<h1 class="company">MEME BRO</h1>
			</a>
			<div class="title_links">
				<h2 class="title">
					<abbr title="(614) 636-3276">
						<a href="sms:+1-614-MEME-BRO">(614) MEME-BRO</a>
					</abbr>
				</h2>
				<h2 class="title">
					<abbr title="(614) MEME-BRO">
						<a href="sms:+1-614-636-3276">(614) 636-3276</a>
					</abbr>
				</h2>
			</div>
			</header>
			<nav>
				<!-- AddThis Button BEGIN -->
				<div class="addthis_toolbox addthis_counter_style" style="width:50px;">
				<a class="addthis_button_facebook_like" fb:like:layout="box_count"></a>
				<a class="addthis_button_tweet" tw:count="vertical"></a>
				<a class="addthis_button_google_plusone" g:plusone:size="tall"></a>
				<a class="addthis_counter"></a>
				</div>
				<!-- AddThis Button END -->
			</nav>
			<section class="body">
				<img class="addthis_shareable" src="'.$url.'"/><br>
				<!-- AddThis Button BEGIN -->
				<div class="addthis_toolbox addthis_default_style addthis_32x32_style">
				<a class="addthis_button_preferred_1"></a>
				<a class="addthis_button_preferred_2"></a>
				<a class="addthis_button_preferred_3"></a>
				<a class="addthis_button_preferred_4"></a>
				<a class="addthis_button_compact"></a>
				<a class="addthis_counter addthis_bubble_style"></a>
				</div>
				<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
				<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-502407f64d3ce404"></script>
				<!-- AddThis Button END -->
				
			<footer>
				<p>Developed by <a href="http://www.seantburke.com?r=memebro">Sean Thomas Burke</a></p>
				<p>MEME BRO &copy;'.date('Y').'</p>
			</footer>
				<script type="text/javascript">
					var addthis_config = {     
					    services_overlay:\'facebook,twitter,reddit,google+,more\'
					}
				</script>
				<script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-502407f64d3ce404"></script>
				<script type="text/javascript">
				ch_client = "hawaiianchimp";
				ch_width = 320;
				ch_height = 50;
				ch_type = "mobile";
				ch_sid = "Image Link";
				ch_color_site_link = "0000CC";
				ch_color_title = "0000CC";
				ch_color_border = "FFFFFF";
				ch_color_text = "000000";
				ch_color_bg = "FFFFFF";
				</script>
				<script src="http://scripts.chitika.net/eminimalls/amm.js" type="text/javascript">
				</script>
				<script type="text/javascript">
				ch_client = "hawaiianchimp";
				ch_width = 468;
				ch_height = 250;
				ch_type = "mpu";
				ch_sid = "Chitika Default";
				ch_color_site_link = "0000CC";
				ch_color_title = "0000CC";
				ch_color_border = "FFFFFF";
				ch_color_text = "000000";
				ch_color_bg = "FFFFFF";
				</script>
				<script src="http://scripts.chitika.net/eminimalls/amm.js" type="text/javascript">
				</script>
			</section>
		</section>
</body>
</html>';
	
	// Don't forget to die, to interrupt the flow of normal events (ie redirecting to long URL)
	die();
}
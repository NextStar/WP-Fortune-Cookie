<?php
/**
 * @package Fortune Cookie
 * @version 1.0
 */
/*
Plugin Name: Fortune Cookie
Plugin URI: https://github.com/NextStar/WP-Fortune-Cookie
Description: This Plugin will display part of a fortune cookie in the upper right of your admin screen on every page. This plugin is based on the Hello Dolly plugin by Matt Mullenweg, and utilizes data from Larry Price's fortunecookieapi.com
Author: Larry Wickham / Next Star Technologies
Version: 1.0
Author URI: http://nxtar.me
*/

function fortune_get_fortune() {
    //Retrieve the results of the API and decode the JSON to an array
    $fcookie = json_decode(file_get_contents("http://fortunecookieapi.com/v1/cookie"),true);
    /// Reformat into one liners
    $fortune_cookie_pieces[0] = $fcookie[0]['fortune']['message'] ;
    $fortune_cookie_pieces[1] = "English: ".$fcookie[0]['lesson']['english'] ." Chinese: ".$fcookie[0]['lesson']['chinese'] ." Pronunciation: ".$fcookie[0]['lesson']['pronunciation'] ;
    $fortune_cookie_pieces[2] = "Lotto Numbers: ". implode(', ',$fcookie[0]['lotto']['numbers']);

	// And then randomly choose a line
	return wptexturize( $fortune_cookie_pieces[ mt_rand( 0, 2) ] );
}

// This just echoes the chosen line, we'll position it later
function show_fortune() {
	$chosen = fortune_get_fortune();
	echo "<p id='fortune'>$chosen</p>";
}

// Now we set that function up to execute when the admin_notices action is called
add_action( 'admin_notices', 'show_fortune' );

// We need some CSS to position the paragraph
function fortune_css() {
	// This makes sure that the positioning is also good for right-to-left languages
	$x = is_rtl() ? 'left' : 'right';

	echo "
	<style type='text/css'>
	#fortune {
		float: $x;
		padding-$x: 15px;
		padding-top: 5px;		
		margin: 0;
		font-size: 11px;
	}
	</style>
	";
}

add_action( 'admin_head', 'fortune_css' );

?>

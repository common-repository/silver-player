<?php

/*  Copyright 2012  Clown  (email: clownuser@gmail.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * @package Silver_Player
 * @version 1.0
 * 
  Plugin Name: Silver Player 
  Plugin URI: http://wordpress.org/extend/plugins/silver-player/
  Description: Silverlight Player analog to Flash Player
  Author: Clown Clownada
  Version: 1.0
  Author URI: http://project.org/
 */
function set_supportedtypes_options() {
    add_option("silver_player_supportedtypes", "350/250");
}

function unset_supportedtypes_options() {
    delete_option("silver_player_supportedtypes");
}

//add_options_page('Test Options', 'Test Options', 8, 'testoptions', 'demo');
function modify_menu_for_supportedtypes() {
    add_menu_page('Silver Player', 'Silver Player', 8, __FILE__, 'supportedtypes_options');

    
}
// next version add_submenu_page(__FILE__, 'Params Player', 'Params Player', 8, 'sub-page', 'demo_page');
function demo_page() {
    
}

function supportedtypes_options() {

    echo '<div class="wrap"><h2>Silver Player</h2>';
    if ($_REQUEST['submit']) {
        update_supportedtypes_options();
    }
    print_supportedtypes_form();
    echo '</div>';
}
//cheak valid field
function update_supportedtypes_options() {
    $updated = false;
    if ($_REQUEST['silver_player_supportedtypes']) {
        if(silver_player_regex($_REQUEST['silver_player_supportedtypes'])){
        update_option('silver_player_supportedtypes', $_REQUEST['silver_player_supportedtypes']);
        $updated = true;
        }
    }
    if ($updated) {
        echo '<div id="message" class="updated fade">';
        echo '<p>Silver Player options successfully updated!</p>';
        echo '</div>';
    } else {
        echo '<div id="message" class="error fade">';
        echo '<p>Unable to update Silver Player options!</p>';
        echo '</div>';
    }
}
//print form
function print_supportedtypes_form() {
    $val_ahs_supportedtypes = stripslashes(get_option('silver_player_supportedtypes'));
    echo <<<EOF
<p>Silver Player.<br />
 Height and Weight
 <i>Sample 350/250 .</i>
 To post your Videos can easy by shortcode [SilverPlayer url="http://youvideo"]
</p>
<form method="post">
<input type="text" name="silver_player_supportedtypes" size="50" value="$val_ahs_supportedtypes" />

<input type="submit" name="submit" value="Save Changes" />
</form>
EOF;
}

//check for format input
function silver_player_regex($text) {
    if (!preg_match("#^\d{1,3}/\d{1,3}$#", $text)) {
        return false;
    } else {
        return true;
    }

    return $text;
}

//WP_PLUGIN_URL

function silver_player_styles() {
    echo "<!-- for the plugin Document Type Styles -->\n";
    echo "<style>\n.link { background-repeat: no-repeat; padding: 2px 0 2px 20px; }\n";
    //echo "silver { background: #ccc url('" . WP_PLUGIN_URL . "/wp-silver-player/silver.png'); }\n";

    echo "</style>\n\n";
}

//replace shortcode to silverlight object player
function silver_shortcode($atts, $content = null) {
    $opt = explode('/', get_option("silver_player_supportedtypes"));

    $SilverVideo = '<object data="data:application/x-silverlight-2," type="application/x-silverlight-2" width="' . $opt[0] . '" height="' . $opt[1] . '">
		  <param name="source" value="' . WP_PLUGIN_URL . '/wp-silver-player/silverlightvideos/chrometemplate.xap"/>
		  <param name="onError" value="onSilverlightError" />
		  <param name="background" value="white" />
		  <param name="minRuntimeVersion" value="5.0.61118.0" />
		  <param name="autoUpgrade" value="true" />
		  <param name="initparams" value="playerSettings = 
		<Playlist>
               <AutoPlay>false</AutoPlay>
			<AutoRepeat>false</AutoRepeat>
<StaysFullScreenWhenUnfocused>true</StaysFullScreenWhenUnfocused>
<Items>
<PlaylistItem>
	<AudioCodec>WmaProfessional</AudioCodec>
<Description></Description>
<Height>' . $opt[1] . '</Height>
<MediaSource>' . $atts[url] . '</MediaSource>
<ThumbSource></ThumbSource>
<VideoCodec>VC1</VideoCodec>
<Width>' . $opt[0] . '</Width>
</PlaylistItem>
</Items>
</Playlist>"/>
<a href="http://go.microsoft.com/fwlink/?LinkID=149156&v=5.0.61118.0" style="text-decoration:none">
<img src="http://go.microsoft.com/fwlink/?LinkId=161376" alt="Get Microsoft Silverlight" style="border-style:none"/>
</a></object>';
    return $SilverVideo;
}

add_shortcode('SilverPlayer', 'silver_shortcode');
add_action('admin_menu', 'modify_menu_for_supportedtypes');

register_activation_hook(__FILE__, 'set_supportedtypes_options');
register_deactivation_hook(__FILE__, 'unset_supportedtypes_options');

//add_filter('the_content', 'silver_player_regex');
//add_action('wp_head','silver_player_styles');
?>
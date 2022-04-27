<?php
/**
 * @author      Jesús Olazagoitia
 * @license     GPL2+
 *
 * @wordpress-plugin
 * Plugin Name: Plugin with React
 * Version:     1.0.0
 * Description: Un ejemplo de integración de React con WordPress
 * Author:      Jesús Olazagoitia
 * Author URI:  https://goiblas.com/
 * License:     GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('ABSPATH') || exit;

function goiblas_enqueue_assets(){
	// Setting path variables.
	$plugin_app_dir_url =plugin_dir_url( __FILE__ ) . '/';
	$react_app_build = $plugin_app_dir_url .'build/';
	$manifest_url = $react_app_build. 'asset-manifest.json';

	// Request manifest file.
	$request = file_get_contents( $manifest_url );

	if( !$request ) {
		return false;
    }

	$files_data = json_decode($request);
	if($files_data === null) {
		return false;
    }

	if(!property_exists($files_data,'entrypoints')) {
		return false;
    }

	// Get assets links.
	$assets_files = $files_data->entrypoints;

	$js_files = array_filter($assets_files,'goiblas_filter_js_files');
	$css_files = array_filter($assets_files,'goiblas_filter_css_files');

	foreach ($css_files as $index => $css_file){
		wp_enqueue_style('as-booking-form-'.$index, $react_app_build . $css_file);
	}

	foreach ($js_files as $index => $js_file){
		wp_enqueue_script('as-booking-form-'.$index, $react_app_build . $js_file, array(), 1, true);
	}
}

function goiblas_filter_js_files ($file_string){
  return pathinfo($file_string, PATHINFO_EXTENSION) === 'js';
}

function goiblas_filter_css_files ($file_string) {
	return pathinfo( $file_string, PATHINFO_EXTENSION ) === 'css';
}


add_action('wp_enqueue_scripts','goiblas_enqueue_assets');

function goiblas_print_container() {
    ?>
        <div id="react-app"></div>
    <?php
}

add_action('wp_footer', 'goiblas_print_container');
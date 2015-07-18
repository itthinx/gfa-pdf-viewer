<?php
/**
 * gfa-pdf-viewer.php
 *
 * Copyright (c) 2015 "kento" Karim Rahimpur www.itthinx.com
 *
 * This code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header and all notices must be kept intact.
 *
 * @author Karim Rahimpur
 * @package groups
 * @since groups 1.0.0
 *
 * Plugin Name: GFA PDF Viewer
 * Plugin URI: http://www.itthinx.com/plugins/groups-file-access
 * Description: Integrates Groups File Access and PDF Viewer providing the [gfapdfviewer] shortcode version of [pdfviewer] shortcode for files protected by Groups File Access.
 * Version: 1.0.0
 * Author: itthinx
 * Author URI: http://www.itthinx.com
 * Donate-Link: http://www.itthinx.com
 * License: GPLv3
 */

class GFA_PDF_Viewer {
	
	public static function init() {
		add_shortcode( 'gfapdfviewer', array( __CLASS__, 'gfapdfviewer' ) );
	}

	private static function older_ie($version) {
		global $is_IE;
		// Return early, if not IE
		if ( ! $is_IE ) return false;
		// Include the file, if needed
		if ( ! function_exists( 'wp_check_browser_version' ) )
			include_once( ABSPATH . 'wp-admin/includes/dashboard.php' );
		// IE version conditional enqueue
		$response = wp_check_browser_version();
		if ( 0 > version_compare( intval( $response['version'] ) , $version ) )
			return true;
	}

	public static function gfapdfviewer( $atts, $content = '' ) {

// 		remove_shortcode( 'gfapdfviewer' );
// 		$content = do_shortcode( $content );
// 		add_shortcode( 'gfapdfviewer', array( __CLASS__, 'gfapdfviewer' ) );

// 		error_log( var_export( $content,true )); // @todo remove
		
		
		$content = GFA_Shortcodes::groups_file_url( $atts );
		$content = rawurlencode( $content );
		
		
		error_log( var_export( $content,true )); // @todo remove

		if ( !empty($content) ) {//&& filter_var($content, FILTER_VALIDATE_URL) ) {
		
			//TODO: filter URL to check if PDF only
			$options = get_option('pdfviewer_options');
			if ( self::older_ie($options['olderIE']) ) {
		
				$notice = str_replace('%%PDF_URL%%', $content, $options['ta_notice']);
				echo html_entity_decode($notice);
		
			} else {
		
				$atts = shortcode_atts(
						array(
								'width' => $options['tx_width'],
								'height' => $options['tx_height'],
								'beta' => empty($options['beta']) ? 0 : "true",
						),
						$atts,
						'pdfviewer'
				);
		
				$pdfjs_mode = ( $atts['beta'] === "true" ) ? 'beta' : 'stable';
				//$pdfjs_url = plugin_dir_url( __FILE__ ).$pdfjs_mode.'/web/viewer.html?file='.$content;
				$pdfjs_url = plugins_url( 'pdf-viewer' ).'/'.$pdfjs_mode.'/web/viewer.html?file='.$content;
				
				
				error_log( 'url = '.var_export( $pdfjs_url,true )); // @todo remove
		
				$pdfjs_iframe = '<iframe class="pdfjs-viewer" width="'.$atts['width'].'" height="'.$atts['height'].'" src="'.$pdfjs_url.'"></iframe> ';
		
				return $pdfjs_iframe;
			}
		} else {
			return 'Invalid URL for PDF Viewer';
		}
	}
}
GFA_PDF_Viewer::init();

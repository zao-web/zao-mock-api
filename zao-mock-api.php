<?php
/**
 * Plugin Name: Zao Mock API
 * Plugin URI: http://zao.is
 * Description: Create a mock api response by sending query params. Do not use in production. Simply make a GET/POST/PUT/ETC request to <site_url>?mock_api&code=503.
 * Version: 0.1.0
 * Author: Justin Sternberg
 * Author URI: http://zao.is
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Sample URL:
 * <site_url>?mock_api=null&code=503&response[boolean]=1&response[string]=Hello World
 *
 * @since  0.1.0
 *
 * @return void
 */
function zao_mock_api_response() {
	// ?mock_api query param required.
	if ( ! isset( $_GET['mock_api'] ) ) {
		return;
	}

	// code query param optional.. defaults to 200.
	$code = isset( $_GET['code'] ) && is_numeric( $_GET['code'] )
		? absint( $_GET['code'] )
		: 200;

	// response query param optional.. defaults to object.
	if ( isset( $_GET['response'] ) ) {
		$response = $_GET['response'];
	} else {
		$response = array( 'success' => $code < 300, 'data' => 'Hello World' );
	}

	wp_send_json( $response, $code );
}
add_action( 'template_redirect', 'zao_mock_api_response' );

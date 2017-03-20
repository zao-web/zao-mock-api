<?php
/**
 * Plugin Name: Zao Mock API
 * Plugin URI: https://github.com/zao-web/zao-mock-api
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
 * <site_url>?mock_api=1&code=503&response[boolean]=1&response[string]=Hello World
 *
 * To modify if/when the mock api can be used, use the `allow_mock_api` filter:
 *
 * Examples:
 * 	// Disable:
 * 	add_filter( 'allow_mock_api', '__return_false' );
 *
 *  	// Allow for logged-in users only:
 * 	add_filter( 'allow_mock_api', 'is_user_logged_in' );
 *
 *
 * @since  0.1.0
 *
 * @return void
 */
function zao_mock_api_response() {
	// ?mock_api query param required.
	$can_api = isset( $_GET['mock_api'] ) ? $_GET['mock_api'] : false;

	if ( ! apply_filters( 'allow_mock_api', $can_api ) ) {
		return;
	}

	// If checking meta, special handling for meta values here:
	if ( 'meta' === $can_api ) {
		return zao_mock_api_response_with_meta();
	}

	// Ok, let's mock the data instead.

	// code query param optional.. defaults to 200.
	$code = isset( $_GET['code'] ) && is_numeric( $_GET['code'] )
		? absint( $_GET['code'] )
		: 200;


	if ( isset( $_GET['response'] ) ) {
		// response query param optional..
		$response = $_GET['response'];
	} else {
		// defaults to object.
		$response = array( 'success' => $code < 300, 'data' => 'Hello World' );
	}

	wp_send_json( $response, $code );
}
add_action( 'template_redirect', 'zao_mock_api_response' );

/**
 * Checks for meta for the mock api, and sends JSON response with success param
 * and possible data param (if data found).
 *
 * @since  0.1.0
 * @uses   wp_send_json_success()
 * @uses   wp_send_json_error()
 *
 * @return void
 */
function zao_mock_api_response_with_meta() {
	$meta = zao_mock_get_api_meta();

	if ( $meta || is_numeric( $meta ) ) {
		// You asked for meta, so here you go.
		wp_send_json_success( $meta );
	}

	// You asked for meta, but it wasn't found.
	wp_send_json_error();
}

/**
 * Get the queried object's mock_api meta value.
 *
 * @since  0.1.0
 *
 * @return mixed
 */
function zao_mock_get_api_meta() {
	$meta = false;
	$object = get_queried_object();

	/**
	 * Use this filter to change the meta key mock_api is looking for.
	 * Use with caution as this will expose data to the public.
	 *
	 * @var string $meta_key The meta key mock-api uses to show meta.
	 */
	$meta_key = apply_filters( 'mock_api_meta_key', '_mock_api' );

	if ( $object instanceof WP_Post ) {
		$meta = get_post_meta( $object->ID, $meta_key );
	}
	if ( $object instanceof WP_Term ) {
		$meta = get_term_meta( $object->ID, $meta_key );
	}

	return $meta;
}

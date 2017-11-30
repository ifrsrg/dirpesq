<?php

if ( ! function_exists( 'fetchURL' ) ) {

	function fetchURL( $url ) {

		$args = array(
			'user-agent'  => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5',
		);

		$response = wp_remote_get( $url, $args );

		$output = false;

		if ( wp_remote_retrieve_response_code( $response ) < 400 ) {
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				$output = $response['body'];
			}
		}

		return $output;

	}

}
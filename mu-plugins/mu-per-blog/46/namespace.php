<?php 

namespace Websites_Fuer_Figuren_Theater_Demos;

use FT_CORESITES;

use function add_action;
use function add_filter;
use function add_query_arg;
use function get_site_url;



/**
 * websites.fuer.figuren.theater/demos
rewrite the homepage of the (just adminsitrative) network
to the PT archive for 'ft_theme's
 */
add_action( 'plugins_loaded', function (){

	if ( '/demos' === \untrailingslashit( $_SERVER['REQUEST_URI'] ) )
		if ( \wp_safe_redirect( '/themes/', 302, 'WordPress' ) )
			exit;

	add_action( 'template_redirect', __NAMESPACE__ . '\\re_add_noblogredirect', -1 );
}, 0 );



function re_add_noblogredirect(){
	// re-add what was removed by Figuren_Theater\Routes\Noblogredirect_Fix;
	add_action( 'template_redirect', 'maybe_redirect_404' );
	// Redirect to a non-existent URL of the mainsite and trigger 404
	add_filter( 'blog_redirect_404', __NAMESPACE__ . '\\redirect_404_to_websfft' );
}

function redirect_404_to_websfft( string $no_blog_redirect ) : string {
	global $wp;
	$current_slug = add_query_arg( [], $wp->request );

	$coresites = array_flip( FT_CORESITES );
	return get_site_url( 
		$coresites['webs'], 
		'/404/demos-' . $current_slug . '/',
		'https'
	);
}

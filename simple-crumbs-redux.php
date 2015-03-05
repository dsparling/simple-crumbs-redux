<?php
/*
Plugin Name: Simple Crumbs Redux
Plugin URI: http://plugins.svn.wordpress.org/simple-crumbs-redux
Description: Simple Crumbs  Redux- Generates a breadcrumb trail for pages and blog entries. Requires use of permalinks and php > 4.1.0, tested up to WP 4.1.1.
Author: Doug Sparling
Version: 1.1.0
Author URI: http://www.dougsparling.org
Note: link/crumb information from $query_string
Note: page/post information from $post
Note: using permalink info for making links
Note: using permalink structure for bootstrapping unrolled recursions (deepest to topmost)
Usage Examples:
Usage: <?php echo do_shortcode('[simple_crumbs root="Home" /]') ?>
Usage: [simple_crumbs root="Some Root" /]
Usage: [simple_crumbs /]
License: Released under GNU v2 June 1991

Simple Crumbs Redux is a resurrection of the Simple Crumbs plugin by Can Koluman (http://wordpress.org/plugins/simple-crumbs/) 0.2.5 codebase. Version 1.0.0 of Simple Crumbs Redux fixes a fatal error generated when using PHP 5.4 and greater (and a deprecation warning with PHP 5.3). I'm keeping the original short code (though you can also use [simple_crumbs_redux]) so this plugin will work as a drop in replacement for Simple Crumbs.

Simple Crumbs Redux - Generates a breadcrumb trail for pages and blog entries. Requires use of permalinks and PHP >= 4.1.0. Tested up to WordPress 3.6 and PHP 5.5.

fixes in this version:
Fixed 'PHP Fatal error: Call-time pass-by-reference has been removed' with PHP 5.4 and greater.
*/


class SimpleCrumbsRedux {
	private $titles = array();
	
	public function __construct() {
		add_shortcode( 'simple_crumbs', array( $this, 'simplecrumbs_shortcode' ) );
		add_shortcode( 'simple_crumbs_redux', array( $this, 'simplecrumbs_shortcode' ) );
	}

	public function simplecrumbs_shortcode( $attr ) {
		$divider = ' &gt; ';
		$strCrumb = '';
		$baseURL = get_bloginfo( 'url' );
	
		//use post id to get original title
		//note: ideally should sanitise title to HTML conformant
		global $post;
		global $query_string;
		$query_array = wp_parse_args( $query_string );
	
		extract( shortcode_atts( array(
			'root' => ''
		), $attr ) );
		
		$postID = ( int ) $post->ID;
		$post_name = $post->post_name;
		$post_type = $post->post_type;  //select page (default) versus post
		
		//templating
		$htmlTemplate = '<a class="navCrumb lnk" href="[__1__]">[__2__]</a>';
		$pattern = array( '/\[__1__\]/','/\[__2__\]/' );
		
		//make permalink from query string
		$permalink = $this->make_permalink( $query_array, $post_type );
		
		$this->titles[$post_name] = get_the_title( $postID );
		$this->get_path_titles( $post );
		
		//populate crumb structure
		if ( $root ) {
			$replace = array( $baseURL, ucfirst( $root ) );
			$strCrumb = preg_replace( $pattern, $replace, $htmlTemplate );
		}
		
		$tok = strtok( $permalink, '/');
		
		while( $tok ) {
			$baseURL .= sprintf( "/$tok" );
			
			if ( $tok <> 'category' ) $strCrumb .= ( $strCrumb ) ? $divider : '';
			
			switch ( $tok ) {
				// breadcrumb components which are not linked
				// can be customized
				case 'attachment':
				case 'share':
				case 'tag':
				case 'Search Results':
					$strCrumb .= ucfirst( $tok );
					break;
				case 'category':
					break;
				default:
					if ( isset( $query_array['tag'] ) ) $this->titles[$tok] = single_tag_title( "", false );
					//if (isset($query_array['category_name'])) $titles[$tok] = $query_array['category_name'];
					$replace = ( isset( $this->titles[$tok] ) ) ? array( $baseURL . '/', $this->titles[$tok] ) : array( $baseURL . '/', ucfirst( $tok ) );
					$strCrumb .= preg_replace( $pattern, $replace, $htmlTemplate );
			}
			
			$tok = strtok( '/' );
				
		}
		
		return $strCrumb;
		
	}
	
	function get_path_titles( $post ) {
		$post_parent;
		
		if ( $post->post_parent ) {
			$post_parent = get_post( $post->post_parent );
			$this->titles[$post_parent->post_name] = get_the_title( $post_parent );
			$this->get_path_titles( $post_parent );
		}
		
		return;
	}
	
	// mimics get_permalink, does not parse
	// page numbers
	// returns ordered string
	function make_permalink( $array, $post_type ) {
		
		if ( isset( $array['s'] ) ) return 'Search Results';
		
		$base_URL = get_bloginfo( 'url' );
		$permalink = get_permalink();
		
		switch ( $post_type ) {
			//reconstruct permalink to match link selection
			case 'post':
				$permalink = '/';
				if ( isset($array['category_name'])) $permalink .= '/category/' . urldecode( $array['category_name'] ) . '/';
				if ( isset($array['year'] ) ) $permalink .= urldecode( $array['year'] ) . '/';
				if ( isset($array['monthnum'] ) ) $permalink .= urldecode( $array['monthnum'] ) . '/';
				if ( isset($array['day'] ) ) $permalink .= urldecode( $array['day'] ) . '/';
				if ( isset($array['name'] ) ) $permalink .= urldecode( $array['name'] ) . '/';
				
				if ( isset($array['tag'] ) ) $permalink .= 'tag/' . urldecode( $array['tag'] ) . '/';
				break;
			case 'page':
			case 'attachment':
			default:
				$permalink = str_replace( $base_URL, '', $permalink );
		}
		
		//strip first and last '/'
		//we will add these later
		$permalink = preg_replace( '/^\/(.+)\/$/', '${1}', $permalink );
		
		return $permalink;
	
	}

}

$simpleCrumbsRedux = new SimpleCrumbsRedux();

?>

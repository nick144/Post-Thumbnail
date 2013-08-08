<?php

/*
Plugin Name: Post Article
Plugin URI: 
Description: Pages With Grid view including Thumbnail
Version: 1.0
Author: Dominic Fernandes
Author URI: 
License: A GPL2
*/

add_shortcode( 'pArticle', 'post_articles' );
add_action( 'wp_head', 'pteaser_css' );
add_filter( 'excerpt_more', 'new_excerpt_more' );
function post_articles($atts){

		extract( shortcode_atts( array(
		'category'  => '',
		'id' 		=> '',
		'num'	 	=> '',
		'orderby' 	=> '',
		'exclude'	=> ''
		
		), $atts ) );


		if(empty($category)){
			$cat = '';	
		}else{
			
			$cat = explode(',', $category);
			
		}
		
		if(empty($orderby)){
			$order = 'post_date';	
		}else{
			
			$order = explode(',', $orderby);
			
		}
		
		if(empty($id)){
			$uid = '';
		}else{
			if(count($id) <= 1){
				$uid = (int)$id;
			}
			else{
			$uid = (int)explode(',', $id);
			}
		}
		
		
		if(empty($exclude)){
			$ex = '';
		}else{
			if(count($ex) <= 1){
				$uid = (int)$exclude;
			}
			else{
			$uid = (int)explode(',', $exclude);
			}
		}

		
		$args = $args = array(
			'posts_per_page'   => $num,
			'offset'           => 0,
			'category_name'    => $cat,
			'orderby'          => $order,
			'order'            => 'DESC',
			'include'          => $id,
			'exclude'          => $ex,
			'meta_key'         => '',
			'meta_value'       => '',
			'post_type'        => 'post',
			'post_mime_type'   => '',
			'post_parent'      => '',
			'post_status'      => 'publish',
			'suppress_filters' => true 
		);
		 
		$lastposts = get_posts($args);


	  $html =	"<div class='teaser_wrapper'>";

		foreach($lastposts as $posts) : 
		
		$html .='<div class="img_thumb">';
		$html .= '<div class="details">';
		
		$medium_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($posts->ID), 'small');
		$html .= '<h3 class="title_link"><a href="' .get_permalink( $posts->ID ) .'">' . $posts->post_title .'</a></h3>';
		$html .= '<p>By '. ucfirst(get_the_author()) .' <span class="posts-date">' . get_the_date() .'</span></p>';
		$html .= '<a href="#"><img src="'. $medium_image_url[0] .'"  style="width:190px;height:128px;"></a>';
		$html .= '<p>' . substr(strip_tags($posts->post_content), 0, 50) .'  <a href="' .get_permalink( $posts->ID ) .'">more..</a></p>';
		$html .= '<p class="comments"><a href="' .get_permalink( $posts->ID ) .'"><span class="comment-flag"></span>Comments  ' . ($posts->comment_count) .'</a></p>';
		$html .= '</div><div class="clear"></div></div>';
		
		endforeach;

$html .= '</div>'; 

	return $html; 

}

function new_excerpt_more( $more ) {
	return ' <a class="read-more" href="'. get_permalink( get_the_ID() ) . '">Read More</a>';
}

function pteaser_css()
{
?>
	<link rel="stylesheet" type="text/css" href="<?php echo plugins_url( 'css/style.css' , __FILE__ ); ?>" />
<?php
}
?>
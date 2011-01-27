<?php
/*	
Plugin Name: Digress.it Enhancements (University of Leicester)
Plugin URI: http://digress.it
Description:  University of Leicester customizations for Digress.it
Author: Eddie A Tejeda
Version: 1.0
Author URI: http://www.visudo.com
License: GPLv2 (http://creativecommons.org/licenses/GPL/2.0/)

*/


add_action('init', 'digressit_leicester_flush_rewrite_rules' );
add_action('public_ajax_function', 'add_comment_tag_ajax');


add_action('wp_print_styles', 'digressit_leicester_print_styles');
add_action('wp_print_scripts', 'digressit_leicester_print_scripts' );

add_action('digressit_custom_comment_footer', 'digressit_leicester_add_comment_tags');
add_action('digressit_custom_commenbox_header', 'digressit_leicester_show_comment_tags');

add_action('add_lightbubble', 'digressit_lightbubble_leicester_comment_tags');
add_action('add_digressit_leicester_link', 'digressit_leicester_digressit_leicester_link');

add_action('custom_default_top_menu', 'digressit_leicester_top_menu');


add_action('add_commentbrowser', 'commentbrowser_comments_by_tag');


// Flush your rewrite rules if you want pretty permalinks
function digressit_leicester_flush_rewrite_rules() {
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}




function commentbrowser_comments_by_tag(){
	global $wp;
	echo "<h3>Comments by Tags</h3>";
	commentbrowser_list_tags();
	return get_comments_by_tag(urldecode($wp->query_vars['commentbrowser_params']));
}


function commentbrowser_list_tags(){
	global $wpdb, $wp;
	$query = 'SELECT *, COUNT(*) comment_tag_count FROM '.$wpdb->commentmeta.' WHERE meta_key = "comment_tag" GROUP BY meta_value';
	$commenttags = $wpdb->get_results( $query);		
	
	?>
	
	<ol class="navigation">
	<?php
	foreach($commenttags as $tag): ?>
	<?php
	$permalink = get_bloginfo('siteurl')."/".$wp->query_vars['commentbrowser_function'].'/'.$tag->meta_value;
	?>
	<li><a href="<?php echo $permalink; ?>"><?php echo $tag->meta_value; ?> (<?php echo $tag->comment_tag_count; ?>)</a></li>
	<?php endforeach;
	?>
	</ol>
	<?php

		
}

function get_comments_by_tag($tag){

	global $wpdb;
	$query = 'SELECT * FROM '.$wpdb->comments.' c, '.$wpdb->commentmeta.' m WHERE m.meta_key = "comment_tag" AND  m.meta_value = "'.$tag.'"  AND c.comment_ID = m.comment_id GROUP BY c.comment_ID';
//	echo $query;
	$comments = $wpdb->get_results( $query);
//	var_dump($comments);
	return $comments;		
	
}





function digressit_leicester_digressit_leicester_link(){
	?>
	<li><a href="<?php bloginfo('home'); ?>/comments-by-tag">Comments by Tag</a></li>
	<?php
}

function digressit_leicester_print_styles(){
	if(is_single()):
	?>
	<link rel="stylesheet" href="<?php echo WP_PLUGIN_URL .'/' . str_replace("/", "", str_replace(basename( __FILE__),"",plugin_basename(__FILE__))); ?>/leicester.css" type="text/css" media="screen" />
	<?php
	endif;
}

function digressit_leicester_print_scripts(){
	if(is_single()):
	wp_enqueue_script('digressit.leicester', WP_PLUGIN_URL .'/' . str_replace("/", "", str_replace(basename( __FILE__),"",plugin_basename(__FILE__))).'/leicester.js', 'jquery', false, true );
	endif;
}


function digressit_leicester_show_comment_tags(){
	?>
	<div id="lightbubble-leicester-comment-tags">
	<div class="bubble">
		<form class="ajax-auto-submit" method="post"  id="add-comment-tag">
			<blockquote>
				<p>
					Add Tag
					<input type="text" id="comment_tag" name="comment_tag">
					<input type="hidden" id="comment_tag_id" name="comment_id">
					<input disabled='disabled' type="submit">
				</p>
			</blockquote>
		
			<cite>
				
			</cite>
		</form>
		</div>
	</div>
	<?php
}


function digressit_leicester_add_comment_tags(){
	global $comment;
	?>
	

	<?php /* if(is_user_logged_in()):*/ ?>
	<span class="lightbubble lightbubble-leicester-comment-tags small-button" title="<?php echo  $comment->comment_ID; ?>">add tag</span>
	<?php /*endif;*/ ?>

	<span class="current-leicester-comment-tags">
	
	<?php
	$current_tags  = (array)get_metadata('comment', $comment->comment_ID, 'comment_tag') ;
	?>
	
	
	<?php if(count($current_tags)): ?>
	<b>Tags:</b> &nbsp;
	<?php endif; ?>
	
	<?php
	foreach($current_tags as $key=>$tag){
	
		?><a href='<?php echo  get_bloginfo('home')."/comments-by-tag/".$tag; ?>'><span class='tag'><?php echo $tag; ?></span></a><?php
	
	}
	?>
	</span>	
	<?php
	
}

function digressit_lightbubble_leicester_comment_tags(){
	?>
	
	<div class="lightbubble lightbubble-content" id="lightbubble-leicester-comment-tags">
		<div class="rounded">
		<div class="loading" style="display: none;"></div>	
		<form name="tagform" id="add-comment-tag" action="/" method="post">

			<h3>Tags</h3>
			<div id="current-tags">
			</div>
			<input name="tag" id="comment-tags" type="text" value="">
			<input name="tags-comment-id" id="tags-comment-id" type="hidden" value="">

			<span class="lightbubble-submit ajax" tabindex="100" > Add Tag <span class="loading-bars"></span></span>
			<span class="lightbubble-close lightbubble-close-icon"></span>

		</form>
		</div>
	</div>	
	
	<?php
	
}



function digressit_leicester_top_menu(){
	?>
	<li><a href="<?php bloginfo('home'); ?>/comments-by-tag/">Comments by Tag</a></li>
	<?php
}


function add_comment_tag_ajax($request_params){
	extract($request_params);

	if(add_metadata('comment', $request_params['comment_id'], 'comment_tag', $request_params['comment_tag'])){
		die(json_encode(array('status' => 1, "message" => $request_params)));
	}
	else{
		die(json_encode(array('status' => 0, "message" => $request_params)));		
	}
}




?>

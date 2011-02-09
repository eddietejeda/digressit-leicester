jQuery(document).ready(function() {



	AjaxResult.add_comment_tag = function(data) {


		var title = jQuery('#lightbubble-leicester-comment-tags').parent().attr('title');
		
		
		var new_html = jQuery('#comment-'+current_blog_id+'-'+title+' .current-leicester-comment-tags').html() + '<a href="'+siteurl+'/comments-by-tag/'+data.message.comment_tag+'">'  + data.message.comment_tag + '</a>';

		var tag_count =  jQuery('#comment-'+current_blog_id+'-'+title+' .comment_tag').length;
		
		alert(tag_count);
		
				
		jQuery('#comment-'+current_blog_id+'-'+title+' .current-leicester-comment-tags').html(new_html);
	
		jQuery('#comment_tag').val('');
		jQuery('#lightbubble-leicester-comment-tags').fadeOut('slow');

	}


	jQuery('.lightbubble-leicester-comment-tags').click(function(e){
		var comment_id = jQuery(this).attr('title');
		jQuery('#comment_tag_id').val(comment_id);
	});
	
	
	
	jQuery('#lightbubble-leicester-comment-tags').hover(function(e){

	}, function(e){
		
		jQuery('#comment_tag_id').val('');
		jQuery('#comment_tag').val('');
		jQuery('#lightbubble-leicester-comment-tags').fadeOut();
		
		
	});
	

	
});
<?php

	/*
		Plugin Name: Koyocreateive Like Button
		Plugin URI: http://koyocreative.com
		description: add like button your posts
		Author: Aleksandar Kojovic
		Author URI: http://koyocreative.com
		License: GPL2
	*/

	function kc_like($content) 
	{

		// Check if single post
		if(is_singular('post') ) 
		{
			// Start output buffering
			ob_start()

			?>

				<div class="like-btn">
					<a href="<?php echo add_query_arg('post_action', 'like'); ?>">
						<i class="far fa-heart"></i> <?php echo kc_get_like_count('likes') ?> Love It 
					</a>		
				</div>
		
			<?php	

			// End output buffering and add output to variable 
			$output = ob_get_clean();

			// Concentrate our buttons with the post content
			return $content . $output;

		} 
		else 
		{
			// Return untouched post content for pages and other post types
			return $content;
		}

	}
	add_filter('the_content', 'kc_like');

	// Get like count
	function kc_get_like_count($type = 'likes') 
	{
		$current_count = get_post_meta(get_the_id(), $type, true);

		return ($current_count ? $current_count : 0);
	}

	// Proces the like by updating the post meta
	function kc_process_like() 
	{
		$processed_like = false;
		$redirect 		= false;

		// Check if like or dislike
		if(is_singular('post')) 
		{
			if( isset($_GET['post_action']) ) 
			{
				if( $_GET['post_action'] == 'like' )
				{

					// Like 
					$like_count = get_post_meta(get_the_id(), 'likes', true);

					$like_count ? $like_count = $like_count + 1 : $like_count = 1;

					$processed_like = update_post_meta(get_the_id(), 'likes', $like_count);

				} 

				if($processed_like) $redirect = get_the_permalink();
			}
		}

		// Redirect
		if($redirect) 
		{
			wp_redirect($redirect);
			die;
		}
	}
	add_action('template_redirect', 'kc_process_like');

	// Adding Stylesheet
	function kc_add_stylesheet() 
	{
		wp_enqueue_style('kc_like_stylesheet', plugins_url('kc_like.css', __FILE__) );
	}
	add_action('wp_enqueue_scripts', 'kc_add_stylesheet');
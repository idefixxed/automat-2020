<?php
// Child theme
// https://developer.wordpress.org/themes/advanced-topics/child-themes/#enqueueing-styles-and-scripts
// https://kinsta.com/blog/twenty-twenty-one-theme/#how-to-build-a-child-theme-on-twenty-twentyone
// https://kinsta.com/blog/twenty-twenty-theme/#child-theming
add_action( 'wp_enqueue_scripts', 'automat_parent_styles');
function automat_parent_styles() {
	wp_enqueue_style( 'parent', get_template_directory_uri() . '/style.css' );
}

// Custom Post Meta - single_top
add_action( 'after_setup_theme', function() {
	add_filter( 'twentytwenty_post_meta_location_single_top', function() {
		?>
			<div class="post-meta-wrapper<?php echo esc_attr( $post_meta_wrapper_classes ); ?>">
				<ul class="post-meta<?php echo esc_attr( $post_meta_classes ); ?>">
					<li class="post-date meta-wrapper">
						<span class="meta-icon">
							<span class="screen-reader-text"><?php _e( 'Post date', 'twentytwenty' ); ?></span>
							<?php twentytwenty_the_theme_svg( 'calendar' ); ?>
						</span>
						<span class="meta-text">
							<a href="<?php echo get_day_link(get_post_time('Y'), get_post_time('m'), get_post_time('j'));  ?>"><?php the_time( get_option( 'date_format' ) ); ?></a>
						</span>
					</li>
				<?php
					$post_tags = get_the_tags();
					if ( ! empty ( $post_tags ) ) {
				?>
					<li class="post-tags meta-wrapper">
						<span class="meta-icon">
							<span class="screen-reader-text"><?php _e( 'Tags', 'twentytwenty' ); ?></span>
							<?php twentytwenty_the_theme_svg( 'tag' ); ?>
						</span>
						<span class="meta-text">
							<?php the_tags( '', ', ', '' ); ?>
						</span>
					</li>
				<?php
					}
					//else { echo 'no tags'; }
				?>
				<?php
					if ( ! empty ( $comments ) ) {
				?>
					<li class="post-comment-link meta-wrapper">
						<span class="meta-icon">
							<?php twentytwenty_the_theme_svg( 'comment' ); ?>
						</span>
						<span class="meta-text">
							<?php comments_popup_link(); ?>
						</span>
					</li>
				<?php
					}
					//else { echo 'no comments'; }
				?>
				<?php
					if ( is_sticky() ) {
				?>
					<li class="post-sticky meta-wrapper">
						<span class="meta-icon">
							<?php twentytwenty_the_theme_svg( 'bookmark' ); ?>
						</span>
						<span class="meta-text">
							<?php _e( 'Sticky post', 'twentytwenty' ); ?>
						</span>
					</li>
				<?php
					}
					//else { echo 'not a sticky post'; }
				?>
				</ul>
			</div>
		<?php
		}
	);
	} 
);

// Custom Post Meta - single_top
// Another (easy) way how to arrange post meta stuff
/*add_filter(
	'twentytwenty_post_meta_location_single_top',
	function() { 
	if( get_comments_number() ) { 
		return array(
			//'author',
			'post-date',
			'comments',
			'sticky',
			'tags',
		);
	} else {
		return array(
			//'author',
			'post-date',
			'sticky',
			'tags',
		);
	}}
);*/

// Custom Post Meta - single_bottom
add_filter(
	'twentytwenty_post_meta_location_single_bottom',
	array(
		//'tags',
	)
);

// https://stackoverflow.com/questions/27573017/failed-to-execute-postmessage-on-domwindow-https-www-youtube-com-http
remove_action( 'wp_head', 'wp_resource_hints', 2 );

// Read more link
function modify_read_more_link() {
	return '<a class="more-link" href="' . get_permalink() . '">&rarr;</a>';
}
add_filter( 'the_content_more_link', 'modify_read_more_link' );

// Automatically set the image Title, Alt-Text, Caption & Description upon upload
add_action( 'add_attachment', 'my_set_image_meta_upon_image_upload' );
function my_set_image_meta_upon_image_upload( $post_ID ) {
	// Check if uploaded file is an image, else do nothing
	if ( wp_attachment_is_image( $post_ID ) ) {
		$my_image_title = get_post( $post_ID )->post_title;
		
		// Sanitize the title: remove hyphens, underscores & extra spaces:
		// $my_image_title = preg_replace( '%\s*[-_\s]+\s*%', ' ',
		// $my_image_title );
		
		// Sanitize the title: capitalize first letter of every word (other letters lower case):
		// $my_image_title = ucwords( strtolower( $my_image_title ) );
		
		// Create an array with the image meta (Title, Caption, Description) to be updated
		// Note: comment out the Excerpt/Caption or Content/Description lines if not needed
		$my_image_meta = array(
			// Specify the image (ID) to be updated
			'ID' => $post_ID,
			// Set image Title to sanitized title
			'post_title' => $my_image_title,
			// Set image Caption (Excerpt) to sanitized title
			'post_excerpt' => $my_image_title,
			// Set image Description (Content) to sanitized title
			'post_content' => $my_image_title,
		);

		// Set the image Alt-Text
		update_post_meta( $post_ID, '_wp_attachment_image_alt',
		$my_image_title );
		
		// Set the image meta (e.g. Title, Excerpt, Content)
		wp_update_post( $my_image_meta );
	}
}

// Login "W" logo link
add_filter('login_headerurl', 'put_my_url');
function put_my_url(){
	return "https://automat.idefixx.cz/";
}

// Elegant Themes Monarch plugin stuff
add_action( 'init', 'changeActions' );
function changeActions () {
	remove_action( 'wp_head', 'et_preload_fonts', 10 );
}

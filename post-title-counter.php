<?php
/*
Plugin Name: Post Title Counter
Plugin URI: http://wpleet.com/wordpress-development/
Description: Display post title count in real-time when creating your post in your Wordpress blog.
Version: 1.1
Author: Oscar De Gracia Jr.
Author URI: http://wpleet.com/about/

Copyright 2010 Oscar De Gracia Jr (email wpleet@gmail.com)

This script is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This script is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/
$script_name = $_SERVER['SCRIPT_NAME'];
add_action('admin_menu', 'ptc_settings');
add_action('add_meta_boxes', 'ptc_add_this_to_post');

if( $script_name == '/wp-admin/post.php' || $script_name == '/wp-admin/post-new.php' ):	
	add_action('init', 'ptc_load_scripts');
endif;

register_activation_hook( __FILE__, 'load_default_options' );
register_deactivation_hook( __FILE__, 'remove_default_options' );
if( $_POST['action'] == 'ptc_save' ):
	add_action( 'init','save_opt_changes');
endif;
function ptc_settings() {
	add_options_page('Post Title Counter', 'Post Title Counter', 'manage_options', 'ptc_options', 'ptc_settings_page');
}
function ptc_add_this_to_post(){
	add_meta_box('post-title-counter','Post Title Count', 'ptc_counter_html', 'post', 'side', 'high');
	add_meta_box('post-title-counter','Page Title Count', 'ptc_counter_html', 'page', 'side', 'high');
}
function ptc_load_scripts(){
	wp_enqueue_style('ptc_css',WP_PLUGIN_URL . '/post-title-counter/ptc-style.css','','1.1');
	wp_enqueue_script('ptc_js',WP_PLUGIN_URL . '/post-title-counter/js/ptc-script.js',array('jquery'),'1.1',true );
}
function get_title_count(){
	global $post;
	return strlen($post->post_title);
}
function load_default_options(){
	add_option( 'ptc_maxcount', 60 );
}
function remove_default_options(){
	delete_option( 'ptc_maxcount' );
}
function get_maxcount_class(){
	global $post;
	$class = "";
	if(strlen($post->post_title) > get_option('ptc_maxcount')):
		$class = "ptc-over";
	endif;
	return $class;
}function save_opt_changes(){
	check_admin_referer('ptc-update-options');
	update_option( 'ptc_maxcount', $_POST['max_count'] );
	$_POST['notice'] = "Settings Saved";
}
function ptc_counter_html(){?>
	<div id="ptc-container">
		<input type="hidden" id="ptc-maxcount" value="<?php _e(get_option('ptc_maxcount')); ?>"/>
		<div id="ptc-count" class="post-title-count <?php _e(get_maxcount_class()); ?>"><?php echo get_title_count(); ?></div>
		<div id="ptc-extra">
			<span id="clear-post-title" class="button-secondary"><?php _e('Clear Post Title'); ?></span>
		</div>
	</div>
	<?php
}
function ptc_settings_page(){?>
	<div class="wrap">
		<div id="icon-edit-pages" class="icon32"></div>
		<h2><?php _e('Post Title Counter Settings');?></h2>
		<?php if($_POST['notice']):?>
			<div class="updated fade"><p><strong><?php echo $_POST['notice'];?></strong></p></div>
		<?php endif; ?>
		<form method="post" action="" enctype="multipart/form-data">
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><label for="blogname">Max Title Count:</label></th>
						<td><input type="text" class="regular-text" value="<?php _e(get_option('ptc_maxcount')); ?>" name="max_count"></td>
					</tr>
					<tr valign="top">
						<td>
							<?php if( function_exists( 'wp_nonce_field' )) wp_nonce_field('ptc-update-options'); ?>
							<input name="action" value="ptc_save" type="hidden" />
							<input class="button-primary" type="submit" name="Save" value="<?php _e('Save Options'); ?>" />
						</td>
					</tr>
				</tbody>
				
			</table>
		</form>
	</div>
<?php
}
?>
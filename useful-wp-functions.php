/* simple get posts function */

function custom_get_posts($number = -1, $type = ''){
	$customPosts = new WP_Query(
		array(
		'posts_per_page' => $number,
		'post_type' => $type,
		'orderby' => 'date',
		'order' => 'DESC',
		'post_status' => 'publish',
		'suppress_filters' => 0
		)
	);
	return $customPosts;
}

/* get posts by ACF custom field */

function get_posts_by_custom_field($numberposts = -1, $customPostType = '', $meta_key = '', $meta_value = ''){
  $customPosts = new WP_Query(
    array (
  	'numberposts'	=> $numberposts,
  	'post_type'		=> $customPostType,
  	'meta_key'		=> $meta_key,
  	'meta_value'	=> $meta_value
    )
  );
  return $customPosts;
}
/* set posts views */
/* copy wpb_set_post_views and wpb_track_post_views and wpb_get_posts to get posts by views */
function wpb_set_post_views($postID)
{
  $count_key = 'wpb_post_views_count';
  $count = get_post_meta($postID, $count_key, true);
  if ($count == '') {
    $count = 0;
    delete_post_meta($postID, $count_key);
    add_post_meta($postID, $count_key, '0');
  } else {
    $count++;
    update_post_meta($postID, $count_key, $count);
  }
}
//To keep the count accurate, get rid of prefetching
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

/* Action popular post count on every page */
function wpb_track_post_views($post_id)
{
  if (!is_single()) return;
  if (empty($post_id)) {
    global $post;
    $post_id = $post->ID;
  }
  wpb_set_post_views($post_id);
}
add_action('wp_head', 'wpb_track_post_views');
/* get posts by views  */
function wpb_get_posts($number = -1, $type = ''){
	$popularpost = new WP_Query(
		array(
		'posts_per_page' => $number,
		'post_type' => $type,
		'meta_key' => 'wpb_post_views_count',
		'orderby' => 'meta_value_num',
		'order' => 'DESC'
		)
	);
	return $popularpost;
}
/* copy wpb_set_post_views and wpb_track_post_views and wpb_get_posts to get posts by views */

/* list taxonomies  */

function list_taxonomies($post_id, $post_type)
{
  $list = wp_get_object_terms($post_id, $post_type);
  $terms = '';
  foreach ($list as $k => $v) {
    $terms .= $v->slug . ' ';
  }
  return $terms;
}
/*  change login logo */
function my_custom_login_logo() {
echo '<style type="text/css">
h1 a {
	background-image:url('.get_bloginfo('template_directory').'rest-of-the-link-to-logo) !important;
	background-size: contain!important;
  height: 50px!important;
  width: 189px!important;
}
</style>';
}
add_action('login_head', 'my_custom_login_logo');
